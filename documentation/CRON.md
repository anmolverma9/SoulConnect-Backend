# CRON & SCHEDULER CONFIGURATION

## 1. System Cron Setup

Add the Laravel Scheduler cron entry to the server cron tab:

```bash
sudo crontab -u www-data -e
```

Add the following line:
```cron
* * * * * cd /var/www/dating-backend && php artisan schedule:run >> /dev/null 2>&1
```

---

## 2. Scheduled Jobs Reference

| Schedule | Command / Task | Purpose |
|---|---|---|
| `everyFifteenMinutes()` | `cleanup:otp` | Delete expired OTP verification entries |
| `everyFiveMinutes()` | `cleanup:boosts` | Mark expired profile boosts as expired |
| `daily()` | `subscriptions:process-expirations` | Transition expired subscriptions to expired |
| `everyMinute()` | `cleanup:calls` | Auto-fail unanswered/abandoned ringing call requests |
| `dailyAt('10:00')` | `engagement:process` | Dispatch push engagement reminders to inactive users |
| `weekly()` | `cleanup:stale-tokens` | Remove FCM device tokens unused for over 90 days |
