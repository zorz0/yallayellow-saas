@extends('layouts.app')

@section('page-title', __('Plan Coupon'))

@section('action-button')
    {{-- @permission('Create Coupon') --}}
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="Add Coupon"
            data-url="{{ route('plan-coupon.create') }}" data-toggle="tooltip" title="{{ __('Create Coupon') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
    {{-- @endpermission --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Plan Coupon') }}</li>
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
                                    <th>{{ __('Discount (%)') }}</th>
                                    <th>{{ __('Limit') }}</th>
                                    {{-- <th>{{ __('Used') }}</th> --}}
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->name }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ $coupon->discount }}</td>
                                        <td>{{ $coupon->limit }}</td>
                                        {{-- <td>{{ $coupon->used_coupon() }}</td> --}}
                                        <td class="text-end">

                                            {{-- View --}}
                                            {{-- @permission('Manage Coupon') --}}
                                            <button class="btn btn-sm btn-info me-2"
                                                    data-url="{{ route('plan-coupon.show', $coupon->id) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __('View') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="{{ __('View Coupon') }}"
                                                    data-tooltip="View">
                                                    <i class="ti ti-eye f-20"></i>
                                                </button>
                                            {{-- @endpermission --}}

                                            {{-- Edit --}}
                                            {{-- @permission('Edit Coupon') --}}
                                                <button class="btn btn-sm btn-primary me-2"
                                                    data-url="{{ route('plan-coupon.edit', $coupon->id) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Coupon') }}">
                                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                                </button>
                                            {{-- @endpermission --}}

                                            {{-- Delete --}}
                                            {{-- @permission('Delete Coupon') --}}
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['plan-coupon.destroy', $coupon->id], 'class' => 'd-inline']) !!}
                                                <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                        title="Delete"></i>
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
        $(document).on('click', '#code-generate', function() {
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
