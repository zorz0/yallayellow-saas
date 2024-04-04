@extends('layouts.app')

@section('page-title')
    {{ __('Stock Reports') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">
        {{ __('Stock Reports') }}</li>
@endsection

@section('action-button')
    <div class="text-end">

    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xxl-12">
            <div class="card card-body">
                <ul class="nav nav-pills gap-2" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-low-stock-tab" data-bs-toggle="pill" href="#pills-low-stock"
                            role="tab" aria-controls="pills-low-stock" aria-selected="true">{{ __('Low in stock') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-out-of-stock-tab" data-bs-toggle="pill" href="#pills-out-of-stock"
                            role="tab" aria-controls="pills-out-of-stock"
                            aria-selected="false">{{ __('Out of stock') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-most-stocked-tab" data-bs-toggle="pill" href="#pills-most-stocked"
                            role="tab" aria-controls="pills-most-stocked"
                            aria-selected="false">{{ __('Most Stocked') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show card" id="pills-low-stock" role="tabpanel"
                aria-labelledby="pills-low-stock-tabContent-tab">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Product Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Stock Status') }}</th>
                                    <th>{{ __('Stock Quntity') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($low_stock_products))
                                    @foreach ($low_stock_products as $low_stock_product)
                                        <tr>
                                            <td>{{ $low_stock_product['product_name'] }}</td>
                                            <td>{{ $low_stock_product['category'] }}</td>
                                            <td>
                                                @if (
                                                    $low_stock_product['stock_status'] == 'in_stock' ||
                                                        $low_stock_product['stock_status'] == 'on_backorder' ||
                                                        $low_stock_product['stock_status'] == 'notify_customer' ||
                                                        $low_stock_product['stock_status'] == 'allow')
                                                    <span class="badge rounded p-2 f-w-600  bg-light-primary">
                                                        {{ __('In stock') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $low_stock_product['stock'] }}</td>
                                            <td>
                                               
                                                <a class="btn btn-sm btn-primary me-2"
                                                    href="{{ route('product.edit', $low_stock_product['product_id']) }}"
                                                   >
                                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip"
                                                        title="edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade card" id="pills-out-of-stock" role="tabpanel"
                aria-labelledby="pills-out-of-stock-tabContent-tab">
                <div class="card-body table-border-style">
                    <div class="table-responsive table-product">
                        <table class="table" id="pc-dt-dynamic-import">
                            <thead>
                                <tr>
                                    <th>{{ __('Product Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Stock Status') }}</th>
                                    <th>{{ __('Stock Quntity') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="service-filter-data">
                                @if (!empty($out_of_stock_products))
                                    @foreach ($out_of_stock_products as $out_of_stock_product)
                                        <tr>
                                            <td>{{ $out_of_stock_product['product_name'] }}</td>
                                            <td>{{ $out_of_stock_product['category'] }}</td>
                                            <td>
                                                <span class="badge rounded p-2 f-w-600  bg-light-danger">
                                                    {{ __('Out of stock') }}</span>
                                            </td>
                                            <td>{{ $out_of_stock_product['stock'] }}</td>
                                            <td>
                                            <a class="btn btn-sm btn-primary me-2"
                                                    href="{{ route('product.edit', $out_of_stock_product['product_id']) }}"
                                                   >
                                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip"
                                                        title="edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade card" id="pills-most-stocked" role="tabpanel"
                aria-labelledby="pills-most-stocked-tabContent-tab">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('Product Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Stock Status') }}</th>
                                    <th>{{ __('Stock Quntity') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($most_stocked_products))
                                    @foreach ($most_stocked_products as $most_stocked_product)
                                    @if ($most_stocked_product['stock_status'] != 'out_of_stock' && $most_stocked_product['stock_status'] != 'not_allow')
                                        <tr>
                                            <td>{{ $most_stocked_product['product_name'] }}</td>
                                            <td>{{ $most_stocked_product['category'] }}</td>
                                            <td>
                                                @if (
                                                    $most_stocked_product['stock_status'] == 'in_stock' ||
                                                        $most_stocked_product['stock_status'] == 'on_backorder' ||
                                                        $most_stocked_product['stock_status'] == 'notify_customer' ||
                                                        $most_stocked_product['stock_status'] == 'allow')
                                                    <span class="badge rounded p-2 f-w-600  bg-light-primary">
                                                        {{ __('In stock') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $most_stocked_product['stock'] }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary me-2"
                                                    href="{{ route('product.edit', $most_stocked_product['product_id']) }}"
                                                   >
                                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip"
                                                        title="edit"></i>
                                                </a>
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
    </div>
@endsection

@push('custom-script')
@endpush
