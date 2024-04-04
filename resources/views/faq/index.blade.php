
@extends('layouts.app')

@section('page-title', __('FAQs'))

@section('action-button')
    {{-- @permission('Create Faqs') --}}
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="Add FAQs"
            data-url="{{ route('faqs.create') }}" data-toggle="tooltip" title="{{ __('Create FAQs') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
    {{-- @endpermission --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('FAQs') }}</li>
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
                                    <th>{{ __('Topic') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($faqs as $faq)
                                    <tr>
                                        <td>{{$faq->topic}}</td>
                                        <td class="text-end">
                                            {{-- @permission('Edit Faqs') --}}
                                            <button class="btn btn-sm btn-primary me-2"
                                                data-url="{{ route('faqs.edit', $faq->id) }}" data-size="lg"
                                                data-ajax-popup="true" data-title="{{ __('Edit FAQs') }}">
                                                <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                            </button>
                                            {{-- @endpermission --}}

                                            {{-- @permission('Delete Faqs') --}}
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['faqs.destroy', $faq->id], 'class' => 'd-inline']) !!}
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

