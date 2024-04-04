@extends('layouts.app')

@section('page-title', __('Language'))

@section('action-button')
@if (auth()->user()->type == 'super admin')
<div class="lang-setting">
    @if($currantLang != (!empty(env('default_language')) ? env('default_language') : 'en'))
        <div class="form-check form-switch custom-switch-v1">
            <input type="hidden" name="disable_lang" value="off">
            <input type="checkbox" class="form-check-input input-primary" name="disable_lang" data-bs-placement="top" title="{{ __('Enable/Disable') }}" id="disable_lang" data-bs-toggle="tooltip" {{ !in_array($currantLang,$disabledLang) ? 'checked':'' }} >
            <label class="form-check-label" for="disable_lang"></label>
        </div>
    @endif
    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="Add Language"
        data-url="{{ route('create.language') }}" data-toggle="tooltip" title="{{ __('Create Language') }}">
        <i class="ti ti-plus"></i>
        </a>
    </div>

    @if($currantLang != (!empty(env('default_language')) ? env('default_language') : 'en'))
    {!! Form::open(['method' => 'DELETE', 'route' => ['lang.destroy', $currantLang], 'class' => 'd-inline']) !!}
    <button type="button" class="btn btn-sm btn-danger show_confirm">
        <i class="ti ti-trash text-white py-1" data-bs-toggle="tooltip"
            title="Delete"></i>
    </button>
    {!! Form::close() !!}
    @endif
</div>
@endif

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Language') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body p-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                        @foreach($languages as $key=> $lang)
                            <a href="{{route('manage.language',[$key])}}" class="nav-link text-sm font-weight-bold @if($currantLang == $key) active @endif">
                                <i class="d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">{{Str::upper($lang)}}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="p-3 card">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill"
                        data-bs-target="#home" type="button">{{ __('Labels')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill"
                        data-bs-target="#profile" type="button">{{ __('Messages')}}</button>
                </li>
            </ul>
        </div>
        <div class="col-xl-12 col-md-12">
            <div class="card card-fluid">
                <div class="card-body" style="position: relative;">
                    <div class="tab-content no-padding" id="myTab2Content">
                        <div class="tab-pane fade show active" id="lang1" role="tabpanel" aria-labelledby="home-tab4">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                                        @csrf
                                        <div class="row">
                                            @foreach($arrLabel as $label => $value)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="example3cols1Input">{{$label}} </label>
                                                        <input type="text" class="form-control" name="label[{{$label}}]" value="{{$value}}">
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <div class="d-flex justify-content-end">
                                                            {{Form::submit(__('Save Changes'),array('class'=>'btn btn-xs btn-primary'))}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                                        @csrf
                                        <div class="row">
                                            @foreach($arrMessage as $fileName => $fileValue)
                                                <div class="col-lg-12">
                                                    <h5>{{ucfirst($fileName)}}</h5>
                                                </div>
                                                @foreach($fileValue as $label => $value)
                                                    @if(is_array($value))
                                                        @foreach($value as $label2 => $value2)
                                                            @if(is_array($value2))
                                                                @foreach($value2 as $label3 => $value3)
                                                                    @if(is_array($value3))
                                                                        @foreach($value3 as $label4 => $value4)
                                                                            @if(is_array($value4))
                                                                                @foreach($value4 as $label5 => $value5)
                                                                                    <div class="col-md-6">
                                                                                        <div class="form-group">
                                                                                            <label class="form-label">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}.{{$label5}}</label>
                                                                                            <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}][{{$label5}}]" value="{{$value5}}">
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            @else
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}</label>
                                                                                        <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}]" value="{{$value4}}">
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    @else
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}</label>
                                                                                <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}]" value="{{$value3}}">
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{$fileName}}.{{$label}}.{{$label2}}</label>
                                                                        <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}]" value="{{$value2}}">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="form-label">{{$fileName}}.{{$label}}</label>
                                                                <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}]" value="{{$value}}">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <div class="d-flex justify-content-end">
                                                        {{Form::submit(__('Save Changes'),array('class'=>'btn btn-xs btn-primary'))}}
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-script')
    <script>
        $(document).on('change','#disable_lang',function(){
        var val = $(this).prop("checked");
        if(val == true){
                var langMode = 'on';
        }
        else{
            var langMode = 'off';
        }

        $.ajax({
                type:'POST',
                url: "{{route('disablelanguage')}}",
                datType: 'json',
                data:{
                    "_token": "{{ csrf_token() }}",
                    "mode":langMode,
                    "lang":"{{ $currantLang }}"
                },
                success : function(data){
                    show_toastr('Success',data.message, 'success')
                }
        });
        });
    </script>
@endpush

