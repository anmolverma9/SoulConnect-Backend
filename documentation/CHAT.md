# REAL-TIME CHAT & WEBSOCKETS SPECIFICATION

## 1. Chat Architecture

- **1-on-1 Direct Messaging**: Each match is automatically paired with a `Conversation`.
- **Participants**: Tracked in `conversation_participants` with `last_read_at` timestamps for unread counts.
- **Supported Message Types**: `text`, `image`, `audio`, `system`.
- **Status Lifecycle**: `sent` ➔ `delivered` ➔ `read` ➔ `deleted` (soft deletes).

## 2. WebSockets & Reverb Channels

Channels are authenticated via Laravel Sanctum on `/broadcasting/auth`:

| Channel | Type | Authorization Rule | Payload |
|---|---|---|---|
| `private-user.{id}` | Private | Authenticated User ID must match channel ID | Match alerts, incoming calls, system toasts |
| `private-conversation.{id}` | Private | User must be a participant in the conversation | Real-time messages, typing indicators |
| `private-call.{id}` | Private | User must be a participant in the call | Call status updates, SDP offers/answers |

## 3. Message Broadcasting Event

When a message is sent via `POST /api/v1/conversations/{id}/messages`:
1. The message is inserted in the DB.
2. `MessageSentEvent` is broadcast to `private-conversation.{id}`.
3. If the recipient is offline or not actively on screen, a queued FCM Push Notification (`MessageNotification`) is dispatched.
