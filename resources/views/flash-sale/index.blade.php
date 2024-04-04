@extends('layouts.app')

@section('page-title', __('Flash Sale'))

@section('action-button')
    {{-- @permission('Create Flash Sale') --}}
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="Add Flash Sale"
                data-url="{{ route('flash-sale.create') }}" data-toggle="tooltip" title="{{ __('Create Flash Sale') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    {{-- @endpermission --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Flash Sale') }}</li>
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
                                    <th>{{ __('Discount Type') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($flashsales as $flashsale)
                                    <tr>
                                        <td>{{ $flashsale->name }}</td>
                                        <td>{{ $flashsale->discount_type }}</td>
                                        <td>{{ $flashsale->start_date }}</td>
                                        <td>{{ $flashsale->end_date }}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input flashsale-toggle"
                                                    data-flashsale-id="{{ $flashsale->id }}"
                                                    {{ $flashsale->is_active ? 'checked' : '' }}
                                                />
                                            </div>
                                        </td>
                                        <td>
                                            {{-- @permission('Edit Flash Sale') --}}
                                            <button class="btn btn-sm btn-primary me-2"
                                                data-url="{{ route('flash-sale.edit', $flashsale->id) }}" data-size="lg"
                                                data-ajax-popup="true" data-title="{{ __('Edit Flash Sale') }}">
                                                <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                            </button>
                                            {{--@endpermission--}}
                                            {{-- @permission('Delete Flash Sale') --}}
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['flash-sale.destroy',$flashsale->id], 'class' => 'd-inline']) !!}
                                            <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                    title="Delete"></i>
                                            </button>
                                            {!! Form::close() !!}
                                            {{--@endpermission--}}
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
        $(document).ready(function () {
            $('.flashsale-toggle').on('change', function () {
                const flashsaleId = $(this).data('flashsale-id');
                const isActivated = $(this).prop('checked');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('update-flashsale-status') }}',
                    data: {
                        flashsaleId: flashsaleId,
                        isActivated: isActivated,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (data) {
                        show_toastr('{{ __('Success') }}',
                        '{{ __('Status Updated Successfully!') }}', 'success');
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    },
                });
            });
        });
    </script>
    @endpush
