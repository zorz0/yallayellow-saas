@extends('layouts.app')

@section('page-title', __('Tax Class'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Tax Class') }}</li>
    <li class="breadcrumb-item">{{ $tax_option->name }}</li>
@endsection

@section('action-button')
{{-- @permission('Create Tax Method') --}}
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="Add Tax Rates"
            data-url="{{ route('taxes-method.create',$tax_option->id) }}" data-toggle="tooltip" title="{{ __('Create Tax Rates') }}">
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
                                    <th>{{ __('Tax Rate') }}</th>
                                    <th>{{ __('Country') }}</th>
                                    <th>{{ __('State') }}</th>
                                    <th>{{ __('City') }}</th>
                                    <th>{{ __('Priority') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            @foreach ($tax_method as $index => $tax)
                                <tbody>
                                    <td>{{ ++$index }}</td>
                                    <td>{{ $tax->name }}</td>
                                    <td>{{ $tax->tax_rate }}</td>
                                    <td>{{ $tax->getCountryNameAttribute()->name ?? '*' }}</td>
                                    <td>{{ $tax->getStateNameAttribute()->name ?? '*'}}</td>
                                    <td>{{ $tax->getCityNameAttribute()->name ?? '*'}}</td>
                                    <td>{{ $tax->priority }}</td>
                                    <td class="text-end">
                                        {{-- @permission('Edit Tax Method') --}}
                                        <button class="btn btn-sm btn-primary me-2" data-url="{{ route('taxes-method.edit', $tax->id) }}" data-size="md" data-ajax-popup="true" data-title="{{ __('Edit Tax Class') }}">
                                            <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                        </button>
                                        {{--@endpermission--}}
                                        {{-- @permission('Delete Tax Method') --}}
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['taxes-method.destroy', $tax->id], 'class' => 'd-inline']) !!}
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
