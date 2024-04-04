@extends('layouts.app')

@section('page-title', __('Coupon'))

@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Coupon') }}</li>
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
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Discount') }}</th>
                            <th>{{ __('Limit') }}</th>
                            <th>{{ __('Expiry Date') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupon['price_rules'] as $key => $data )
                        <tr>
                            <td>{{ $data['title'] }}</td>
                            <td>{{ $data['title'] }}</td>
                            <td>{{ str_replace('-', '', $data['value']) }}<i class="{{ $data['value_type'] == 'fixed_amount' ? 'ti ti-currency-dollar' : 'ti ti-percentage' }}"></i> {{ __('Discount') }} </td>
                            <td>{{ !empty($data['usage_limit']) ? $data['usage_limit'] : -1}}</td>
                            <td>{{ \App\Models\Utility::dateFormat($data['ends_at']) }}</td>
                            <td class="text-end">
                                @if ( in_array($data['id'],$upddata))
                                    {{-- @permission('Edit Shopify Coupon')
                                    <a href="{{ route('shopify_coupon.edit', $data['id']) }}"  class="btn btn-sm btn-primary"
                                        data-title="{{ __('Sync Again') }}" >
                                        <i class="ti ti-refresh " data-bs-toggle="tooltip" title="Sync Again"></i>
                                    </a>
                                    @endpermission --}}
                                @else
                                {{-- @permission('Create Shopify Coupon') --}}
                                    <a href="{{ route('shopify_coupon.show', $data['id']) }}" class="btn btn-sm btn-primary"
                                        data-title="Add Coupon"
                                        data-toggle="tooltip" title="{{ __('Create Main Category') }}">
                                        <i class="ti ti-plus" data-bs-toggle="tooltip" title="Add Coupon"></i>
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

