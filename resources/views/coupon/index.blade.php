@extends('layouts.app')

@section('page-title', __('Coupon'))

@section('action-button')
    <div class=" text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a class="btn btn-sm btn-primary btn-icon me-2" href="{{ route('coupon.export') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Export') }}">
            <i  class="ti ti-file-export"></i>
        </a>
        {{-- @permission('Create Coupon') --}}
            <a href="#" class="btn btn-sm btn-primary add_coupon" data-ajax-popup="true" data-size="lg"
                data-title="{{ __("Add Coupon") }}"
                data-url="{{ route('coupon.create') }}"
                data-toggle="tooltip" title="{{ __('Create Coupon') }}">
                <i class="ti ti-plus"></i>
            </a>

        {{--@endpermission--}}
    </div>
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
                        @foreach ($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->coupon_name }}</td>
                            <td>{{ $coupon->coupon_code }}</td>
                            <td>{{ $coupon->discount_amount }}<i class="{{ $coupon->coupon_type == 'flat' || $coupon->coupon_type == 'fixed product discount' ? 'ti ti-currency-dollar' : 'ti ti-percentage' }}"></i> {{ __('Discount') }} </td>
                            <td>{{ $coupon->coupon_limit }}</td>
                            <td>{{ \App\Models\Utility::dateFormat($coupon->coupon_expiry_date) }}</td>
                            <td class="text-end">
                                {{-- @permission('Show Coupon') --}}
                                <a class="btn btn-sm btn-info me-2" href="{{ route('coupon.show', $coupon->id) }}" >
                                    <i class="ti ti-eye py-1" data-bs-toggle="tooltip" title="view"></i>
                                </a>
                                {{-- @endpermission --}}
                                {{-- @permission('Edit Coupon') --}}
                                <button class="btn btn-sm btn-primary me-2"
                                    data-url="{{ route('coupon.edit', $coupon->id) }}"
                                    data-size="lg" data-ajax-popup="true"
                                    data-title="{{ __('Edit Coupon') }}" >
                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                </button>
                                {{-- @endpermission --}}
                                {{-- @permission('Delete Coupon') --}}
                                {!! Form::open(['method' => 'DELETE', 'route' => ['coupon.destroy', $coupon->id], 'class' => 'd-inline']) !!}
                                <button type="button" class="btn btn-sm btn-danger show_confirm">
                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip" title="Delete"></i>
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
    $(document).on('click', '.code', function () {
        var type = $(this).val();
        $('#code_text').addClass('col-md-12').removeClass('col-md-8');
        $('#autogerate_button').addClass('d-none');
        if (type == 'auto') {
            $('#code_text').addClass('col-md-8').removeClass('col-md-12');
            $('#autogerate_button').removeClass('d-none');
        }
    });

    $(document).on('click', '#code-generate', function () {
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

