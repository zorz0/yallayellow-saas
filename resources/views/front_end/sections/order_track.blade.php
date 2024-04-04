@extends('layouts.layouts')

@section('page-title')
    {{ __('Order Track') }}
@endsection

@section('content')
    @include('front_end.sections.partision.header_section')
    <section class="about-us-page padding-bottom padding-top ">
        <div class="container">
            @if ( !empty($order))
                @if( !empty($order_status->delivered_status != 2))
                    <div class="card">
                        <div class="card-body">
                            <div
                                class="account-info d-flex align-items-center gap-6 p-4 p-sm-6 bg-white rounded mb-4 flex-wrap flex-lg-nowrap">
                                <div class="profile-pic bg-shade rounded">
                                    <img src="{{ !empty($customer->profile_image) ? get_file($customer->profile_image, APP_THEME()) : asset(Storage::url('uploads/customerprofile/avatar.png')) }}"
                                        alt="avatar" class="img-fluid rounded-2">
                                </div>
                                <div class="profile-inf-right">
                                    <h4 class="">{{ !empty($order_detail['delivery_informations']['name']) ? $order_detail['delivery_informations']['name'] : $customer->first_name }}</h4>
                                    <div class="info-meta d-flex align-items-center gap-2 gap-md-4 fs-xs flex-wrap">
                                        <span><i
                                                class="fa fa-email fa-envelope"></i>{{ $order_detail['delivery_informations']['email'] }}</span>
                                        <span><i
                                                class="fa fa-phonenumber fa-phone me-2"></i>+{{ $order_detail['delivery_informations']['phone'] }}</span>
                                    </div>
                                    <div class="profile-achievements d-flex align-items-center flex-wrap mt-6">
                                        <div class="achievement-box d-flex align-items-center gap-3 " id="account">
                                            <span
                                                class=" d-inline-flex align-items-center justify-content-center flex-shrink-0 bg-color-1 rounded-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                    viewBox="0 0 384 512">
                                                    <style>
                                                        svg {
                                                            fill: var(--second-color)
                                                        }
                                                    </style>
                                                    <path
                                                        d="M32 0C14.3 0 0 14.3 0 32S14.3 64 32 64V75c0 42.4 16.9 83.1 46.9 113.1L146.7 256 78.9 323.9C48.9 353.9 32 394.6 32 437v11c-17.7 0-32 14.3-32 32s14.3 32 32 32H64 320h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V437c0-42.4-16.9-83.1-46.9-113.1L237.3 256l67.9-67.9c30-30 46.9-70.7 46.9-113.1V64c17.7 0 32-14.3 32-32s-14.3-32-32-32H320 64 32zM288 437v11H96V437c0-25.5 10.1-49.9 28.1-67.9L192 301.3l67.9 67.9c18 18 28.1 42.4 28.1 67.9z" />
                                                </svg>
                                            </span>
                                            <div>
                                                <h6 class="mb-1">
                                                    {{ date('Y-m-d ', strtotime($order->order_date)) }}</h6>
                                                <span>{{ __('Pendding') }}</span>
                                            </div>
                                        </div>

                                        <div class="achievement-box d-flex align-items-center gap-3">
                                            <span
                                                class=" bg-color-3 d-inline-flex align-items-center justify-content-center flex-shrink-0 bg-color-1 rounded-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                    viewBox="0 0 512 512">
                                                    <style>
                                                        svg {
                                                            fill: var(--second-color)
                                                        }
                                                    </style>
                                                    <path
                                                        d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
                                                </svg>
                                            </span>
                                            <div>
                                                <h6 class="mb-1">
                                                    @if(in_array($order_status->delivered_status, [1, 4, 5, 6]))
                                                    {{ !empty($order->confirmed_date)  ? $order->confirmed_date :$order->picked_date}}
                                                        @if(empty($order->picked_date))
                                                        {{ !empty($order->shipped_date)  ? $order->shipped_date : $order->delivery_date }}
                                                        @endif
                                                    @endif
                                                </h6>
                                                <span>{{ __('Comfirmed') }}</span>
                                            </div>
                                        </div>


                                        <div class="achievement-box d-flex align-items-center gap-3">
                                            <span
                                                class=" bg-color-2 d-inline-flex align-items-center justify-content-center flex-shrink-0 bg-color-1 rounded-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                    viewBox="0 0 640 512">
                                                    <style>
                                                        svg {
                                                            fill: var(--second-color)
                                                        }
                                                    </style>
                                                    <path
                                                        d="M48 0C21.5 0 0 21.5 0 48V368c0 26.5 21.5 48 48 48H64c0 53 43 96 96 96s96-43 96-96H384c0 53 43 96 96 96s96-43 96-96h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V288 256 237.3c0-17-6.7-33.3-18.7-45.3L512 114.7c-12-12-28.3-18.7-45.3-18.7H416V48c0-26.5-21.5-48-48-48H48zM416 160h50.7L544 237.3V256H416V160zM112 416a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm368-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                                                </svg>
                                            </span>
                                            <div>
                                                <h6 class="mb-1">
                                                    @if (in_array($order_status->delivered_status, [1, 5, 6]))
                                                    {{ !empty($order->picked_date)  ? $order->picked_date : $order->shipped_date }}
                                                    @if(empty($order->shipped_date)) {{ !empty($order->shipped_date )? $order->shipped_date :$order->delivery_date }} @endif
                                                    @endif
                                                </h6>
                                                <span>{{ __('Picked Up') }}</span>
                                            </div>
                                        </div>

                                        <div class="achievement-box d-flex align-items-center gap-3">
                                            <span
                                                class=" bg-color-4 d-inline-flex align-items-center justify-content-center flex-shrink-0 bg-color-1 rounded-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                    viewBox="0 0 640 512">
                                                    <style>
                                                        svg {
                                                            fill: var(--second-color)
                                                        }
                                                    </style>
                                                    <path
                                                        d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z" />
                                                </svg>
                                            </span>
                                            <div>
                                                <h6 class="mb-1">
                                                    @if (in_array($order_status->delivered_status, [1, 6]))
                                                    {{ !empty($order->shipped_date )? $order->shipped_date : $order->delivery_date }}
                                                    @endif
                                                </h6>
                                                <span>{{ __('Shipped') }}</span>
                                            </div>
                                        </div>

                                        <div class="achievement-box d-flex align-items-center gap-3">
                                            <span
                                                class=" bg-color-4 d-inline-flex align-items-center justify-content-center flex-shrink-0 bg-color-1 rounded-3">
                                                <svg xmlns="http://www.w3.org/2000/svg"width="32" height="32"
                                                    viewBox="0 0 640 512">
                                                    <style>
                                                        svg {
                                                            fill: var(--second-color)
                                                        }
                                                    </style>
                                                    <path
                                                        d="M313.4 32.9c26 5.2 42.9 30.5 37.7 56.5l-2.3 11.4c-5.3 26.7-15.1 52.1-28.8 75.2H464c26.5 0 48 21.5 48 48c0 18.5-10.5 34.6-25.9 42.6C497 275.4 504 288.9 504 304c0 23.4-16.8 42.9-38.9 47.1c4.4 7.3 6.9 15.8 6.9 24.9c0 21.3-13.9 39.4-33.1 45.6c.7 3.3 1.1 6.8 1.1 10.4c0 26.5-21.5 48-48 48H294.5c-19 0-37.5-5.6-53.3-16.1l-38.5-25.7C176 420.4 160 390.4 160 358.3V320 272 247.1c0-29.2 13.3-56.7 36-75l7.4-5.9c26.5-21.2 44.6-51 51.2-84.2l2.3-11.4c5.2-26 30.5-42.9 56.5-37.7zM32 192H96c17.7 0 32 14.3 32 32V448c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32V224c0-17.7 14.3-32 32-32z" />
                                                </svg>
                                            </span>
                                            <div>
                                                <h6 class="mb-1">
                                                    @if (in_array($order_status->delivered_status, [1]))
                                                    {{ $order->delivery_date}}
                                                    @endif
                                                </h6>
                                                <span>{{ __('Delivered') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <article class="card">
                        <header class="card-header">{{ __('Order Tracking') }}
                        </header>
                        <div class="card-body">
                            <h6>{{ __('Order ID') }}: {{ $order->product_order_id }}</h6>
                            <ul id="progressbar">
                                <li class="active" id="account"><strong>{{ __('Pendding') }}</strong></li>
                                <li @if (in_array($order_status->delivered_status, [1, 4, 5, 6])) class="active" @endif id="personal">
                                    <strong>{{ __('Comfirmed') }}</strong></li>
                                <li @if (in_array($order_status->delivered_status, [1, 5, 6])) class="active" @endif id="payment">
                                    <strong>{{ __('Picked Up') }}</strong></li>
                                <li @if (in_array($order_status->delivered_status, [1, 6])) class="active" @endif id="confirm">
                                    <strong>{{ __('Shipped') }}</strong></li>
                                <li @if (in_array($order_status->delivered_status, [1])) class="active" @endif id="delivered">
                                    <strong>{{ __('Delivered') }}</strong></li>
                            </ul>

                            <br>
                            <div class="col-lg-2 " style="margin-left: auto;">
                                <a class="btn submit-btn"
                                    href="{{ env('APP_URL') . '/' . $slug . '/order/' . \Illuminate\Support\Facades\Crypt::encrypt($order->id) }}"
                                    type="submit">
                                    {{ 'View Order' }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14"
                                        fill="none">
                                        <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z"></path>
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </article>
                @elseif (!empty($order_status->delivered_status) == 2)
                    <div class="error-container">
                        <i class="fas fa-exclamation-circle error-icon"></i>
                        <h1 class="order-errror">{{__('Oops!  Your order is Cancel.')}}</h1>

                        <a href="{{route('landing_page',$slug)}}" class="order-errror">{{ __('Return to Home')}}</a>
                    </div>
                @else
                @endif
            @else
            <div class="error-container">
                <i class="fas fa-exclamation-circle error-icon"></i>
                <h1 class="order-errror">{{__('Oops! Something went wrong.')}}</h1>
                <p class="order-errror">{{ __("We couldn't find your order with the provided order ID.")}}</p>
                <p class="order-errror" >{{ __("Please double-check your order ID and try again.")}}</p>
                <a href="{{route('landing_page',$slug)}}" class="order-errror">{{ __('Return to Home')}}</a>
            </div>
            @endif
        </div>
    </section>

    @include('front_end.sections.partision.footer_section')
@endsection



