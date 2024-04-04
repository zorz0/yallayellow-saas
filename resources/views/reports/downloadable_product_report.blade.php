@extends('layouts.app')

@section('page-title')
    {{ __('Sales Downloadable Product') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Sales Downloadable Product') }}</li>
@endsection

@section('action-button')
    <div class="text-end">
    </div>
@endsection
@section('content')
    <div class="row">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Order Id') }}</th>
                                    <th>{{ __('Product Name') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Attachment') }}</th>
                                    <th>{{ __('Timestamp') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($orders))
                                    @foreach ($orders as $order)
                                    @php
                                        $products = json_decode($order->product_json, true);
                                    @endphp

                                    @foreach ($products as $product)
                                    @php
                                        $variant = \App\Models\ProductVariant::where('id', $product['variant_id'])->first();
                                        $d_product = \App\Models\Product::where('id',$product['product_id'])->first();
                                    @endphp
                                    @if (!empty($variant->downloadable_product) != null || !empty($d_product->downloadable_product) != null)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ route('order.view', \Illuminate\Support\Facades\Crypt::encrypt($order->id)) }}"
                                                        @php
                                                            $btn_class = 'bg-info';
                                                            if($order->delivered_status == 2 || $order->delivered_status == 3) {
                                                                $btn_class = 'bg-danger';
                                                            } elseif($order->delivered_status == 1) {
                                                                $btn_class = 'bg-success';
                                                            }elseif($order->delivered_status == 4) {
                                                                $btn_class = ' btn-warning';
                                                            } elseif($order->delivered_status == 5) {
                                                                $btn_class = 'btn-secondary';
                                                            } elseif($order->delivered_status == 6) {
                                                                $btn_class = 'btn-dark';
                                                            }
                                                        @endphp

                                                        class="btn {{ $btn_class }}  text-white btn-sm text-sm"
                                                        data-toggle="tooltip" title="{{ __('Invoice ID') }}">
                                                        <span class="btn-inner--icon"></span>
                                                        <span class="btn-inner--text">#{{ $order->product_order_id }}</span>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="h6 text-sm">{{ $product['name'] }}
                                                </span> <br>
                                                <span class="text-sm"> {{ $product['variant_name'] }} </span>
                                            </td>
                                            <td>
                                                @if ($order->is_guest == 1)
                                                    {{ __('Guest') }}
                                                @elseif ($order->user_id != 0)
                                                    {{ !empty($order->UserData->name) ? $order->UserData->name : '' }}
                                                @else
                                                    {{ __('Walk-in-customer') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($variant->downloadable_product))
                                                    <div class="media align-items-center">
                                                        <div>
                                                            <img src="{{ get_file($variant->downloadable_product) }}" class="wid-40 rounded-circle">
                                                        </div>
                                                    </div>
                                                @endif
                                                @if (!empty($d_product->downloadable_product))
                                                    <div class="media align-items-center">
                                                        <div>
                                                            <img src="{{ get_file($d_product->downloadable_product) }}" class="wid-40 rounded-circle">
                                                        </div>
                                                    </div>
                                                @endif
                                                {{-- <button
                                                    class="download-btn_{{ $product['product_id'] }} me-2 action-btn btn-primary btn btn-sm align-items-center"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Download') }}"><i
                                                        class="ti ti-download text-white"></i></button> --}}
                                            </td>
                                            <td>{{ $order->created_at }}</td>
                                        </tr>
                                    @endif
                                    @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-script')
@endpush
