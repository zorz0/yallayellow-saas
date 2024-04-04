@extends('layouts.theme_layout')
@section('customize-section')
<div class="container-fluid px-2">
    <header class="row preview-header-main">
        <div class="col-12">
            <div class=" pos-top-bar preview-header  bg-primary d-flex justify-content-between">
                <div class="preview-header-left">
                    <a href="{{ route('theme-preview.index') }}" class="text-black"><i class="ti ti-home"
                        style="font-size: 20px;"></i> </a>
                <span class="text-black">{{ __('Theme Preview') }}</span>
                </div>
   
                <div class="preview-header-right">
                    <button id="save-theme-btn" class="btn text-black" disabled="true">{{ __('Save as Draft') }} </button>
                    <input type="hidden" id="publish_theme_url" name="theme_id" value="{{ route('publish-theme') }}">
                    <input type="hidden" id="publish_theme_id" name="theme_id" value="{{ $currentTheme }}">
                    <input type="hidden" id="publish_store_id" name="store_id" value="{{ $store_id }}">
                    <input type="hidden" id="publish_is_publish" name="is_publish" value="1">
                    <button id="publish-theme-btn" class="btn text-black" {{ $is_publish ? 'disabled' : '' }}>
                        {{ __('Publish') }}
                    </button>
                </div>
            
        </header>
    <div class="wrapper">
    <div class="row mt-4">
        <div class="col-mb-6 col-lg-8">
            <div class="shop-theme-wrapper">
                <div class="sop-card card">
                    <div class="card-header">
                        {{ __('Home Page')}}
                    </div>
                    <div class="card-body p-2">
                        <div class="right-content">
                            <div id="theme_preview_section">
                                @include('main_file')
                            </div>
                            <div id="default_tool_bar" class="d-none">
                                <a class="option-button" href="#" id="up-section-btn" role="button">
                                    <i class="ti ti-arrow-up"></i>
                                </a>
                                <a class="option-button" href="#" id="down-section-btn" role="button">
                                    <i class="ti ti-arrow-down"></i>
                                </a>
                                <a class="option-button" href="#" id="hide-section-btn" role="button">
                                    <i class="ti ti-eye-off"></i>
                                </a>
                                <a class="option-button" href="#" id="show-section-btn" role="button">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-mb-3 col-lg-4">
            <div class="card m-0 sidebar_form">
                
            </div>
        </div>
    </div>
</div>
</div>
@endsection

