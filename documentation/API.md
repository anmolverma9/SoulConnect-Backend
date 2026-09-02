# REST API DOCUMENTATION — DATING APP V1

Base URL: `/api/v1`

All responses follow a consistent JSON envelope structure:
```json
{
  "success": true,
  "message": "Success message here",
  "data": {}
}
```

---

## 1. Authentication Endpoints

### Request OTP
* **URL:** `POST /api/v1/auth/request-otp`
* **Rate Limit:** 3 per minute
* **Request:**
```json
{
  "email": "user@example.com"
}
```
* **Response (200):**
```json
{
  "success": true,
  "message": "Verification code sent to your email.",
  "data": {
    "expires_at": "2026-09-02T12:00:00Z",
    "cooldown_seconds": 60
  }
}
```

### Verify OTP
* **URL:** `POST /api/v1/auth/verify-otp`
* **Request:**
```json
{
  "email": "user@example.com",
  "otp": "123456",
  "device_id": "uuid-here",
  "platform": "android",
  "fcm_token": "fcm-token-here"
}
```
* **Response (200):**
```json
{
  "success": true,
  "message": "Successfully authenticated.",
  "data": {
    "token": "1|sanctum_plain_token_string_here",
    "is_new_user": false,
    "user": {
      "id": 1,
      "email": "user@example.com",
      "status": "active"
    }
  }
}
```

---

## 2. Profile & Preferences Endpoints

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/profile` | Get current user's profile |
| `PUT` | `/api/v1/profile` | Update profile bio, dob, city, interests |
| `POST` | `/api/v1/profile/photos` | Upload multipart profile photo |
| `DELETE` | `/api/v1/profile/photos/{id}` | Delete profile photo |
| `POST` | `/api/v1/profile/photos/{id}/primary` | Set primary avatar |
| `GET` | `/api/v1/preferences` | Get discovery filters |
| `PUT` | `/api/v1/preferences` | Update min/max age, distance, gender |

---

## 3. Discovery & Dating Endpoints

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/discover?per_page=15` | Paginated candidate profiles matching criteria |
| `POST` | `/api/v1/profiles/{user}/like` | Like a profile (returns `is_match: true` if mutual) |
| `POST` | `/api/v1/profiles/{user}/pass` | Pass a profile |
| `POST` | `/api/v1/profiles/{user}/super-like`| Spend coins to send a Super Like |
| `GET` | `/api/v1/likes` | See who liked you (Premium entitlement required) |
| `GET` | `/api/v1/matches` | Get active matches list |
| `DELETE` | `/api/v1/matches/{match}` | Unmatch a conversation |

---

## 4. Chat & Messaging Endpoints

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/conversations` | List conversation threads |
| `POST` | `/api/v1/conversations` | Start/get 1-on-1 thread with user |
| `GET` | `/api/v1/conversations/{id}/messages` | Paginated message history |
| `POST` | `/api/v1/conversations/{id}/messages`| Send text or media message |
| `POST` | `/api/v1/conversations/{id}/read` | Mark all unread messages as read |
| `DELETE`| `/api/v1/messages/{id}` | Soft-delete a sent message |

---

## 5. Voice & Video Calls

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/v1/calls` | Initiate call (`receiver_id`, `type`: `voice`/`video`) |
| `GET` | `/api/v1/calls/{id}` | Get call status & signaling info |
| `POST` | `/api/v1/calls/{id}/accept` | Accept incoming call with WebRTC signaling |
| `POST` | `/api/v1/calls/{id}/reject` | Reject incoming call |
| `POST` | `/api/v1/calls/{id}/end` | Terminate call & finalize coin billing |

---

## 6. Financial & Subscriptions

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/wallet` | Get coin balance |
| `GET` | `/api/v1/wallet/transactions` | Paginated ledger statement |
| `GET` | `/api/v1/coin-packages` | Active coin packages |
| `POST` | `/api/v1/payments/google-play/verify` | Server-side verify Google Play coin purchase |
| `GET` | `/api/v1/subscription/plans` | Subscription plans list |
| `GET` | `/api/v1/subscription` | Current user active subscription & entitlements |
| `POST` | `/api/v1/subscription/google-play/verify` | Server-side verify Google Play subscription |

---

## 7. Boosts, Gifts & Safety

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/v1/boosts` | Purchase profile boost |
| `GET` | `/api/v1/boosts/active` | Active boost timer & status |
| `GET` | `/api/v1/gifts` | Virtual gifts catalog |
| `POST` | `/api/v1/gifts/send` | Send gift to user (`receiver_id`, `gift_id`) |
| `GET` | `/api/v1/blocks` | Blocked users list |
| `POST` | `/api/v1/users/{id}/block` | Block user |
| `DELETE`| `/api/v1/users/{id}/block` | Unblock user |
| `POST` | `/api/v1/reports` | Report user, photo, or message |
| `DELETE`| `/api/v1/account` | Permanent account deletion / anonymization |
