@php
    $adminSetting = getSuperAdminAllSetting(); // الحصول على جميع الإعدادات للمشرف العام
    config([
        'captcha.secret' => $adminSetting['NOCAPTCHA_SECRET'] ?? '', // تعيين سر التحقق من عدم الروبوتات
        'captcha.sitekey' => $adminSetting['NOCAPTCHA_SITEKEY'] ?? '', // تعيين موقع المفتاح للتحقق من عدم الروبوتات
        'options' => [
            'timeout' => 30, // إعداد مهلة الانتظار
        ],
    ]);
@endphp

@extends('layouts.guest')

@section('page-title')
    {{ __('التسجيل ') }} 
@endsection

@section('content')
    <div class="">
        @if (session('status'))
            <div class="mb-4 font-medium text-lg text-green-600 text-danger">
                {{ session('status') ?? __('Email SMTP settings does not configured so please contact to your site admin.') }} // إعدادات SMTP للبريد الإلكتروني غير مهيأة لذا يرجى الاتصال بمسؤول الموقع.
            </div>
        @endif
        <h2 class="mb-3 f-w-600" style="text-align:center">{{ __('تسجيل متجر جديد')}}</h2> 
    </div>
    <div class="">
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <div style="text-align: right;">
            <form method="POST" action="{{ route('register') }}">
                @csrf
               

               <div class="form-group mb-3">
    <label class="form-label" for="name">{{ __('الاسم') }}</label>
    <input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus />
</div>
<div class="form-group mb-3">
    <label class="form-label" for="store_name">{{ __(' ( بالأنجليزية )اسم المتجر') }}</label>
    <input id="store_name" class="form-control" type="text" name="store_name" :value="old('store_name')" required autofocus />
</div>
<div class="form-group mb-3">
    <label class="form-label" for="email">{{ __('البريد الإلكتروني') }}</label>
    <input id="email" class="form-control" type="email" name="email" :value="old('email')" required />
</div>
<div class="form-group mb-3">
    <label class="form-label" for="password">{{ __('كلمة المرور') }}</label>
    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
</div>
<div class="form-group mb-3">
    <label class="form-label" for="password_confirmation">{{ __('تأكيد كلمة المرور') }}</label>
    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required />
</div>

                @if (isset($adminSetting['RECAPTCHA_MODULE']) && $adminSetting['RECAPTCHA_MODULE'] == 'yes')
                    <div class="form-group col-lg-12 col-md-12 mt-3">
                        {!! NoCaptcha::display() !!}  عرض التحقق من عدم الروبوتات
                        @error('g-recaptcha-response')
                            <span class="error small text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                @endif
                <div class="d-grid">
                    <button class="btn btn-primary btn-block mt-2" type="submit"> {{ __('التسجيل الأن ') }} </button> 
                </div>
            </form>
        </div>
    </div>
    <p class="mb-2 text-center font-weight-bold" style="color: black;"> <!-- تم إغلاق العلامة بشكل صحيح -->
    هل لديك حساب بالفعل؟
    <a href="{{ route('login') }}" class="f-w-400" style="color: black; font-weight: bold;">تسجيل الدخول</a> <!-- جعلت الرابط أيضًا باللون الأسود وغامق -->
</p>

@endsection

@if (isset($adminSetting['RECAPTCHA_MODULE']) && $adminSetting['RECAPTCHA_MODULE'] == 'yes')
    {!! NoCaptcha::renderJs() !!} // عرض الجافا سكريبت للتحقق من عدم الروبوتات
@endif
