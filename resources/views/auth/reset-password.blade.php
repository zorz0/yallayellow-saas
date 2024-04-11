@php
$adminSetting = getSuperAdminAllSetting();
config([
    'captcha.secret' => $adminSetting['NOCAPTCHA_SECRET'] ?? '',
    'captcha.sitekey' => $adminSetting['NOCAPTCHA_SITEKEY'] ?? '',
    'options' => [
        'timeout' => 30,
    ],
]);
@endphp

@extends('layouts.guest')

@section('page-title')
    {{ __('إعادة تعيين كلمة المرور') }}
@endsection

@if ($adminSetting['cust_darklayout'] == 'on')
    <style>
        .g-recaptcha {
            filter: invert(1) hue-rotate(180deg) !important;
        }
    </style>
@endif

@section('content')
    <div class="">
        <h2 class="mb-3 f-w-600">إعادة تعيين كلمة المرور</h2>
    </div>
    <div class="">
        <!-- عرض أخطاء التحقق -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.store') }}">
            <!-- رمز إعادة تعيين كلمة المرور -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            @csrf

            <div class="form-group mb-3">
                <label class="form-label" for="email">{{ __('البريد الإلكتروني') }}</label>
                <x-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="password">{{ __('كلمة المرور') }}</label>
                <x-input id="password" class="form-control" type="password" name="password" required />
            </div>

            <div class="form-group mb-3">
                <label class="form-label" for="password_confirmation">{{ __('تأكيد كلمة المرور') }}</label>
                <x-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
            </div>

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

            <div class="form-group mb-4">
                {!! Form::hidden('type', 'admin') !!}
                <button class="btn btn-primary btn-block mt-2" type="submit">
                    {{ __('إعادة تعيين كلمة المرور') }}
                </button>
            </div>

        </form>
    </div>
@endsection

@if (isset($adminSetting['RECAPTCHA_MODULE']) && $adminSetting['RECAPTCHA_MODULE'] == 'yes')
    {!! NoCaptcha::renderJs() !!}
@endif
