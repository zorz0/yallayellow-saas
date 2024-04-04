@extends('layouts.app')

@section('page-title', __('Sub Category'))

@section('action-button')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Sub Category') }}</li>
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
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($jsonData as $key => $data)
                                    <tr>
                                        <td> <img src="{{ !empty($data->image) ? asset($data->image->src) : asset(Storage::url('uploads/woocommerce.png')) }}" alt=""
                                            width="100" class="cover_img"> </td>
                                        <td> {{ $data->name }} </td>

                                        <td class="text-end">
                                            @if ( in_array($data->id,$upddata))
                                            {{-- @permission('Edit Woocommerce Sub Category') --}}
                                                <a href="{{ route('woocom_sub_category.edit', $data->id) }}"  class="btn btn-sm btn-info"
                                                    data-title="{{ __('Sync Again') }}" >
                                                    <i class="ti ti-refresh "data-bs-toggle="tooltip" title="Sync Again" ></i>
                                                </a>
                                                {{-- @endpermission --}}
                                            @else
                                            {{-- @permission('Create Woocommerce SubCategory') --}}
                                                <a href="{{ route('woocom_sub_category.show', $data->id) }}" class="btn btn-sm btn-primary"
                                                    data-title="Add main-category"
                                                    data-toggle="tooltip" title="{{ __('Create Sub Category') }}">
                                                    <i class="ti ti-plus" data-bs-toggle="tooltip" title="Add Category"></i>
                                                </a>
                                                {{-- @endpermission --}}
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

