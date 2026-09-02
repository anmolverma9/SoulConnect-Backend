# AUTHENTICATION & SECURITY SPECIFICATION

## 1. Flow Overview

Authentication is passwordless and relies on **Email OTP (One-Time Password)** issued via `POST /api/v1/auth/request-otp` and verified via `POST /api/v1/auth/verify-otp`.

```
[ Android App ] ─── 1. POST /auth/request-otp ───► [ Laravel API ]
                                                          │
                                                Generate 6-digit OTP
                                                Store SHA-256 Hash
                                                Queue Email Notification
                                                          │
[ Android App ] ◄── 2. Success Envelope ──────────────────┘
      │
[ User receives Email ]
      │
[ Android App ] ─── 3. POST /auth/verify-otp ────► [ Laravel API ]
                                                          │
                                                Verify Hash & Attempts
                                                Create/Find User
                                                Create Wallet & Prefs
                                                Issue Sanctum Token
                                                          │
[ Android App ] ◄── 4. Token + User Data ─────────────────┘
```

## 2. Security Safeguards

1. **Cryptographic Generation**: Generated via `random_int(100000, 999999)`.
2. **Hashed Storage**: Stored exclusively using `Hash::make()` in `otp_verifications.otp_hash`. Plaintext OTPs are never stored in database or logged.
3. **Short Expiration**: Configurable default of 5 minutes (`OTP_EXPIRY_MINUTES`).
4. **Brute Force Protection**: Max 5 attempts allowed (`OTP_MAX_ATTEMPTS`). On the 5th failed attempt, the record is purged and user must request a new OTP.
5. **Resend Cooldown**: Enforces 60 seconds cooldown between successive OTP requests (`OTP_RESEND_COOLDOWN_SECONDS`).
6. **Rate Limiting**: Throttled at 3 OTP requests per minute per IP/Email.
7. **Session Invalidation**: When a new OTP is requested, older pending OTPs for that email are automatically invalidated.
