@extends('front_end.layouts.app')
@section('page-title')
    {{ __('Checkout Page') }}
@endsection
@section('content')
    @include('front_end.sections.partision.header_section')

    <section class="checkout-page padding-bottom padding-top">
        <div class="container">
            <div class="my-acc-head">
                <div class="d-flex justify-content-start back-toshop">
                    <a href="{{ route('page.product-list', $store->slug) }}" class="back-btn">
                        <span class="svg-ic">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5"
                                fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                                    fill="white"></path>
                            </svg>
                        </span>
                        {{ __('Back to Shop') }}
                    </a>
                </div>
                <div class="section-title">
                    <h2>{{ __('Checkout') }}</h2>
                </div>
            </div>
            <div class="row align-items-start">
                <div class="col-lg-9 col-12">
                    @if (!auth()->guard('customers')->check())
                        <div class="set has-children is-open">
                            <a href="javascript:;" class="acnav-label">
                                <span> {{ __('Step 1') }} : <b>{{ __('Checkout options') }}</b></span>
                            </a>
                            <div class="acnav-list" style="display: block;">
                                <div class="row">
                                    <div class="col-md-6 col-12 ">
                                        <h3 class="check-head">{{ __('New Customer?') }}</h3>
                                        <p>{{ __('By creating an account you will be able to shop faster, be up to date on an
                                                                                                                                                                order status,
                                                                                                                                                                and keep track of the orders you have previously made.') }}
                                        </p>
                                        <div class="btn-flex d-flex align-items-center">
                                            <a href="{{ route('customer.register', $store->slug) }}" class="btn  reg-btn">
                                                {{ __('Register') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                                    viewBox="0 0 35 14" fill="none">
                                                    <path
                                                        d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a class="btn-secondary login-btn" href="{{  route('customer.login', $store->slug) }}">
                                                {{ __('Login') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                                    viewBox="0 0 35 14" fill="none">
                                                    <path
                                                        d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <form method="POST" action="{{  route('customer.login', $store->slug) }}" class="login-form">
                                            @csrf
                                            <div class="form-container">
                                                <div class="form-heading">
                                                    <h3>{{ __('Log in') }}</h3>
                                                </div>
                                            </div>
                                            <div class="form-container">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <p>{{ __('I am a returning customer') }}</p>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('E-mail') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            <input type="email" name="email" class="form-control"
                                                                placeholder="shop@company.com" required="" autocomplete="username">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Password') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            <input type="password" name="password" class="form-control"
                                                                placeholder="**********" required="" autocomplete="current-password">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-container">
                                                <div class="row align-items-center form-footer  ">
                                                    <div class="col-lg-12  col-12 d-flex align-items-center">
                                                        <button class="btn-secondary" type="submit">
                                                            {{ __('Login') }}
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="35"
                                                                height="14" viewBox="0 0 35 14" fill="none">
                                                                <path
                                                                    d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                        <a href="#"
                                                            class="forgot-pass">{{ __('Forgot Password?') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth()->guard('customers')->check())
                        {!! Form::open([
                            'route' => ['payment.process', $store->slug],
                            'method' => 'post',
                            'enctype' => 'multipart/form-data',
                            'id' => 'formdata',
                        ]) !!}
                    @else
                        {!! Form::open([
                            'route' => ['place.order.guest', $store->slug],
                            'method' => 'post',
                            'enctype' => 'multipart/form-data',
                            'id' => 'formdata',
                        ]) !!}
                    @endif
                    <div class="checkout-page-left">
                        {{-- Billing data --}}
                        <div class="set has-children billing_data_tab is-open">
                            <a href="javascript:;" class="acnav-label">
                                <span>{{ __('Step') }} {{ auth()->guard('customers')->check() ? '1' : '2' }}:
                                    <b>{{ __('Billing details') }}</b></span>
                            </a>
                            <div class="acnav-list billing_data_tab_list" style="display: block;">
                                <h3 class="check-head">{{ __('Your Personal Details') }}</h3>
                                <div class="personal-info-form">
                                    <div class="form-container">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ __('First Name') }}<sup aria-hidden="true">*</sup>:</label>
                                                    {!! Form::text('billing_info[firstname]', !empty(Auth::guard('customers')->user()) ? Auth::guard('customers')->user()->first_name : '', [
                                                        'class' => 'form-control',
                                                        'placeholder' => 'John',
                                                        // 'required' => true,
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ __('Last Name') }}<sup aria-hidden="true">*</sup>:</label>
                                                    {!! Form::text('billing_info[lastname]', !empty(Auth::guard('customers')->user()) ? Auth::guard('customers')->user()->last_name : '', [
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Doe',
                                                        // 'required' => true,
                                                    ]) !!}

                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ __('E-mail') }}<sup aria-hidden="true">*</sup>:</label>
                                                    {!! Form::email('billing_info[email]', !empty(Auth::guard('customers')->user()) ? Auth::guard('customers')->user()->email : '', [
                                                        'class' => 'form-control',
                                                        'placeholder' => 'shop@company.com',
                                                        // 'required' => true,
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ __('Telephone') }}<sup aria-hidden="true">*</sup>:</label>
                                                    {!! Form::number('billing_info[billing_user_telephone]', !empty(Auth::guard('customers')->user()) ? Auth::guard('customers')->user()->mobile : '', [
                                                        'class' => 'form-control',
                                                        'placeholder' => '1234567890',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="your-add-form">
                                    <section>
                                        <h3 class="check-head">{{ __('Billing Address') }}</h3>
                                        <div class="form-container">
                                            <div class="row">
                                                @auth('customers')
                                                    @if (!empty($address_list->data->data))
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label> {{ __('Address') }} <sup
                                                                        aria-hidden="true">*</sup>:</label>
                                                                <select
                                                                    class="form-control billing_address_list shipping_list">
                                                                    @if (empty($address_list->data->data))
                                                                        <option value="">{{ __('select address') }}
                                                                        </option>
                                                                    @endif
                                                                    @foreach ($address_list->data->data as $address)
                                                                        <option value="{{ $address->id }}">
                                                                            {{ $address->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group">
                                                                <a href="{{ route('my-account.index', $store->slug) }}">
                                                                    <i class="ti ti-circle-plus" style="font-size: 25px;"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endauth

                                                @if (auth('customers')->user() && empty($address_list->data->data))
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Address') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::text('billing_info[billing_address]', null, [
                                                                'class' => 'form-control getvalueforval',
                                                                'placeholder' => 'address',
                                                                'required' => true,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 list_height_css">
                                                        <div class="form-group">
                                                            <label>{{ __('Country') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[billing_country]', $country_option, null, [
                                                                'class' => 'form-control country_change',
                                                                'required' => true,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 list_height_css">
                                                        <div class="form-group">
                                                            <label>{{ __('Region') }} / {{ __('State') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[billing_state]', [], null, [
                                                                'class' => 'form-control state_name state_chage',
                                                                'placeholder' => 'Select State',
                                                                'required' => true,
                                                                'data-select' => 0,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 list_height_css">
                                                        <div class="form-group">
                                                            <label>{{ __('City') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[billing_city]', [], null, [
                                                                'class' => 'form-control city_change',
                                                                'placeholder' => 'Select city',
                                                                'required' => true,
                                                                'data-select' => 0,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Post Code') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::number('billing_info[billing_postecode]', null, [
                                                                'class' => 'form-control getvalueforval',
                                                                'placeholder' => 'post code',
                                                                'required' => true,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (\App\Models\Utility::CustomerAuthCheck($store->slug) != true)
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Address') }}<sup aria-hidden="true">*</sup>:</label>
                                                            {!! Form::text('billing_info[billing_address]', null, [
                                                                'class' => 'form-control getvalueforval',
                                                                'placeholder' => 'address',
                                                                'required' => true,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 list_height_css">
                                                        <div class="form-group">
                                                            <label>{{ __('Country') }}<sup aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[billing_country]', $country_option, null, [
                                                                'class' => 'form-control country_change',
                                                                'required' => true,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 list_height_css">
                                                        <div class="form-group">
                                                            <label>{{ __('Region') }} / {{ __('State') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[billing_state]', [], null, [
                                                                'class' => 'form-control state_name state_chage',
                                                                'placeholder' => 'Select State',
                                                                'required' => true,
                                                                'data-select' => 0,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12 list_height_css">
                                                        <div class="form-group">
                                                            <label>{{ __('City') }}<sup aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[billing_city]', [], null, [
                                                                'class' => 'form-control city_change',
                                                                'placeholder' => 'Select city',
                                                                'required' => true,
                                                                'data-select' => 0,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Post Code') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::number('billing_info[billing_postecode]', null, [
                                                                'class' => 'form-control getvalueforval',
                                                                'placeholder' => 'post code',
                                                                'required' => true,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (\App\Models\Utility::CustomerAuthCheck($store->slug) == true)
                                                    @if (!empty($address_list->data->data))
                                                        <div class="col-md-6 col-12">
                                                            <div class="form-group">
                                                                <label> </label>
                                                                <div class="checkbox-custom">
                                                                    <input type="checkbox" id="da"
                                                                        name="delivery_and_billing"
                                                                        class="delivery_and_billing">
                                                                    <label for="da">
                                                                        <span>{{ __('My delivery and billing addresses are the same') }}.</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </section>

                                    <section class="Delivery_Address">
                                        <h3 class="check-head">{{ __('Delivery Address') }}</h3>
                                        <div class="form-container addressbook_checkout_edit">
                                            @if (auth('customers')->user() && empty($address_list->data->data))
                                                <div class="row list_height_css">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Address') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::text('billing_info[delivery_address]', null, [
                                                                'class' => 'form-control getvalueforval',
                                                                'placeholder' => 'address',
                                                                'required' => true,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Country') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[delivery_country]', $country_option, null, [
                                                                'class' => 'form-control country_change',
                                                                'required' => true,
                                                                'id' => 'country_id',
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Region') }} / {{ __('State') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[delivery_state]', [], null, [
                                                                'class' => 'form-control state_name state_chage delivery_list',
                                                                'placeholder' => 'Select State',
                                                                'required' => true,
                                                                'data-select' => 0,
                                                                'id' => 'state_id',
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('City') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[delivery_city]', [], null, [
                                                                'class' => 'form-control city_change delivery_list',
                                                                'placeholder' => 'Select city',
                                                                'required' => true,
                                                                'data-select' => 0,
                                                                'id' => 'city_id',
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Post Code') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::number('billing_info[delivery_postcode]', null, [
                                                                'class' => 'form-control getvalueforval',
                                                                'placeholder' => 'post code',
                                                                'required' => true,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif (\App\Models\Utility::CustomerAuthCheck($store->slug) !== true)
                                            
                                                <div class="row list_height_css">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Address') }}<sup aria-hidden="true">*</sup>:</label>
                                                            {!! Form::text('billing_info[delivery_address]', null, [
                                                                'class' => 'form-control getvalueforval',
                                                                'placeholder' => 'address',
                                                                'required' => true,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Country') }}<sup aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[delivery_country]', $country_option, null, [
                                                                'class' => 'form-control country_change',
                                                                'required' => true,
                                                                'id' => 'country_id',
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Region') }} / {{ __('State') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[delivery_state]', [], null, [
                                                                'class' => 'form-control state_name state_chage delivery_list',
                                                                'placeholder' => 'Select State',
                                                                'required' => true,
                                                                'data-select' => 0,
                                                                'id' => 'state_id',
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('City') }}<sup aria-hidden="true">*</sup>:</label>
                                                            {!! Form::select('billing_info[delivery_city]', [], null, [
                                                                'class' => 'form-control city_change delivery_list',
                                                                'placeholder' => 'Select city',
                                                                'required' => true,
                                                                'data-select' => 0,
                                                                'id' => 'city_id',
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label>{{ __('Post Code') }}<sup
                                                                    aria-hidden="true">*</sup>:</label>
                                                            {!! Form::number('billing_info[delivery_postcode]', null, [
                                                                'class' => 'form-control getvalueforval',
                                                                'placeholder' => 'post code',
                                                                'required' => true,
                                                            ]) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if (\App\Models\Utility::CustomerAuthCheck($store->slug) != true)
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label> </label>
                                                    <div class="checkbox-custom">
                                                        <input type="checkbox" id="register" name="register"
                                                            class="">
                                                        <label for="register">
                                                            <span>{{ __(' Create an account?') }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </section>

                                    <div class="form-container">
                                        <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
                                            <button class="btn continue-btn confirm_btn billing_done" type="button">
                                                {{ __('Continue') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="12"
                                                    viewBox="0 0 11 12" fill="none">
                                                    <g clip-path="url(#down)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M5.28956 0.546387C5.04611 0.546383 4.84876 0.743733 4.84875 0.987181L4.84862 9.59851L3.84237 8.56269C3.67274 8.38807 3.39367 8.38403 3.21905 8.55366C3.04443 8.72329 3.04039 9.00236 3.21002 9.17698L4.97323 10.992C5.05623 11.0774 5.17028 11.1257 5.2894 11.1257C5.40852 11.1257 5.52257 11.0774 5.60558 10.992L7.36878 9.17698C7.53841 9.00236 7.53437 8.72329 7.35975 8.55366C7.18514 8.38403 6.90606 8.38807 6.73643 8.56269L5.73022 9.59847L5.73035 0.987195C5.73036 0.743747 5.53301 0.54639 5.28956 0.546387Z"
                                                            fill="white" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="down">
                                                            <rect width="10.5792" height="10.5792" fill="white"
                                                                transform="translate(10.5791 0.546387) rotate(90)" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Payment List --}}
                        <div class="set has-children paymentlist_data_tab">
                            <a href="javascript:;" class="acnav-label">
                                <span>Step {{ auth()->guard('customers')->check() ? '2' : '3' }}: <b>{{ __('Payments Method') }}</b></span>
                            </a>
                            <div class="acnav-list paymentlist_data">

                            </div>
                        </div>

                        {{-- Additional Notes --}}
                        @if (isset($settings['additional_notes']) && $settings['additional_notes'] == 'on')
                            <div class="set has-children">
                                <a href="javascript:;" class="acnav-label additional_notes_tab">
                                    <span>Step {{ auth()->guard('customers')->check() ? '3' : '4' }}: <b>{{ __('Additional Notes') }}</b></span>
                                </a>
                                <div class="acnav-list additional_notes">

                                </div>
                            </div>
                        @endif


                        {{-- Coupon data --}}
                        {!! Form::hidden('coupon_code', null, ['id' => 'coupon_code']) !!}
                        {!! Form::hidden('sub_total', null, ['id' => 'sub_total_checkout_page']) !!}

                        <div class="set has-children comfirm_list_tab">
                            <a href="javascript:;" class="acnav-label">
                                <span>{{ __('Step') }}
                                    {{ auth()->guard('customers')->check() ? (isset($settings['additional_notes']) && $settings['additional_notes'] == 'on' ? '4' : '3') : (isset($settings['additional_notes']) && $settings['additional_notes'] == 'on' ? '5' : '4') }}:
                                    <b>
                                        {{ __('Confirm Order') }}</b></span>
                            </a>
                            <div class="acnav-list comfirm_list_data">
                                <h3 class="check-head">{{ __('Confirm order') }}</h3>
                                <p>{{ __('Please select the preferred payment method to use on this order.') }}</p>
                                <div class="order-confirmation-body">
                                    <div class="row">
                                        <div class="checkout_products col-md-4 col-sm-6 col-12">
                                            <div class="order-confirm-details">
                                                <h5> {{ __('Product informations') }} :</h5>
                                                <ul>
                                                    <li>1x Sunglasses with black ($24.99)</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="order-confirm-details">
                                                <h5>{{ __('Delivery informations') }}:</h5>
                                                <p class="mb-5"><b>{{ __('Address') }} : </b> <span
                                                        class="delivery_address"> </span> </p>
                                                <p class="mb-5"><b>{{ __('city') }} : </b> <span
                                                        class="delivery_city"> </span> </p>
                                                <p class="mb-5"><b>{{ __('state') }} : </b> <span
                                                        class="delivery_state"> </span> </p>
                                                <p class="mb-5"><b>{{ __('Country') }} : </b> <span
                                                        class="delivery_country"> </span> </p>
                                                <p class="mb-5"><b>{{ __('Postcode') }} : </b> <span
                                                        class="delivery_postcode"> </span> </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="order-confirm-details">
                                                <h5>{{ __('Billing informations') }}:</h5>
                                                <p class="mb-5"><b>{{ __('Address') }} : </b> <span
                                                        class="billing_address"> </span> </p>
                                                <p class="mb-5"><b>{{ __('city') }} : </b> <span
                                                        class="billing_city"> </span> </p>
                                                <p class="mb-5"><b>{{ __('state') }} : </b> <span
                                                        class="billing_state"> </span> </p>
                                                <p class="mb-5"><b>{{ __('Country') }} : </b> <span
                                                        class="billing_country"> </span> </p>
                                                <p class="mb-5"><b>{{ __('Postcode') }} : </b> <span
                                                        class="billing_postecode"> </span> </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-payment-box">
                                    <div class="order-paymentcol">
                                        <div class="order-paycol-inner">
                                            <p>{{ __('Payment method') }}</p>
                                            <img src="" class="payment_img_path" alt="">
                                        </div>
                                    </div>
                                    <div class="order-paymentcol">
                                    </div>
                                    <div class="checkout_amount order-paymentcol">
                                        <div class="order-paycol-inner">
                                            <div class="d-flex align-items-center justify-content-between payment-ttl-row">
                                                <div class="payment-ttl-left">
                                                    <span>
                                                        {{ __('Sub-total') }}: <b> $0.00</b>
                                                    </span>
                                                    <span>
                                                        {{ __('TAX') }} (00%)<b>$0.30</b>
                                                    </span>
                                                </div>
                                                <div class="payment-ttl-left">
                                                    <h5>{{ __('Total') }}:</h5>
                                                    <div class="ttl-pric"> $0.00 </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="payment_type payment_types" id="payment_type" value="">
                                <input type="hidden" class="method_id" id="method_id" name="method_id" value="">
                                <div class="form-container">
                                    <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
                                        <button class="btn continue-btn place_order_submit payfast_form" id="payfast_form"
                                            type="submit">
                                            {{ __('Confirm Order') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                                viewBox="0 0 35 14" fill="none">
                                                <path
                                                    d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="checkout_page_cart col-lg-3 col-12 ">
                    <div class="checkout-page-right">

                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('front_end.sections.partision.footer_section')
@endsection
@php
    $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : $currentTheme;
    $payfast_mode = \App\Models\Utility::GetValueByName('payfast_mode', $theme_name);
    $pfHost = $payfast_mode == 'sandbox' ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';

@endphp

@push('page-script')
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(() => {
                $("#formdata").append("<div id='get-payfast-input-data'></div>");
            }, 100);
        });
        $(document).on("click", ".payfast_form", function(event) {

            var payment_type = $('.payment_types').val();
            if (payment_type == 'payfast') {
                get_payfast_status();
            }else{
                $('#formdata').submit();
            }
        });
        @if (\Auth::guard('customers')->user())
            function get_payfast_status(amount,coupon){
                var formdata = $('#formdata').serializeArray();
                var slug = '{{ $store->slug }}';
                $.ajax({
                    url: payfast_payment,
                    method: 'POST',
                    data: formdata,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.success == true) {
                            $('#get-payfast-input-data').append(data.inputs);
                            $('#formdata').submit();
                        }else{
                            show_toastr('Error', data.inputs, 'error')
                        }
                    }
                });
            }
        @else
            function get_payfast_status(amount,coupon){
                var formdata = $('#formdata').serializeArray();
                var slug = '{{ $store->slug }}';
                $.ajax({
                    url: payfast_payment_guest,
                    method: 'POST',
                    data: formdata,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.success == true) {
                            $('#get-payfast-input-data').append(data.inputs);
                            $('#formdata').submit();
                        }else{
                            show_toastr('Error', data.inputs, 'error')
                        }
                    }
                });
            }
        @endif
        $(".payfast_form").on("click", function(e) {
            var payment_type = $('#payment_type').val();

            if (payment_type == 'payfast') {
                $('#formdata').attr('action', "https://{{ $pfHost }}/eng/process");
                e.preventDefault();
            }
        });
    </script>
@endpush