@extends('layouts.app')

@section('page-title')
    {{ __('Plan') }}
@endsection

@php
    $logo = asset(Storage::url('uploads/profile/'));
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Plan') }}</li>
@endsection

@section('action-button')
    {{-- @permission('Create Plan') --}}
    @if (auth()->user()->type == 'super admin' && ((isset($setting['is_stripe_enabled']) && $setting['is_stripe_enabled'] == 'on') ||
            (isset($setting['is_paystack_enabled']) && $setting['is_paystack_enabled'] == 'on') ||
            (isset($setting['is_razorpay_enabled']) && $setting['is_razorpay_enabled'] == 'on') ||
            (isset($setting['is_mercado_enabled']) && $setting['is_mercado_enabled'] == 'on') ||
            (isset($setting['is_skrill_enabled']) && $setting['is_skrill_enabled'] == 'on') ||
            (isset($setting['is_paymentwall_enabled']) && $setting['is_paymentwall_enabled'] == 'on') ||
            (isset($setting['is_paypal_enabled']) && $setting['is_paypal_enabled'] == 'on') ||
            (isset($setting['is_flutterwave_enabled']) && $setting['is_flutterwave_enabled'] == 'on') ||
            (isset($setting['is_paytm_enabled']) && $setting['is_paytm_enabled'] == 'on') ||
            (isset($setting['is_mollie_enabled']) && $setting['is_mollie_enabled'] == 'on') ||
            (isset($setting['is_coingate_enabled']) && $setting['is_coingate_enabled'] == 'on') ||
            (isset($setting['is_sspay_enabled']) && $setting['is_sspay_enabled'] == 'on') ||
            (isset($setting['is_toyyibpay_enabled']) && $setting['is_toyyibpay_enabled'] == 'on') ||
            (isset($setting['is_bank_transfer_enabled']) && $setting['is_bank_transfer_enabled'] == 'on') ||
            (isset($setting['is_paytabs_enabled']) && $setting['is_midtrans_enabled'] == 'on') ||
            (isset($setting['is_nepalste_enabled']) && $setting['is_nepalste_enabled'] == 'on') || (isset($setting['is_khalti_enabled']) && $setting['is_khalti_enabled'] == 'on')))
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="Add Plan"
                data-url="{{ route('plan.create') }}" data-toggle="tooltip" title="{{ __('Create Plan') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endif
    {{-- @endpermission --}}
@endsection

