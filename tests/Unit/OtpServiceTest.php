<?php

namespace Tests\Unit;

use App\Exceptions\ApiException;
use App\Models\OtpVerification;
use App\Services\Auth\OtpService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OtpServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OtpService $otpService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->otpService = app(OtpService::class);
        Notification::fake();
    }

    public function test_can_request_otp_and_hash_is_stored(): void
    {
        $email = 'user@example.com';
        $result = $this->otpService->requestOtp($email);

        $this->assertArrayHasKey('expires_at', $result);
        $this->assertDatabaseHas('otp_verifications', [
            'email' => $email,
            'attempts' => 0,
        ]);

        $otp = OtpVerification::where('email', $email)->first();
        $this->assertNotEquals('123456', $otp->otp_hash); // Hashed, not plain
    }

    public function test_verifying_correct_otp_succeeds(): void
    {
        $email = 'user@example.com';
        $plainOtp = '654321';

        OtpVerification::create([
            'email' => $email,
            'otp_hash' => Hash::make($plainOtp),
            'expires_at' => Carbon::now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $verified = $this->otpService->verifyOtp($email, $plainOtp);
        $this->assertTrue($verified);

        $otp = OtpVerification::where('email', $email)->first();
        $this->assertNotNull($otp->verified_at);
    }

    public function test_verifying_wrong_otp_increments_attempts_and_fails(): void
    {
        $email = 'user@example.com';
        OtpVerification::create([
            'email' => $email,
            'otp_hash' => Hash::make('654321'),
            'expires_at' => Carbon::now()->addMinutes(5),
            'attempts' => 0,
        ]);

        try {
            $this->otpService->verifyOtp($email, '000000');
            $this->fail('Expected ApiException on wrong OTP');
        } catch (ApiException $e) {
            $this->assertEquals(422, $e->getStatusCode());
        }

        $otp = OtpVerification::where('email', $email)->first();
        $this->assertEquals(1, $otp->attempts);
    }
}
