# SECURITY, PRIVACY & HARDENING GUIDELINES

## 1. Authentication & Session Security

- **Sanctum Tokens**: Issued per device, revocable on logout or status change.
- **Account Status Middleware**: `EnsureActiveUser` intercepts all requests and instantly rejects banned, suspended, or deleted accounts.
- **Rate Limiting**: Throttling configured for API endpoints, OTP requests (3/min), verification attempts (5/min), and likes (60/min).

## 2. Data Protection & Privacy

- **No PII Leaks**: Discovery endpoints use `DiscoveryProfileResource` which strips email addresses, raw coordinates (approximate distance only), and moderation flags.
- **Account Deletion / Anonymization**: Calling `DELETE /api/v1/account` anonymizes user PII and revokes tokens while preserving immutable financial audit ledger rows for legal compliance.
- **Strict File Upload Validation**: Profile photos enforce MIME validation (`image/jpeg`, `image/png`, `image/webp`), 10MB limits, and random UUID storage keys to prevent path traversal and script execution.

## 3. Financial Integrity

- Row-level database locking (`lockForUpdate()`) on all wallet balance mutations.
- Unique purchase token constraints to prevent purchase replay attacks.
- Immutable admin audit logging on all manual balance adjustments.
