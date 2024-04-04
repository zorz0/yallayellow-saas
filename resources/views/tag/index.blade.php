@extends('layouts.app')

@section('page-title', __('Tag'))

@section('action-button')
    {{-- @if (auth()->user()->isAbleTo('Create Tag')) --}}

    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="Add Tag"
            data-url="{{ route('tag.create') }}" data-toggle="tooltip" title="{{ __('Create Tag') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
    {{-- @endif --}}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Tag') }}</li>
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
                                @foreach ($Tag as $tag)
                                    <tr>
                                        <td>{{ $tag->name }}</td>
                                        <td class="text-end">


                                            {{-- Edit --}}
                                            {{-- @if (auth()->user()->isAbleTo('Edit Tag')) --}}
                                                <button class="btn btn-sm btn-primary me-2"
                                                    data-url="{{ route('tag.edit', $tag->id) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Tag') }}">
                                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                                </button>
                                            {{-- @endif --}}

                                            {{-- Delete --}}
                                            {{-- @if (auth()->user()->isAbleTo('Delete Tag')) --}}
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['tag.destroy', $tag->id], 'class' => 'd-inline']) !!}
                                                <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                    <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
                                                        title="Delete"></i>
                                                </button>
                                                {!! Form::close() !!}
                                            {{-- @endif --}}
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

