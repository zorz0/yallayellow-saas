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
                        @foreach ($jsonData as $key => $data )
                        <tr>
                            <td>{{ $data->code }}</td>
                            <td>{{ $data->code }}</td>
                            <td>{{ round($data->amount) }}<i class="{{ $data->discount_type == 'fixed_cart' ? 'ti ti-currency-dollar' : 'ti ti-percentage' }}"></i> {{ __('Discount') }} </td>
                            <td>{{ !empty($data->usage_limit_per_user) ? $data->usage_limit_per_user : -1}}</td>
                            <td>{{ \App\Models\Utility::dateFormat($data->date_expires) }}</td>
                            <td class="text-end">
                                @if ( in_array($data->id,$upddata))
                                {{-- @permission('Edit Woocommerce Coupon') --}}
                                    <a href="{{ route('woocom_coupon.edit', $data->id) }}"  class="btn btn-sm btn-primary"
                                        data-title="{{ __('Sync Again') }}" >
                                        <i class="ti ti-refresh " data-bs-toggle="tooltip" title="Sync Again"></i>
                                    </a>
                                    {{--@endpermission --}}
                                @else
                                {{-- @permission('Create Woocommerce Coupon') --}}
                                    <a href="{{ route('woocom_coupon.show', $data->id) }}" class="btn btn-sm btn-primary"
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
