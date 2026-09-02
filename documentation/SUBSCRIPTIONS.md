# SUBSCRIPTIONS & CENTRALIZED ENTITLEMENTS

## 1. Subscription Tiers & Plans

Configured dynamically in the `subscription_plans` database table:

- **Monthly**: 30 days renewal cycle + 100 periodic bonus coins.
- **Quarterly**: 90 days renewal cycle + 350 periodic bonus coins.
- **Yearly**: 365 days renewal cycle + 1,500 periodic bonus coins.

## 2. Server-Side Verification

Android Google Play subscription purchase tokens are verified on the backend via `GooglePlayPurchaseService` interacting with the Google Android Publisher API. The server verifies that:
- The package name matches `config('services.google_play.package_name')`.
- The purchase token is authentic and unused.
- The subscription state is active (`SUBSCRIPTION_STATE_ACTIVE`).

## 3. Centralized Entitlement Checks

All feature gates are evaluated through `EntitlementService`:

```php
$entitlements = app(EntitlementService::class);

// Checks if user is entitled to see who liked them
if ($entitlements->can($user, 'see_likes')) { ... }

// Checks daily swipe quota for free vs premium users
if ($entitlements->can($user, 'unlimited_likes')) { ... }

// Advanced search filters
if ($entitlements->can($user, 'advanced_filters')) { ... }
```
This ensures zero scattered conditional checks across controllers.
