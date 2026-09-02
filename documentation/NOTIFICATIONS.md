# PUSH NOTIFICATIONS & ENGAGEMENT ENGINE

## 1. Multi-Device Push Delivery (FCM)

- Users can register multiple Android devices (`devices` table) with individual FCM tokens.
- FCM notifications are sent via HTTP v1 OAuth2 API in `FcmService`.
- If Google FCM responds with `404` or `410` (token unregistered / expired), the backend automatically invalidates the token from the `devices` table.

## 2. Notification Types & Deep-Link Payloads

| Type | Trigger | Deep-Link Payload |
|---|---|---|
| `match` | Mutual like created | `{"type": "match", "match_id": 12, "user_id": 45}` |
| `message` | New chat message | `{"type": "message", "conversation_id": 8, "sender_id": 45}` |
| `call` | Incoming voice/video call | `{"type": "call", "call_id": 99, "channel_name": "call_abc"}` |
| `gift` | Virtual gift received | `{"type": "gift", "gift_id": 3, "sender_id": 45}` |
| `system` | Engagement / Re-engagement | `{"type": "system", "route": "discovery"}` |

## 3. Server-Controlled Engagement Engine

- Engagement is controlled exclusively on the backend (`engagement_rules` and `engagement_events`).
- Rules enforce daily frequency limits and cooldown periods (e.g. inactive user reminder sent maximum once every 72 hours).
- **Zero Deception Policy**: The engine never fabricates fake likes, fake profiles, or simulated real-user calls.
