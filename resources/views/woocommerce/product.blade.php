@extends('layouts.app')

@section('page-title', __('Product'))

@section('action-button')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Product') }}</li>
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
                                    <th>{{ __('Cover Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('price') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jsonData as $key => $data )
                                <tr>

                                        <td> <img src="{{ !empty($data->images) ? get_file($data->images['0']->src, APP_THEME()) : asset(Storage::url('uploads/woocommerce.png')) }}" alt="" width="100"
                                                class="cover_img}"> </td>
                                        <td> {{ $data->name }} </td>
                                        <td> {{ $data->categories['0']->name }} </td>
                                        <td> {{ $data->price }} </td>

                                        <td class="text-end">
                                        @if (in_array($data->id,$upddata))
                                        {{-- @permission('Edit Woocommerce Product') --}}
                                            <a href="{{ route('woocom_product.edit', $data->id) }}"  class="btn btn-sm btn-info"
                                                data-title="{{ __('Sync Again') }}" >
                                                <i class="ti ti-refresh " data-bs-toggle="tooltip" title="Sync Again"></i>
                                            </a>
                                            {{--@endpermission--}}
                                        @else
                                        {{-- @permission('Create Woocommerce Product') --}}
                                            <a href="{{ route('woocom_product.show', $data->id) }}" class="btn btn-sm btn-primary"
                                                data-title="Add Product">
                                                <i class="ti ti-plus" data-bs-toggle="tooltip" title="Add Product"></i>
                                            </a>
                                            {{--@endpermission--}}
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
    </div>
    @endsection

