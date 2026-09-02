<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soul Connect Verification Code</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body {
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-collapse: collapse;
        }
        .otp-digit {
            display: inline-block;
            width: 44px;
            height: 52px;
            line-height: 52px;
            text-align: center;
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            margin: 0 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9; padding: 40px 16px;">
        <tr>
            <td align="center">
                <!-- MAIN CARD CONTAINER -->
                <table role="presentation" width="100%" max-width="520" cellspacing="0" cellpadding="0" border="0" style="max-width: 520px; width: 100%; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px -15px rgba(236, 72, 153, 0.12), 0 0 1px 1px rgba(0, 0, 0, 0.04);">
                    
                    <!-- TOP BRAND HEADER WITH VIBRANT GRADIENT -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #ec4899 0%, #f43f5e 50%, #fb923c 100%); padding: 36px 30px 30px 30px; text-align: center;">
                            <!-- Glowing Heart Icon Badge -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center">
                                <tr>
                                    <td align="center" style="width: 56px; height: 56px; background: rgba(255, 255, 255, 0.22); border: 1.5px solid rgba(255, 255, 255, 0.4); border-radius: 18px; text-align: center; line-height: 56px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);">
                                        <span style="font-size: 28px; line-height: 56px;">💖</span>
                                    </td>
                                </tr>
                            </table>
                            <h1 style="margin: 14px 0 0 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Soul Connect</h1>
                            <p style="margin: 4px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 13px; font-weight: 500; letter-spacing: 0.5px;">FIND YOUR PERFECT SOUL MATCH</p>
                        </td>
                    </tr>

                    <!-- CARD BODY CONTENT -->
                    <tr>
                        <td style="padding: 36px 32px 32px 32px;">
                            <h2 style="margin: 0 0 10px 0; color: #0f172a; font-size: 20px; font-weight: 700; text-align: center; letter-spacing: -0.3px;">
                                Verification Code
                            </h2>
                            <p style="margin: 0 0 26px 0; color: #64748b; font-size: 14px; line-height: 1.6; text-align: center;">
                                Welcome! Use the 6-digit one-time password below to securely verify and log in to your account:
                            </p>

                            <!-- OTP DIGITS VISUAL BOXES -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 24px auto;">
                                <tr>
                                    <td align="center">
                                        <div style="display: inline-block; background: #f8fafc; border: 1.5px solid #e2e8f0; padding: 12px 16px; border-radius: 20px;">
                                            @php
                                                $digits = str_split($otp);
                                            @endphp
                                            @foreach($digits as $d)
                                                <span style="display: inline-block; width: 40px; height: 48px; line-height: 48px; text-align: center; font-size: 24px; font-weight: 800; color: #0f172a; background: #ffffff; border: 2px solid #cbd5e1; border-radius: 12px; margin: 0 3px; box-shadow: 0 2px 4px rgba(0,0,0,0.04);">
                                                    {{ $d }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- EXPIRY TIME PILL -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 28px;">
                                <tr>
                                    <td align="center">
                                        <span style="display: inline-block; background: #fff1f2; color: #e11d48; font-size: 12.5px; font-weight: 700; padding: 6px 14px; border-radius: 20px; border: 1px solid #ffe4e6;">
                                            ⏱️ Valid for {{ $expiryMinutes ?? 5 }} minutes
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- SECURITY ADVISORY BOX -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 18px; margin-bottom: 20px;">
                                <tr>
                                    <td style="font-size: 18px; width: 26px; vertical-align: top; padding-top: 2px;">🔒</td>
                                    <td style="font-size: 12.5px; color: #64748b; line-height: 1.5; padding-left: 8px;">
                                        <strong style="color: #334155;">Security Note:</strong> Never share this OTP with anyone. Soul Connect staff will never ask for your verification code.
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.5; text-align: center;">
                                If you did not request this login code, you can safely disregard this email.
                            </p>
                        </td>
                    </tr>

                    <!-- FOOTER SECTION -->
                    <tr>
                        <td style="background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 22px 30px; text-align: center;">
                            <p style="margin: 0 0 6px 0; color: #94a3b8; font-size: 11.5px; font-weight: 500;">
                                &copy; {{ date('Y') }} Soul Connect Inc. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #cbd5e1; font-size: 11px;">
                                Designed with ❤️ for genuine connections and soulmates.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
