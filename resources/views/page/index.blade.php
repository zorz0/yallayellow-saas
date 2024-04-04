@extends('layouts.app')

@section('page-title', __('Pages'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Pages') }}</li>
@endsection

@section('action-button')
{{-- @permission('Create Page') --}}
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="Add Page"
            data-url="{{ route('pages.create') }}" data-toggle="tooltip" title="{{ __('Create Page') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
    {{--@endpermission--}}
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
                                    <th>{{ __('Page Slug') }}</th>
                                    <th>{{ __('Page Status') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            @foreach ($pages as $index => $page)
                                <tbody>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $page->page_name }}</td>
                                    <td>{{ $page->page_slug }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                type="checkbox"
                                                class="form-check-input page-toggle"
                                                data-page-id="{{ $page->id }}"
                                                {{ $page->page_status ? 'checked' : '' }}
                                            />
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        {{-- @permission('Edit Page') --}}
                                        <button class="btn btn-sm btn-primary me-2" data-url="{{ route('pages.edit', $page->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{ __('Edit Page') }}">
                                            <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                        </button>
                                        {{--@endpermission--}}
                                        {{-- @permission('Delete Page') --}}
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['pages.destroy', $page->id], 'class' => 'd-inline']) !!}
                                        <button type="button" class="btn btn-sm btn-danger show_confirm">
                                            <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                title="Delete"></i>
                                        </button>
                                        {!! Form::close() !!}
                                        {{--@endpermission--}}
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
@push('custom-script')

<script>
    $(document).ready(function () {
        $('.page-toggle').on('change', function () {
            const pageId = $(this).data('page-id');
            const isActivated = $(this).prop('checked');

            $.ajax({
                type: 'POST',
                url: '{{ route('update-page-status') }}',
                data: {
                    pageId: pageId,
                    isActivated: isActivated,
                    _token: '{{ csrf_token() }}',
                },
                    success: function (data) {
                        show_toastr('{{ __('Success') }}',
                        '{{ __('Status Updated Successfully!') }}', 'success');
                    },
                    error: function (xhr, status, error) {
                    },
                });
            });
        });
    </script>
@endpush
