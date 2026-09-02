# DATABASE SCHEMA & RELATIONSHIPS — DATING APP BACKEND

## 1. Tables Overview

| Table | Description | Primary Key / Unique Keys | Indexes |
|---|---|---|---|
| `users` | Core user accounts | `id` (PK), `email` (UQ) | `status`, `last_active_at`, `[status, last_active_at]` |
| `otp_verifications` | Hashed email verification codes | `id` (PK) | `email`, `expires_at`, `verified_at`, `[email, expires_at, verified_at]` |
| `devices` | FCM token and device registrations | `id` (PK), `[user_id, device_id]` (UQ) | `last_seen_at`, `[user_id, platform]` |
| `user_profiles` | Dating profile bio, dob, location | `id` (PK), `user_id` (UQ) | `date_of_birth`, `gender`, `[latitude, longitude]`, `[gender, profile_visibility]` |
| `profile_photos` | User uploaded photos | `id` (PK) | `[user_id, is_primary]`, `[user_id, sort_order]`, `status` |
| `user_preferences` | Matchmaking filter preferences | `id` (PK), `user_id` (UQ) | `user_id` |
| `likes` | Swipe right / like records | `id` (PK), `[user_id, liked_user_id]` (UQ) | `is_super_like`, `[liked_user_id, created_at]` |
| `passes` | Swipe left / pass records | `id` (PK), `[user_id, passed_user_id]` (UQ) | `[user_id, created_at]` |
| `matches` | Mutual match pairs | `id` (PK), `[user_one_id, user_two_id]` (UQ) | `status`, `[user_two_id, status]` |
| `profile_views` | Profile view impressions | `id` (PK) | `[viewed_id, created_at]`, `[viewer_id, viewed_id]` |
| `super_likes` | Super like purchase logs | `id` (PK), `[user_id, target_user_id]` (UQ) | `[target_user_id, created_at]` |
| `conversations` | Chat conversation threads | `id` (PK) | `type`, `last_message_at`, `last_message_id` |
| `conversation_participants` | Conversation members | `id` (PK), `[conversation_id, user_id]` (UQ) | `[user_id, conversation_id]` |
| `messages` | Chat messages & media | `id` (PK) | `[conversation_id, created_at]`, `[sender_id, created_at]`, `status` |
| `message_reads` | Read receipts | `id` (PK), `[message_id, user_id]` (UQ) | `user_id` |
| `calls` | Voice & Video calls | `id` (PK), `channel_name` (UQ) | `[caller_id, status]`, `[receiver_id, status]`, `[created_at, status]` |
| `call_participants` | Call participants | `id` (PK), `[call_id, user_id]` (UQ) | `user_id` |
| `wallets` | User coin ledger balance | `id` (PK), `user_id` (UQ) | `balance` |
| `wallet_transactions` | Immutable balance modification history | `id` (PK) | `[user_id, created_at]`, `[wallet_id, created_at]`, `[reference_type, reference_id]` |
| `coin_packages` | In-app coin purchase tiers | `id` (PK), `google_product_id` (UQ) | `is_active`, `sort_order` |
| `coin_purchases` | Google Play purchase records | `id` (PK), `purchase_token` (UQ) | `[user_id, status]`, `product_id`, `order_id` |
| `subscription_plans` | Monthly/quarterly/yearly plans | `id` (PK), `google_product_id` (UQ) | `duration`, `is_active` |
| `subscriptions` | Active user subscriptions | `id` (PK), `purchase_token` (UQ) | `[user_id, status]`, `ends_at` |
| `subscription_transactions` | Renewal & billing history | `id` (PK) | `[user_id, created_at]` |
| `boosts` | Timed profile visibility boosts | `id` (PK) | `[user_id, status]`, `[status, expires_at]` |
| `gift_catalog` | Virtual gifts catalog | `id` (PK) | `is_active`, `sort_order` |
| `gift_transactions` | Virtual gift send records | `id` (PK) | `[receiver_id, created_at]`, `[sender_id, created_at]` |
| `notifications` | In-app notifications | `id` (UUID PK) | `[user_id, is_read, created_at]` |
| `blocks` | User block relationships | `id` (PK), `[blocker_id, blocked_id]` (UQ) | `[blocked_id, created_at]` |
| `reports` | Abuse and safety reports | `id` (PK) | `[status, created_at]`, `reporter_id`, `reported_id`, `[reportable_type, reportable_id]` |
| `admin_users` | Admin moderators & superadmins | `id` (PK), `email` (UQ) | `role`, `is_active` |
| `admin_actions` | Immutable admin audit trail | `id` (PK) | `[admin_user_id, created_at]`, `[target_type, target_id]` |
| `app_settings` | Runtime config & pricing | `id` (PK), `key` (UQ) | `group`, `is_public` |
| `engagement_rules` | Server-controlled engagement rules | `id` (PK), `event_type` (UQ) | `is_enabled`, `priority` |
| `engagement_events` | Triggered user engagement logs | `id` (PK) | `[user_id, event_type, triggered_at]`, `[status, triggered_at]` |

---

## 2. Haversine Query Formula for Discovery

Distance calculation is executed inside the SQL query planner without loading excess records into PHP memory:

```sql
(6371 * acos(
    cos(radians(?)) * 
    cos(radians(user_profiles.latitude)) * 
    cos(radians(user_profiles.longitude) - radians(?)) + 
    sin(radians(?)) * 
    sin(radians(user_profiles.latitude))
)) AS distance
```
This is combined with bounding box calculations and indexed gender/age range filters.
