@extends('layouts.app')

@section('page-title', __('Newsletters'))


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Newsletters') }}</li>
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
                                    <th>{{ __('Email') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($newsletters as $newsletter)
                                    <tr>
                                        <td>{{$newsletter->email}}</td>
                                        <td class="text-end">
                                            {{-- @permission('Delete Newsletter') --}}
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['newsletter.destroy', $newsletter->id], 'class' => 'd-inline']) !!}
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

