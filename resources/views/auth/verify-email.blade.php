@extends('layouts.guest')

@section('page-title')
    {{ __('تأكيد البريد الإلكتروني') }}
@endsection

@if ($adminSetting['cust_darklayout'] == 'on')
    <style>
        .g-recaptcha {
            filter: invert(1) hue-rotate(180deg) !important;
        }
    </style>
@endif

@section('content')
    <div>
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('تم إرسال رابط التحقق الجديد إلى البريد الإلكتروني الذي قدمته أثناء التسجيل.') }}
            </div>
        @endif
        <div class="mb-4 text-sm text-gray-600">
            {{ __('شكراً لتسجيلك! قبل البدء، هل يمكنك التحقق من عنوان بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه إليك للتو؟ إذا لم تتلق البريد الإلكتروني، سنكون سعداء بإرسال آخر لك.') }}
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

        <div class="mt-4 flex items-center justify-between">
            <div class="row">
                <div class="col-auto">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('إعادة إرسال بريد التحقق') }}
                        </button>
                    </form>
                </div>
                <div class="col-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            {{ __('تسجيل الخروج') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (isset($adminSetting['RECAPTCHA_MODULE']) && $adminSetting['RECAPTCHA_MODULE'] == 'yes')
    {!! NoCaptcha::renderJs() !!}
@endif
