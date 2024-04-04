@extends('layouts.app')

@section('page-title', __('Order'))

@section('action-button')
<div class="text-end">
    <a class="btn btn-sm btn-primary btn-icon " href="{{ route('order.export') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Export') }}">
        <i  class="ti ti-file-export"></i>
    </a>
</div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Order') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('OrderID') }}</th>
                                    <th>{{ __('date') }}</th>
                                    <th>{{ __('Customer Info') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Payment type') }}</th>
                                    <th>{{ __('order status') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $item)

                                <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('order.view', \Illuminate\Support\Facades\Crypt::encrypt($item->id)) }}"
                                                    @php
                                                        $btn_class = 'bg-info';
                                                        if($item->delivered_status == 2 || $item->delivered_status == 3) {
                                                            $btn_class = 'bg-danger';
                                                        } elseif($item->delivered_status == 1) {
                                                            $btn_class = 'bg-success';
                                                        }elseif($item->delivered_status == 4) {
                                                            $btn_class = ' btn-warning';
                                                        } elseif($item->delivered_status == 5) {
                                                            $btn_class = 'btn-secondary';
                                                        } elseif($item->delivered_status == 6) {
                                                            $btn_class = 'btn-dark';
                                                        }
                                                    @endphp

                                                    class="btn {{ $btn_class }} btn-sm text-sm"
                                                    data-toggle="tooltip" title="{{ __('Invoice ID') }}">
                                                    <span class="btn-inner--icon"></span>
                                                    <span class="btn-inner--text">#{{ $item->product_order_id }}</span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ \App\Models\Utility::dateFormat($item->order_date) }}</td>

                                        <td>
                                            @if ($item->is_guest == 1)
                                                {{ __('Guest') }}
                                            @elseif ($item->user_id != 0)
                                                {{ !empty($item->UserData->name) ? $item->UserData->name : '' }}<br>
                                                {{ !empty($item->UserData->mobile) ? $item->UserData->mobile : '' }}
                                            @else
                                                {{ __('Walk-in-customer') }}
                                            @endif
                                        </td>
                                        <td>{{ currency_format_with_sym( ($item->final_price ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($item->final_price) }}</td>
                                        <td>
                                            @if ($item->payment_type == 'cod')
                                                {{ __('Cash On Delivery') }}
                                            @elseif ($item->payment_type == 'bank_transfer')
                                                {{ __('Bank Transfer') }}
                                            @elseif ($item->payment_type == 'stripe')
                                                {{ __('Stripe')}}
                                            @elseif ($item->payment_type == 'paystack')
                                            {{ __('Paystack')}}
                                            @elseif ($item->payment_type == 'Mercado')
                                            {{ __('Mercado Pago')}}
                                            @elseif ($item->payment_type == 'skrill')
                                            {{ __('Skrill')}}
                                            @elseif ($item->payment_type == 'paymentwall')
                                            {{ __('PaymentWall')}}
                                            @elseif ($item->payment_type == 'Razorpay')
                                            {{ __('Razorpay')}}
                                            @elseif ($item->payment_type == 'paypal')
                                            {{ __('Paypal')}}
                                            @elseif ($item->payment_type == 'flutterwave')
                                            {{ __('Flutterwave')}}
                                            @elseif ($item->payment_type == 'mollie')
                                            {{ __('Mollie')}}
                                            @elseif ($item->payment_type == 'coingate')
                                            {{ __('Coingate')}}
                                            @elseif ($item->payment_type == 'paytm')
                                            {{ __('Paytm')}}
                                            @elseif ($item->payment_type == 'POS')
                                            {{ __('POS')}}
                                            @elseif ($item->payment_type == 'toyyibpay')
                                            {{ __('Toyyibpay')}}
                                            @elseif ($item->payment_type == 'sspay')
                                            {{ __('Sspay')}}
                                            @elseif ($item->payment_type == 'Paytabs')
                                            {{ __('Paytabs')}}
                                            @elseif ($item->payment_type == 'iyzipay')
                                            {{ __('IyziPay')}}
                                            @elseif ($item->payment_type == 'payfast')
                                            {{ __('PayFast')}}
                                            @elseif ($item->payment_type == 'benefit')
                                            {{ __('Benefit')}}
                                            @elseif ($item->payment_type == 'cashfree')
                                            {{ __('Cashfree')}}
                                            @elseif ($item->payment_type == 'aamarpay')
                                            {{ __('Aamarpay')}}
                                            @elseif ($item->payment_type == 'telegram')
                                            {{ __('Telegram')}}
                                            @elseif ($item->payment_type == 'whatsapp')
                                            {{ __('Whatsapp')}}
                                            @elseif ($item->payment_type == 'paytr')
                                            {{ __('PayTR')}}
                                            @elseif ($item->payment_type == 'yookassa')
                                            {{ __('Yookassa')}}
                                            @elseif ($item->payment_type == 'midtrans')
                                            {{ __('Midtrans')}}
                                            @elseif ($item->payment_type == 'Xendit')
                                            {{ __('Xendit')}}
                                            @elseif ($item->payment_type == 'Nepalste')
                                            {{ __('Nepalste')}}
                                            @elseif ($item->payment_type == 'khalti')
                                            {{ __('Khalti')}}
                                            @elseif ($item->payment_type == 'AuthorizeNet')
                                            {{ __('AuthorizeNet')}}
                                            @elseif ($item->payment_type == 'Tap')
                                            {{ __('Tap')}}
                                            @elseif ($item->payment_type == 'PhonePe')
                                            {{ __('PhonePe')}}
                                            @elseif ($item->payment_type == 'Paddle')
                                            {{ __('Paddle')}}
                                            @elseif ($item->payment_type == 'Paiementpro')
                                            {{ __('Paiement Pro')}}
                                            @elseif ($item->payment_type == 'FedPay')
                                            {{ __('FedPay')}}
                                            @endif
                                        </td>

                                        <td>
                                            @if ($item->delivered_status == 0)
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-info btn-icon rounded-pill badge-same">
                                                    <span class="btn-inner--icon">
                                                        <i class="fas fa-check soft-info"></i>
                                                    </span>
                                                    <span class="btn-inner--text"> {{ __('Pending'  ) }} : {{ \App\Models\Utility::dateFormat($item->order_date) }} </span>
                                                </button>
                                            @elseif ($item->delivered_status == 1)
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-success btn-icon bg-success rounded-pill badge-same">
                                                    <span class="btn-inner--text"> {{ __('Delivered') }} : {{ \App\Models\Utility::dateFormat($item->delivery_date) }} </span>
                                                </button>
                                            @elseif ($item->delivered_status == 2)
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-danger btn-icon bg-danger rounded-pill badge-same">
                                                    <span class="btn-inner--text"> {{ __('Cancel') }} : {{ \App\Models\Utility::dateFormat($item->cancel_date) }} </span>
                                                </button>
                                            @elseif ($item->delivered_status == 3)
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-danger btn-icon bg-danger rounded-pill badge-same">
                                                    <span class="btn-inner--text"> {{ __('Return') }} : {{ \App\Models\Utility::dateFormat($item->return_date) }} </span>
                                                </button>
                                            @elseif ($item->delivered_status == 4)
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-warning btn-icon bg-warning rounded-pill badge-same">
                                                    <span class="btn-inner--text"> {{ __('Confirmed') }} : {{ \App\Models\Utility::dateFormat($item->confirmed_date) }} </span>
                                                </button>
                                            @elseif ($item->delivered_status == 5)
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-secondary btn-icon rounded-pill badge-same">
                                                    <span class="btn-inner--icon">
                                                        <i class="fas fa-check soft-secondary"></i>
                                                    </span>
                                                    <span class="btn-inner--text"> {{ __('Picked Up') }} : {{ \App\Models\Utility::dateFormat($item->picked_date) }} </span>
                                                </button>
                                            @elseif ($item->delivered_status == 6)
                                                <button type="button"
                                                    class="btn btn-sm btn-soft-dark btn-icon bg-dark rounded-pill badge-same">
                                                    <span class="btn-inner--text"> {{ __('Shipped') }} : {{ \App\Models\Utility::dateFormat($item->shipped_date) }} </span>
                                                </button>
                                            @endif
                                    </td>
                                        </td>

                                        <td class="text-end">
                                            @if ($item->delivered_status == 3 && $item->return_status == 1)
                                            <a href="#"
                                                class="btn btn-sm btn-info me-2 return_request" data-id="{{ $item->id }}" data-status="2">
                                                <i class="ti ti-check py-1" data-bs-toggle="tooltip" title="{{ __('Return order request approve') }}"></i>
                                            </a>
                                            <a href="#"
                                                class="btn btn-sm btn-info me-2 return_request" data-id="{{ $item->id }}" data-status="3">
                                                <i class="ti ti-circle-x py-1" data-bs-toggle="tooltip" title="{{ __('Return order request cancel') }}"></i>
                                            </a>
                                            @endif

                                            {{-- @permission('Show Orders') --}}
                                                <a href="javascript:void(0)" data-url="{{ route('order.order_view', \Illuminate\Support\Facades\Crypt::encrypt($item->id)) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Order')}}    #{{ $item->product_order_id }}" class="x-3 btn btn-sm align-items-center btn btn-sm btn-info me-2" data-bs-toggle="tooltip" data-original-title="{{__('Show')}}">
                                                    <i class="ti ti-eye py-1" data-bs-toggle="tooltip" title="view"></i>
                                                </a>
                                                <a href="{{ route('order.view', \Illuminate\Support\Facades\Crypt::encrypt($item->id)) }}"
                                                    class="btn btn-sm btn-primary me-2">
                                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                                </a>
                                            {{-- @endpermission --}}

                                            {{-- @permission('Delete Orders') --}}
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['order.destroy', $item->id], 'class' => 'd-inline']) !!}
                                            <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                <i class="ti ti-trash text-white py-1"></i>
                                            </button>
                                            {!! Form::close() !!}
                                            {{-- @endpermission --}}
                                        </td>
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
        $(document).on('click', '.code', function() {
            var type = $(this).val();
            $('#code_text').addClass('col-md-12').removeClass('col-md-8');
            $('#autogerate_button').addClass('d-none');
            if (type == 'auto') {
                $('#code_text').addClass('col-md-8').removeClass('col-md-12');
                $('#autogerate_button').removeClass('d-none');
            }
        });

        $(document).on('click', '.return_request', function() {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var data = {
                id: id,
                status: status
            }
            $.ajax({
                url: '{{ route('order.return.request') }}',
                method: 'POST',
                data: data,
                context:this,
                success: function (response)
                {
                    if(response.status == 'error') {
                        show_toastr('{{ __('Error') }}', response.message, 'error')
                    } else {
                        show_toastr('{{ __('Success') }}', response.message, 'success')
                        $(this).parent().find('.return_request').remove();
                    }
                }
            });
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
@endpush
