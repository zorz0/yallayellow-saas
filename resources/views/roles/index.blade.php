@extends('layouts.app')

@section('page-title', __('Roles'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Roles') }}</li>
@endsection

@section('action-button')
    {{-- @permission('Create Role') --}}
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="xl" data-title="Create Role"
                data-url="{{ route('roles.create') }}" data-toggle="tooltip" title="{{ __('Create Role') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    {{-- @endpermission --}}
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Permissions') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr class="border-0">
                                    <td class="border-0 capitalize">{{ $role->name }}</td>
                                    <td class="border-0" style="white-space: inherit">
                                        All {{ $role->name }} Permissions here
                                    </td>
                                    <td class="border-0">
                                        @permission('Create Role')
                                            <button class="btn btn-sm btn-primary me-2"
                                                data-url="{{ route('roles.edit', $role->id) }}" data-size="xl"
                                                data-ajax-popup="true" data-title="{{ __('Edit Roles') }}">
                                                <i class="ti ti-pencil py-1" data-bs-toggle="tooltip" title="edit"></i>
                                            </button>
                                        @endpermission
                                        {{-- @permission('Delete Role') --}}
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'class' => 'd-inline']) !!}
                                            <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                <i class="ti ti-trash text-white py-1"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        {{-- @endpermission --}}
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="3" style="white-space: inherit">
                                        @foreach ($role->permissions()->pluck('name') as $permission)
                                            <span class="badge rounded p-2 m-1 px-3 py-3 bg-dark ">
                                                <a href="#" class="text-dark f-12">{{ $permission }}</a>
                                            </span>
                                        @endforeach
                                    </td>
                                    <td></td>
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
        function Checkall(module = null) {
            var ischecked = $("#checkall-"+module).prop('checked');
            if(ischecked == true)
            {
                $('.checkbox-'+module).prop('checked',true);
            }
            else
            {
                $('.checkbox-'+module).prop('checked',false);
            }
        }
    </script>
    <script type="text/javascript">
        function CheckModule(cl = null)
        {
            var ischecked = $("#"+cl).prop('checked');
            if(ischecked == true)
            {
                $('.'+cl).find("input[type=checkbox]").prop('checked',true);
            }
            else
            {
                $('.'+cl).find("input[type=checkbox]").prop('checked',false);
            }
        }
    </script>
@endpush