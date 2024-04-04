@extends('layouts.app')

@section('page-title', __('Blog Category'))

@section('action-button')
{{-- @permission('Create Blog Category') --}}
<div class=" text-end d-flex all-button-box justify-content-md-end justify-content-center">
    <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
        data-title="Add Blog Category"
        data-url="{{ route('blog-category.create') }}"
        data-toggle="tooltip" title="{{ __('Create Blog Category') }}">
        <i class="ti ti-plus"></i>
    </a>
</div>
{{-- @endpermission --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Blog Category') }}</li>
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
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blogCategory as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td class="text-end">
                            {{-- @permission('Edit Blog Category') --}}
                                <button class="btn btn-sm btn-primary me-2"
                                    data-url="{{ route('blog-category.edit', $category->id) }}"
                                    data-size="md" data-ajax-popup="true"
                                    data-title="{{ __('Edit Blog Category') }}" >
                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                </button>
                            {{-- @endpermission --}}
                            {{-- @permission('Delete Blog Category') --}}
                                {!! Form::open(['method' => 'DELETE', 'route' => ['blog-category.destroy', $category->id], 'class' => 'd-inline']) !!}
                                <button type="button" class="btn btn-sm btn-danger show_confirm"><i class="ti ti-trash text-white py-1"></i></button>
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
@endpush
