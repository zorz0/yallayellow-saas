@extends('layouts.app')

@section('page-title')
    {{ __('Product') }}
@endsection

@php
    $logo = asset(Storage::url('uploads/profile/'));
    $admin = getAdminAllSetting();

@endphp


@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page">{{ __('Product') }}</li>
@endsection

@section('action-button')
{{-- @permission('Create Product') --}}
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="{{ route('product.create') }}" class="btn btn-sm btn-primary" data-title="Create New Store"
            data-toggle="tooltip" title="{{ __('Create New Store') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
{{-- @endpermission --}}
@endsection

@push('custom-script')
    <script>

    </script>
@endpush

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
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Sub Category') }}</th>
                                    <th>{{ __('Brand') }}</th>
                                    <th>{{ __('Label') }}</th>
                                    <th>{{ __('Cover Image') }}</th>
                                    <th>{{ __('Varint') }}</th>
                                    <th>{{ __('Review') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Stock Status') }}</th>
                                    <th>{{ __('Stock Quntity') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($products as $product)
                                    <tr>
                                        <td> {{ $product->name }} </td>
                                        <td> {{ !empty($product->ProductData) ? $product->ProductData->name : '' }}
                                        </td>
                                        <td>{{ !empty($product->SubCategoryctData) ? $product->SubCategoryctData->name : '' }}
                                        </td>
                                        <td>{{ !empty($product->brand) ? $product->brand->name : '-' }}
                                        </td>
                                        <td>{{ !empty($product->label) ? $product->label->name : '-' }}
                                        </td>
                                        <td> <img src="{{ get_file($product->cover_image_path, APP_THEME()) }}"
                                                alt="" width="100" class="cover_img{{ $product->id }}"> </td>
                                        <td> {{ $product->variant_product == 1 ? 'has variant' : 'no variant' }} </td>
                                        <td> <i class="ti ti-star text-warning "></i>{{ $product->average_rating}} </td>
                                        @if ($product->variant_product == 0)
                                        <td>{{  currency_format_with_sym($product->price, auth()->user()->current_store, APP_THEME()) ?? SetNumberFormat($product->price) }} </td>
                                        @else
                                        <td>{{ __('In Variant') }}</td>
                                        @endif
                                        <td>
                                            @if ($product->variant_product == 1)
                                                <span
                                                    class="badge badge-80 rounded p-2 f-w-600  bg-light-warning">{{ __('In Variant') }}</span>
                                            @else
                                                @if ($product->track_stock == 0)
                                                    @if ($product->stock_status == 'out_of_stock')
                                                        <span class="badge badge-80 rounded p-2 f-w-600  bg-light-danger">
                                                            {{ __('Out of stock') }}</span>
                                                    @elseif ($product->stock_status == 'on_backorder')
                                                        <span class="badge badge-80 rounded p-2 f-w-600  bg-light-warning">
                                                            {{ __('On Backorder') }}</span>
                                                    @else
                                                        <span class="badge badge-80 rounded p-2 f-w-600  bg-light-primary">
                                                            {{ __('In stock') }}</span>
                                                    @endif
                                                @else
                                                    @if ($product->product_stock <= (isset($admin['out_of_stock_threshold']) ? $admin['out_of_stock_threshold'] : 0))
                                                        <span class="badge badge-80 rounded p-2 f-w-600  bg-light-danger">
                                                            {{ __('Out of stock') }}</span>
                                                    @else
                                                        <span class="badge badge-80 rounded p-2 f-w-600  bg-light-primary">
                                                            {{ __('In stock') }}</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->variant_product == 1)
                                                <span class=""> - </span>
                                            @else
                                                @if ($product->product_stock <= 0)
                                                    -
                                                @else
                                                    <span>
                                                        {{ $product->product_stock }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            {{-- @permission('Edit Products') --}}
                                                {{-- <button class="btn btn-sm btn-info me-2"
                                                    data-url="{{ route('product.image.form', $product->id) }}"
                                                    data-size="md" data-ajax-popup="true"
                                                    data-title="{{ __('Edit Product Image') }}">
                                                    <i class="ti ti-camera py-1" data-bs-toggle="tooltip"
                                                        title="{{ __('Edit Product Image') }}"></i>
                                                </button>
                                                @endpermission --}}
                                                {{-- @permission('Edit Products') --}}
                                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary me-2"
                                                         data-title="{{ __('Edit Product') }}">
                                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                                </a>
                                                {{-- @endpermission --}}
                                                {{-- @permission('Delete Products') --}}
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['product.destroy', $product->id],
                                                    'class' => 'd-inline',
                                                ]) !!}
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

@if ($msg != 0 )
<script>
show_toastr('{{ __('Success') }}', '{!! $msg !!}', 'success');
</script>
@endif

@endpush

