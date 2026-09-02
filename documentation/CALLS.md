# VOICE & VIDEO CALLING SYSTEM & BILLING

## 1. Call State Machine

```
[Requested] ──► [Ringing] ──► [Accepted] ──► [Ended] (Billed)
    │               │
    ├──► [Missed]   └──► [Rejected] (Free)
    └──► [Cancelled] (Free)
```

## 2. Call Lifecycle Steps

1. **Initiate Call (`POST /api/v1/calls`)**:
   - Validates caller wallet balance against the per-minute rate.
   - Generates unique WebRTC `channel_name` (UUID).
   - Broadcasts `CallStateChangedEvent` (`requested`) and dispatches high-priority incoming call FCM push.
2. **Accept Call (`POST /api/v1/calls/{id}/accept`)**:
   - Updates `answered_at` timestamp and stores WebRTC SDP signaling parameters.
   - Broadcasts `accepted` state.
3. **End Call (`POST /api/v1/calls/{id}/end`)**:
   - Calculates duration (`ended_at - answered_at`).
   - Calls `CallBillingService::finalizeBilling()` to calculate billable minutes and debit caller coins atomically.
   - Updates status to `ended` and marks billing as `billed`.

## 3. Idempotent Billing Rules

- Calls under 10 seconds or rejected/missed calls are marked as `billing_status = 'free'` and charged 0 coins.
- Billing is rounded up to the nearest minute (`ceil(duration_seconds / 60)`).
- Debit is strictly ledger-recorded under `wallet_transactions` with `type = 'call'`.
