@extends('layouts.app')

@section('page-title', __('Main Category'))

@section('action-button')
{{-- @permission('Create Product Category') --}}
<div class=" text-end d-flex all-button-box justify-content-md-end justify-content-center">
    <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
        data-title="Add Main Category"
        data-url="{{ route('main-category.create') }}"
        data-toggle="tooltip" title="{{ __('Create Main Category') }}">
        <i class="ti ti-plus"></i>
    </a>
</div>
{{-- @endpermission --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Main Category') }}</li>
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
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Icon') }}</th>
                            <th>{{ __('Trending') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($MainCategory as $Category)
                        <tr>
                            <td>{{ $Category->name }}</td>
                            <td>
                                <img src="{{ get_file($Category->image_path , APP_THEME()) }}" alt="" class="category_Image">
                            </td>
                            <td> <img src="{{ get_file($Category->icon_path , APP_THEME()) }}" alt="" class="category_Image"> </td>

                            <td>{{ $Category->trending == 1 ? __('Yes') : __('No') }}</td>
                            <td>{{ $Category->status == 1 ? __('Active') : __('InActive') }}</td>
                            <td class="text-end">
                                {{-- @permission('Edit Product Category') --}}
                                <button class="btn btn-sm btn-primary me-2"
                                    data-url="{{ route('main-category.edit', $Category->id) }}"
                                    data-size="md" data-ajax-popup="true"
                                    data-title="{{ __('Edit Main Category') }}" >
                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                </button>
                                {{-- @endpermission --}}
                                {{-- @permission('Delete Product Category') --}}
                                {!! Form::open(['method' => 'DELETE', 'route' => ['main-category.destroy', $Category->id], 'class' => 'd-inline']) !!}
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
