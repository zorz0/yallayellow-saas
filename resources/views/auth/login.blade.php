@php
    // الحصول على إعدادات المدير
    $adminSetting = getSuperAdminAllSetting();

    // تكوين إعدادات كابتشا
    config([
        'captcha.secret' => $adminSetting['NOCAPTCHA_SECRET'] ?? '',
        'captcha.sitekey' => $adminSetting['NOCAPTCHA_SITEKEY'] ?? '',
        'options' => [
            'timeout' => 30, // وقت المهلة
        ],
    ]);

    // تعيين اللغة الافتراضية للتطبيق إلى العربية
    app()->setLocale('ar');
@endphp

{{-- استدعاء تخطيط الضيف من Blade --}}
@extends('layouts.guest')

{{-- تعيين عنوان الصفحة --}}
@section('page-title')
    {{ __('Login') }} {{-- تسجيل الدخول --}}
@endsection

{{-- تطبيق الوضع الداكن إذا تم تفعيله في الإعدادات --}}
@if ($adminSetting['cust_darklayout'] == 'on')
    <style>
        .g-recaptcha {
            filter: invert(1) hue-rotate(180deg) !important;
        }
    </style>
@endif

{{-- بداية قسم المحتوى --}}
@section('content')
    <div class="">
        <h2 class="mb-3 f-w-600 text-right">{{ __('Login') }}</h2> {{-- تسجيل الدخول --}}
    </div>
    <div class="">
        {{-- عرض حالة الجلسة --}}
        <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- عرض أخطاء التحقق --}}
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <div style="text-align: right;">
            {{-- استمارة تسجيل الدخول --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- حقل البريد الإلكتروني --}}
                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Email') }}</label>
                    <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
                </div>

                {{-- حقل كلمة المرور --}}
                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Password') }}</label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                </div>

                {{-- رابط نسيان كلمة المرور --}}
                <div class="my-1">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                {{-- إدراج كابتشا إذا تم تفعيل الوحدة --}}
                @if (isset($adminSetting['RECAPTCHA_MODULE']) && $adminSetting['RECAPTCHA_MODULE'] == 'yes')
                    <div class="form-group col-lg-12 col-md-12 mt-3">
                        {!! NoCaptcha::display() !!}
                        @error('g-recaptcha-response')
                            <span class="error small text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                @endif

                {{-- زر تسجيل الدخول --}}
                <div class="d-grid">
                    <button class="btn btn-primary btn-block mt-2"> {{ __('Login') }} </button>
                </div>

                {{-- رابط التسجيل --}}
                <p class="my-4 text-center">{{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="my-4 text-primary">{{ __('Register') }}</a>
                </p>
            </form>
        </div>
    </div>
@endsection

{{-- تحميل جافاسكريبت كابتشا إذا تم تفعيل الوحدة --}}
@if (isset($adminSetting['RECAPTCHA_MODULE']) && $adminSetting['RECAPTCHA_MODULE'] == 'yes')
    {!! NoCaptcha::renderJs() !!}
@endif
