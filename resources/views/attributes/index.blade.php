@extends('layouts.app')

@section('page-title', __('Attributes'))

@section('action-button')
{{-- @permission('Create Attributes') --}}
<div class=" text-end d-flex all-button-box justify-content-md-end justify-content-center">
    <a href="#" class="btn btn-sm btn-primary add_attribute" data-ajax-popup="true" data-size="md"
        data-title="{{ __("Add Attribute") }}"
        data-url="{{ route('product-attributes.create') }}"
        data-toggle="tooltip" title="{{ __('Create Attribute') }}">
        <i class="ti ti-plus"></i>
    </a>
</div>
{{-- @endpermission --}}

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attributes') }}</li>
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
                                    <th>{{ __('Slug') }}</th>
                                    <th>{{ __('Terms') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ProductAttributes as $productAttribute)
                                    <tr>
                                        <td>{{ $productAttribute->name }}</td>
                                        <td>{{ $productAttribute->slug }}</td>
                                        <td>

                                            @foreach ($productAttribute->attributeOptions as $option)
                                                <span
                                                    class="badge bg-light-primary p-2 border border-primary rounded-5 mb-2">
                                                    {{ $option->terms }}</span>
                                            @endforeach

                                            <a class="text-success f-w-600"
                                                href="{{ route('product-attribute-option.show', [$productAttribute->id]) }}">{{ __('Configure terms') }}</a>
                                        </td>


                                        <td class="text-end">
                                            {{-- @permission('Edit Attributes') --}}
                                            <button class="btn btn-sm btn-primary me-2"
                                                data-url="{{ route('product-attributes.edit', $productAttribute->id) }}"
                                                data-size="md" data-ajax-popup="true"
                                                data-title="{{ __('Edit Attribute') }}">
                                                <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                            </button>
                                            {{-- @endpermission --}}
                                            {{-- @permission('Delete Attributes') --}}
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['product-attributes.destroy', $productAttribute->id],
                                                'class' => 'd-inline',
                                            ]) !!}
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
