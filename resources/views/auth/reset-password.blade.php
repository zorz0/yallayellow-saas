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
        {{ __('Reset Password') }}
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
            <h2 class="mb-3 f-w-600">Reset Password</h2>
        </div>
        <div class="">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('password.store') }}">
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                @csrf

                <div class="form-group mb-3">
                    <label class="form-label" for="email">{{ __('Email') }}</label>
                    <x-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required
                        autofocus />
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="password">{{ __('Password') }}</label>
                    <x-input id="password" class="form-control" type="password" name="password" required />
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                    <x-input id="password_confirmation" class="form-control" type="password" name="password_confirmation"
                        required />
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
                        {{ __('Reset Password') }}
                    </button>
                </div>

            </form>
        </div>
    @endsection

    @if (isset($adminSetting['RECAPTCHA_MODULE']) && $adminSetting['RECAPTCHA_MODULE'] == 'yes')
        {!! NoCaptcha::renderJs() !!}
    @endif

    {{-- <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form> --}}
