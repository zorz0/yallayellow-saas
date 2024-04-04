@extends('front_end.layouts.app')
@section('page-title')
    {{ __('My Account') }}
@endsection
@php
    
@endphp

@section('content')
    @include('front_end.sections.partision.header_section')
        <section class="my-account-page padding-bottom padding-top">
            <div class="container">
                <div class="my-acc-head">
                    <div class="d-flex justify-content-start back-toshop">
                        <a href="{{route('page.product-list',$slug)}}" class="back-btn">
                            <span class="svg-ic">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z"
                                        fill="white"></path>
                                </svg>
                            </span> {{ __('Back to Shop') }}
                        </a>
                    </div>
                    <div class="section-title">
                        <h2>{{ __('My Account') }}</h2>
                    </div>
                </div>
                <div class="row align-items-start">
                    <div class="col-lg-3 col-md-4 col-12 my-acc-column" id="scroll">
                        <div class="my-acc-leftbar">
                            <h4>{{ __('Account Settings') }}</h4>
                            <ul class="account-list" id="account-nav">
                                <li class="active">
                                    <a href="#tab-1" data-scroll="tab-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 13C8.13401 13 5 16.134 5 20V22C5 22.5523 4.55228 23 4 23C3.44772 23 3 22.5523 3 22V20C3 15.0294 7.02944 11 12 11C16.9706 11 21 15.0294 21 20V22C21 22.5523 20.5523 23 20 23C19.4477 23 19 22.5523 19 22V20C19 16.134 15.866 13 12 13Z"
                                                fill="#183A40" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11ZM12 13C15.3137 13 18 10.3137 18 7C18 3.68629 15.3137 1 12 1C8.68629 1 6 3.68629 6 7C6 10.3137 8.68629 13 12 13Z"
                                                fill="#183A40" />
                                        </svg>
                                        <span>
                                            <b>{{ __('Edit your account information') }}</b>
                                            {{ __('edit your account') }}
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab-2" data-scroll="tab-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 11C10.2274 11 8.64844 11.0646 7.35838 11.1466C6.13471 11.2243 5.19379 12.158 5.10597 13.373C5.0435 14.2373 5 15.1481 5 16C5 16.8519 5.0435 17.7627 5.10597 18.627C5.19379 19.842 6.13471 20.7757 7.35838 20.8534C8.64844 20.9354 10.2274 21 12 21C13.7726 21 15.3516 20.9354 16.6416 20.8534C17.8653 20.7757 18.8062 19.842 18.894 18.627C18.9565 17.7627 19 16.8519 19 16C19 15.1481 18.9565 14.2373 18.894 13.373C18.8062 12.158 17.8653 11.2243 16.6416 11.1466C15.3516 11.0646 13.7726 11 12 11ZM7.2315 9.15059C5.01376 9.29156 3.27137 11.0124 3.11117 13.2288C3.04652 14.1234 3 15.085 3 16C3 16.915 3.04652 17.8766 3.11118 18.7712C3.27137 20.9876 5.01376 22.7084 7.23151 22.8494C8.55778 22.9337 10.1795 23 12 23C13.8205 23 15.4422 22.9337 16.7685 22.8494C18.9862 22.7084 20.7286 20.9876 20.8888 18.7712C20.9535 17.8766 21 16.915 21 16C21 15.085 20.9535 14.1234 20.8888 13.2288C20.7286 11.0124 18.9862 9.29156 16.7685 9.15059C15.4422 9.06629 13.8205 9 12 9C10.1795 9 8.55778 9.06629 7.2315 9.15059Z"
                                                fill="#67898F" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M13 16.7324C13.5978 16.3866 14 15.7403 14 15C14 13.8954 13.1046 13 12 13C10.8954 13 10 13.8954 10 15C10 15.7403 10.4022 16.3866 11 16.7324V18C11 18.5523 11.4477 19 12 19C12.5523 19 13 18.5523 13 18V16.7324Z"
                                                fill="#67898F" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7 6C7 3.23858 9.23858 1 12 1C14.7614 1 17 3.23858 17 6V10C17 10.5523 16.5523 11 16 11C15.4477 11 15 10.5523 15 10V6C15 4.34315 13.6569 3 12 3C10.3431 3 9 4.34315 9 6V10C9 10.5523 8.55228 11 8 11C7.44772 11 7 10.5523 7 10V6Z"
                                                fill="#67898F" />
                                        </svg>
                                        <span>
                                            <b>{{ __('Change your password') }}</b>
                                            {{ __('Change Your Passowrd') }}
                                        </span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#tab-3" data-scroll="tab-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M19 20C19.5523 20 20 19.5523 20 19C20 18.4477 19.5523 18 19 18C18.4477 18 18 18.4477 18 19C18 19.5523 18.4477 20 19 20ZM19 22C20.6569 22 22 20.6569 22 19C22 17.3431 20.6569 16 19 16C17.3431 16 16 17.3431 16 19C16 20.6569 17.3431 22 19 22Z"
                                                fill="#67898F" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M15.5 4C14.1193 4 13 5.11929 13 6.5V17.5C13 19.9853 10.9853 22 8.5 22C6.01472 22 4 19.9853 4 17.5V10C4 9.44772 4.44772 9 5 9C5.55228 9 6 9.44772 6 10V17.5C6 18.8807 7.11929 20 8.5 20C9.88071 20 11 18.8807 11 17.5V6.5C11 4.01472 13.0147 2 15.5 2C17.9853 2 20 4.01472 20 6.5V13C20 13.5523 19.5523 14 19 14C18.4477 14 18 13.5523 18 13V6.5C18 5.11929 16.8807 4 15.5 4Z"
                                                fill="#67898F" />
                                            <path
                                                d="M4.13595 2.48099C4.52183 1.81949 5.47763 1.81949 5.86351 2.48099L7.62247 5.49636C8.01135 6.16302 7.53048 7.00023 6.75869 7.00023H3.24076C2.46897 7.00023 1.9881 6.16301 2.37699 5.49636L4.13595 2.48099Z"
                                                fill="#67898F" />
                                        </svg>
                                        <span>
                                            <b>{{ __('Modify your address book entries') }}</b>
                                            {{ __('Edit your address') }}
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab-4" data-scroll="tab-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M20 6H4C2.89543 6 2 6.89543 2 8V16C2 17.1046 2.89543 18 4 18H20C21.1046 18 22 17.1046 22 16V8C22 6.89543 21.1046 6 20 6ZM4 4C1.79086 4 0 5.79086 0 8V16C0 18.2091 1.79086 20 4 20H20C22.2091 20 24 18.2091 24 16V8C24 5.79086 22.2091 4 20 4H4Z"
                                                fill="#67898F" />
                                            <path
                                                d="M16 10C16 9.44772 16.4477 9 17 9H19C19.5523 9 20 9.44772 20 10C20 10.5523 19.5523 11 19 11H17C16.4477 11 16 10.5523 16 10Z"
                                                fill="#67898F" />
                                            <path
                                                d="M4 14C4 13.4477 4.44772 13 5 13C5.55228 13 6 13.4477 6 14C6 14.5523 5.55228 15 5 15C4.44772 15 4 14.5523 4 14Z"
                                                fill="#67898F" />
                                            <path
                                                d="M8 14C8 13.4477 8.44772 13 9 13H15C15.5523 13 16 13.4477 16 14C16 14.5523 15.5523 15 15 15H9C8.44772 15 8 14.5523 8 14Z"
                                                fill="#67898F" />
                                            <path
                                                d="M19 13C18.4477 13 18 13.4477 18 14C18 14.5523 18.4477 15 19 15C19.5523 15 20 14.5523 20 14C20 13.4477 19.5523 13 19 13Z"
                                                fill="#67898F" />
                                            <path
                                                d="M13 9C12.4477 9 12 9.44772 12 10C12 10.5523 12.4477 11 13 11C13.5523 11 14 10.5523 14 10C14 9.44772 13.5523 9 13 9Z"
                                                fill="#67898F" />
                                            <path
                                                d="M8 10C8 9.44772 8.44772 9 9 9C9.55228 9 10 9.44772 10 10C10 10.5523 9.55228 11 9 11C8.44772 11 8 10.5523 8 10Z"
                                                fill="#67898F" />
                                            <path
                                                d="M5 9C4.44772 9 4 9.44772 4 10C4 10.5523 4.44772 11 5 11C5.55228 11 6 10.5523 6 10C6 9.44772 5.55228 9 5 9Z"
                                                fill="#67898F" />
                                        </svg>
                                        <span>
                                            <b>{{__('Support Ticket')}}</b>
                                            {{__('Get support on your order')}}
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab-5" data-scroll="tab-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M20 13H13.5C13.2239 13 13 13.2239 13 13.5V20.5C13 20.7761 13.2239 21 13.5 21H20C20.5523 21 21 20.5523 21 20V14C21 13.4477 20.5523 13 20 13ZM11 11V23H20C21.6569 23 23 21.6569 23 20V14C23 12.3431 21.6569 11 20 11H11Z"
                                                fill="#67898F" />
                                            <path d="M16 11H18V14C18 14.5523 17.5523 15 17 15C16.4477 15 16 14.5523 16 14V11Z"
                                                fill="#67898F" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M11 13.5C11 13.2239 10.7761 13 10.5 13H4C3.44772 13 3 13.4477 3 14V20C3 20.5523 3.44772 21 4 21H10.5C10.7761 21 11 20.7761 11 20.5V13.5ZM4 11C2.34315 11 1 12.3431 1 14V20C1 21.6569 2.34315 23 4 23H13V11H4Z"
                                                fill="#67898F" />
                                            <path d="M6 11H8V14C8 14.5523 7.55228 15 7 15C6.44772 15 6 14.5523 6 14V11Z"
                                                fill="#67898F" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M15 3H9C8.44772 3 8 3.44772 8 4V11H16V4C16 3.44772 15.5523 3 15 3ZM9 1C7.34315 1 6 2.34315 6 4V13H18V4C18 2.34315 16.6569 1 15 1H9Z"
                                                fill="#67898F" />
                                            <path d="M11 1H13V4C13 4.55228 12.5523 5 12 5C11.4477 5 11 4.55228 11 4V1Z"
                                                fill="#67898F" />
                                        </svg>
                                        <span>
                                            <b>{{ __('View your order history') }}</b>
                                            {{ __('See your order history') }}
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab-6" data-scroll="tab-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.9993 11.0292C9.02013 11.5811 8.58962 12.0454 8.03773 12.0662C6.79354 12.1132 5.88545 12.1791 5.23437 12.244C4.59318 12.3079 4.20739 12.695 4.14143 13.2345C4.06365 13.8708 4 14.7683 4 16C4 17.2317 4.06366 18.1292 4.14144 18.7655C4.20736 19.3048 4.59333 19.6921 5.23467 19.7559C6.44321 19.8763 8.53118 20 12 20C15.4688 20 17.5568 19.8763 18.7653 19.7559C19.4067 19.6921 19.7926 19.3048 19.8586 18.7655C19.9363 18.1292 20 17.2317 20 16C20 14.7683 19.9363 13.8708 19.8586 13.2345C19.7926 12.695 19.4068 12.3079 18.7656 12.244C18.1145 12.1791 17.2065 12.1132 15.9623 12.0662C15.4104 12.0454 14.9799 11.5811 15.0007 11.0292C15.0215 10.4773 15.4858 10.0468 16.0377 10.0676C17.3148 10.1158 18.2646 10.1842 18.9639 10.2538C20.4341 10.4003 21.6523 11.4253 21.8438 12.9919C21.9329 13.7211 22 14.7008 22 16C22 17.2992 21.9329 18.2789 21.8438 19.0082C21.6523 20.5748 20.4334 21.5997 18.9636 21.7461C17.6693 21.875 15.5115 22 12 22C8.48847 22 6.33068 21.875 5.03643 21.7461C3.56655 21.5997 2.34774 20.5748 2.15621 19.0082C2.06707 18.2789 2 17.2992 2 16C2 14.7008 2.06707 13.7211 2.15621 12.9919C2.3477 11.4253 3.56595 10.4003 5.03612 10.2538C5.73543 10.1842 6.68525 10.1158 7.96228 10.0676C8.51418 10.0468 8.97846 10.4773 8.9993 11.0292Z"
                                                fill="#67898F" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.20711 7.20711C8.81658 7.59763 8.18342 7.59763 7.79289 7.20711C7.40237 6.81658 7.40237 6.18342 7.79289 5.79289L11.2929 2.29289C11.6834 1.90237 12.3166 1.90237 12.7071 2.29289L16.2071 5.79289C16.5976 6.18342 16.5976 6.81658 16.2071 7.20711C15.8166 7.59763 15.1834 7.59763 14.7929 7.20711L13 5.41421L13 15C13 15.5523 12.5523 16 12 16C11.4477 16 11 15.5523 11 15L11 5.41421L9.20711 7.20711Z"
                                                fill="#67898F" />
                                        </svg>
                                        <span>
                                            <b>{{ __('Your Reward Points') }}</b>
                                            {{ __('Count Reward Point') }}
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab-7" data-scroll="tab-7">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M9.52275 8.93361C9.42172 9.17558 9.19391 9.3408 8.93252 9.36167L4.18325 9.74094C3.56183 9.79057 3.31039 10.5666 3.78461 10.9712L7.39904 14.0554C7.59925 14.2262 7.68672 14.4949 7.62542 14.7509L6.52045 19.3652C6.37552 19.9704 7.03337 20.4497 7.56505 20.1262L11.6365 17.649C11.86 17.5131 12.1406 17.5131 12.3642 17.649L16.4356 20.1262C16.9672 20.4497 17.6251 19.9704 17.4802 19.3652L16.3752 14.7509C16.3139 14.4949 16.4014 14.2262 16.6016 14.0554L20.216 10.9712C20.6902 10.5666 20.4388 9.79057 19.8174 9.74094L15.0681 9.36167C14.8067 9.3408 14.5789 9.17558 14.4779 8.93361L12.6463 4.54699C12.4067 3.97327 11.5939 3.97327 11.3544 4.54699L9.52275 8.93361ZM16.0179 7.43115L14.4918 3.77638C13.5679 1.56346 10.4328 1.56348 9.50877 3.77638L7.98275 7.43115L4.02404 7.74729C1.62712 7.9387 0.65727 10.9318 2.48641 12.4926L5.49492 15.0597L4.57544 18.8994C4.0164 21.234 6.55383 23.0826 8.60461 21.8348L12.0003 19.7688L15.396 21.8348C17.4468 23.0826 19.9842 21.2339 19.4252 18.8994L18.5057 15.0597L21.5142 12.4926C23.3434 10.9318 22.3735 7.9387 19.9766 7.74729L16.0179 7.43115Z"
                                                fill="#67898F" />
                                        </svg>
                                        <span>
                                            <b>{{ __('View your return requests') }}</b>
                                            {{ __('See your Return') }}
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tab-8" data-scroll="tab-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3 2C2.44772 2 2 2.44772 2 3C2 3.55228 2.44772 4 3 4H4V18C4 20.2091 5.79086 22 8 22H16C18.2091 22 20 20.2091 20 18V4H21C21.5523 4 22 3.55228 22 3C22 2.44772 21.5523 2 21 2H3ZM18 4H6V18C6 19.1046 6.89543 20 8 20H16C17.1046 20 18 19.1046 18 18V4Z"
                                                fill="#67898F" />
                                            <path
                                                d="M12.7071 7.29289C12.3166 6.90237 11.6834 6.90237 11.2929 7.29289L8.29289 10.2929C7.90237 10.6834 7.90237 11.3166 8.29289 11.7071C8.68342 12.0976 9.31658 12.0976 9.70711 11.7071L11 10.4142V16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16V10.4142L14.2929 11.7071C14.6834 12.0976 15.3166 12.0976 15.7071 11.7071C16.0976 11.3166 16.0976 10.6834 15.7071 10.2929L12.7071 7.29289Z"
                                                fill="#67898F" />
                                        </svg>
                                        <span>
                                            <b>{{ __('wishlist') }}</b>
                                            {{ __('See your wishlist') }}
                                        </span>
                                    </a>
                                </li>
                                <li class="ogout-link">
                                    <form method="POST" action="{{ route('customer.logout',$slug) }}" id="form_logout">
                                        @csrf
                                    <a href="" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                            viewBox="0 0 22 22" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M10.1096 13.7506C10.6155 13.7315 11.0411 14.1261 11.0602 14.632C11.1033 15.7725 11.1638 16.605 11.2232 17.2018C11.2818 17.7895 11.6366 18.1432 12.1312 18.2036C12.7144 18.2749 13.5372 18.3333 14.6662 18.3333C15.7953 18.3333 16.618 18.2749 17.2012 18.2036C17.6956 18.1432 18.0506 17.7894 18.1092 17.2015C18.2195 16.0937 18.3329 14.1797 18.3329 11C18.3329 7.82021 18.2195 5.90623 18.1092 4.79841C18.0506 4.21051 17.6956 3.85671 17.2012 3.79627C16.618 3.72498 15.7953 3.66662 14.6662 3.66662C13.5372 3.66662 12.7144 3.72497 12.1312 3.79627C11.6366 3.85673 11.2818 4.21037 11.2232 4.79813C11.1638 5.39495 11.1033 6.22737 11.0602 7.36787C11.0411 7.87377 10.6155 8.2684 10.1096 8.24931C9.60374 8.23021 9.20911 7.80461 9.2282 7.29871C9.2724 6.1281 9.33505 5.25743 9.39891 4.61639C9.53316 3.26874 10.4728 2.15202 11.9088 1.97648C12.5772 1.89477 13.4753 1.83329 14.6662 1.83329C15.8572 1.83329 16.7552 1.89477 17.4237 1.97649C18.8598 2.15205 19.7992 3.26929 19.9335 4.61668C20.0517 5.80308 20.1662 7.78106 20.1662 11C20.1662 14.2189 20.0516 16.1968 19.9335 17.3832C19.7992 18.7306 18.8598 19.8479 17.4237 20.0234C16.7552 20.1051 15.8572 20.1666 14.6662 20.1666C13.4753 20.1666 12.5772 20.1051 11.9088 20.0234C10.4728 19.8479 9.53316 18.7312 9.39891 17.3835C9.33505 16.7425 9.2724 15.8718 9.2282 14.7012C9.20911 14.1953 9.60374 13.7697 10.1096 13.7506Z"
                                                fill="#FE4D4D" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.60652 13.5602C6.96451 13.9181 6.96451 14.4985 6.60652 14.8565C6.24854 15.2145 5.66814 15.2145 5.31016 14.8565L2.10183 11.6482C1.74385 11.2902 1.74385 10.7098 2.10183 10.3518L5.31016 7.14349C5.66814 6.7855 6.24854 6.7855 6.60652 7.14349C6.96451 7.50147 6.96451 8.08187 6.60652 8.43985L4.96304 10.0833H13.75C14.2563 10.0833 14.6667 10.4937 14.6667 11C14.6667 11.5063 14.2563 11.9167 13.75 11.9167L4.96304 11.9167L6.60652 13.5602Z"
                                                fill="#FE4D4D" />
                                        </svg>
                                        <span>
                                            <b>{{ __('Logout') }} </b>
                                            {{ __('See you soon!') }}
                                        </span>
                                    </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-12">
                        <div class="my-acc-rightbar">
                            <div id="tab-1" class="my-acc-right-content">
                                <div class="section-title">
                                    <h2>{{ __('Your Personal Details') }}</h2>
                                </div>
                                <div class="form-wrapper">
                                    {!! Form::model(auth('customers')->user(), [
                                        'route' => ['profile.update',$slug],
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                    ]) !!}
                                    <div class="form-container">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ __('First Name') }}<sup aria-hidden="true">*</sup>:</label>
                                                    {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'John']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ __('Last Name') }}<sup aria-hidden="true">*</sup>:</label>
                                                    {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Doe', 'required' => true]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ __('E-mail') }}<sup aria-hidden="true">*</sup>:</label>
                                                    {!! Form::email('email', null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => 'shop@company.com',
                                                        'required' => true,
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ __('Telephone') }}<sup aria-hidden="true">*</sup>:</label>
                                                    {!! Form::number('mobile', null, ['class' => 'form-control', 'placeholder' => '1234567890', 'required' => true]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-container">
                                        <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
                                            <button class="btn-secondary back-btn-acc" type="submit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                                    viewBox="0 0 35 14" fill="none">
                                                    <path
                                                        d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                    </path>
                                                </svg>
                                                {{ __('Back') }}
                                            </button>
                                            <button class="btn continue-btn" type="submit">
                                                {{ __('Continue') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                                    viewBox="0 0 35 14" fill="none">
                                                    <path
                                                        d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div id="tab-2" class="my-acc-right-content">
                                <div class="section-title">
                                    <h2>{{ __('Change Password') }}</h2>
                                </div>
                                <div class="form-wrapper">
                                    {!! Form::model(auth('customers')->user(), [
                                        'route' => ['customer.password.change',['storeSlug'=>$slug]],
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                    ]) !!}
                                    <div class="form-container">
                                        <div class="row">
                                            <div class="col-md-12 col-12">
                                            <input type="hidden" name="type" value="{{auth('customers')->user()->type ?? 'customer'}}">
                                                <div class="form-group">
                                                    <label>{{ __('Old Password') }}<sup aria-hidden="true">*</sup>:</label>
                                                    <input name="old_password" type="password" class="form-control"
                                                        placeholder="**********" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ __('Password') }}<sup aria-hidden="true">*</sup>:</label>
                                                    <input name="new_password" type="password" class="form-control"
                                                        placeholder="**********" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>{{ __('Confirm password') }}<sup
                                                            aria-hidden="true">*</sup>:</label>
                                                    <input name="new_password_confirmation" type="password"
                                                        class="form-control" placeholder="***********" required="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-container">
                                        <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
                                            <button class="btn-secondary back-btn-acc" type="submit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                                    viewBox="0 0 35 14" fill="none">
                                                    <path
                                                        d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                    </path>
                                                </svg>
                                                {{ __('Back') }}
                                            </button>
                                            <button class="btn continue-btn" type="submit">
                                                {{ __('Continue') }}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                                    viewBox="0 0 35 14" fill="none">
                                                    <path
                                                        d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>

                            <div id="tab-3" class="my-acc-right-content">
                                
                                <div class="section-title"> <h2>{{ __('Address book') }}</h2> </div>
                                <div class="order-history-frame address-book-div"> </div>
                            </div>

                            <div id="tab-4" class="my-acc-right-content">
                                <div class="section-title"> <h2>{{ __('Support Ticket') }}</h2> </div>
                                <div class="order-history-frame support-ticket-div">
                                </div>
                            </div>

                            <div id="tab-5" class="my-acc-right-content">
                                <div class="section-title"> <h2>{{ __('Order List') }}</h2> </div>
                                <div class="order-history-frame order-div"> </div>
                            </div>

                            <div id="tab-6" class="my-acc-right-content">
                                <div class="section-title"> <h2>{{ __('Reward List') }}</h2> </div>
                                <div class="order-history-frame reward-div"> </div>
                            </div>

                            <div id="tab-7" class="my-acc-right-content">
                                <div class="section-title"> <h2>{{ __('Order return list') }}</h2> </div>
                                <div class="order-history-frame order-return-div"> </div>
                            </div>

                            <div id="tab-8" class="my-acc-right-content">
                                <div class="section-title">
                                    <h2> {{ __('Wish list') }} <span class="wishcount">[0]</span></h2>
                                </div>
                                <div class="order-confirmation-body wish-list-div"> </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    @include('front_end.sections.partision.footer_section')
@endsection

@push('page-script')
    <script>
        $(document).ready(function(e) {
            get_address();
            get_order();
            get_reward();
            get_order_return();
            get_wishlist();
            get_ticket();
        });

         //ticket start
         function get_ticket(url = '') {
            if(url == '') {
                url = '{{ route('support.ticket',$slug) }}?page=1';
            }
            var data = {};
            $.ajax({
                url: url,
                method: 'GET',
                data: data,
                context:this,
                success: function (response)
                {
                    $('.support-ticket-div').html(response.html);
                }
            });
        }
        
        // Addresbook start
        $(document).on('click', '.edit_address', function() {
            var id = $(this).attr('data-id');
            var url = '{{ route('get.addressbook.data',$slug) }}?id='+id;
            $.ajax({
                url: url,
                method: 'GET',
                context:this,
                success: function(response)
                {
                    $('.addressbook-form').html(response.html);
                    $('.addressbook-form-title').html(response.form_title);
                    $('.country_change').trigger('change');
                }
            });
        });

        $(document).on('click', '.delete_address', function() {
            var id = $(this).attr('data-id');
            var url = '{{ route('delete.addressbook',$slug) }}?id='+id;
            $.ajax({
                url: url,
                method: 'GET',
                context:this,
                success: function(response)
                {
                    get_address();
                }
            });
        });

        function get_address(url = '') {
            if(url == '') {
                url = '{{ route('addressbook',$slug) }}?page=1';
            }
            var data = {};
            $.ajax({
                url: url,
                method: 'GET',
                data: data,
                context:this,
                success: function (response)
                {
                    $('.address-book-div').html(response.html);
                }
            });
        }
        // Addresbook end

        // Order start
        function get_order(url = '') {
            if(url == '') {
                url = '{{ route('order.list',$slug) }}?page=1';
            }
            var data = {};
            $.ajax({
                url: url,
                method: 'GET',
                data: data,
                context:this,
                success: function (response)
                {
                    $('.order-div').html(response.html);
                }
            });
        }
        // Order end

        // order return start
        function get_order_return(url = '') {
            if(url == '') {
                url = '{{ route('order.return.list',$slug) }}?page=1';
            }
            var data = {};
            $.ajax({
                url: url,
                method: 'GET',
                data: data,
                context:this,
                success: function (response)
                {
                    $('.order-return-div').html(response.html);
                }
            });
        }
        // order return end

        // reward start
        function get_reward(url = '') {
            if(url == '') {
                url = '{{ route('reward.list',$slug) }}?page=1';
            }
            var data = {};
            $.ajax({
                url: url,
                method: 'GET',
                data: data,
                context:this,
                success: function (response)
                {
                    $('.reward-div').html(response.html);
                }
            });
        }
        // reward end


        $(document).on('click', '.delete_wishlist', function() {
            var id = $(this).attr('data-id');
            var url = '{{ route('delete.wishlist',$slug) }}?id='+id;
            $.ajax({
                url: url,
                method: 'GET',
                context:this,
                success: function(response)
                {
                    get_wishlist();
                }
            });
        });
        // wishlist end
    </script>
@endpush