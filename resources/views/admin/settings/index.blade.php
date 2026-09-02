@extends('admin.layouts.app')

@section('title', 'System Settings')
@section('page_title', 'Application Pricing & Dynamic Limits')

@section('content')
    <div class="card" style="max-width: 650px;">
        <div class="card-header">
            <span class="card-title">Live App Configuration</span>
        </div>
        <div style="padding: 24px;">
            <form action="{{ route('admin.settings.save') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Voice/Video Call Rate (Coins per minute)</label>
                    <input type="number" name="call_coin_cost_per_minute" class="form-control" value="{{ $settings['call_coin_cost_per_minute'] ?? 20 }}" required>
                </div>

                <div class="form-group">
                    <label>Profile Boost Cost (Coins)</label>
                    <input type="number" name="boost_coin_cost" class="form-control" value="{{ $settings['boost_coin_cost'] ?? 50 }}" required>
                </div>

                <div class="form-group">
                    <label>Boost Duration (Minutes)</label>
                    <input type="number" name="boost_duration_minutes" class="form-control" value="{{ $settings['boost_duration_minutes'] ?? 30 }}" required>
                </div>

                <div class="form-group">
                    <label>Super Like Cost (Coins)</label>
                    <input type="number" name="super_like_coin_cost" class="form-control" value="{{ $settings['super_like_coin_cost'] ?? 10 }}" required>
                </div>

                <div class="form-group">
                    <label>Daily Free Swipes (Free accounts)</label>
                    <input type="number" name="free_daily_likes" class="form-control" value="{{ $settings['free_daily_likes'] ?? 50 }}" required>
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="fa-solid fa-floppy-disk"></i> Save Configuration
                </button>
            </form>
        </div>
    </div>
@endsection
