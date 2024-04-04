@extends('layouts.app')

@section('page-title', __('Contact-us'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Contact-us') }}</li>
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
                                    <th>{{ __('First Name') }}</th>
                                    <th>{{ __('Last Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Subject') }}</th>
                                    <th style="max-width: 50%">{{ __('Description') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contacts as $contact)
                                    <tr>
                                        <td>{{$contact->first_name}}</td>
                                        <td>{{$contact->last_name}}</td>
                                        <td>{{$contact->email}}</td>
                                        <td>{{$contact->contact}}</td>
                                        <td>{{$contact->subject}}</td>
                                        <td>{{$contact->description}}</td>
                                        <td class="text-end d-flex">
                                            {{-- @permission('Edit Contact Us') --}}
                                            <button class="btn btn-sm btn-primary me-2"
                                                data-url="{{ route('contacts.edit', $contact->id) }}" data-size="md"
                                                data-ajax-popup="true" data-title="{{ __('Edit Contact') }}">
                                                <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                            </button>
                                            {{-- @endpermission --}}

                                            {{-- @permission('Delete Contact Us') --}}
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['contacts.destroy', $contact->id], 'class' => 'd-inline']) !!}
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

