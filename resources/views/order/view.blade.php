@extends('layouts.app')

@section('page-title', __('Order Detail'))

@php
if($order['deliveryboy_id']  == 0 && $order['order_status'] == 0)
{
    $disableSelectBox = false;
}else{
    $disableSelectBox = true;
}
@endphp
@section('action-button')
    <div class=" text-end d-flex all-button-box justify-content-md-end justify-content-center">
        {{-- <a href="#" onclick="saveAsPDF();" id="download-buttons" class="btn btn-sm btn-primary btn-icon d-flex align-items-center"
            data-bs-toggle="tooltip" data-bs-placement="top" title="Print" aria-label="Print">
            <i class="ti ti-printer" style="font-size:20px"></i>
        </a> --}}
        <div class="col-md-3 mx-2">
            {!! Form::select('delivery_boy', $deliveryboys,$order['deliveryboy_id'], [
                'class' => 'form-control select category',
                'id' => 'delivery_boy',
                'disabled' => $disableSelectBox,

            ]) !!}
        </div>
        <a href="{{ route('shippinglabel.pdf', \Illuminate\Support\Facades\Crypt::encrypt($order['id'])) }}" target="_blank"
            class="btn btn-sm btn-primary btn-icon d-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top"
            title="Print" aria-label="Print">
            <i class="ti ti-printer" style="font-size:20px"></i>
        </a>
        <a href="{{ route('order.receipt', \Illuminate\Support\Facades\Crypt::encrypt($order['id'])) }}"
            class="mx-1 btn btn-sm btn-primary btn-icon d-flex align-items-center " data-bs-toggle="tooltip"
            data-bs-placement="top" title="{{ __('Receipt') }}"><i class="ti ti-receipt" style="font-size:20px"></i>
        </a>
        <a href="#"
            id="{{ env('APP_URL') . '/' . $store->slug . '/order/' . \Illuminate\Support\Facades\Crypt::encrypt($order['id']) }}"
            class="btn btn-sm btn-primary btn-icon d-flex align-items-center" onclick="copyToClipboard(this)"
            title="Copy link" data-bs-toggle="tooltip" data-original-title="{{ __('Click to copy') }}"><i class="ti ti-link"
                style="font-size:20px"></i></a>
        @php
            $btn_class = 'btn-info';
            if ($order['order_status'] == 2 || $order['order_status'] == 3) {
                $btn_class = 'btn-danger';
            } elseif ($order['order_status'] == 1) {
                $btn_class = 'btn-success';
            } elseif ($order['order_status'] == 4) {
                $btn_class = ' btn-warning';
            } elseif ($order['order_status'] == 5) {
                $btn_class = 'btn-secondary';
            } elseif ($order['order_status'] == 6) {
                $btn_class = 'btn-dark';
            }

        @endphp
        <div class="btn-group mx-1" id="deliver_btn">

            <button
                class="btn {{ $btn_class }} {{ in_array($order['order_status'], [0, 1, 4, 5, 6]) ? 'dropdown-toggle' : '' }} "
                type="button" {{ in_array($order['order_status'], [2, 3]) ? 'data-bs-toggle="dropdown"' : '' }}
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">{{ __('Status') }} :
                {{ $order['order_status_text'] }}
            </button>
            @if (in_array($order['order_status'], [0, 1, 4, 5, 6]))
                <div class="dropdown-menu" data-popper-placement="bottom-start">
                    <h6 class="dropdown-header">{{ __('Set order status') }}</h6>
                    @if ($order['order_status'] == 0)
                        <a class="dropdown-item order_status" href="#" data-value="confirmed">
                            <i class="fa fa-check-circle text-success"></i> {{ __('Confirmed') }}
                        </a>
                    @endif
                    @if ($order['order_status'] == 0 || $order['order_status'] == 4)
                        <a class="dropdown-item order_status " href="#" data-value="pickedup">
                            <i class="fa fa-truck text-success"></i> {{ __('Picked Up') }}
                        </a>
                    @endif
                    @if ($order['order_status'] == 0 || $order['order_status'] == 5 || $order['order_status'] == 4)
                        <a class="dropdown-item order_status " href="#" data-value="shipped">
                            <i class="fa fa-spinner text-success"></i> {{ __('Shipped') }}
                        </a>
                    @endif
                    @if (
                        $order['order_status'] == 0 ||
                            $order['order_status'] == 4 ||
                            $order['order_status'] == 5 ||
                            $order['order_status'] == 6)
                        <a class="dropdown-item order_status" href="#" data-value="delivered">
                            <i class="fa fa-check text-success"></i> {{ __('Delivered') }}
                        </a>
                    @endif
                    @if (
                        $order['order_status'] == 0 ||
                            $order['order_status'] == 4 ||
                            $order['order_status'] == 5 ||
                            $order['order_status'] == 6)
                        <a class="dropdown-item order_status text-danger" href="#" data-value="cancel">
                            <i class="fa fa-check text-danger"></i> {{ __('Cancel Order') }}
                        </a>
                    @endif
                    @if ($order['order_status'] == 1 && $order['is_guest'] == 0)
                        <a class="dropdown-item order_status text-danger" href="#" data-value="return">
                            <i class="fa fa-check text-danger"></i> {{ __('Return Order') }}
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <div class="col-md-2">
            {!! Form::select('payment_status', App\Models\order::payment_status(), $order['payment_status'], [
                'class' => 'form-control select category',
                'id' => 'payment_status',
            ]) !!}
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('order.index') }}">{{ __('Order') }}</a></li>
    <li class="breadcrumb-item"> {{ __('Items from Order') }} {{ $order['order_id'] }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row" id="printableArea">
                <div class="col-xxl-7">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-lg-6 ">
                                <div class="">
                                    <div class="p-4 d-flex gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"
                                            viewBox="0 0 36 36" fill="none">
                                            <circle cx="18" cy="18" r="18" fill="#E8F8FF" />
                                            <path
                                                d="M16.98 28.055C17.0959 28.2289 17.291 28.3333 17.5 28.3333C17.709 28.3333 17.9041 28.2289 18.02 28.055C19.4992 25.8364 21.6778 23.0963 23.196 20.3096C24.4099 18.0815 25 16.1811 25 14.5C25 10.3645 21.6355 7 17.5 7C13.3645 7 10 10.3645 10 14.5C10 16.1811 10.5901 18.0815 11.804 20.3096C13.3211 23.0942 15.5039 25.841 16.98 28.055ZM17.5 8.25C20.9462 8.25 23.75 11.0538 23.75 14.5C23.75 15.9668 23.2097 17.6716 22.0983 19.7116C20.7897 22.1137 18.9222 24.5503 17.5 26.5987C16.078 24.5506 14.2104 22.1138 12.9017 19.7116C11.7903 17.6716 11.25 15.9668 11.25 14.5C11.25 11.0538 14.0538 8.25 17.5 8.25Z"
                                                fill="#002332" />
                                            <path
                                                d="M17.5 18.25C19.5677 18.25 21.25 16.5677 21.25 14.5C21.25 12.4323 19.5677 10.75 17.5 10.75C15.4323 10.75 13.75 12.4323 13.75 14.5C13.75 16.5677 15.4323 18.25 17.5 18.25ZM17.5 12C18.8785 12 20 13.1215 20 14.5C20 15.8785 18.8785 17 17.5 17C16.1215 17 15 15.8785 15 14.5C15 13.1215 16.1215 12 17.5 12Z"
                                                fill="#002332" />
                                        </svg>
                                        <h5 class="d-flex align-items-center mb-0">{{ __('Shipping Information') }}</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <address class="mb-0 text-sm">
                                            <dl class="row mt-4 align-items-center">
                                                <dt class="col-sm-3 h6 text-sm">{{ __('Name') }}</dt>
                                                <dd class="col-sm-9 text-sm">
                                                    {{ !empty($order['delivery_informations']['name']) ? $order['delivery_informations']['name'] : '' }}
                                                </dd>
                                                <dt class="col-sm-3 h6 text-sm">{{ __('Email') }}</dt>
                                                <dd class="col-sm-9 text-sm">
                                                    {{ !empty($order['delivery_informations']['email']) ? $order['delivery_informations']['email'] : '' }}
                                                </dd>
                                                <dt class="col-sm-3 h6 text-sm">{{ __('City') }}</dt>
                                                <dd class="col-sm-9 text-sm">
                                                    {{ !empty($order['delivery_informations']['city']) ? $order['delivery_informations']['city'] : '' }}
                                                </dd>
                                                <dt class="col-sm-3 h6 text-sm">{{ __('State') }}</dt>
                                                <dd class="col-sm-9 text-sm">
                                                    {{ !empty($order['delivery_informations']['state']) ? $order['delivery_informations']['state'] : '' }}
                                                </dd>
                                                <dt class="col-sm-3 h6 text-sm">{{ __('Country') }}</dt>
                                                <dd class="col-sm-9 text-sm">
                                                    {{ !empty($order['delivery_informations']['country']) ? $order['delivery_informations']['country'] : '' }}
                                                </dd>
                                                <dt class="col-sm-3 h6 text-sm">{{ __('Postal Code') }}</dt>
                                                <dd class="col-sm-9 text-sm">
                                                    {{ !empty($order['delivery_informations']['post_code']) ? $order['delivery_informations']['post_code'] : '' }}
                                                </dd>
                                                <dt class="col-sm-3 h6 text-sm">{{ __('Phone') }}</dt>
                                                <dd class="col-sm-9 text-sm">
                                                    <a href="https://api.whatsapp.com/send?phone={{ !empty($order['delivery_informations']['phone']) ? $order['delivery_informations']['phone'] : '' }}&amp;text=Hi"
                                                        target="_blank">
                                                        {{ !empty($order['delivery_informations']['phone']) ? $order['delivery_informations']['phone'] : '' }}
                                                    </a>
                                                </dd>
                                            </dl>
                                        </address>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-lg-6 ">
                                <div class="">
                                    <div class="p-4 d-flex gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"
                                            viewBox="0 0 36 36" fill="none">
                                            <circle cx="18" cy="18" r="18" fill="#FFAEBD"
                                                fill-opacity="0.31" />
                                            <path
                                                d="M27.1475 22.2505V22.2605C27.1421 22.9341 26.5923 23.4806 25.9175 23.4806H10.0834C9.40525 23.4806 8.85336 22.9287 8.85336 22.2505V13.0835C8.85336 12.4053 9.40525 11.8534 10.0834 11.8534H25.9175C26.5956 11.8534 27.1475 12.4053 27.1475 13.0835V22.2505ZM25.9175 10.98H10.0834C8.924 10.98 7.98 11.924 7.98 13.0835V22.2506C7.98 23.41 8.924 24.354 10.0834 24.354H25.9175C27.0769 24.354 28.0209 23.41 28.0209 22.2506V13.0835C28.0209 11.924 27.0769 10.98 25.9175 10.98Z"
                                                fill="#D80027" stroke="#D80027" stroke-width="0.04" />
                                            <path
                                                d="M27.1475 15.9801H8.85336V14.3534H27.1475V15.9801ZM27.5842 13.48H8.4167C8.17564 13.48 7.98 13.6756 7.98 13.9167V16.4168C7.98 16.6579 8.17564 16.8535 8.4167 16.8535H27.5843C27.8253 16.8535 28.021 16.6579 28.021 16.4168V13.9167C28.0209 13.6756 27.8253 13.48 27.5842 13.48Z"
                                                fill="#D80027" stroke="#D80027" stroke-width="0.04" />
                                            <path
                                                d="M14.2503 19.314H10.9168C10.6757 19.314 10.4801 19.5096 10.4801 19.7507C10.4801 19.9917 10.6757 20.1873 10.9168 20.1873H14.2503C14.4913 20.1873 14.687 19.9917 14.687 19.7506C14.687 19.5096 14.4913 19.314 14.2503 19.314Z"
                                                fill="#D80027" stroke="#D80027" stroke-width="0.04" />
                                            <path
                                                d="M16.7504 20.98H10.9168C10.6757 20.98 10.4801 21.1756 10.4801 21.4167C10.4801 21.6578 10.6757 21.8534 10.9168 21.8534H16.7504C16.9914 21.8534 17.1871 21.6578 17.1871 21.4167C17.1871 21.1756 16.9914 20.98 16.7504 20.98Z"
                                                fill="#D80027" stroke="#D80027" stroke-width="0.04" />
                                        </svg>
                                        <h5 class="d-flex align-items-center mb-0">{{ __('Billing Information') }}</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <dl class="row mt-4 align-items-center">
                                            <dt class="col-sm-3 h6 text-sm">{{ __('Name') }}</dt>
                                            <dd class="col-sm-9 text-sm">
                                                {{ !empty($order['billing_informations']['name']) ? $order['billing_informations']['name'] : '' }}
                                            </dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('Email') }}</dt>
                                            <dd class="col-sm-9 text-sm">
                                                {{ !empty($order['billing_informations']['email']) ? $order['billing_informations']['email'] : '' }}
                                            </dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('City') }}</dt>
                                            <dd class="col-sm-9 text-sm">
                                                {{ !empty($order['billing_informations']['city']) ? $order['billing_informations']['city'] : '' }}
                                            </dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('State') }}</dt>
                                            <dd class="col-sm-9 text-sm">
                                                {{ !empty($order['billing_informations']['state']) ? $order['billing_informations']['state'] : '' }}
                                            </dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('Country') }}</dt>
                                            <dd class="col-sm-9 text-sm">
                                                {{ !empty($order['billing_informations']['country']) ? $order['billing_informations']['country'] : '' }}
                                            </dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('Postal Code') }}</dt>
                                            <dd class="col-sm-9 text-sm">
                                                {{ !empty($order['billing_informations']['post_code']) ? $order['billing_informations']['post_code'] : '' }}
                                            </dd>
                                            <dt class="col-sm-3 h6 text-sm">{{ __('Phone') }}</dt>
                                            <dd class="col-sm-9 text-sm">
                                                <a href="https://api.whatsapp.com/send?phone={{ !empty($order['billing_informations']['phone']) ? $order['billing_informations']['phone'] : '' }}&amp;text=Hi"
                                                    target="_blank">
                                                    {{ !empty($order['billing_informations']['phone']) ? $order['billing_informations']['phone'] : '' }}
                                                </a>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mb-0">{{ __('Items from Order') }} {{ $order['order_id'] }} </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Item') }}</th>
                                            <th>{{ __('Quantity') }}</th>
                                            @if ($order['order_status'] == 1)
                                                <th>{{ __('Downloadable Product') }}</th>
                                            @endif
                                            <th>{{ __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order['product'] as $item)
                                            @php
                                                $variant = \App\Models\ProductVariant::where('id', $item['variant_id'])->first();
                                                $product = \App\Models\Product::where('id', $item['product_id'])->first();
                                            @endphp

                                            <tr>
                                                <td class="total">
                                                    <span class="h6 text-sm"> <a href="#">{{ $item['name'] }}</a>
                                                    </span> <br>
                                                    <span class="text-sm"> {{ $item['variant_name'] }} </span>
                                                </td>
                                                <td>
                                                    @if ($order['paymnet_type'] == 'POS')
                                                        {{ $item['quantity'] }}
                                                    @else
                                                        {{ $item['qty'] }}
                                                    @endif
                                                </td>
                                                @if ($product || $variant)
                                                    @if ($order['order_status'] == 1)
                                                        @if (!empty($variant->downloadable_product) != null || !empty($product->downloadable_product) != null)
                                                            <td>
                                                                @if (!empty($variant->downloadable_product))
                                                                    <a class="downloadable_product_{{ $item['product_id'] }}"
                                                                        href="{{ get_file($variant->downloadable_product) }}"
                                                                        download style="display: none;"></a>
                                                                @endif
                                                                @if (!empty($product->downloadable_product))
                                                                    <a class="downloadable_product_{{ $item['product_id'] }}"
                                                                        href="{{ get_file($product->downloadable_product) }}"
                                                                        download style="display: none;"></a>
                                                                @endif
                                                                <button
                                                                    class="download-btn action-btn btn-primary btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Download') }}"
                                                                    data-product-id="{{ $item['product_id'] }}">
                                                                    <i class="ti ti-download text-white"></i>
                                                                </button>
                                                            </td>
                                                        @else
                                                            <td>-</td>
                                                        @endif
                                                    @endif
                                                @endif
                                                <td>
                                                    @if ($order['paymnet_type'] == 'POS')
                                                        {{ currency_format_with_sym( ($item['orignal_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($item['orignal_price']) }}
                                                    @else
                                                        {{ currency_format_with_sym( ($item['final_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($item['final_price']) }}
                                                    @endif
                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-total-footer">
                                <ul class="list-unstyled mt-4">
                                    <li class="d-flex justify-content-end">
                                        <span>{{ __('Sub Total') }} :</span>
                                        <span
                                            class="text-start ps-3"><b>{{ currency_format_with_sym( ($order['sub_total'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['sub_total']) }}</b></span>
                                    </li>
                                    <li class="d-flex justify-content-end">
                                        <span>{{ __('Estimated Tax') }} :</span>
                                        <span class="text-start ps-3"><b>
                                                @if ($order['paymnet_type'] == 'POS')
                                                    {{ currency_format_with_sym( ($order['tax_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['tax_price']) }}
                                                @else
                                                    {{ currency_format_with_sym( ($order['tax_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['tax_price']) }}
                                                    {{-- {{ SetNumberFormat(array_sum(array_column($order['tax'], 'amountstring') )) }} --}}
                                                @endif
                                            </b></span>
                                    </li>
                                    @if ($order['paymnet_type'] == 'POS')
                                        <li class="d-flex justify-content-end">
                                            <span>{{ __('Discount') }} :</span>
                                            <span
                                                class="text-start ps-3"><b>{{ currency_format_with_sym( ($order['coupon_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? (!empty($order['coupon_price']) ? SetNumberFormat($order['coupon_price']) : SetNumberFormat(0)) }}</b></span>
                                        </li>
                                    @else
                                        <li class="d-flex justify-content-end">
                                            <span>{{ __('Discount') }} :</span>
                                            <span
                                                class="text-start ps-3"><b>{{ currency_format_with_sym( ($order['coupon_info']['discount_amount'] ?? 0), getCurrentStore(), APP_THEME()) ?? (!empty($order['coupon_info']['discount_amount']) ? SetNumberFormat($order['coupon_info']['discount_amount']) : SetNumberFormat(0)) }}</b></span>
                                        </li>
                                    @endif
                                    <li class="d-flex justify-content-end">
                                        <span>{{ __('Delivered Charges') }} :</span>
                                        <span
                                            class="text-start ps-3"><b>{{ currency_format_with_sym( ($order['delivered_charge'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['delivered_charge']) }}</b></span>
                                    </li>
                                    <li class="d-flex justify-content-end">
                                        <span><b>{{ __('Grand Total') }} :</b></span>
                                        <span
                                            class="text-start ps-3"><b>{{ currency_format_with_sym( ($order['final_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['final_price']) }}</b></span>
                                    </li>

                                    <li class="d-flex justify-content-end">
                                        <span>{{ __('Return') }} :</span>
                                        <span
                                            class="text-start ps-3"><b>{{ currency_format_with_sym( ($order['return_price'] ?? 0), getCurrentStore(), APP_THEME()) ?? SetNumberFormat($order['return_price']) }}</b></span>
                                    </li>
                                    <li class="d-flex justify-content-end">
                                        <span>{{ __('Payment Type') }} :</span>
                                        <span class="text-start ps-3"><b>{{ $order['paymnet_type'] }}</b></span>
                                    </li>
                                    <li class="d-flex justify-content-end">
                                        <span>{{ __('Order Status') }} :</span>
                                        <span class="text-start ps-3"><b>{{ $order['order_status_text'] }}</b></span>
                                    </li>
                                    <li class="d-flex justify-content-end">
                                        <span>{{ __('Last Return Date') }} :</span>
                                        <span class="text-start ps-3"><b>-</b></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-5">
                    {{-- @permission('Manage Order Note') --}}
                    <div class="card  p-0">
                        <div class="card-header d-flex justify-content-between pb-0">
                            <h5 class="mb-4">{{ __('Order Notes') }}</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($order_notes as $note)
                                <div class="card">
                                    @if ($note->status == 'Stock Manage')
                                        <div class="card-header note-bg-color  stock-mange-note">
                                            <span class="tl-btn licence-btn ">
                                                {{ $note->notes }}
                                            </span>
                                            <div class="cart-time-wrapper">
                                                <span
                                                    class="time">{{ $note->created_at->format('d M Y h:i A') }}{{ __(' by ') }}{{ Auth::user()->name }}</span>
                                                {{-- @permission('Delete Order Note') --}}
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['order-note.destroy', $note->id],
                                                    'class' => 'd-inline',
                                                ]) !!}
                                                <button type="button" class="btn btn-sm show_confirm order-not-dlt">
                                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                        title="Delete"></i>
                                                </button>
                                                {!! Form::close() !!}
                                                {{-- @endpermission --}}
                                            </div>
                                        </div>
                                    @elseif ($note->status == 'Order Created')
                                        <div class="card-header note-bg-color order-create-note">
                                            <span class="tl-btn licence-btn ">
                                                {{ $note->notes }}
                                            </span>
                                            <div class="cart-time-wrapper">
                                                <span
                                                    class="time">{{ $note->created_at->format('d M Y h:i A') }}{{ __(' by ') }}{{ Auth::user()->name }}</span>
                                                {{-- @permission('Delete Order Note') --}}
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['order-note.destroy', $note->id],
                                                    'class' => 'd-inline',
                                                ]) !!}
                                                <button type="button" class="btn btn-sm  show_confirm order-not-dlt">
                                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                        title="Delete"></i>
                                                </button>
                                                {!! Form::close() !!}
                                                {{-- @endpermission --}}
                                            </div>
                                        </div>
                                    @elseif ($note->status == 'Order status change')
                                        <div class="card-header note-bg-color order-status-note">
                                            <span class="tl-btn licence-btn ">
                                                {{ $note->notes }}
                                            </span>
                                            <div class="cart-time-wrapper">
                                                <span
                                                    class="time">{{ $note->created_at->format('d M Y h:i A') }}{{ __(' by ') }}{{ Auth::user()->name }}</span>
                                                {{-- @permission('Delete Order Note') --}}
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['order-note.destroy', $note->id],
                                                    'class' => 'd-inline',
                                                ]) !!}
                                                <button type="button" class="btn btn-sm  show_confirm order-not-dlt">
                                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                        title="Delete"></i>
                                                </button>
                                                {!! Form::close() !!}
                                                {{-- @endpermission --}}
                                            </div>
                                        </div>
                                    @elseif ($note->status == '' && $note->note_type == 'private_note')
                                        <div class="card-header note-bg-color admin-private-note">
                                            <span class="tl-btn licence-btn ">
                                                {{ $note->notes }}
                                            </span>
                                            <div class="cart-time-wrapper">
                                                <span
                                                    class="time">{{ $note->created_at->format('d M Y h:i A') }}{{ __(' by ') }}{{ Auth::user()->name }}</span>
                                                {{-- @permission('Delete Order Note') --}}
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['order-note.destroy', $note->id],
                                                    'class' => 'd-inline',
                                                ]) !!}
                                                <button type="button" class="btn btn-sm  show_confirm order-not-dlt">
                                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                        title="Delete"></i>
                                                </button>
                                                {!! Form::close() !!}
                                                {{-- @endpermission --}}
                                            </div>
                                        </div>
                                    @elseif ($note->status == '' && $note->note_type == 'to_customer')
                                        <div class="card-header note-bg-color customer-note">
                                            <span class="tl-btn licence-btn ">
                                                {{ $note->notes }}
                                            </span>
                                            <div class="cart-time-wrapper">
                                                <span
                                                    class="time">{{ $note->created_at->format('d M Y h:i A') }}{{ __(' by ') }}{{ Auth::user()->name }}</span>
                                                {{-- @permission('Delete Order Note') --}}
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['order-note.destroy', $note->id],
                                                    'class' => 'd-inline',
                                                ]) !!}
                                                <button type="button" class="btn btn-sm  show_confirm order-not-dlt">
                                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                        title="Delete"></i>
                                                </button>
                                                {!! Form::close() !!}
                                                {{-- @endpermission --}}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                            {{-- @permission('Create Order Note') --}}
                            {{ Form::open(['route' => 'order-note.store', 'method' => 'POST', 'id' => 'choice_form', 'enctype' => 'multipart/form-data']) }}
                            <div class="row">
                                <input type="hidden" class="hidden" name="order_id" value="{{ $order['id'] }}">
                                <div class="form-group col-md-8">
                                    {!! Form::label('', __('Note'), ['class' => 'form-label']) !!}
                                    <textarea name="note" rows="3" class="autogrow form-control" placeholder="message here..."></textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::label('', __('Note Type'), ['class' => 'form-label']) !!}
                                    {!! Form::select('note_type', ['private_note' => 'Private Note', 'to_customer' => 'To Customer'], null, [
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="submit" value="Create" class="btn btn-primary ">
                                </div>
                            </div>
                            {!! Form::close() !!}
                            {{-- @endpermission --}}
                        </div>
                    </div>
                    {{-- @endpermission --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-script')
    <script src="{{ asset('js/html2pdf.bundle.min.js') }}{{ '?' . time() }}"></script>
    <script>
        var filename = $('#filesname').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();

        }

        $(document).on('click', '.order_return', function() {
            var product_id = $(this).attr('product-id');
            var variant_id = $(this).attr('variant-id');
            var order_id = $(this).attr('order-id');

            var data = {
                product_id: product_id,
                variant_id: variant_id,
                order_id: order_id
            }
            $.ajax({
                url: '{{ route('order.return') }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(data) {
                    if (data.status == 'error') {
                        show_toastr('{{ __('Error') }}', data.message, 'error')
                    } else {
                        show_toastr('{{ __('Success') }}', data.message, 'success')
                        $(this).parent().append('<h6 class="text-danger">{{ __('Returned') }}</h6>');
                        $(this).parent().find('.order_return').remove();
                    }
                }
            });
        });

        $(document).on('click', '.order_status', function() {
            var status = $(this).attr('data-value');

            var data = {
                delivered: status,
                id: "{{ $order['id'] }}",
            }
            $.ajax({
                url: '{{ route('order.status.change', $order['id']) }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(data) {
                    if (data.status == 'error') {
                        show_toastr('{{ __('Error') }}', data.message, 'error')
                    } else {
                        show_toastr('{{ __('Success') }}', data.message, 'success')
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });

        $(document).on('change', '#payment_status', function() {
            var payment_status = $(this).val();
            var data = {
                payment_status: payment_status,
                order_id: "{{ $order['id'] }}",

            }
            $.ajax({
                url: '{{ route('order.payment.status') }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(data) {
                    show_toastr('{{ __('Success') }}', data.message, 'success')


                }
            });
        });

        $(document).on('change', '#delivery_boy', function() {
            var delivery_boy = $(this).val();

             var data = {
                delivery_boy: delivery_boy,
                order_id: "{{ $order['id'] }}",
            }

            $.ajax({
                url: '{{ route('order.assign') }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(data) {
                    show_toastr('{{ __('Success') }}', data.message, 'success')


                }
            });
        });

        function myFunction() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            show_toastr('Success', 'Link copied', 'success');
        }

        function copyToClipboard(element) {

            var copyText = element.id;
            document.addEventListener('copy', function(e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('success', 'Url copied to clipboard', 'success');
        }

        document.querySelectorAll('.download-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const downloadLink = document.querySelector('.downloadable_product_' + productId);

                if (downloadLink) {
                    downloadLink.click();
                } else {
                    console.error('Download link not found for product ID:', productId);
                }
            });
        });
    </script>
@endpush
