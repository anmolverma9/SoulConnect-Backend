<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In — Soul Connect</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            padding: 36px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 6px; }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #0f172a;
            font-size: 14px;
            outline: none;
        }
        .form-control:focus { border-color: #6366f1; }
        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #6366f1;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-primary:hover { background: #4f46e5; }
        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .alert-danger { background: #fef2f2; color: #ef4444; border: 1px solid #fca5a5; }
        .alert-success { background: #ecfdf5; color: #10b981; border: 1px solid #6ee7b7; }
    </style>
</head>
<body>

    <div class="login-card">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
            <div class="logo-icon"><i class="fa-solid fa-heart"></i></div>
            <div>
                <h2 style="font-size: 20px; font-weight: 700;">Soul Connect</h2>
                <p style="font-size: 12.5px; color: #64748b;">Administrator Sign In</p>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Admin Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', 'admin@datingapp.example.com') }}" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="Admin@Secure2026!" required>
            </div>
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In to Portal
            </button>
        </form>
    </div>

</body>
</html>
