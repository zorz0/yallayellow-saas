{{ Form::model($role, ['route' => ['roles.update', $role->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}

            <div class="form-icon-user">
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Role Name')]) }}
            </div>

            @error('name')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
        @if (!empty($permissions))
                    <div class="col-sm-12 col-md-10 col-xxl-12 col-md-12">
                        <div class="p-3 card">
                            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                                @foreach ($modules as $module)
                                    @if ($module == 'General')
                                        <li class="nav-item" role="presentation">
                                            <button
                                                class="nav-link text-capitalize {{ $loop->index == 0 ? 'active' : '' }}"
                                                id="pills-{{ strtolower($module) }}-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-{{ strtolower($module) }}"
                                                type="button">{{ Module_Alias_Name($module) }}</button>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                            <div class="card-body">
                                <div class="tab-content" id="pills-tabContent">
                                    @foreach ($modules as $module)
                                        @if ($module == 'General')
                                            <div class="tab-pane text-capitalize fade show {{ $loop->index == 0 ? 'active' : '' }}"
                                                id="pills-{{ strtolower($module) }}" role="tabpanel"
                                                aria-labelledby="pills-{{ strtolower($module) }}-tab">
                                                <input type="checkbox" class="form-check-input pointer"
                                                    name="checkall-{{ strtolower($module) }}"
                                                    id="checkall-{{ strtolower($module) }}"
                                                    onclick="Checkall('{{ strtolower($module) }}')">
                                                <small class="text-muted mx-2">
                                                    {{ Form::label('checkall-' . strtolower($module), 'Assign ' .  Module_Alias_Name($module)  . ' Permission to Roles', ['class' => 'form-check-label pointer']) }}
                                                </small>
                                                <table class="table table-striped mb-0  mt-3" id="dataTable-1">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                        </th>
                                                        <th>{{__('Module')}} </th>
                                                        <th>{{__('Permissions')}} </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $permissions = get_permission_by_module($module);
                                                            $m_permissions = array_column($permissions->toArray(),'name');
                                                            $module_list = [];
                                                                foreach ($m_permissions as $key => $value) {
                                                                    $parts = explode(" ", $value);
                                                                   // array_push($module_list,strtok($value, " "));
                                                                    array_push($module_list, strtolower($parts[1] ?? ($parts[0])));
                                                                }
                                                            $module_list =  array_unique($module_list)
                                                        @endphp
                                                     @foreach ($module_list as  $list)
                                                        <tr>
                                                            <td><input type="checkbox" class="form-check-input ischeck pointer" onclick="CheckModule('module_checkbox_{{$key}}_{{$list}}')"  id="module_checkbox_{{$key}}_{{$list}}"></td>
                                                            <td>{{ Form::label('module_checkbox_'.$key.'_'.$list, $list, ['class' => 'form-check-label pointer']) }}</td>
                                                            <td class="module_checkbox_{{$key}}_{{$list}}">
                                                                <div class="row">
                                                                    @foreach ($permissions as $key => $prermission)
                                                                        @php
                                                                        $parts = explode(" ", $prermission->name);
                                                                        $check = strtolower($parts[1] ?? $parts[0]);
                                                                            //$check = strtok($prermission->name, " ");
                                                                            $name =str_replace($check,"",$prermission->name);
                                                                        @endphp
                                                                        @if ($list == $check)
                                                                            <div class="col-xl-4 col-ld-6 col-md-12 col-12 form-check mb-2">
                                                                                {{ Form::checkbox('permissions[]', $prermission->id, $role->permission, ['class' => 'form-check-input checkbox-' . strtolower($module), 'id' => 'permission_' . $key . '_' . $prermission->id]) }}
                                                                                {{ Form::label('permission_' . $key . '_' . $prermission->id, $name, ['class' => 'form-check-label']) }}
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                    </div>
                @endif
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}

