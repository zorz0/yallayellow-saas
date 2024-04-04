@extends('layouts.app')

@section('page-title', __('Dashboard'))

@section('action-button')
@endsection

@section('breadcrumb')
@endsection

@section('content')
<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="row ">
            <div class="col-12">
                <div class="row mb-4 g-3">
                    <div class="col-xxl-12">
                        <div class="row g-3 mb-3">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="card stats-wrapper info-card">
                                    <div class="card-body stats">
                                        <div class="theme-avtar bg-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                viewBox="0 0 25 25" fill="none">
                                                <path
                                                    d="M18.7146 14.1316L16.7109 17.1076L16.7109 17.1077C16.5912 17.285 16.3957 17.3868 16.1913 17.3868C16.1284 17.3868 16.0649 17.3774 16.0022 17.3572C16.0022 17.3572 16.0022 17.3572 16.0021 17.3571L16.0342 17.2576C15.8145 17.187 15.665 16.9778 15.665 16.741L18.7146 14.1316ZM18.7146 14.1316L20.0344 15.1009L20.0345 15.1009C20.227 15.2421 20.4811 15.2616 20.6919 15.1495C20.9021 15.0383 21.0327 14.8162 21.0327 14.576V9.02057L23.192 7.9103V17.8308L13.4011 22.8654V12.9446L15.5604 11.8344L18.7146 14.1316ZM24.1063 6.28609L24.1062 6.28606L13.0536 0.60295L13.0535 0.602915C12.8754 0.511498 12.6651 0.511459 12.4872 0.60295L1.43453 6.28606C1.22031 6.39621 1.08673 6.61997 1.08673 6.8632V18.2294C1.08673 18.4726 1.22029 18.6964 1.43421 18.8065L1.43427 18.8066L12.4869 24.4897C12.5761 24.5355 12.673 24.5583 12.7702 24.5583C12.8675 24.5583 12.9644 24.5355 13.0536 24.4897L24.1062 18.8066L24.1063 18.8065C24.3202 18.6964 24.4538 18.4726 24.4538 18.2294V6.8632C24.4538 6.61999 24.3202 6.39625 24.1063 6.28609ZM18.4509 12.5865L18.4505 12.5866C18.2837 12.6169 18.1359 12.7141 18.0401 12.8562L18.0401 12.8562L16.8222 14.6658V11.1856L19.7709 9.66936V13.3175L18.9275 12.698C18.79 12.597 18.6178 12.5569 18.4509 12.5865ZM20.5334 7.8319L10.8866 2.87149L12.7702 1.9028L22.4173 6.8632L20.5334 7.8319ZM16.3229 9.99689L6.67578 5.03649L9.48077 3.5942L19.1279 8.5546L16.3229 9.99689ZM3.12315 6.8632L5.27025 5.7592L14.9171 10.7196L12.7702 11.8236L3.12315 6.8632ZM2.34851 17.8308V7.9103L12.1394 12.9446V22.8652L2.34851 17.8308Z"
                                                    fill="#F4B41A" stroke="#F4B41A" stroke-width="0.209149" />
                                            </svg>
                                        </div>
                                        <h6 class="mt-4 mb-2">{{ __('Total Products') }}</h6>
                                        <h3 class="mb-0">{{ $totalproduct ?? 0 }} <span class="text-success text-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="card stats-wrapper info-card">
                                    <div class="card-body stats">
                                        <div class="theme-avtar bg-info">
                                            <i class="ti ti-click"></i>
                                        </div>
                                        <h6 class="mt-4 mb-2">{{ __('Total Customers') }}</h6>
                                        <h3 class="mb-0">{{ $totle_sales ?? 0 }} <span class="text-danger text-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="card stats-wrapper info-card">
                                    <div class="card-body stats">
                                        <div class="theme-avtar bg-dark">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                                                viewBox="0 0 23 23" fill="none">
                                                <path
                                                    d="M20.7006 23H7.31878C6.70878 23 6.12377 22.7577 5.69243 22.3263C5.2611 21.895 5.01878 21.31 5.01878 20.7V20.3236C3.59181 19.993 2.3189 19.1888 1.40751 18.0421C0.496126 16.8954 0 15.4739 0 14.0091C0 12.5443 0.496126 11.1227 1.40751 9.97605C2.3189 8.82936 3.59181 8.02517 5.01878 7.69455V2.3C5.01878 1.69001 5.2611 1.10499 5.69243 0.673658C6.12377 0.242325 6.70878 3.93723e-06 7.31878 3.93723e-06H16.661C16.9641 -0.000557969 17.2643 0.0590303 17.5443 0.175318C17.8242 0.291605 18.0783 0.46228 18.2919 0.677458L22.3231 4.70873C22.5434 4.92718 22.717 5.18807 22.8335 5.4756C22.9499 5.76313 23.0068 6.0713 23.0006 6.38146V20.7C23.0006 21.31 22.7583 21.895 22.3269 22.3263C21.8956 22.7577 21.3106 23 20.7006 23ZM6.27332 20.4909V20.7C6.27332 20.9773 6.38347 21.2432 6.57953 21.4392C6.77559 21.6353 7.04151 21.7455 7.31878 21.7455H20.7006C20.9779 21.7455 21.2438 21.6353 21.4398 21.4392C21.6359 21.2432 21.746 20.9773 21.746 20.7V7.10909H18.1915C17.5815 7.10909 16.9965 6.86677 16.5652 6.43544C16.1338 6.00411 15.8915 5.41909 15.8915 4.80909V1.25455H7.31878C7.04151 1.25455 6.77559 1.36469 6.57953 1.56076C6.38347 1.75682 6.27332 2.02273 6.27332 2.3V7.52728H6.48241C8.2015 7.52728 9.85018 8.21018 11.0658 9.42576C12.2813 10.6413 12.9642 12.29 12.9642 14.0091C12.9642 15.7282 12.2813 17.3769 11.0658 18.5924C9.85018 19.808 8.2015 20.4909 6.48241 20.4909H6.27332ZM6.48241 8.78182C5.44856 8.78182 4.43792 9.0884 3.5783 9.66278C2.71868 10.2372 2.04869 11.0535 1.65305 12.0087C1.25741 12.9639 1.15389 14.0149 1.35558 15.0289C1.55728 16.0429 2.05513 16.9743 2.78618 17.7053C3.51722 18.4364 4.44863 18.9342 5.46262 19.1359C6.47662 19.3376 7.52765 19.2341 8.48281 18.8385C9.43796 18.4428 10.2544 17.7728 10.8287 16.9132C11.4031 16.0536 11.7097 15.0429 11.7097 14.0091C11.7097 12.6227 11.159 11.2932 10.1787 10.3129C9.19835 9.33255 7.86877 8.78182 6.48241 8.78182ZM17.1461 1.37164V4.80909C17.1461 5.08637 17.2562 5.35228 17.4523 5.54834C17.6483 5.7444 17.9142 5.85455 18.1915 5.85455H21.629C21.5772 5.7596 21.5125 5.67233 21.4366 5.59528L17.4053 1.564C17.3283 1.48814 17.241 1.42339 17.1461 1.37164ZM19.0279 17.1455H14.8461C14.6797 17.1455 14.5201 17.0794 14.4025 16.9617C14.2849 16.8441 14.2188 16.6845 14.2188 16.5182C14.2188 16.3518 14.2849 16.1923 14.4025 16.0746C14.5201 15.957 14.6797 15.8909 14.8461 15.8909H19.0279C19.1942 15.8909 19.3538 15.957 19.4714 16.0746C19.5891 16.1923 19.6551 16.3518 19.6551 16.5182C19.6551 16.6845 19.5891 16.8441 19.4714 16.9617C19.3538 17.0794 19.1942 17.1455 19.0279 17.1455ZM5.22787 15.8909C5.0665 15.8786 4.91638 15.8035 4.80969 15.6818C4.70682 15.5668 4.64996 15.4179 4.64996 15.2636C4.64996 15.1093 4.70682 14.9605 4.80969 14.8455L5.88023 13.7749V11.5C5.88023 11.3336 5.94632 11.1741 6.06396 11.0565C6.18159 10.9388 6.34114 10.8727 6.50751 10.8727C6.67387 10.8727 6.83342 10.9388 6.95106 11.0565C7.06869 11.1741 7.13478 11.3336 7.13478 11.5V14.0091C7.13463 14.1754 7.06845 14.3348 6.95078 14.4524L5.64605 15.6818C5.53936 15.8035 5.38924 15.8786 5.22787 15.8909ZM19.0279 13.8H15.6824C15.5161 13.8 15.3565 13.7339 15.2389 13.6163C15.1212 13.4986 15.0551 13.3391 15.0551 13.1727C15.0551 13.0064 15.1212 12.8468 15.2389 12.7292C15.3565 12.6115 15.5161 12.5455 15.6824 12.5455H19.0279C19.1942 12.5455 19.3538 12.6115 19.4714 12.7292C19.5891 12.8468 19.6551 13.0064 19.6551 13.1727C19.6551 13.3391 19.5891 13.4986 19.4714 13.6163C19.3538 13.7339 19.1942 13.8 19.0279 13.8ZM19.0279 10.4545H14.8461C14.6797 10.4545 14.5201 10.3885 14.4025 10.2708C14.2849 10.1532 14.2188 9.99364 14.2188 9.82728C14.2188 9.66091 14.2849 9.50136 14.4025 9.38373C14.5201 9.26609 14.6797 9.2 14.8461 9.2H19.0279C19.1942 9.2 19.3538 9.26609 19.4714 9.38373C19.5891 9.50136 19.6551 9.66091 19.6551 9.82728C19.6551 9.99364 19.5891 10.1532 19.4714 10.2708C19.3538 10.3885 19.1942 10.4545 19.0279 10.4545ZM13.1733 7.10909H10.6642C10.4979 7.10909 10.3383 7.04301 10.2207 6.92537C10.103 6.80773 10.037 6.64818 10.037 6.48182C10.037 6.31546 10.103 6.15591 10.2207 6.03827C10.3383 5.92064 10.4979 5.85455 10.6642 5.85455H13.1733C13.3397 5.85455 13.4992 5.92064 13.6169 6.03827C13.7345 6.15591 13.8006 6.31546 13.8006 6.48182C13.8006 6.64818 13.7345 6.80773 13.6169 6.92537C13.4992 7.04301 13.3397 7.10909 13.1733 7.10909Z"
                                                    fill="#D80027" />
                                            </svg>
                                        </div>
                                        <h6 class="mt-4 mb-2">{{ __('Total Orders') }}</h6>
                                        <h3 class="mb-0">{{ $totle_order ?? 0 }}<span class="text-success text-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="card stats-wrapper info-card">
                                    <div class="card-body stats">
                                        <div class="theme-avtar bg-danger">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <mask id="mask0_724_629" style="mask-type:luminance"
                                                    maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                                                    <path d="M0 0H20V20H0V0Z" fill="white" />
                                                </mask>
                                                <g mask="url(#mask0_724_629)">
                                                    <path
                                                        d="M19.2188 8.59375V4.6875C19.2188 2.53012 17.4699 0.78125 15.3125 0.78125H4.68752C2.53014 0.78125 0.781273 2.53012 0.781273 4.6875V15.3125C0.781273 17.4699 2.53014 19.2188 4.68752 19.2188H8.75002"
                                                        stroke="#D80027" stroke-width="1.2" stroke-miterlimit="10"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M8.00781 4.297H11.9922" stroke="#D80027" stroke-width="1.2"
                                                        stroke-miterlimit="10" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M19.2187 14.7266C19.2187 17.2075 17.2075 19.2188 14.7266 19.2188C12.2456 19.2188 10.2344 17.2075 10.2344 14.7266C10.2344 12.2456 12.2456 10.2344 14.7266 10.2344C17.2075 10.2344 19.2187 12.2456 19.2187 14.7266Z"
                                                        stroke="#D80027" stroke-width="1.2" stroke-miterlimit="10"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M13.5547 15.8984L15.8984 13.5547" stroke="#D80027"
                                                        stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M15.8984 15.8984L13.5547 13.5547" stroke="#D80027"
                                                        stroke-width="1.2" stroke-miterlimit="10" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </g>
                                            </svg>
                                        </div>
                                        <h6 class="mt-4 mb-2">{{ __('Total Cancel Orders') }}</h6>
                                        <h3 class="mb-0">{{ $totle_cancel_order ?? 0 }} <span
                                                class="text-success text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-xxl-6">
                                <div class="row g-3">
                                    <div class="col-xxl-6 col-lg-3 col-sm-6 col-12">
                                        <div class="card stats-wrapper info-card">
                                            <div class="card-body stats">
                                                <div class="theme-avtar bg-blue">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="21"
                                                        viewBox="0 0 26 21" fill="none">
                                                        <path
                                                            d="M3.37502 3.37512H21.4251C21.677 3.37512 21.9186 3.47518 22.0968 3.65333C22.275 3.83149 22.375 4.07308 22.3751 4.32503L22.375 7.88403"
                                                            stroke="#D80027" stroke-width="1.18937"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M22.375 15.0091L22.375 18.1001C22.375 19.1495 21.5244 20 20.475 20H2.9C1.85066 20 1 19.1495 1 18.1001V2.90003C1 1.85066 1.85066 1 2.9 1H22.85C23.8994 1 24.75 1.85066 24.75 2.90003V17.1869C24.75 17.7465 24.4338 18.2581 23.9333 18.5085"
                                                            stroke="#D80027" stroke-width="1.18937"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M24.7501 8.59998L22.375 9.54999H17.625C17.3755 9.54999 17.1285 9.59915 16.8979 9.69463C16.6674 9.79012 16.458 9.92998 16.2815 10.1064C16.1051 10.2828 15.9651 10.4924 15.8697 10.7229C15.7742 10.9534 15.725 11.2005 15.725 11.45C15.725 11.9539 15.9252 12.4371 16.2815 12.7934C16.6379 13.1497 17.1211 13.3499 17.625 13.3499H22.375L24.7501 12.3999"
                                                            stroke="#D80027" stroke-width="1.18937"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M18.1 11.4502C18.1 11.5762 18.05 11.697 17.9609 11.7861C17.8718 11.8752 17.751 11.9251 17.625 11.9251C17.499 11.9251 17.3782 11.8752 17.2892 11.7861C17.2001 11.697 17.15 11.5762 17.15 11.4502C17.15 11.3243 17.2001 11.2033 17.2892 11.1143C17.3782 11.0252 17.499 10.9752 17.625 10.9752C17.751 10.9752 17.8718 11.0252 17.9609 11.1143C18.05 11.2033 18.1 11.3243 18.1 11.4502Z"
                                                            fill="#D80027" />
                                                    </svg>
                                                </div>
                                                <h6 class="mt-4 mb-2">{{ __('Total Refund Orders') }}</h6>
                                                <h3 class="mb-0">{{ $total_refund_requests ?? 0 }} <span
                                                        class="text-success text-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-lg-3 col-sm-6 col-12">
                                        <div class="card stats-wrapper info-card">
                                            <div class="card-body stats">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-report-money"></i>
                                                </div>
                                                <h6 class="mt-4 mb-2">{{ __('Total Revenues') }}</h6>
                                                <h3 class="mb-0">{{  currency_format_with_sym($total_revenues ?? 0, auth()->user()->current_store, APP_THEME()) ?? SetNumberFormat($total_revenues ?? 0) }} <span
                                                        class="text-success text-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-12 col-xl-6 col-12">
                                        <div class="card mb-0 ">
                                            <div class="d-flex align-items-center flex-wrap justify-content-center">
                                                <div class="flex-1 stats-wrapper" style="flex:1; ">
                                                    <div class="card-body stats welcome-card">
                                                        <div class="row align-items-center">
                                                            <div class="col-xxl-12">
                                                                <h3 class="mb-1" id="greetings"></h3>
                                                                <h4 class="f-w-400">
                                                                    <a href="{{ asset(!empty(auth()->user()->profile_image) ? auth()->user()->profile_image : Storage::url('uploads/profile/avatar.png')) }}"
                                                                        target="_blank">
                                                                        <img src="{{ asset(!empty(auth()->user()->profile_image) ? auth()->user()->profile_image : Storage::url('storage/uploads/profile/avatar.png')) }}"
                                                                            alt="user-image"
                                                                            class="wid-35 me-2 img-thumbnail rounded-circle">
                                                                    </a>
                                                                    {{ __(auth()->user()->name) }}
                                                                </h4>
                                                                <p>{{ __('Have a nice day! Did you know that you can quickly add your favorite product or category to the theme?') }}
                                                                </p>
                                                                <div class="dropdown quick-add-btn">
                                                                    <a class="btn btn-primary btn-q-add dropdown-toggle"
                                                                        data-bs-toggle="dropdown" href="#" role="button"
                                                                        aria-haspopup="false" aria-expanded="false">
                                                                        <i class="ti ti-plus drp-icon"></i>
                                                                        <span
                                                                            class="ms-2 me-2">{{ __('Quick add') }}</span>
                                                                    </a>
                                                                    <div class="dropdown-menu">

                                                                        <a href="{{ route('product.create') }}"
                                                                            data-size="lg"
                                                                            data-title="{{ __('Add Product') }}"
                                                                            class="dropdown-item"
                                                                            data-bs-placement="top "><span>{{ __('Add new product') }}</span></a>

                                                                        <a href="#" data-size="md"
                                                                            data-url="{{ route('taxes.create') }}"
                                                                            data-ajax-popup="true"
                                                                            data-title="{{ __('Create Tax') }}"
                                                                            class="dropdown-item"
                                                                            data-bs-placement="top "><span>{{ __('Add new tax') }}</span></a>

                                                                        <a href="#" data-size="md"
                                                                            data-url="{{ route('main-category.create') }}"
                                                                            data-ajax-popup="true"
                                                                            data-title="{{ __('Create Main Category') }}"
                                                                            class="dropdown-item"
                                                                            data-bs-placement="top"><span>{{ __('Add new main category') }}</span></a>

                                                                        <a href="#" data-size="md"
                                                                            data-url="{{ route('coupon.create') }}"
                                                                            data-ajax-popup="true"
                                                                            data-title="{{ __('Create Coupon') }}"
                                                                            class="dropdown-item"
                                                                            data-bs-placement="top "><span>{{ __('Add new coupon') }}</span></a>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="stats-wrapper info-card">
                                                    <div class="card-body stats ps-3 pe-3">
                                                        <h6 class="">{{ $store->name }}</h6>
                                                        <div class="mb-3 qrcode">
                                                            {!! QrCode::generate($theme_url) !!}
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <a href="#!"
                                                                class="btn btn-light-primary btn-sm w-100 cp_link"
                                                                data-link="{{ $theme_url }}" data-bs-toggle="tooltip"
                                                                data-bs-placement="top"
                                                                title="{{ __('Click to copy link') }}">
                                                                {{ __('Theme Link') }}
                                                                <i class="ms-1" data-feather="copy"></i>
                                                            </a>
                                                            <a href="#" id="socialShareButton"
                                                                class="socialShareButton btn btn-sm btn-primary ms-1 share-btn">
                                                                <i class="ti ti-share"></i>
                                                            </a>
                                                            <div id="sharingButtonsContainer"
                                                                class="sharingButtonsContainer" style="display: none;">
                                                                <div
                                                                    class="Demo1 d-flex align-items-center justify-content-center mb-5 hidden">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xxl-6">
                                <div class="theme-card dashboard-theme-card card p-3 mb-0">
                                    <div class="theme-image">
                                    <span class="badges bg-success">Current Active Theme</span>
                                        <img src="{{ asset('themes/'.$theme_name.'/theme_img/img_1.png') }}"
                                            alt="theme-image">
                                    </div>
                                    <div class="theme-bottom-content">
                                        <div class="theme-card-lable">
                                            <b>{{ ucfirst($store->name ?? '') }}</b>
                                        </div>
                                        <div class="theme-card-button ">
                                            <a class="btn btn-sm btn-primary text-end"
                                                href="{{ route('theme-preview.create', ['theme_id' => $theme_name]) }}">
                                                Customize
                                            </a>
                                            <a class="btn btn-sm btn2 btn-primary text-end"
                                                href="{{ route('theme-preview.index') }}">
                                                Manage Themes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- quick add --}}

            </div>
            {{-- latest product --}}
        <div class="col-xxl-7 mb-4">
            <div class="card min-h-390 overflow-auto card-dash">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Latest Products') }}</h5>
                    <a class="btn btn-primary" href="{{ route('product.index') }}">{{ __('View All') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">
                                        {{ __('Product') }}
                                    </th>
                                    <th scope="col" class="sort" data-sort="budget">
                                        {{ __('Quantity') }}
                                    </th>
                                    <th scope="col" class="sort text-right" data-sort="completion">
                                        {{ __('Price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latests as $latest)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ get_file($latest->cover_image_path, APP_THEME()) }}"
                                                target="_blank">
                                                <img src="{{ get_file($latest->cover_image_path, APP_THEME()) }}"
                                                    class="wid-25" alt="images">
                                            </a>
                                            <div class="ms-3">
                                                <h6 class="m-0">{{ $latest->name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    @if ($latest->variant_product == 0)
                                    <td>
                                        <h6 class="m-0">{{ $latest->product_stock }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="m-0">{{ $latest->final_price }}</h6>
                                    </td>
                                    @else
                                    <td>
                                        <h6 class="m-0">{{ __('In Variant') }}</h6>
                                    </td>
                                    <td>
                                        <h6 class="m-0">{{ __('In Variant') }}</h6>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- orders chart --}}
        <div class="col-xxl-5 mb-4">
            <div class="card min-h-390 overflow-auto card-dash">
                <div class="card-header">
                    <h5>{{ __('Orders') }}</h5>
                </div>
                <div class="card-body">
                    <div id="traffic-chart"></div>
                </div>
            </div>

        </div>


        <div class="col-xxl-7 mb-4">
            <div class="card min-h-390 overflow-auto card-dash">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Customer Vs Guest') }}</h5>
                    <a class="btn btn-primary" href="{{ route('reports.index') }}">{{ __('View All') }}</a>
                </div>
                <div class="card-body">
                    <div class="customer-chart">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-5 mb-4">
            <div class="card min-h-390 overflow-auto card-dash">
                <div class="card-header">
                    <h5>{{ __('Storage Status ') }}<small>({{ $users->storage_limit . 'MB' }} /
                            {{ ($plan->storage_limit ?? 0). 'MB' }})</small></h5>
                </div>
                <div class="card-body">
                    <div id="device-chart"></div>
                </div>
            </div>
        </div>

        </div>


    </div>
</div>

{{-- recent orders --}}
<div class="col-sm-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>{{ __('Recent Orders') }}</h5>
            <a class="btn btn-primary" href="{{ route('order.index') }}">{{ __('View All') }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('Orders') }}</th>
                            <th scope="col" class="sort">{{ __('Date') }}</th>
                            <th scope="col" class="sort">{{ __('Name') }}</th>
                            <th scope="col" class="sort">{{ __('Value') }}</th>
                            <th scope="col" class="sort">{{ __('Payment Type') }}</th>
                            <th scope="col" class="sort">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($new_orders))
                        @foreach ($new_orders as $order)
                        @if ($order->status != 'Cancel Order')
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('order.view', \Illuminate\Support\Facades\Crypt::encrypt($order->id)) }}"
                                        @php $btn_class='bg-info' ; if($order->delivered_status == 2 ||
                                        $order->delivered_status == 3) {
                                        $btn_class = 'bg-danger';
                                        } elseif($order->delivered_status == 1) {
                                        $btn_class = 'bg-success';
                                        } elseif($order->delivered_status == 4) {
                                        $btn_class = ' btn-warning';
                                        } elseif($order->delivered_status == 5) {
                                        $btn_class = 'btn-secondary';
                                        } elseif($order->delivered_status == 6) {
                                        $btn_class = 'btn-dark';
                                        } @endphp
                                        class="btn {{ $btn_class }} text-white btn-sm text-sm"
                                        data-toggle="tooltip" title="{{ __('Invoice ID') }}">
                                        <span class="btn-inner--icon"></span>
                                        <span class="btn-inner--text">#{{ $order->product_order_id }}</span>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <h6 class="m-0">
                                    {{ \App\Models\Utility::dateFormat($order->order_date) }}
                                </h6>
                            </td>
                            <td>
                                <h6 class="m-0">
                                    @if ($order->is_guest == 1)
                                    {{ __('Guest') }}
                                    @elseif ($order->user_id != 0)
                                    {{ !empty($order->UserData->name) ? $order->UserData->name : '' }}
                                    @else
                                    {{ __('Walk-in-customer') }}
                                    @endif
                                </h6>
                            </td>
                            <td>
                                <h6 class="m-0">
                                    {{ currency_format_with_sym( ($order->final_price ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order->final_price) }}
                                    <h6>
                            </td>
                            <td class="">
                                <h6 class="m-0">
                                    @if ($order->payment_type == 'cod')
                                    {{ __('Cash On Delivery') }}
                                    @elseif ($order->payment_type == 'bank_transfer')
                                    {{ __('Bank Transfer') }}
                                    @elseif ($order->payment_type == 'stripe')
                                    {{ __('Stripe') }}
                                    @elseif ($order->payment_type == 'paystack')
                                    {{ __('Paystack') }}
                                    @elseif ($order->payment_type == 'Mercado')
                                    {{ __('Mercado Pago') }}
                                    @elseif ($order->payment_type == 'skrill')
                                    {{ __('Skrill') }}
                                    @elseif ($order->payment_type == 'paymentwall')
                                    {{ __('PaymentWall') }}
                                    @elseif ($order->payment_type == 'Razorpay')
                                    {{ __('Razorpay') }}
                                    @elseif ($order->payment_type == 'paypal')
                                    {{ __('Paypal') }}
                                    @elseif ($order->payment_type == 'flutterwave')
                                    {{ __('Flutterwave') }}
                                    @elseif ($order->payment_type == 'mollie')
                                    {{ __('Mollie') }}
                                    @elseif ($order->payment_type == 'coingate')
                                    {{ __('Coingate') }}
                                    @elseif ($order->payment_type == 'paytm')
                                    {{ __('Paytm') }}
                                    @elseif ($order->payment_type == 'POS')
                                    {{ __('POS') }}
                                    @elseif ($order->payment_type == 'toyyibpay')
                                    {{ __('Toyyibpay') }}
                                    @elseif ($order->payment_type == 'sspay')
                                    {{ __('Sspay') }}
                                    @elseif ($order->payment_type == 'Paytabs')
                                    {{ __('Paytabs') }}
                                    @elseif ($order->payment_type == 'iyzipay')
                                    {{ __('IyziPay') }}
                                    @elseif ($order->payment_type == 'payfast')
                                    {{ __('PayFast') }}
                                    @elseif ($order->payment_type == 'benefit')
                                    {{ __('Benefit') }}
                                    @elseif ($order->payment_type == 'cashfree')
                                    {{ __('Cashfree') }}
                                    @elseif ($order->payment_type == 'aamarpay')
                                    {{ __('Aamarpay') }}
                                    @elseif ($order->payment_type == 'telegram')
                                    {{ __('Telegram') }}
                                    @elseif ($order->payment_type == 'whatsapp')
                                    {{ __('Whatsapp') }}
                                    @elseif ($order->payment_type == 'paytr')
                                    {{ __('PayTR') }}
                                    @elseif ($order->payment_type == 'yookassa')
                                    {{ __('Yookassa') }}
                                    @elseif ($order->payment_type == 'Xendit')
                                    {{ __('Xendit') }}
                                    @elseif ($order->payment_type == 'midtrans')
                                    {{ __('Midtrans') }}
                                    @endif
                                    <h6>
                            </td>
                            <td class="">
                                @if ($order->delivered_status == 0)
                                <button type="button" class="badge-same btn btn-sm btn-info btn-icon rounded-pill">

                                    <span class="btn-inner--text"> {{ __('Pending') }} :
                                        {{ \App\Models\Utility::dateFormat($order->order_date) }}
                                    </span>
                                </button>
                                @elseif ($order->delivered_status == 1)
                                <button type="button" class="badge-same btn btn-sm btn-success btn-icon rounded-pill">

                                    <span class="btn-inner--text"> {{ __('Delivered') }} :
                                        {{ \App\Models\Utility::dateFormat($order->delivery_date) }}
                                    </span>
                                </button>
                                @elseif ($order->delivered_status == 2)
                                <button type="button" class="badge-same btn btn-sm btn-danger btn-icon rounded-pill">

                                    <span class="btn-inner--text"> {{ __('Cancel') }} :
                                        {{ \App\Models\Utility::dateFormat($order->cancel_date) }}
                                    </span>
                                </button>
                                @elseif ($order->delivered_status == 3)
                                <button type="button" class="badge-same btn btn-sm btn-danger btn-icon rounded-pill">

                                    <span class="btn-inner--text"> {{ __('Return') }} :
                                        {{ \App\Models\Utility::dateFormat($order->return_date) }}
                                    </span>
                                </button>
                                @elseif ($order->delivered_status == 4)
                                <button type="button" class="badge-same btn btn-sm btn-warning btn-icon rounded-pill">

                                    <span class="btn-inner--text"> {{ __('Confirmed') }} :
                                        {{ \App\Models\Utility::dateFormat($order->confirmed_date) }}
                                    </span>
                                </button>
                                @elseif ($order->delivered_status == 5)
                                <button type="button" class="badge-same btn btn-sm btn-secondary btn-icon rounded-pill">

                                    <span class="btn-inner--text"> {{ __('Picked Up') }} :
                                        {{ \App\Models\Utility::dateFormat($order->picked_date) }}
                                    </span>
                                </button>
                                @elseif ($order->delivered_status == 6)
                                <button type="button" class="badge-same btn btn-sm btn-dark btn-icon rounded-pill">

                                    <span class="btn-inner--text"> {{ __('Shipped') }} :
                                        {{ \App\Models\Utility::dateFormat($order->shipped_date) }}
                                    </span>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>{{ __('Top Selleing Products') }}</h5>
            <a class="btn btn-primary" href="{{ route('product.index') }}">{{ __('View All') }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Cover Image') }}</th>
                            <th>{{ __('Varint') }}</th>
                            <th>{{ __('Review') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Stock Status') }}</th>
                            <th>{{ __('Stock Quntity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topSellingProducts as $product)
                        <tr>
                            <td> {{ $product->name }} </td>
                            <td> {{ !empty($product->ProductData) ? $product->ProductData->name : '' }}
                            </td>
                            <td> <img src="{{ asset($product->cover_image_path) }}" alt="" width="100"
                                    class="cover_img{{ $product->id }}">
                            </td>
                            <td> {{ $product->variant_product == 1 ? 'has variant' : 'no variant' }} </td>
                            <td> <i class="ti ti-star text-warning "></i>{{ $product->average_rating }} </td>
                            @if ($product->variant_product == 0)
                            <td>{{ currency_format_with_sym($product->price, auth()->user()->current_store, APP_THEME()) ?? SetNumberFormat($product->price) }} </td>
                            @else
                            <td>{{ __('In Variant') }}</td>
                            @endif
                            <td>
                                @if ($product->variant_product == 1)
                                <span class="badge rounded p-2 f-w-600  bg-light-warning">{{ __('In Variant') }}</span>
                                @else
                                @if ($product->track_stock == 0)
                                @if ($product->stock_status == 'out_of_stock')
                                <span class="badge rounded p-2 f-w-600  bg-light-danger">
                                    {{ __('Out of stock') }}</span>
                                @elseif ($product->stock_status == 'on_backorder')
                                <span class="badge rounded p-2 f-w-600  bg-light-warning">
                                    {{ __('On Backorder') }}</span>
                                @else
                                <span class="badge rounded p-2 f-w-600  bg-light-primary">
                                    {{ __('In stock') }}</span>
                                @endif
                                @else
                                @if ($product->product_stock <= $out_of_stock_threshold) <span
                                    class="badge rounded p-2 f-w-600  bg-light-danger">
                                    {{ __('Out of stock') }}</span>
                                    @else
                                    <span class="badge rounded p-2 f-w-600  bg-light-primary">
                                        {{ __('In stock') }}</span>
                                    @endif
                                    @endif
                                    @endif
                            </td>
                            <td>
                                @if ($product->variant_product == 1)
                                <span class=""> - </span>
                                @else
                                @if ($product->product_stock <= 0) - @else <span>
                                    {{ $product->product_stock }}
                                    </span>
                                    @endif
                                    @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- [ sample-page ] end -->
</div>
@php
$setting =getSuperAdminAllSetting();
@endphp
@if (isset($setting['enable_cookie']) && $setting['enable_cookie'] == 'on')
@include('layouts.cookie_consent')
@endif
@endsection

@push('custom-script')
@if (auth()->user()->type != 'superadmin')
<script>
$(document).ready(function() {
    $('.cp_link').on('click', function() {
        var value = $(this).attr('data-link');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(value).select();
        document.execCommand("copy");
        $temp.remove();
        show_toastr('Success', '{{ __('Link copied ') }}', 'success')
    });
});
</script>
@endif
<script>
(function() {
    var options = {
        chart: {
            height: 250,
            type: 'area',
            toolbar: {
                show: false,
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            curve: 'smooth'
        },
        series: [{
            name: 'order',
            data: [10, 20, 5, 30, 40, 25, 15]
        }],
        xaxis: {
            categories: {!!json_encode($chartData['label']) !!},
            title: {
                text: '{{ __('Days') }}'
            }
        },
        colors: ['#6FD943', '#ffa21d'],

        grid: {
            strokeDashArray: 4,
        },
        legend: {
            show: false,
        },
        yaxis: {
            tickAmount: 3,
            title: {
                text: '{{ __('Amount') }}'
            },
        }
    };
    var chart = new ApexCharts(document.querySelector("#traffic-chart"), options);
    chart.render();
})();

var options = {
    series: [{{
            round($storage_limit, 2)
        }}],
    chart: {
        height: 575,
        type: 'radialBar',
        offsetY: -20,
        sparkline: {
            enabled: true
        }
    },
    plotOptions: {
        radialBar: {
            startAngle: -90,
            endAngle: 90,
            track: {
                background: "#E9FFDF",
                strokeWidth: '97%',
                margin: 5, // margin is in pixels
            },
            dataLabels: {
                name: {
                    show: true
                },
                value: {
                    offsetY: -50,
                    fontSize: '20px'
                }
            }
        }
    },
    grid: {
        padding: {
            top: -10
        }
    },
    colors: ["#6FD943"],
    labels: ['Used'],
};
var chart = new ApexCharts(document.querySelector("#device-chart"), options);
chart.render();

(function() {
    var options = {
        chart: {
            height: 250,
            type: 'area',
            toolbar: {
                show: false,
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 2,
            curve: 'smooth'
        },
        series: [{
            name: 'New Customer',
            data: [10, 20, 5, 10, 30, 40, 35]
        }, {
            name: 'New Guest',
            data: [5, 50, 45, 42, 10, 20, 60]
        }],
        xaxis: {
            categories: {!! json_encode($chartData['label']) !!},
            title: {
                text: '{{ __('Days') }}'
            }
        },
        colors: ['#6FD943', '#ffa21d'],

        grid: {
            strokeDashArray: 4,
        },
        legend: {
            show: false,
        },
        yaxis: {
            tickAmount: 3,
            title: {},
        }
    };
    var chart = new ApexCharts(document.querySelector(".customer-chart"), options);
    chart.render();
})();
</script>
<script>
var today = new Date()
var curHr = today.getHours()
var target = document.getElementById("greetings");

if (curHr < 12) {
    target.innerHTML = "{{ __('Good Morning,') }}";
} else if (curHr < 17) {
    target.innerHTML = "{{ __('Good Afternoon,') }}";
} else {
    target.innerHTML = "{{ __('Good Evening,') }}";
}
</script>
<script>
$(document).on('click', '.code', function() {
    var type = $(this).val();
    $('#code_text').addClass('col-md-12').removeClass('col-md-8');
    $('#autogerate_button').addClass('d-none');
    if (type == 'auto') {
        $('#code_text').addClass('col-md-8').removeClass('col-md-12');
        $('#autogerate_button').removeClass('d-none');
    }
});

$(document).on('click', '#code-generate', function() {
    var length = 10;
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    $('#auto-code').val(result);
});
</script>
<script>
function add_more_customer_choice_option(i, name) {
    $('#customer_choice_options').append(
        '<div class="form-group"><input type="hidden" name="choice_no[]" value="' + i + '">' +
        '<label for="choice_attributes">' + name + ':</label>' +
        '<input type="text" class="form-control variant_choice" name="choice_options_' + i +
        '[]" __="{{ __('Enter choice values ') }}"  data-role="tagsinput" id="variant_tag' + i +
        '" onchange="update_sku($(this).val())">' +
        '</div>');
    comman_function();
}

$(document).on('change', '#maincategory', function() {
    var maincategory = $(this).val();
    var subcategory = $(this).attr('data-val');
    var data = {
        maincategory: maincategory,
        subcategory: subcategory
    }
    $.ajax({
        url: '{{ route('get.subcategory') }}',
        method: 'POST',
        data: data,
        context: this,
        success: function(response) {
            $('.subcategory_selct').html();
            var select =
                '<select class="form-control" data-role="tagsinput" id="subcategory_id" name="subcategory_id">' +
                response.html + '</select>';
            $('.subcategory_selct').html(select);
            comman_function();
            $(this).attr('data-val', '0');
        }
    });
});
$(document).ready(function() {
    var customURL = {!!json_encode($theme_url) !!};
    $('.Demo1').socialSharingPlugin({
        url: customURL,
        title: $('meta[property="og:title"]').attr('content'),
        description: $('meta[property="og:description"]').attr('content'),
        img: $('meta[property="og:image"]').attr('content'),
        enable: ['whatsapp', 'facebook', 'twitter', 'pinterest', 'instagram']
    });

    $('.socialShareButton').click(function(e) {
        e.preventDefault();
        $('.sharingButtonsContainer').toggle();
    });
});
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-DN7D163CD8"></script>
<script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-DN7D163CD8'); </script>
@endpush
