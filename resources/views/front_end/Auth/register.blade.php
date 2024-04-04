@extends('front_end.layouts.app')
@section('page-title')
    {{ __('Register Page') }}
@endsection
@section('content')
    @include('front_end.sections.partision.header_section')
    <section class="register-page padding-bottom padding-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-12">
                    <div class="d-flex justify-content-center back-toshop">
                        <a href="{{route('page.product-list',$store->slug)}}" class="back-btn">
                            <span class="svg-ic">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z" fill="white"></path>
                                </svg>
                            </span>
                            {{ __('Back to Shop') }}
                        </a>
                    </div>
                    <div class="section-title text-center">
                        <h2>{{ __('Register') }}</h2>
                    </div>
                    <div class="form-wrapper register-form">
                        <form method="POST" action="{{ route('customer.registerdata',$store->slug) }}">
                            @csrf
                            <div class="form-container">
                                <div class="form-heading">
                                    <h4> {{ __('Your') }} <b> {{ __('Personal Details') }} </b></h4>
                                </div>
                            </div>
                            <div class="form-container">
                                <div class="row">
                                    <div class="col-12">
                                        @if ($errors->any())
                                            <ul class="mt-3 list-disc list-inside ">
                                                @foreach ($errors->all() as $error)
                                                    <li class="text-sm text-danger text-600">{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <br>
                                        @endif
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>{{ __('First Name') }}<sup aria-hidden="true">*</sup>:</label>
                                            <input name="first_name" type="text" class="form-control" placeholder="John" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label> {{ __('Last Name') }} <sup aria-hidden="true">*</sup>:</label>
                                            <input name="last_name" type="text" class="form-control" placeholder="Doe" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label> {{ __('E-mail') }} <sup aria-hidden="true">*</sup>:</label>
                                            <input name="email" type="email" class="form-control" placeholder="shop@company.com" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label> {{ __('Telephone') }} <sup aria-hidden="true">*</sup>:</label>
                                            <input name="mobile" type="number" class="form-control" placeholder="1234567890" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-container">
                                <h4> {{ __('Your') }} <b> {{ __('Password') }} </b></h4>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label>{{ __('Password') }}<sup aria-hidden="true">*</sup>:</label>
                                            <input name="password" type="password" class="form-control" placeholder="**********" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label> {{ __('Confirm password') }} <sup aria-hidden="true">*</sup>:</label>
                                            <input name="password_confirmation" type="password" class="form-control" placeholder="***********" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-container">
                                <div class="row">
                                    <div class="col-sm-6 col-12">
                                        <h4><b> {{ __('Subscribe') }} </b> {{ __('our newsletter?')}} </h4>
                                    </div>
                                    <div class="col-sm-6 col-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="radio-group">
                                                    <input type="radio" id="yes" name="subscribe" value="yes" checked/>
                                                    <label for="yes"> {{ __('Yes')}} </label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="radio-group">
                                                    <input type="radio" id="no" name="subscribe" value="no"/>
                                                    <label for="no"> {{ __('No')}} </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center form-footer">
                                    <div class="col-lg-8   col-12">
                                        <div class="checkbox-custom">
                                            <input type="checkbox" id="ch1">
                                            <label for="ch1">
                                                <span> {{ __('I have read and agree to the ')}} <a href="#"> {{ __('Terms')}} &amp; {{ __('Conditions.')}} </a>  </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-12">
                                        {!! Form::hidden('login_type', 'customer') !!}
                                        <button class="btn submit-btn" type="submit">
                                            {{ __('Register') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                                                <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('front_end.sections.partision.footer_section')
@endsection