@section('content')

    <div class="row mb-4">
        @foreach ($plans as $plan)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="plan_card">
                    <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                        style="
                                    visibility: visible;
                                    animation-delay: 0.2s;
                                    animation-name: fadeInUp;
                                  ">
                        <div class="card-body">
                            <span class="price-badge text-dark f-w-600 text-start f-16 ps-0 mb-2">{{ $plan->name }}</span>
                            @if (\Auth::user()->type == 'admin' && \Auth::user()->plan_id == $plan->id)
                                <div class="d-flex flex-row-reverse m-0 p-0 plan-active-status bg-primary">
                                    <span class="d-flex align-items-center ">

                                        <span class="m-2">{{ __('Active') }}</span>
                                    </span>
                                </div>
                            @endif



                                <div class="d-flex flex-row-reverse gap-2 active-tag mb-3 align-items-center">

                                    @if (\Auth::user()->type != 'admin')
                                        {{-- @permission('Edit Plan') --}}
                                        <div class="d-inline-flex align-items-center">
                                            <a class="btn btn-sm btn-icon  bg-light-secondary"
                                                data-url="{{ route('plan.edit', $plan->id) }}"
                                                data-title="{{ __('Edit Plan') }}" data-ajax-popup="true"
                                                data-size="lg" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Edit') }}">
                                                <i class="ti ti-edit f-20"></i>
                                            </a>
                                        </div>
                                        {{-- @endpermission --}}

                                        @if ($plan->price > 0)
                                            <div class="">
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['plan.destroy', $plan->id],
                                                    'id' => 'delete-form-' . $plan->id,
                                                ]) !!}
                                                <a href="#!"
                                                    class="bs-pass-para btn btn-danger show_confirm btn-icon btn-sm">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
                                        @endif
                                    @endif
                                    @if (\Auth::user()->type == 'super admin' && $plan->price > 0)
                                    <div class="form-check form-switch custom-switch-v1 float-end">
                                        <input type="checkbox" name="plan_disable"
                                            class="form-check-input input-primary is_disable" value="1"
                                            data-id='{{ $plan->id }}' data-name="{{ __('plan') }}"
                                            {{ $plan->is_disable == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="plan_disable"></label>
                                    </div>
                                    @endif
                                </div>

                            <h3 class="mb-3 f-w-600 text-start text-success">
                                {{ !empty($setting['CURRENCY']) ? $setting['CURRENCY'] : '$' }}{{ $plan->price . ' / ' . __(\App\Models\Plan::$arrDuration[$plan->duration]) }}</small>
                            </h3>
                            @if ($plan->trial != 0)
                                <p class="mb-0">
                                    {{ __('Free Trial Days : ') . __($plan->trial_days ? $plan->trial_days : 0) }}<br />
                                </p>
                            @endif
                            @if ($plan->description)
                                <p class="text-start">
                                    {{ $plan->description }}<br />
                                </p>
                            @endif
                            <div class="row mb-0">
                                <div class="col-4 text-center">
                                    @if ($plan->max_products == '-1')
                                        <span class="h5 mb-0">{{ __('Unlimited') }}</span>
                                    @else
                                        <span class="h5 mb-0">{{ $plan->max_products }}</span>
                                    @endif
                                    <span class="d-block text-sm">{{ __('Products') }}</span>
                                </div>
                                <div class="col-4 text-center">
                                    <span class="h5 mb-0">
                                        @if ($plan->max_stores == '-1')
                                            <span class="h5 mb-0">{{ __('Unlimited') }}</span>
                                        @else
                                            <span class="h5 mb-0">{{ $plan->max_stores }}</span>
                                        @endif
                                    </span>
                                    <span class="d-block text-sm">{{ __('Store') }}</span>
                                </div>
                                <div class="col-4 text-center">
                                    <span class="h5 mb-0">
                                        @if ($plan->max_users == '-1')
                                            <span class="h5 mb-0">{{ __('Unlimited') }}</span>
                                        @else
                                            <span class="h5 mb-0">{{ $plan->max_users }}</span>
                                        @endif
                                    </span>
                                    <span class="d-block text-sm">{{ __('Users') }}</span>
                                </div>
                            </div>
                            <div class="plan-card-detail text-center">
                                <ul class="list-unstyled d-inline-block my-4">
                                    @if ($plan->enable_domain == 'on')
                                        <li class="d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i
                                                    class="text-success ti ti-circle-plus"></i></span>{{ __('Custom Domain') }}
                                        </li>
                                    @else
                                        <li class="text-danger d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i
                                                    class="text-danger ti ti-circle-plus"></i></span>{{ __('Custom Domain') }}
                                        </li>
                                    @endif
                                    @if ($plan->enable_subdomain == 'on')
                                        <li class="d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i class="text-success ti ti-circle-plus"></i></span>{{ __('Sub Domain') }}
                                        </li>
                                    @else
                                        <li class="text-danger d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i class="text-danger ti ti-circle-plus"></i></span>{{ __('Sub Domain') }}
                                        </li>
                                    @endif
                                    @if ($plan && $plan->enable_chatgpt == 'on')
                                        <li class="d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i class="text-success ti ti-circle-plus"></i></span>{{ __('Chatgpt') }}
                                        </li>
                                    @else
                                        <li class="text-danger d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i class="text-danger ti ti-circle-plus"></i></span>{{ __('Chatgpt') }}
                                        </li>
                                    @endif
                                    @if ($plan && $plan->pwa_store == 'on')
                                        <li class="d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i class="text-success ti ti-circle-plus"></i></span>
                                            {{ __('Progressive Web App (PWA)') }}
                                        </li>
                                    @else
                                        <li class="text-danger d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i
                                                    class="text-danger ti ti-circle-plus"></i></span>{{ __('Progressive Web App (PWA)') }}
                                        </li>
                                    @endif
                                    @if ($plan && $plan->shipping_method == 'on')
                                        <li class="d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i class="text-success ti ti-circle-plus"></i></span>
                                            {{ __('Shipping Method') }}
                                        </li>
                                    @else
                                        <li class="text-danger d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i
                                                    class="text-danger ti ti-circle-plus"></i></span>{{ __('Shipping Method') }}
                                        </li>
                                    @endif
                                    @if ($plan && $plan->enable_tax == 'on')
                                        <li class="d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i class="text-success ti ti-circle-plus"></i></span>{{ __('Enable Tax') }}
                                        </li>
                                    @else
                                        <li class="text-danger d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i class="text-danger ti ti-circle-plus"></i></span>{{ __('Enable Tax') }}
                                        </li>
                                    @endif
                                    @if ($plan && $plan->storage_limit != '0.00')
                                        <li class="d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i class="text-success ti ti-circle-plus"></i></span>
                                            {{ $plan->storage_limit }}{{ __('MB Storage') }}
                                        </li>
                                    @else
                                        <li class="text-danger d-flex align-items-center">
                                            <span class="theme-avtar">
                                                <i class="text-danger ti ti-circle-plus"></i></span>{{ __('0 MB Storage') }}
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="row">
                                @if (\Auth::user()->type != 'super admin')
                                    @if (\Auth::user()->type == 'admin' && \Auth::user()->trial_expire_date)
                                        @if (\Auth::user()->type == 'admin' && \Auth::user()->trial_plan == $plan->id)
                                            <p class="display-total-time mb-0">
                                                {{ __('Plan Trial Expired : ') }}
                                                {{ !empty(\Auth::user()->trial_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->trial_expire_date) : 'Unlimited' }}
                                            </p>
                                        @elseif(\Auth::user()->plan_id == $plan->id &&  !empty(\Auth::user()->trial_expire_date) &&
                                        \Auth::user()->trial_expire_date < date('Y-m-d') && $plan->price > 0)
                                            <div class="col-12">
                                                <p
                                                    class="server-plan font-bold text-center bg-primary mb-0 btn btn-primary w-100 text-success">
                                                    {{ __('Expired') }}
                                                </p>
                                            </div>
                                        @endif
                                    @else
                                        @if (\Auth::user()->type == 'admin' && \Auth::user()->plan_id == $plan->id)
                                            <p class="display-total-time mb-0">
                                                {{ __('Plan Expired : ') }}
                                                {{ !empty(\Auth::user()->plan_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->plan_expire_date) : 'Unlimited' }}
                                            </p>
                                        @elseif(\Auth::user()->plan_id == $plan->id &&
                                        !empty(\Auth::user()->plan_expire_date) &&
                                        \Auth::user()->plan_expire_date < date('Y-m-d') && $plan->price > 0)
                                            <div class="col-12">
                                                <p
                                                    class="server-plan font-bold text-center bg-primary mb-0 btn btn-primary w-100 text-success">
                                                    {{ __('Expired') }}
                                                </p>
                                            </div>
                                        @endif
                                    @endif
                                    @if ($plan->id != \Auth::user()->plan_id)
                                        @if ($plan->price > 0)
                                            <div class="{{ $plan->id == 1 ? 'col-12' : 'col-8' }}">
                                                <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                    class="btn  btn-primary d-flex justify-content-center align-items-center ">{{ __('Subscribe') }}
                                                    <i class="fas fa-arrow-right ms-2"></i></a>
                                            </div>
                                        @endif
                                    @endif

                                @endif
                                @if (\Auth::user()->type != 'super admin' && \Auth::user()->plan_id != $plan->id)
                                    @if ($plan && $plan->id != 1)
                                        @if (\Auth::user()->requested_plan != $plan->id)
                                            <div class="col-4">
                                                <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                                    class="btn btn-primary btn-icon"
                                                    data-title="{{ __('Send Request') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Send Request') }}">
                                                    <span class="btn-inner--icon"><i class="fas fa-share"></i></span>
                                                </a>
                                            </div>
                                        @else
                                            <div class="col-4">
                                                <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                                    class="btn btn-icon m-0 btn-danger"
                                                    data-title="{{ __('Cancle Request') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('Cancel Request') }}">
                                                    <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                    @if ($plan->price > 0 && \Auth::user()->trial_plan == 0 && \Auth::user()->plan_id != $plan->id && $plan->trial == 1)
                                        <a href="{{ route('plan.trial', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                            class="btn btn-lg btn-primary btn-icon m-1">{{ __('Start Free Trial') }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 dataTable ">
                            <thead>
                                <tr>
                                    <th> {{ __('Order Id') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Plan Name') }}</th>
                                    <th> {{ __('Price') }}</th>
                                    <th> {{ __('Payment Type') }}</th>
                                    <th> {{ __('Status') }}</th>
                                    <th> {{ __('Coupon') }}</th>
                                    <th> {{ __('Invoice') }}</th>
                                    @if (\Auth::user()->type == 'super admin')
                                        <th> {{ __('Action') }}</th>
                                    @endif
                                    <th>{{ __('Refund') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>{{ $order->user_name }}</td>
                                        <td>{{ $order->plan_name }}</td>
                                        <td>{{ GetCurrency() . $order->price }}</td>
                                        <td>{{ $order->payment_type }}</td>
                                        <td>
                                            @if ($order->payment_status == 'succeeded')
                                                <i class="mdi mdi-circle text-primary"></i>
                                                {{ ucfirst($order->payment_status) }}
                                            @else
                                                <i class="mdi mdi-circle text-danger"></i>
                                                {{ ucfirst($order->payment_status) }}
                                            @endif
                                        </td>
                                        <td>{{ !empty($order->total_coupon_used) ? (!empty($order->total_coupon_used->coupon_detail) ? $order->total_coupon_used->coupon_detail->code : '-') : '-' }}
                                        </td>

                                        <td class="text-center">
                                            @if ($order->receipt != 'free coupon' && $order->payment_type == 'STRIPE')
                                                <a href="{{ $order->receipt }}" title="Invoice" target="_blank"
                                                    class=""><i class="fas fa-file-invoice"></i> </a>
                                            @elseif($order->receipt == 'free coupon')
                                                <p>{{ __('Used') . '100 %' . __('discount coupon code.') }}</p>
                                            @elseif($order->payment_type == 'Manually')
                                                <p>{{ __('Manually plan upgraded by super admin') }}</p>
                                            @elseif ($order->payment_type == 'Bank Transfer')
                                                <a href="{{ asset($order->receipt) }}" class="btn  btn-outline-primary"
                                                    target="_blank">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @php
                                            $user = App\Models\User::find($order->user_id);
                                        @endphp
                                        @if (\Auth::user()->type == 'super admin')
                                            <td class="text-center">
                                                @if ($order->payment_status == 'Pending' && $order->payment_type == 'Bank Transfer')
                                                    <button class="btn btn-sm btn-primary me-2"
                                                        data-url="{{ route('order.show', $order->id) }}" data-size="lg"
                                                        data-ajax-popup="true" data-title="{{ __('Payment Status') }}"
                                                        title="{{ __('Details') }}">
                                                        <i class="ti ti-caret-right" data-bs-toggle="tooltip"
                                                            title="edit"></i>
                                                    </button>
                                                @endif
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['bank_transfer.destroy', $order->id], 'class' => 'd-inline']) !!}
                                                <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                        title="Delete"></i>
                                                </button>
                                                {!! Form::close() !!}
                                                @foreach($userOrders as $userOrder)
                                                @if ($user->plan_id == $order->plan_id &&
                                                $order->order_id == $userOrder->order_id &&
                                                $order->is_refund == 0)
                                                        <div class="btn btn-sm btn-success ms-2">
                                                            <a href="{{ route('plan.order.refund' , [$order->id , $order->user_id])}}"
                                                                class=""
                                                                data-bs-toggle="tooltip" title="{{ __('Refund') }}"
                                                                data-original-title="{{ __('Refund') }}">
                                                                <span ><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-receipt-refund"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" /><path d="M15 14v-2a2 2 0 0 0 -2 -2h-4l2 -2m0 4l-2 -2" /></svg></span>
                                                            </a>
                                                        </div>
                                                @endif
                                            @endforeach
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
    </div>
@endsection
@push('custom-script')
    <script>
        $(document).on('change', '#trial', function() {
            if ($(this).is(':checked')) {
                $('.plan_div').removeClass('d-none');
                $('#trial_days').attr("required", true);

            } else {
                $('.plan_div').addClass('d-none');
                $('#trial_days').removeAttr("required");
            }
        });
    </script>

    <script>
        $(document).on("click", ".is_disable", function() {

            var id = $(this).attr('data-id');
            var is_disable = ($(this).is(':checked')) ? $(this).val() : 0;
            $.ajax({
                url: '{{ route('plan.disable') }}',
                type: 'POST',
                data: {
                    "is_disable": is_disable,
                    "id": id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.success) {
                        show_toastr('Success', data.success, 'success');
                    } else {
                        show_toastr('error', data.error);

                    }

                }
            });
        });
    </script>
@endpush
