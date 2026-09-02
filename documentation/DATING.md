# DATING, MATCHMAKING & DISCOVERY ENGINE

## 1. Discovery Pipeline

The discovery feed query (`DiscoveryService::discover`) applies multi-layered database filtering:

1. **Eligibility Check**: User must be `active`, have `profile_visibility = 'public'`, and have `date_of_birth` defined.
2. **Mutual Exclusion Filter**:
   - Excludes current user.
   - Excludes already liked user IDs (`likes.liked_user_id`).
   - Excludes already passed user IDs (`passes.passed_user_id`).
   - Excludes existing mutual match IDs (`matches`).
   - Excludes blocked and blocking user IDs (`blocks`).
3. **Preferences Filter**:
   - Age range (`minimum_age` <= age <= `maximum_age`).
   - Gender preference (`male`, `female`, `non_binary`, or `all`).
   - Geo-spatial radius limit (`distance <= maximum_distance_km`).
4. **Boost Weighting**:
   - Active boosted users (`boosts.status = 'active' AND expires_at > NOW()`) are weighted to appear first.
   - Secondary sorting by `last_active_at DESC`.

## 2. Likes, Mutual Matches & Super Likes

- **Like**: User A likes User B. If User B already liked User A, a `MatchModel` record is created inside an atomic database transaction along with a `Conversation` record, and a `MatchCreatedEvent` is broadcast to both clients over WebSockets.
- **Super Like**: Deducts coins transactionally via `WalletService`, marks `likes.is_super_like = true`, and highlights the profile.
- **Pass**: Registers a pass record to exclude the candidate from future discovery queries.
- **Unmatch**: Deactivates the match (`status = 'unmatched'`) and archives the conversation thread.
