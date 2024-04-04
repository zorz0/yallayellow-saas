@extends('layouts.app')

@section('page-title', __('Mobile App Setting'))

@section('action-button')
    <div class=" text-end d-flex all-button-box justify-content-md-end justify-content-center">

    </div>
@endsection
@php
$theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
    $date_format = \App\Models\Utility::GetValueByName('date_format',$theme_name);
    $fcm_Key = \App\Models\Utility::GetValueByName('fcm_Key',$theme_name);
$firebase_enabled = \App\Models\Utility::GetValueByName('firebase_enabled',$theme_name);
$firebase_enabled = empty($firebase_enabled) || $firebase_enabled == 'on' ? 1 : 0;
@endphp
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Mobile App Setting') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card sticky-top" style="top:60px">
                <div class="list-group list-group-flush theme-set-tab" id="useradd-sidenav">
                    <ul class="nav nav-pills w-100 flex-md-nowrap gap-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            @if (env('IS_MOBILE') == 'yes')
                                <a href="#api_url" class="nav-link rounded-2 text-center f-w-600 active list-group-item list-group-item-action border-0" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                                    {{ __('API url') }}
                                </a>
                            @endif
                        </li>
                        <li class="nav-item" role="presentation">
                            @if (env('IS_MOBILE') == 'yes')
                                <a href="#App_Setting" class="nav-link rounded-2 text-center f-w-600 list-group-item list-group-item-action border-0" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                                    {{ __('Date Format') }}
                                </a>
                            @endif
                        </li>
                        <li class="nav-item" role="presentation">
                            @if (env('IS_MOBILE') == 'yes')
                                <a href="#Firebase_setting" class="nav-link rounded-2 text-center f-w-600 list-group-item list-group-item-action border-0" id="pills-firebase-tab" data-bs-toggle="pill" data-bs-target="#pills-firebase" type="button" role="tab" aria-controls="pills-firebase" aria-selected="false">
                                    {{ __('Firebase settings') }}
                                </a>
                            @endif
                        </li>
                        <li class="nav-item" role="presentation">
                            @if (env('IS_MOBILE') == 'yes')
                                <a href="#Main_Page_Content" class="nav-link rounded-2 text-center f-w-600 list-group-item list-group-item-action border-0" id="pills-main-tab" data-bs-toggle="pill" data-bs-target="#pills-main" type="button" role="tab" aria-controls="pills-main" aria-selected="false">
                                    {{ __('Main Screen') }}
                                </a>
                            @endif
                        </li>
                        <li class="nav-item" role="presentation">
                            @if (env('IS_MOBILE') == 'yes')
                            <a href="#Product_Page_Content" class="nav-link rounded-2 text-center f-w-600 list-group-item list-group-item-action border-0" id="pills-product-tab" data-bs-toggle="pill" data-bs-target="#pills-product" type="button" role="tab" aria-controls="pills-product" aria-selected="false">
                                {{ __('Product Screen') }}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#loyality_program_json" class="nav-link rounded-2 text-center f-w-600 list-group-item list-group-item-action border-0" id="pills-loyal-tab" data-bs-toggle="pill" data-bs-target="#pills-loyal" type="button" role="tab" aria-controls="pills-loyal" aria-selected="false">
                                {{ __('Loyality Program Screen') }}
                            </a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    @if (env('IS_MOBILE') == 'yes')
                        <div id="api_url">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('API URL') }}</h5>
                                    <small class="text-muted"></small>
                                </div>


                                <div class="card-body">
                                    <div class="form-group col-md-7" id="StoreLink">
                                        {{-- {{ Form::label('store_link', __('APP Link'), ['class' => 'form-label']) }} --}}
                                        <div class="input-group">
                                            <input type="text"
                                                value="{{ url('/api/'.$slug) }}"
                                                id="AppInput" class="form-control d-inline-block"
                                                aria-label="Recipient's username"
                                                aria-describedby="button-addon2" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary" type="button"
                                                    onclick="AppFunction()" id="button-addon2"><i
                                                        class="far fa-copy"></i>
                                                    {{ __('Copy Link') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    @if (env('IS_MOBILE') == 'yes')
                        <div id="App_Setting">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Date Format') }}</h5>
                                    <small class="text-muted"></small>
                                </div>
                                <div class="card-body">
                                    {{ Form::open(['route' => 'site.setting', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                {!! Form::label('', __('Date Format'), ['class' => 'form-label']) !!}
                                                {!! Form::select('date_format', ['d M, Y' => date('d M, Y'), 'Y-m-d' => date('Y-m-d')], $date_format, [
                                                    'data-role' => 'tagsinput',
                                                    'id' => 'date_formate',
                                                ]) !!}
                                            </div>
                                        </div>

                                        <div class="col-lg-12 text-end">
                                            <input type="submit" value="{{ __('Save Changes') }}" class="btn-submit btn btn-primary">
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="pills-firebase" role="tabpanel" aria-labelledby="pills-firebase-tab">
                    @if (env('IS_MOBILE') == 'yes')
                        <div id="Firebase_setting">
                            <div class="card">
                                {{ Form::open(['route' => 'firebase.settings', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class=""> {{ __('Firebase Settings') }} </h5>
                                            {!! Form::hidden('firebase_enabled', 'off') !!}
                                            <div class="form-check form-switch d-inline-block">
                                                {!! Form::checkbox('firebase_enabled', 'on', $firebase_enabled, ['class' => 'form-check-input', 'id' => 'firebase_enabled']) !!}
                                                <label class="custom-control-label form-control-label" for="firebase_enabled"></label>
                                            </div>

                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-8 col-sm-8 form-group">
                                                {!! Form::label('', __('FCM Key'), ['class' => 'form-label']) !!}
                                                {!! Form::text('fcm_Key', $fcm_Key, ['class' => 'form-control', 'placeholder' => 'Enter FCM Key']) !!}
                                            </div>
                                            <div class="col-lg-12 text-end">
                                                <input type="submit" value="{{ __('Save Changes') }}" class="btn-submit btn btn-primary">
                                            </div>
                                        </div>

                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="pills-main" role="tabpanel" aria-labelledby="pills-main-tab">
                    @if (env('IS_MOBILE') == 'yes')
                        <div id="Main_Page_Content">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Main Screen') }}</h5>
                                    <small class="text-muted"></small>
                                </div>
                                <div class="card-body">
                                    {{ Form::open(['route' => 'app-setting.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                    @foreach ($json as $json_key => $section)
                                        @php
                                            $id = '';

                                            if ($section['section_name'] == 'Home-Brand-Logo') {
                                                $id = 'Brand_Logo';
                                            }
                                            if ($section['section_name'] == 'Home-Header') {
                                                $id = 'Header_Setting';
                                                $class = 'card';
                                            }
                                            if ($section['section_name'] == 'Home-Promotions') {
                                                $id = 'Features_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Email-Subscriber') {
                                                $id = 'Email_Subscriber_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Categories') {
                                                $id = 'Categories';
                                            }
                                            if ($section['section_name'] == 'Home-Testimonial') {
                                                $id = 'Testimonials';
                                            }
                                            if ($section['section_name'] == 'Home-Footer-1') {
                                                $id = 'Footer_1';
                                            }
                                            if ($section['section_name'] == 'Home-Footer-2') {
                                                $id = 'Footer_2';
                                            }

                                        @endphp
                                        <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                            value="{{ $section['section_name'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                            value="{{ $section['section_slug'] }}">

                                        <input type="hidden" name="array[{{ $json_key }}][section_enable]"
                                            value="{{ $section['section_enable'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                            value="{{ $section['array_type'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                            value="{{ $section['loop_number'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][unique_section_slug]"
                                            value="{{ $section['unique_section_slug'] }}">


                                        @php
                                            $loop = 1;
                                            $section = (array) $section;
                                        @endphp
                                        {{-- @if ($json_key - 1 > -1 && $json[$json_key - 1]['section_slug'] != $section['section_slug']) --}}
                                        @if(($json_key-1 > 0 && ( $json[$json_key-1]['section_slug'] != $section['section_slug']) ) || $json_key == 0)
                                        {{-- @if(($json_key-1 > 0 && ( $json[$json_key-1]['section_slug'] != $section['section_slug']) ) || $json_key == 0) --}}
                                            <div class="card " id="{{ $id }}">
                                                <div class="card-header d-flex justify-content-between">
                                                    <div>
                                                        <h5> {{ $section['section_name'] }} </h5>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="form-check form-switch form-switch-right">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input mx-2 off switch"
                                                                data-toggle="switchbutton"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                        @endif
                                        <div class="card-body">
                                            @php $loop1 = 1; @endphp
                                            @if ($section['array_type'] == 'multi-inner-list')
                                                @php
                                                    $loop1 = (int) $section['loop_number'];
                                                @endphp
                                            @endif

                                            @for ($i = 0; $i < $loop1; $i++)
                                                <div class="row">
                                                    @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                        <?php $field = (array) $field; ?>

                                                        <input type="hidden"
                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                            value="{{ $field['field_name'] }}">
                                                        <input type="hidden"
                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                            value="{{ $field['field_slug'] }}">
                                                        <input type="hidden"
                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                            value="{{ $field['field_help_text'] }}">
                                                        <input type="hidden"
                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                            value="{{ $field['field_default_text'] }}">
                                                        <input type="hidden"
                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                            value="{{ $field['field_type'] }}">

                                                        @if ($field['field_type'] == 'text')
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">{{ $field['field_name'] }}</label>
                                                                    @php
                                                                        $checked1 = $field['field_default_text'];
                                                                        if (!empty($section[$field['field_slug']][$i])) {
                                                                            $checked1 = $section[$field['field_slug']][$i];
                                                                        }
                                                                    @endphp
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        <input type="text"
                                                                            name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                            class="form-control" value="{{ $checked1 }}"
                                                                            placeholder="{{ $field['field_help_text'] }}">
                                                                    @else
                                                                        <input type="text"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"class="form-control"
                                                                            value="{{ $field['field_default_text'] }}"
                                                                            placeholder="{{ $field['field_help_text'] }}">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($field['field_type'] == 'text area')
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">{{ $field['field_name'] }}</label>
                                                                    @php
                                                                        $checked1 = $field['field_default_text'];
                                                                        if (!empty($section[$field['field_slug']][$i])) {
                                                                            $checked1 = $section[$field['field_slug']][$i];
                                                                        }
                                                                    @endphp
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        <textarea class="form-control" name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                            rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                    @else
                                                                        <textarea class="form-control"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" {{-- name="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]" --}}
                                                                            rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($field['field_type'] == 'photo upload')
                                                            <div class="col-sm-6">
                                                                @if ($section['array_type'] == 'multi-inner-list')
                                                                    @php
                                                                        $checked2 = $field['field_default_text'];

                                                                        if (!empty($section[$field['field_slug']])) {
                                                                            $checked2 = $section[$field['field_slug']][$i];
                                                                            if (is_array($checked2)) {
                                                                                $checked2 = $checked2['field_prev_text'];
                                                                            }
                                                                        }

                                                                    @endphp
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ $field['field_name'] }}</label>
                                                                        <input type="hidden"
                                                                            name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                            value="{{ $checked2 }}">
                                                                        <input type="file"
                                                                            name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                            class="form-control"
                                                                            placeholder="{{ $field['field_help_text'] }}">
                                                                    </div>
                                                                    {{-- <img src="{{ asset($checked2) }}"
                                                                        style="width: auto; max-height: 80px;"> --}}
                                                                        <img src="{{ get_file($checked2 , APP_THEME()) }}"
                                                                        style="width: auto; max-height: 80px;">
                                                                @else
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ $field['field_name'] }}</label>
                                                                        <input type="hidden"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]"
                                                                            value="{{ $field['field_default_text'] }}">
                                                                        <input type="file"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                            class="form-control"
                                                                            placeholder="{{ $field['field_help_text'] }}">
                                                                    </div>

                                                                    <img src="{{ get_file($field['field_default_text'] ,APP_THEME()) }}"
                                                                        style="width: 200px; height: 200px;">
                                                                @endif
                                                            </div>
                                                        @endif

                                                        @if ($field['field_type'] == 'button')
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">{{ $field['field_name'] }}</label>
                                                                    <input type="text"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                        class="form-control"
                                                                        value="{{ $field['field_default_text'] }}"
                                                                        placeholder="{{ $field['field_help_text'] }}">
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @php
                                                            $checked = '';
                                                            if ($field['field_slug'] == 'homepage-quick-link-enable') {
                                                                $checked = $field['field_default_text'] == 'on' ? 'checked' : '';
                                                            }
                                                            if ($field['field_slug'] == 'homepage-testimonial-card-enable') {
                                                                // echo $field['field_default_text'];
                                                                $checked = $field['field_default_text'] == 'on' ? 'checked' : '';
                                                                // dd($checked);
                                                            }
                                                        @endphp

                                                        @if ($field['field_type'] == 'checkbox')
                                                            <div class="col-sm-6">
                                                                <label class="form-label">{{ $field['field_name'] }}</label>
                                                                <div class="form-check form-switch form-switch-right mb-2">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked1 = '';
                                                                            if (!empty($section[$field['field_slug']][$i]) && $section[$field['field_slug']][$i] == 'on') {
                                                                                $checked1 = 'checked';
                                                                            }
                                                                        @endphp
                                                                        <input type="hidden"
                                                                            name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                            value="off">
                                                                        <input type="checkbox" class="form-check-input mx-2"
                                                                            name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                            id="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"
                                                                            {{ $checked1 }}>
                                                                    @else
                                                                        <input type="hidden"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                            value="off">
                                                                        <input type="checkbox" class="form-check-input mx-2"
                                                                            {{-- name="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]" --}}
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                            id="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"
                                                                            {{ $checked }}>
                                                                    @endif


                                                                    <label class="form-check-label"
                                                                        for="array[ {{ $section['section_slug'] }}][{{ $field['field_slug'] }}]">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($field['field_type'] == 'multi file upload')
                                                            <div class="form-group">
                                                                <label class="form-label">{{ $field['field_name'] }}</label>

                                                                <input type="file"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][multi_image][]"
                                                                    class="form-control custom-input-file" multiple>
                                                            </div>
                                                            <div id="img-count" class="badge badge-success rounded-pill"></div>
                                                            <div class="col-12">
                                                                <div class="card-wrapper p-3 lead-common-box">
                                                                    @if (!empty($field['image_path']))
                                                                        @foreach ($field['image_path'] as $key => $file_pathh)
                                                                            <div class="card mb-3 border shadow-none product_Image"
                                                                                data-value="{{ $file_pathh }}">
                                                                                <div class="px-3 py-3">
                                                                                    <div class="row align-items-center">
                                                                                        <div class="col ml-n2">
                                                                                            <p class="card-text small text-muted">

                                                                                                <img class="rounded"
                                                                                                    src="{{ get_file($file_pathh , APP_THEME()) }}"
                                                                                                    width="70px"
                                                                                                    alt="Image placeholder"
                                                                                                    data-dz-thumbnail>
                                                                                            </p>
                                                                                        </div>
                                                                                        <div class="col-auto actions">
                                                                                            <a class="action-item"
                                                                                                href=" {{ get_file($file_pathh , APP_THEME()) }}"
                                                                                                download="" data-toggle="tooltip"
                                                                                                data-original-title="{{ __('Download') }}">
                                                                                                <i class="fas fa-download"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class="col-auto actions">
                                                                                            <a name="deleteRecord"
                                                                                                class="action-item deleteRecord"
                                                                                                data-name="{{ $file_pathh }}">
                                                                                                <i class="fas fa-trash"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endfor

                                        </div>
                                        @if ($json_key > 0 && $json_key + 1 <= count($json) - 1)
                                            @if ($json[$json_key + 1]['section_slug'] != $section['section_slug'])
                                    </div>
                                        @endif
                                    @endif
                                    @endforeach
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary me-3 mb-4">save</button>
                                    </div>
                                {!! Form::close() !!}
                                </div>
                            </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="pills-product" role="tabpanel" aria-labelledby="pills-product-tab">
                    @if (env('IS_MOBILE') == 'yes')
                        <div id="Product_Page_Content">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Product Screen') }}</h5>
                                    <small class="text-muted"></small>
                                </div>
                                <div class="card-body">
                                    {{ Form::open(['route' => 'product.page.setting', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                    {!! Form::hidden('page_name', 'product_banner') !!}
                                    @foreach ($product_banner_json as $product_banner_json_key => $product_banner_json_section)


                                        @php
                                            $id = '';

                                            if ($product_banner_json_section['section_name'] == 'Home-Brand-Logo') {
                                                $id = 'Brand_Logo';
                                            }
                                            if ($product_banner_json_section['section_name'] == 'Home-Header') {
                                                $id = 'Header_Setting';
                                                $class = 'card';
                                            }
                                            if ($product_banner_json_section['section_name'] == 'Home-Promotions') {
                                                $id = 'Features_Setting';
                                            }
                                            if ($product_banner_json_section['section_name'] == 'Home-Email-Subscriber') {
                                                $id = 'Email_Subscriber_Setting';
                                            }
                                            if ($product_banner_json_section['section_name'] == 'Home-Categories') {
                                                $id = 'Categories';
                                            }
                                            if ($product_banner_json_section['section_name'] == 'Home-Testimonial') {
                                                $id = 'Testimonials';
                                            }
                                            if ($product_banner_json_section['section_name'] == 'Home-Footer-1') {
                                                $id = 'Footer_1';
                                            }
                                            if ($product_banner_json_section['section_name'] == 'Home-Footer-2') {
                                                $id = 'Footer_2';
                                            }

                                        @endphp
                                        <input type="hidden" name="array[{{ $product_banner_json_key }}][section_name]"
                                            value="{{ $product_banner_json_section['section_name'] }}">
                                        <input type="hidden" name="array[{{ $product_banner_json_key }}][section_slug]"
                                            value="{{ $product_banner_json_section['section_slug'] }}">
                                        <input type="hidden" name="array[{{ $product_banner_json_key }}][array_type]"
                                            value="{{ $product_banner_json_section['array_type'] }}">
                                        <input type="hidden" name="array[{{ $product_banner_json_key }}][loop_number]"
                                            value="{{ $product_banner_json_section['loop_number'] }}">
                                        @php
                                            $loop = 1;
                                            $product_banner_json_section = (array) $product_banner_json_section;
                                        @endphp
                                        @if ($product_banner_json_key - 1 > -1 &&
                                            $product_banner_json[$product_banner_json_key - 1]['section_slug'] != $product_banner_json_section['section_slug'])
                                            <div class="card " id="{{ $id }}">
                                                <div class="card-header d-flex justify-content-between">
                                                    <div>
                                                        <h5> {{ $product_banner_json_section['section_name'] }} </h5>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="form-check form-switch form-switch-right">
                                                            <input type="hidden"
                                                                name="array[{{ $product_banner_json_key }}][section_enable]{{ $product_banner_json_section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input mx-2 off switch"
                                                                data-toggle="switchbutton"
                                                                name="array[{{ $product_banner_json_key }}][section_enable]{{ $product_banner_json_section['section_enable'] }}"
                                                                id="array[{{ $product_banner_json_key }}]{{ $product_banner_json_section['section_slug'] }}"
                                                                {{ $product_banner_json_section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                        @endif
                                        <div class="card-body">
                                            @php $loop1 = 1; @endphp
                                            @if ($product_banner_json_section['array_type'] == 'multi-inner-list')
                                                @php
                                                    $loop1 = (int) $product_banner_json_section['loop_number'];
                                                @endphp
                                            @endif

                                            @for ($i = 0; $i < $loop1; $i++)
                                                <div class="row">
                                                    @foreach ($product_banner_json_section['inner-list'] as $product_banner_inner_list_key => $product_banner_field)
                                                        <?php $product_banner_field = (array) $product_banner_field; ?>

                                                        <input type="hidden"
                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_name]"
                                                            value="{{ $product_banner_field['field_name'] }}">
                                                        <input type="hidden"
                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_slug]"
                                                            value="{{ $product_banner_field['field_slug'] }}">
                                                        <input type="hidden"
                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_help_text]"
                                                            value="{{ $product_banner_field['field_help_text'] }}">
                                                        <input type="hidden"
                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_default_text]"
                                                            value="{{ $product_banner_field['field_default_text'] }}">
                                                        <input type="hidden"
                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_type]"
                                                            value="{{ $product_banner_field['field_type'] }}">

                                                        @if ($product_banner_field['field_type'] == 'text')
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="form-label">{{ $product_banner_field['field_name'] }}</label>
                                                                    @php
                                                                        $checked1 = $product_banner_field['field_default_text'];
                                                                        if (!empty($product_banner_json_section[$product_banner_field['field_slug']][$i])) {
                                                                            $checked1 = $product_banner_json_section[$product_banner_field['field_slug']][$i];
                                                                        }
                                                                    @endphp
                                                                    @if ($product_banner_json_section['array_type'] == 'multi-inner-list')
                                                                        <input type="text"
                                                                            name="array[{{ $product_banner_json_key }}][{{ $product_banner_field['field_slug'] }}][{{ $i }}]"
                                                                            class="form-control" value="{{ $checked1 }}"
                                                                            placeholder="{{ $product_banner_field['field_help_text'] }}">
                                                                    @else
                                                                        <input type="text"
                                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_default_text]"class="form-control"
                                                                            value="{{ $product_banner_field['field_default_text'] }}"
                                                                            placeholder="{{ $product_banner_field['field_help_text'] }}">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($product_banner_field['field_type'] == 'text area')
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="form-label">{{ $product_banner_field['field_name'] }}</label>
                                                                    @php
                                                                        $checked1 = $product_banner_field['field_default_text'];
                                                                        if (!empty($product_banner_json_section[$product_banner_field['field_slug']][$i])) {
                                                                            $checked1 = $product_banner_json_section[$product_banner_field['field_slug']][$i];
                                                                        }
                                                                    @endphp
                                                                    @if ($product_banner_json_section['array_type'] == 'multi-inner-list')
                                                                        <textarea class="form-control"
                                                                            name="array[{{ $product_banner_json_key }}][{{ $product_banner_field['field_slug'] }}][{{ $i }}]"
                                                                            rows="3" placeholder="{{ $product_banner_field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                    @else
                                                                        <textarea class="form-control"
                                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_default_text]"
                                                                            {{-- name="array[{{ $product_banner_json_section['section_slug'] }}][{{ $product_banner_field['field_slug'] }}]" --}} rows="3" placeholder="{{ $product_banner_field['field_help_text'] }}">{{ $product_banner_field['field_default_text'] }}</textarea>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($product_banner_field['field_type'] == 'photo upload')
                                                            <div class="col-sm-6">
                                                                @if ($product_banner_json_section['array_type'] == 'multi-inner-list')
                                                                    @php
                                                                        $checked2 = $product_banner_field['field_default_text'];
                                                                        if (!empty($product_banner_json_section[$product_banner_field['field_slug']])) {
                                                                            $checked2 = $product_banner_json_section[$product_banner_field['field_slug']][$i];
                                                                            if (is_array($checked2)) {
                                                                                $checked2 = $checked2['field_prev_text'];
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $product_banner_field['field_name'] }}</label>
                                                                        <input type="hidden"
                                                                            name="array[{{ $product_banner_json_key }}][{{ $product_banner_field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                            value="{{ $checked2 }}">
                                                                        <input type="file"
                                                                            name="array[{{ $product_banner_json_key }}][{{ $product_banner_field['field_slug'] }}][{{ $i }}][image]"
                                                                            class="form-control"
                                                                            placeholder="{{ $product_banner_field['field_help_text'] }}">
                                                                    </div>
                                                                    <img src="{{ get_file($checked2 , APP_THEME()) }}"
                                                                        style="width: auto; max-height: 80px;">
                                                                @else
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $product_banner_field['field_name'] }}</label>
                                                                        <input type="hidden"
                                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_prev_text]"
                                                                            value="{{ $product_banner_field['field_default_text'] }}">
                                                                        <input type="file"
                                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_default_text]"
                                                                            class="form-control"
                                                                            placeholder="{{ $product_banner_field['field_help_text'] }}">
                                                                    </div>

                                                                    <img src="{{ get_file($product_banner_field['field_default_text'] , APP_THEME()) }}"
                                                                        style="width: 200px; height: 200px;">
                                                                @endif
                                                            </div>
                                                        @endif

                                                        @if ($product_banner_field['field_type'] == 'button')
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="form-label">{{ $product_banner_field['field_name'] }}</label>
                                                                    <input type="text"
                                                                        name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_default_text]"
                                                                        class="form-control"
                                                                        value="{{ $product_banner_field['field_default_text'] }}"
                                                                        placeholder="{{ $product_banner_field['field_help_text'] }}">
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @php
                                                            $checked = '';
                                                            if ($product_banner_field['field_slug'] == 'homepage-quick-link-enable') {
                                                                $checked = $product_banner_field['field_default_text'] == 'on' ? 'checked' : '';
                                                            }
                                                            if ($product_banner_field['field_slug'] == 'homepage-testimonial-card-enable') {
                                                                // echo $product_banner_field['field_default_text'];
                                                                $checked = $product_banner_field['field_default_text'] == 'on' ? 'checked' : '';
                                                                // dd($checked);
                                                            }
                                                        @endphp

                                                        @if ($product_banner_field['field_type'] == 'checkbox')
                                                            <div class="col-sm-6">
                                                                <label class="form-label">{{ $product_banner_field['field_name'] }}</label>
                                                                <div class="form-check form-switch form-switch-right mb-2">
                                                                    @if ($product_banner_json_section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked1 = '';
                                                                            if (!empty($product_banner_json_section[$product_banner_field['field_slug']][$i]) && $product_banner_json_section[$product_banner_field['field_slug']][$i] == 'on') {
                                                                                $checked1 = 'checked';
                                                                            }
                                                                        @endphp
                                                                        <input type="hidden"
                                                                            name="array[{{ $product_banner_json_key }}][{{ $product_banner_field['field_slug'] }}][{{ $i }}]"
                                                                            value="off">
                                                                        <input type="checkbox" class="form-check-input mx-2"
                                                                            name="array[{{ $product_banner_json_key }}][{{ $product_banner_field['field_slug'] }}][{{ $i }}]"
                                                                            id="array[{{ $product_banner_json_section['section_slug'] }}][{{ $product_banner_field['field_slug'] }}]"
                                                                            {{ $checked1 }}>
                                                                    @else
                                                                        <input type="hidden"
                                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_default_text]"
                                                                            value="off">
                                                                        <input type="checkbox" class="form-check-input mx-2"
                                                                            {{-- name="array[{{ $product_banner_json_section['section_slug'] }}][{{ $product_banner_field['field_slug'] }}]" --}}
                                                                            name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][field_default_text]"
                                                                            id="array[{{ $product_banner_json_section['section_slug'] }}][{{ $product_banner_field['field_slug'] }}]"
                                                                            {{ $checked }}>
                                                                    @endif


                                                                    <label class="form-check-label"
                                                                        for="array[ {{ $product_banner_json_section['section_slug'] }}][{{ $product_banner_field['field_slug'] }}]">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($product_banner_field['field_type'] == 'multi file upload')
                                                            <div class="form-group">
                                                                <label class="form-label">{{ $product_banner_field['field_name'] }}</label>

                                                                <input type="file"
                                                                    name="array[{{ $product_banner_json_key }}][inner-list][{{ $product_banner_inner_list_key }}][multi_image][]"
                                                                    class="form-control custom-input-file" multiple>
                                                            </div>
                                                            <div id="img-count" class="badge badge-success rounded-pill"></div>
                                                            <div class="col-12">
                                                                <div class="card-wrapper p-3 lead-common-box">
                                                                    @if (!empty($product_banner_field['image_path']))
                                                                        @foreach ($product_banner_field['image_path'] as $key => $file_pathh)
                                                                            <div class="card mb-3 border shadow-none product_Image"
                                                                                data-value="{{ $file_pathh }}">
                                                                                <div class="px-3 py-3">
                                                                                    <div class="row align-items-center">
                                                                                        <div class="col ml-n2">
                                                                                            <p class="card-text small text-muted">

                                                                                                <img class="rounded"
                                                                                                    src="{{ get_file($file_pathh , APP_THEME()) }}"
                                                                                                    width="70px" alt="Image placeholder"
                                                                                                    data-dz-thumbnail>
                                                                                            </p>
                                                                                        </div>
                                                                                        <div class="col-auto actions">
                                                                                            <a class="action-item"
                                                                                                href=" {{ get_file($file_pathh , APP_THEME()) }}"
                                                                                                download="" data-toggle="tooltip"
                                                                                                data-original-title="{{ __('Download') }}">
                                                                                                <i class="fas fa-download"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class="col-auto actions">
                                                                                            <a name="deleteRecord"
                                                                                                class="action-item deleteRecord"
                                                                                                data-name="{{ $file_pathh }}">
                                                                                                <i class="fas fa-trash"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endfor

                                        </div>
                                        @if ($product_banner_json_key > 0 && $product_banner_json_key + 1 <= count($product_banner_json) - 1)
                                            @if ($product_banner_json[$product_banner_json_key + 1]['section_slug'] != $product_banner_json_section['section_slug'])
                                </div>
                                @endif
                                @endif

                                @endforeach
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">save</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="pills-loyal" role="tabpanel" aria-labelledby="pills-loyal-tab">
                    @if (env('IS_MOBILE') == 'yes')
                        <div id="loyality_program_json">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class=""> {{ __('Loyality program Screen') }} </h5>
                                    </div>
                                    <div class="card-body">
                                        {{ Form::open(['route' => ['product.page.setting'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                                        {!! Form::hidden('page_name', 'loyality_program') !!}
                                        @foreach ($loyality_program_json as $json_key => $section)
                                            @php $section = (array)$section; @endphp
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ $section['section_name'] }}</h5>
                                                </div>
                                                <div class="card-body">

                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            @php $field = (array)$field; @endphp

                                                            <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                                                value="{{ $section['section_name'] }}">
                                                            <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                                                value="{{ $section['section_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">

                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ $field['field_name'] }}</label>
                                                                        <input type="text"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][value]"
                                                                            class="form-control"
                                                                            value="{{ !empty($field['value']) ? $field['value'] : $field['field_default_text'] }}">
                                                                        <small>{{ $field['field_help_text'] }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ $field['field_name'] }}</label>
                                                                        <textarea class="form-control" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][value]"
                                                                            rows="3">{{ !empty($field['value']) ? $field['value'] : $field['field_default_text'] }}</textarea>
                                                                        <small>{{ $field['field_help_text'] }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-sm-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ $field['field_name'] }}</label>
                                                                        <input type="hidden"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][image_path]"
                                                                            value="{{ !empty($field['value']) ? $field['value'] : $field['field_default_text'] }}">
                                                                        <input type="file"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][value]"
                                                                            class="form-control">
                                                                        <small>{{ $field['field_help_text'] }}</small>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    @php
                                                                        $path = !empty($field['image_url']) ? $field['image_url'] : $field['field_default_text'];
                                                                    @endphp
                                                                    <img src="{{ get_file($path , APP_THEME()) }}" alt="" class="w-100">
                                                                </div>
                                                            @endif

                                                            @if ($field['field_type'] == 'product single')
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ $field['field_name'] }}</label>
                                                                        <select class="form-select"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][value]">
                                                                            <option>1</option>
                                                                            <option>2</option>
                                                                            <option>3</option>
                                                                            <option>4</option>
                                                                            <option>5</option>
                                                                        </select>
                                                                        <small>{{ $field['field_help_text'] }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($field['field_type'] == 'product multi')
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ $field['field_name'] }}</label>
                                                                        <select class="form-select"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][value]">
                                                                            <option>1</option>
                                                                            <option>2</option>
                                                                            <option>3</option>
                                                                            <option>4</option>
                                                                            <option>5</option>
                                                                        </select>
                                                                        <small>{{ $field['field_help_text'] }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($field['field_type'] == 'category')
                                                                <div class="col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ $field['field_name'] }}</label>
                                                                        <select class="form-select"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][value]">
                                                                            <option>1</option>
                                                                            <option>2</option>
                                                                            <option>3</option>
                                                                            <option>4</option>
                                                                            <option>5</option>
                                                                        </select>
                                                                        <small>{{ $field['field_help_text'] }}</small>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">save</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('custom-script')
    <script>
		function AppFunction() {
            var copyText = document.getElementById("AppInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            show_toastr('Success', "{{ __('Link copied') }}", 'success');
        }

        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        });
    </script>


    <script>
        $(function(){
            $('body').on('click', '.image_delete', function(e) {
                e.preventDefault();
                var id = $(this).attr('data-id');
                var data = {
                    'image': id
                };
                // now make the ajax request
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('product.image.delete') }}",
                    data: data,
                    context: this,
                    success: function(data){
                        $(this).closest('.product_Image').remove();
                        $('#Main_Page_Content_web_post').find('.submit_form').click();
                    }
                });
            });
        });
    </script>
@endpush
