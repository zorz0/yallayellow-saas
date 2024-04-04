@extends('layouts.app')

@section('page-title', __('Shipping Zone'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Shipping Zone') }}</li>
@endsection

@section('action-button')
{{-- @permission('Create Shipping Zone') --}}
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="Add Shipping Zone"
            data-url="{{ route('shipping-zone.create') }}" data-toggle="tooltip" title="{{ __('Create Shipping Zone') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
    {{-- @endpermission --}}
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
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Country') }}</th>
                                <th>{{ __('State') }}</th>
                                <th>{{ __('Shipping Method') }}</th>
                                <th class="text-start">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @foreach ($shippingZones as $value => $shippingZone)
                            <tbody>
                                <td>{{ ++$value }}</td>
                                <td>{{ $shippingZone->zone_name }}</td>
                                <td>{{ $shippingZone->getCountryNameAttribute()->name ?? '-' }}</td>
                                <td>{{ $shippingZone->getStateNameAttribute()->name ?? '-'}}</td>
                                <td>{{ $shippingZone->shipping_method }}</td>
                                <td class="text-start">
                                    {{-- @permission('Show Shipping Zone') --}}
                                    <a href="{{ route('shipping-zone.show',$shippingZone->id) }}" class="btn btn-sm btn-info me-2" data-title="Show Shipping Zone">
                                        <i class="ti ti-eye py-1" data-bs-toggle="tooltip" title="" data-bs-original-title="Show Shipping Zone" aria-label="Show Shipping Zone"></i>
                                    </a>
                                    {{-- @endpermission --}}
                                    {{-- @permission('Edit Shipping Zone') --}}
                                    <button class="btn btn-sm btn-primary me-2" data-url="{{ route('shipping-zone.edit', $shippingZone->id) }}" data-size="md" data-ajax-popup="true" data-title="{{ __('Edit Shipping Zone') }}">
                                        <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                    </button>
                                    {{-- @endpermission --}}
                                    {{-- @permission('Delete Shipping Zone') --}}
                                        @if($shippingZone->zone_name != 'Locations not covered by your other zones')
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['shipping-zone.destroy', $shippingZone->id], 'class' => 'd-inline']) !!}
                                            <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                    title="Delete"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        @else
                                        @endif
                                    {{-- @endpermission --}}
                                </td>
                            </tbody>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
