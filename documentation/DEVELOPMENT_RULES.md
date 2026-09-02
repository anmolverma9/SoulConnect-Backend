# DEVELOPMENT RULES & CODING STANDARDS

Follow these mandatory rules when extending or modifying this codebase:

1. **No Business Logic in Controllers**: Controllers must remain thin. Business operations belong in `app/Services/*`.
2. **Never Trust the Client for Money**: Never credit coins or activate subscriptions based solely on client status payloads. Always perform cryptographic verification against Google Play Developer APIs.
3. **Pessimistic Concurrency on Financial Ledger**: Always use `DB::transaction()` and `$wallet->lockForUpdate()` when modifying coins. Never allow balances to drop below 0.
4. **Form Request Validation**: Always create a FormRequest with explicit validation rules for every POST, PUT, and PATCH endpoint.
5. **API Resources for Data Protection**: Always return models through API Resources to prevent accidental PII leakage (e.g. emails, exact coordinates, hashed secrets).
6. **Zero Fake User Interactions**: Do not fabricate fake real-user activity or simulate calls. If AI entities are added in the future, they must be explicitly labeled.
7. **Consistent Response Envelope**: All endpoints must return `ApiResponse::success()`, `ApiResponse::paginated()`, or throw an `ApiException`.
8. **Keep Documentation Synchronized**: When adding new models, routes, or services, update the corresponding markdown file in `documentation/`.
