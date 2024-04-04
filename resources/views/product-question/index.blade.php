
@extends('layouts.app')

@section('page-title', __('Product Question'))

@section('action-button')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Product Question') }}</li>
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
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Question') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($question as $que)
                                    <tr>
                                        <td>{{$que->users->name ?? '' }}</td>
                                        <td class="">{{$que->question}}</td>
                                        <td>
                                            @if(!empty($que->answers))<span class="badges fix_badges bg-success p-2 px-3 rounded">{{ __('Answered') }}</span>
                                            @else <span class="badges fix_badges bg-danger p-2 px-3 rounded">{{ __('Pending') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            {{-- @permission('Replay Product Question') --}}
                                            <button class="btn btn-sm btn-primary me-2"
                                                data-url="{{ route('product-question.edit', $que->id) }}" data-size="lg"
                                                data-ajax-popup="true" data-title="{{ __('Reply Product Question') }}">
                                                <i class="fas fa-share py-1" data-bs-toggle="tooltip" title="reply"></i>
                                            </button>
                                            {{-- @endpermission --}}

                                            {{-- @permission('Delete Product Question') --}}
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['product-question.destroy', $que->id], 'class' => 'd-inline']) !!}
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

