@extends('layouts.app')

@section('page-title', __('Product Brand'))

@section('action-button')
{{-- @permission('Create Product Brand') --}}
<div class=" text-end d-flex all-button-box justify-content-md-end justify-content-center">
    <a href="#" class="btn btn-sm btn-primary add_attribute" data-ajax-popup="true" data-size="md"
        data-title="{{ __("Add Brand") }}"
        data-url="{{ route('product-brand.create') }}"
        data-toggle="tooltip" title="{{ __('Create Brand') }}">
        <i class="ti ti-plus"></i>
    </a>
</div>
{{-- @endpermission --}}

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Brand') }}</li>
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
                                    <th>{{ __('Logo') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Popular') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productBrands as $productBrand)
                                    <tr>
                                        <td>{{ $productBrand->name }}</td>
                                        <td>{{ $productBrand->slug }}</td>
                                        <td>
                                            <img src="{{ asset($productBrand->logo) }}" width="5%">
                                        </td>
                                        <td>
                                            {{-- @permission('Manage Product Brand Status') --}}
                                            <div class="form-check form-switch">
                                                <input type="checkbox" data-id="{{$productBrand->id}}"  class="form-check-input status-index" name="status"
                                                id="status_{{$productBrand->id}}" value="{{$productBrand->status}}" {{ $productBrand->status ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_{{$productBrand->id}}"></label>
                                            </div>
                                            {{-- @endpermission --}}
                                        </td>
                                        <td>
                                        {{-- @permission('Manage Product Brand Popular-Status') --}}
                                            <div class="form-check form-switch">
                                                <input type="checkbox" data-id="{{$productBrand->id}}"  class="form-check-input popular-index" name="is_popular"
                                                id="is_popular_{{$productBrand->id}}" value="{{$productBrand->is_popular}}" {{ $productBrand->is_popular ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_popular_{{$productBrand->id}}"></label>
                                            </div>
                                            {{-- @endpermission --}}
                                        </td>

                                        <td class="text-end">
                                            {{-- @permission('Edit Product Brand') --}}
                                            <button class="btn btn-sm btn-primary me-2"
                                                data-url="{{ route('product-brand.edit', $productBrand->id) }}"
                                                data-size="md" data-ajax-popup="true"
                                                data-title="{{ __('Edit Brand') }}">
                                                <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="Edit Brand"></i>
                                            </button>
                                            {{-- @endpermission --}}
                                            {{-- @permission('Delete Product Brand') --}}
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['product-brand.destroy', $productBrand->id],
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
@push('scripts')
<script>
    $(document).on('change', '.status-index',function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var id = $(this).data('id'); 
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('product-brand.status') }}",
            data: {'status': status, 'id': id},
            success: function(data){
                if (data.status != 'success') {
                    show_toastr('Error', data.message, 'error');
                } else {
                    show_toastr('Success', data.message, 'success');
                }
            }
        });
    });

    $(document).on('change', '.popular-index',function() {
        var is_popular = $(this).prop('checked') == true ? 1 : 0; 
        var id = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('product-brand.popular') }}",
            data: {'is_popular': is_popular, 'id': id},
            success: function(data){
                if (data.status != 'success') {
                    show_toastr('Error', data.message, 'error');
                } else {
                    show_toastr('Success', data.message, 'success');
                }
            }
        });
    });
</script>
@endpush