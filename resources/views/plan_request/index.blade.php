@extends('layouts.app')

@section('page-title', __('Plan Request'))

@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Plan Request') }}</li>
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
                                    <th>{{ __('Plan Name') }}</th>
                                    <th>{{ __('Max Products') }}</th>
                                    <th>{{ __('Max Stores') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Created at') }}</th>
                                    <th class="text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plan_requests as $prequest)
                                    <tr>
                                        <td>{{ $prequest->user->name  }}</td>
                                        <td>{{ $prequest->plan->name }} </td>
                                        <td>{{ $prequest->plan->max_products .' Products' }}</td>
                                        <td>{{ $prequest->plan->max_stores .' Stores' }}</td>
                                        <td>{{ ($prequest->duration == 'Month') ? __('One Month') : __('One Year') }}</td>
                                        <td>{{$prequest->created_at }}</td>
                                        
                                        
                                        
                                        <td class="text-end">
                                            <div>
                                                <a href="{{route('response.request',[$prequest->id,1])}}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="{{route('response.request',[$prequest->id,0])}}" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
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
