# FINANCIAL LEDGER & WALLET SYSTEM SPECIFICATION

## 1. Principles of the Wallet Ledger

1. **Zero Negative Balance**: Balance can never drop below 0. `InsufficientBalanceException` is thrown before any overdraft occurs.
2. **Pessimistic Concurrency Locking**: Every wallet balance alteration is wrapped in `DB::transaction()` and acquires a database row-level lock using `lockForUpdate()`. This eliminates double-spending under concurrent API requests.
3. **Immutable Audit Trail**: Every coin credit or debit writes a `wallet_transactions` record containing `balance_before` and `balance_after`.

## 2. Transaction Types

| Type | Direction | Description |
|---|---|---|
| `purchase` | Credit (+) | Verified Google Play in-app coin package purchase |
| `bonus` | Credit (+) | Promotional or welcome bonus coins |
| `subscription_bonus`| Credit (+) | Recurring coins included with active VIP/Premium tier |
| `admin_adjustment` | (+ / -) | Manual correction with mandatory admin reason & audit log |
| `spend` | Debit (-) | Generic coin spending |
| `gift` | Debit (-) | Sending a virtual gift to another user |
| `boost` | Debit (-) | Purchasing a profile visibility boost |
| `super_like` | Debit (-) | Sending a paid Super Like |
| `call` | Debit (-) | Per-minute voice or video call charge |
| `refund` | Credit (+) | Refund for failed or disputed service |
