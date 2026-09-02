<?php

namespace Tests\Feature;

use App\Models\OtpVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_user_can_request_otp(): void
    {
        $response = $this->postJson('/api/v1/auth/request-otp', [
            'email' => 'test@datingapp.example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('otp_verifications', [
            'email' => 'test@datingapp.example.com',
        ]);
    }

    public function test_user_can_verify_otp_and_receive_sanctum_token(): void
    {
        $email = 'test@datingapp.example.com';
        $otp = '123456';

        OtpVerification::create([
            'email' => $email,
            'otp_hash' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => $email,
            'otp' => $otp,
            'device_id' => 'device_abc123',
            'platform' => 'android',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token',
                    'is_new_user',
                    'user' => ['id', 'email', 'status'],
                ],
            ]);

        $this->assertDatabaseHas('users', ['email' => $email]);
        $this->assertDatabaseHas('wallets', ['user_id' => $response->json('data.user.id')]);
        $this->assertDatabaseHas('devices', ['device_id' => 'device_abc123']);
    }

    public function test_authenticated_user_can_get_me(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/me');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
            ]);
    }
}
