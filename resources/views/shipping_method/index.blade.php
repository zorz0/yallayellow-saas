@extends('layouts.app')

@section('page-title', __('Shipping Method'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('shipping-zone.index') }}">{{ __('Shipping Zone') }} @if(isset($shippingZones->zone_name)) ({{ $shippingZones->zone_name }}) @endif</a></li>
    <li class="breadcrumb-item">{{ __('Shipping Method') }}</li>
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
                                <th>{{ __('Shipping Method') }}</th>
                                <th>{{ __('Shipping Cost') }}</th>
                                <th class="text-start">{{ __('Action') }}</th>
                            </tr>
                        </thead>

                        @foreach ($shippingMethods as $value => $shippingMethod)
                            <tbody>
                                <td>{{ ++$value }}</td>
                                <td>{{ $shippingMethod['method_name'] }}</td>
                                <td>{{ $shippingMethod['cost'] }}</td>
                                <td class="text-start">
                                {{-- @permission('Edit Shipping Method') --}}
                                        @if($shippingMethod['method_name'] == 'Flat Rate')
                                            <button class="btn btn-sm btn-primary me-2" data-url="{{ route('shipping-method.edit', $shippingMethod['id']) }}" data-size="lg" data-ajax-popup="true" data-title="{{ __('Edit Shipping Method') }}">  <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                            </button>
                                        @elseif($shippingMethod['method_name'] == 'Free shipping')
                                            <button class="btn btn-sm btn-primary me-2" data-url="{{ route('free-shipping.edit', $shippingMethod['id']) }}" data-size="md" data-ajax-popup="true" data-title="{{ __('Edit Shipping Method') }}">  <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                            </button>
                                        @elseif($shippingMethod['method_name'] == 'Local pickup')
                                            <button class="btn btn-sm btn-primary me-2" data-url="{{ route('local-shipping.edit', $shippingMethod['id']) }}" data-size="md" data-ajax-popup="true" data-title="{{ __('Edit Shipping Method') }}">  <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                            </button>
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
