@extends('layouts.app')

@section('page-title', __('فئة فرعية'))

@section('action-button')
{{-- @permission('Create Product Sub Category') --}}
<div class=" text-end d-flex all-button-box justify-content-md-end justify-content-center">
    <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
        data-title="إضافة فئة فرعية"
        data-url="{{ route('sub-category.create') }}"
        data-toggle="tooltip" title="{{ __('Create Sub Category') }}">
        <i class="ti ti-plus"></i>
    </a>
</div>
{{-- @endpermission --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('فئة فرعية') }}</li>
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
                            <th>{{ __('الاسم') }}</th>
                            <th>{{ __('الصورة') }}</th>
                            <th>{{ __('الرمز') }}</th>
                            <th>{{ __('الفئة') }}</th>
                            <th>{{ __('الحالة') }}</th>
                            <th>{{ __('معرف السمة') }}</th>
                            <th class="text-end">{{ __('العمليات') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($SubCategory as $Category)
                        <tr>
                            <td>{{ $Category->name }}</td>
                            <td>
                                <img src="{{ get_file($Category->image_path , APP_THEME()) }}" alt="" class="category_Image">
                            </td>
                            <td>

                                <img src="{{ get_file($Category->icon_path , APP_THEME()) }}" alt="" class="category_Image">
                            </td>
                            <td>{{ $Category->MainCategory->name }}</td>
                            <td>{{ $Category->status == 1 ? __('Active') : __('InActive') }}</td>
                            <td>{{ $Category->theme_id }}</td>
                            <td class="text-end">
                                {{-- @permission('Edit Product Sub Category') --}}
                                <button class="btn btn-sm btn-primary me-2"
                                    data-url="{{ route('sub-category.edit', $Category->id) }}"
                                    data-size="md" data-ajax-popup="true"
                                    data-title="{{ __('تعديل فئة فرعية') }}" >
                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="تعديل"></i>
                                </button>
                                {{-- @endpermission --}}
                                {{-- @permission('Delete Product Sub Category') --}}
                                {!! Form::open(['method' => 'DELETE', 'route' => ['sub-category.destroy', $Category->id], 'class' => 'd-inline']) !!}
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
