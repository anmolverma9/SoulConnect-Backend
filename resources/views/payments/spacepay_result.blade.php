<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soul Connect - Payment Result</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        body { background: #0F172A; color: #FFFFFF; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .card { background: #1E293B; border-radius: 24px; padding: 36px 28px; max-width: 420px; width: 100%; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.08); }
        .icon { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 36px; margin: 0 auto 20px; }
        .success-icon { background: linear-gradient(135deg, #10B981, #059669); box-shadow: 0 0 24px rgba(16,185,129,0.4); }
        .failed-icon { background: linear-gradient(135deg, #EF4444, #DC2626); box-shadow: 0 0 24px rgba(239,68,68,0.4); }
        h1 { font-size: 24px; font-weight: 800; margin-bottom: 8px; }
        p { color: #94A3B8; font-size: 14px; line-height: 1.5; margin-bottom: 24px; }
        .details-box { background: rgba(255,255,255,0.04); border-radius: 16px; padding: 16px; margin-bottom: 24px; text-align: left; }
        .detail-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; }
        .detail-label { color: #64748B; }
        .detail-value { color: #F1F5F9; font-weight: 600; }
        .btn { display: inline-block; width: 100%; padding: 14px; background: linear-gradient(135deg, #E11D48, #FB7185); color: white; text-decoration: none; font-weight: 700; border-radius: 14px; font-size: 15px; border: none; cursor: pointer; transition: transform 0.2s; }
        .btn:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="card">
        @if($isSuccess)
            <div class="icon success-icon">✓</div>
            <h1>Payment Successful!</h1>
            <p>Your transaction has been processed. Coins have been credited to your Soul Connect wallet!</p>
            @if($order)
            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">Order ID</span>
                    <span class="detail-value">{{ $order->order_id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount Paid</span>
                    <span class="detail-value">₹{{ number_format($order->amount, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Coins Credited</span>
                    <span class="detail-value" style="color: #F59E0B;">+{{ $order->coins_to_credit }} Coins 🪙</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color: #10B981;">COMPLETED</span>
                </div>
            </div>
            @endif
        @else
            <div class="icon failed-icon">✕</div>
            <h1>Payment Pending / Failed</h1>
            <p>We could not confirm your payment. If money was deducted, it will be automatically credited within a few minutes.</p>
            @if($order)
            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">Order ID</span>
                    <span class="detail-value">{{ $order->order_id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color: #EF4444;">{{ strtoupper($order->status) }}</span>
                </div>
            </div>
            @endif
        @endif

        <button class="btn" onclick="window.close();">Return to App</button>
    </div>
</body>
</html>
