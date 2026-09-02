# ARCHITECTURE & SYSTEM DESIGN — DATING APP LARAVEL BACKEND

## 1. High-Level Overview

This backend is built with **Laravel 12 (PHP 8.3+)** following a clean, modular Service-Oriented Architecture (SOA) designed specifically to power modern native Android/mobile dating applications.

```
                  ┌───────────────────────────────┐
                  │    Android Native Client      │
                  └───────────────┬───────────────┘
                                  │ HTTPS REST / WebSockets / FCM
                                  ▼
                     ┌────────────────────────┐
                     │ Nginx Reverse Proxy /  │
                     │ SSL Termination / Rate │
                     └────────────┬───────────┘
                                  │
         ┌────────────────────────┼────────────────────────┐
         │                        │                        │
         ▼                        ▼                        ▼
┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│  Laravel 12 API  │    │  Laravel Reverb  │    │   Queue Worker   │
│  Controllers &   │    │  WebSocket Host  │    │  (Redis / Jobs)  │
│  Form Requests   │    │  (Signaling)     │    │  (FCM / Mails)   │
└────────┬─────────┘    └─────────┬────────┘    └────────┬─────────┘
         │                        │                      │
         └────────────────────────┼──────────────────────┘
                                  │
                                  ▼
                ┌──────────────────────────────────┐
                │   Domain Services Layer (app/    │
                │   Services/*) & Entitlements     │
                └─────────────────┬────────────────┘
                                  │
         ┌────────────────────────┴────────────────────────┐
         ▼                                                 ▼
┌──────────────────┐                             ┌──────────────────┐
│  MySQL 8+ InnoDB │                             │   Redis Cache /  │
│  (ACID Ledger &  │                             │   PubSub / Queue │
│  Spatial DB)     │                             │   Rate Limiting  │
└──────────────────┘                             └──────────────────┘
```

---

## 2. Directory Structure & Separation of Concerns

```
app/
├── Console/             # Scheduled tasks & maintenance
├── Events/              # WebSockets broadcasting events (Reverb)
├── Exceptions/          # Standardized API & Financial exceptions
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── V1/      # Thin REST API Controllers (Versioned)
│   │           └── Admin/ # Dedicated Admin panel controllers
│   ├── Middleware/      # Active User verification, Admin guard, CORS
│   ├── Requests/        # Strict FormRequest validation rules
│   └── Resources/       # API Resources preventing PII leaks
├── Models/              # Eloquent models with relations and casts
├── Notifications/       # Queueable database + FCM push notifications
├── Policies/            # Authorization policies (Chat, Call, Reports)
├── Services/            # Core business logic layer
│   ├── Admin/           # AdminAuditService
│   ├── Auth/            # OtpService, AuthService
│   ├── Call/            # CallService, CallBillingService
│   ├── Chat/            # ChatService
│   ├── Dating/          # DiscoveryService, MatchingService, ProfileService, BoostService, GiftService
│   ├── Engagement/      # EngagementService
│   ├── Media/           # ImageUploadService
│   ├── Notification/    # NotificationService, FcmService
│   ├── Payment/         # GooglePlayPurchaseService
│   ├── Subscription/    # SubscriptionService, EntitlementService
│   └── Wallet/          # WalletService (Pessimistic row-locking ledger)
└── Support/             # ApiResponse JSON envelope standardizer
```

---

## 3. Core Architectural Principles

1. **Thin Controllers, Rich Services**: Controllers only validate requests via FormRequests, delegate business processing to domain services, and return responses via API Resources.
2. **Atomic Financial Ledger**: All wallet modifications use MySQL row-level locking (`lockForUpdate()`) and database transactions. Balances are never modified directly without creating an immutable audit ledger entry (`wallet_transactions`).
3. **Server-Side Truth**: Client claims (especially around purchases and permissions) are never trusted. Google Play in-app purchases and subscriptions are validated cryptographically against Google Play Developer APIs.
4. **Centralized Entitlements**: Permission to access premium features (`see_likes`, `unlimited_likes`, `advanced_filters`) is checked via `EntitlementService::can($user, $feature)` rather than scattering checks across controllers.
5. **Privacy First**: Sensitive data (emails, raw coordinates, passwords, internal moderation notes) are filtered using dedicated `DiscoveryProfileResource` and `UserProfileResource`.
