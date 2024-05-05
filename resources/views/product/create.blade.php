@extends('layouts.app')

@section('page-title')
    {{ __('Product') }}
@endsection

@php
    $logo = asset(Storage::url('uploads/profile/'));
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item" aria-current="page"><a href="{{ route('product.index') }}">{{ __('Product') }}</a></li>
    <li class="breadcrumb-item" aria-current="page">{{ __('Create') }}</li>
@endsection

@php
    $plan = \App\Models\Plan::find(\Auth::user()->plan_id);
    $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
    $stock_management = \App\Models\Utility::GetValueByName('stock_management', $theme_name);
    $low_stock_threshold = \App\Models\Utility::GetValueByName('low_stock_threshold', $theme_name);
@endphp
@section('content')
    {{ Form::open(['route' => 'product.store', 'method' => 'post', 'id' => 'choice_form', 'enctype' => 'multipart/form-data']) }}



    <!-- One "tab" for each step in the form: -->
    <div class="tab">
        <div class="col-lg-12 col-md-6 col-12">
            <h5 class="mb-3">Main Informations</h5>
            <div class="card border">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-12">
                            {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control name']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12 parmalink " style =  "display: none; ">
                            {!! Form::label('', __('parmalink'), ['class' => 'form-label col-md-3']) !!}
                            <div class="d-flex flex-wrap gap-3">
                                <span class="input-group-text col-12" id="basic-addon2">{{ $link }}</span>
                                {!! Form::text('slug', null, ['class' => 'form-control slug col-12']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            {!! Form::label('', __('Category'), ['class' => 'form-label']) !!}
                            {!! Form::select('maincategory_id', $MainCategory, null, [
                                'class' => 'form-control',
                                'data-role' => 'tagsinput',
                                'id' => 'maincategory_id',
                            ]) !!}
                        </div>
                        <div class="form-group  col-12 subcategory_id_div" data_val='0'>
                            {!! Form::label('', __('Subcategory'), ['class' => 'form-label']) !!}
                            <span>
                                {!! Form::select('subcategory_id', [], null, [
                                    'class' => 'form-control',
                                    'data-role' => 'tagsinput',
                                    'id' => 'subcategory-dropdown',
                                ]) !!}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 col-12 switch-width">
                            {{ Form::label('tax_id', __('Taxs'), ['class' => ' form-label']) }}
                            <select name="tax_id[]" data-role="tagsinput" id="tax_id" multiple>
                                @foreach ($Tax as $Key => $tax)
                                    <option value={{ $Key }}>
                                        {{ $tax }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-12">
                            {!! Form::label('', __('Tax Status'), ['class' => 'form-label']) !!}
                            {!! Form::select('tax_status', $Tax_status, null, [
                                'class' => 'form-control',
                                'data-role' => 'tagsinput',
                                'id' => 'tax_id',
                            ]) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group  col-12" data_val='0'>
                            {!! Form::label('', __('Brand'), ['class' => 'form-label']) !!}
                            <span>
                                {!! Form::select('brand_id', $brands, null, [
                                    'class' => 'form-control',
                                    'data-role' => 'tagsinput',
                                    'id' => 'brand-dropdown',
                                ]) !!}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group  col-12" data_val='0'>
                            {!! Form::label('', __('Label'), ['class' => 'form-label']) !!}
                            <span>
                                {!! Form::select('label_id', $labels, null, [
                                    'class' => 'form-control',
                                    'data-role' => 'tagsinput',
                                    'id' => 'label-dropdown',
                                ]) !!}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-12">
                            {!! Form::label('', __('Tags'), ['class' => 'form-label']) !!}
                            <select name ="tag_id[]" class="select2 form-control" id="tag_id" multiple required>
                                @foreach ($tag as $key => $t)
                                    <option value="{{ $key }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            {!! Form::label('', __('Shipping'), ['class' => 'form-label']) !!}
                            {!! Form::select('shipping_id', $Shipping, null, [
                                'class' => 'form-control',
                                'data-role' => 'tagsinput',
                                'id' => 'shipping_id',
                            ]) !!}
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-12 product-weight">
                            {!! Form::label('', __('Weight(Kg)'), ['class' => 'form-label ']) !!}
                            {!! Form::number('product_weight', null, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
                        </div>
                    </div>
                    <div class="row product-price-div">
                        <div class="form-group col-md-6 col-12 product_price">
                            {!! Form::label('', __('Price'), ['class' => 'form-label']) !!}
                            {!! Form::number('price', null, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
                        </div>
                        <div class="form-group col-md-6 col-12">
                            {!! Form::label('', __('Sale Price'), ['class' => 'form-label']) !!}
                            {!! Form::number('sale_price', null, ['class' => 'form-control', 'min' => '0', 'step' => '0.01']) !!}
                        </div>
                    </div>
                    <hr>
                    <h4>{{ __('Product Stock') }}</h4>
                    <div class="row">
                        @if ($stock_management == 'on')
                            <div class="form-group col-md-6 col-12">
                                {!! Form::label('', __('Stock Management'), ['class' => 'form-label']) !!}
                                <div class="form-check form-switch">
                                    <input type="hidden" name="track_stock" value="0">
                                    <input type="checkbox" class="form-check-input enable_product_stock" name="track_stock"
                                        id="enable_product_stock" value="1">
                                    <label class="form-check-label" for="enable_product_stock"></label>
                                </div>
                            </div>
                        @else
                            <div class="form-group col-md-6 col-12 product_stock">
                                {!! Form::label('', __('Stock Management'), ['class' => 'form-label']) !!}<br>
                                <label name="trending" value=""><small>Disabled in <a
                                            href="{{ route('setting.index') . '#Brand_Setting ' }}"> store
                                            setting</a></small></label>
                            </div>
                        @endif

                    </div>

                    <div class="row">
                        <div class="form-group col-12 stock_stats">
                            {!! Form::label('', __('Stock Status:'), ['class' => 'form-label']) !!}
                            <div class="col-mb-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input code" type="radio" id="in_stock" value="in_stock"
                                        name="stock_status" checked="checked">
                                    <label class="form-check-label" for="   ">
                                        {{ __('In Stock') }}
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input code" type="radio" id="out_of_stock"
                                        value="out_of_stock" name="stock_status">
                                    <label class="form-check-label" for="out_of_stock">
                                        {{ __('Out of stock') }}
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input code" type="radio" id="on_backorder"
                                        value="on_backorder" name="stock_status">
                                    <label class="form-check-label" for="on_backorder">
                                        {{ __('On Backorder') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($stock_management == 'on')
                        <div class="row" id="options">
                            <div class="form-group col-md-6 col-12 product_stock">
                                {!! Form::label('', __('Stock'), ['class' => 'form-label']) !!}
                                {!! Form::number('product_stock', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group col-md-6 col-12">
                                {!! Form::label('', __('Low stock threshold'), ['class' => 'form-label']) !!}
                                {!! Form::number('low_stock_threshold', $low_stock_threshold, ['class' => 'form-control', 'min' => '0']) !!}
                            </div>
                            <div class="col-12 mb-3">
                                {!! Form::label('', __('Allow BackOrders:'), ['class' => 'form-label']) !!}
                                <div class="form-check m-1">
                                    <input type="radio" id="not_allow" value="not_allow" name="stock_order_status"
                                        class="form-check-input code" checked="checked">
                                    <label class="form-check-label" for="not_allow">{{ __('Do Not Allow') }}</label>
                                </div>
                                <div class="form-check m-1">
                                    <input type="radio" id="notify_customer" value="notify_customer"
                                        name="stock_order_status" class="form-check-input code">
                                    <label class="form-check-label"
                                        for="notify_customer">{{ __('Allow, But notify customer') }}</label>
                                </div>
                                <div class="form-check m-1">
                                    <input type="radio" id="allow" value="allow" name="stock_order_status"
                                        class="form-check-input code">
                                    <label class="form-check-label" for="allow">{{ __('Allow') }}</label>
                                </div>
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>

    <div class="tab">
        <div class="col-lg-12 col-md-6 col-12">
            <h5 class="mb-3">{{ __('Product Image') }}</h5>
            <div class="card border">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            {{-- <div class="card border">
                                    <div class="card-body"> --}}
                            <div class="form-group">
                                {{ Form::label('sub_images', __('Upload Product Images'), ['class' => 'form-label f-w-800']) }}
                                <div class="dropzone dropzone-multiple" data-toggle="dropzone1"
                                    data-dropzone-url="http://" data-dropzone-multiple>
                                    <div class="fallback">
                                        <div class="custom-file">
                                            {{-- <input type="file" class="custom-file-input" id="dropzone-1" name="file"
                                                                        multiple> --}}
                                            <input type="file" name="file" id="dropzone-1"
                                                class="fcustom-file-input"
                                                onchange="document.getElementById('dropzone').src = window.URL.createObjectURL(this.files[0])"
                                                multiple>
                                            <img id="dropzone"src="" width="20%" class="mt-2" />
                                            <label class="custom-file-label"
                                                for="customFileUpload">{{ __('Choose file') }}</label>
                                        </div>
                                    </div>
                                    <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <div class="avatar">
                                                        <img class="rounded" src="" alt="Image placeholder"
                                                            data-dz-thumbnail>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h6 class="text-sm mb-1" data-dz-name>...</h6>
                                                    <p class="small text-muted mb-0" data-dz-size>
                                                    </p>
                                                </div>
                                                <div class="col-auto">
                                                    <a href="#" class="dropdown-item" data-dz-remove>
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cover_image" class="col-form-label">{{ __('Upload Cover Image') }}</label>
                                <input type="file" name="cover_image" id="cover_image"
                                    class="form-control custom-input-file"
                                    onchange="document.getElementById('upcoverImg').src = window.URL.createObjectURL(this.files[0]);"
                                    multiple>
                                <img id="upcoverImg" src="" width="20%" class="mt-2" />
                            </div>
                            {{-- </div>
                                </div> --}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-12" id="downloadable-product-div">
                            <div class="form-group">
                                <div class="choose-file">
                                    <label for="downloadable_product"
                                        class="form-label">{{ __('Downloadable Product') }}</label>
                                    <input type="file" class="form-control" name="downloadable_product"
                                        id="downloadable_product"
                                        onchange="document.getElementById('downloadable_product').src = window.URL.createObjectURL(this.files[0]);"
                                        multiple>
                                    <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-12" id="preview_type">
                            {{ Form::label('preview_type', __('Preview Type'), ['class' => 'form-label']) }}
                            {{ Form::select('preview_type', $preview_type, null, ['class' => 'form-control font-style', 'id' => 'preview_type']) }}
                        </div>
                        <div class="form-group  col-md-6 col-12" id="preview-video-div">
                            <div class="form-group">
                                <div class="choose-file">
                                    <label for="preview_video" class="form-label">{{ __('Preview Video') }}</label>
                                    <input type="file" class="form-control" name="preview_video" id="preview_video"
                                        onchange="document.getElementById('preview_video').src = window.URL.createObjectURL(this.files[0]);"
                                        multiple>
                                    <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-6 col-12 ml-auto d-none" id="preview-iframe-div">
                            {{ Form::label('preview_iframe', __('Preview iFrame'), ['class' => 'form-label']) }}
                            {{ Form::textarea('preview_iframe', null, ['class' => 'form-control font-style', 'rows' => 2]) }}
                        </div>

                        <div class="form-group col-md-6 col-12" id="video_url_div">
                            {{ Form::label('video_url', __('Video URL'), ['class' => 'form-label']) }}
                            {{ Form::text('video_url', null, ['class' => 'form-control font-style']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab">
        <div class="col-lg-12 col-12">
            <h5 class="mb-3">{{ __('About product') }}</h5>
            <div class="card border">
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::label('description', __('Product Description'), ['class' => 'form-label']) }}
                        {{ Form::textarea('description', null, ['class' => 'form-control  summernote-simple-product', 'rows' => 1, 'placeholder' => __('Product Description'), 'id' => 'description']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('specification', __('Product Specification'), ['class' => 'form-label']) }}
                        {{ Form::textarea('specification', null, ['class' => 'form-control  summernote-simple-product', 'rows' => 1, 'placeholder' => __('Product Specification'), 'id' => 'specification']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('detail', __('Product Details'), ['class' => 'form-label']) }}
                        {{ Form::textarea('detail', null, ['class' => 'form-control  summernote-simple-product', 'rows' => 1, 'placeholder' => __('Product Details'), 'id' => 'detail']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab">

        <div class="col-12">
            <h5 class="mb-3">Main Informations</h5>
            <div class="card border">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-lg-3 col-md-6  col-12">
                            {!! Form::label('', __('Display Variants'), ['class' => 'form-label']) !!}
                            <div class="form-check form-switch">
                                <input type="hidden" name="variant_product" value="0">
                                <input type="checkbox" class="form-check-input enable_product_variant"
                                    name="variant_product" id="enable_product_variant" value="1">
                                <label class="form-check-label" for="enable_product_variant"></label>
                            </div>
                        </div>
                        <div class="form-group col-lg-3 col-md-6  col-12">
                            {!! Form::label('', __('Trending'), ['class' => 'form-label']) !!}
                            <div class="form-check form-switch">
                                <input type="hidden" name="trending" value="0">
                                <input type="checkbox" class="form-check-input" name="trending" id="trending_product"
                                    value="1">
                                <label class="form-check-label" for="trending_product"></label>
                            </div>
                        </div>
                        <div class="form-group col-lg-3 col-md-6  col-12">
                            {!! Form::label('', __('Display Product'), ['class' => 'form-label']) !!}
                            <div class="form-check form-switch">
                                <input type="hidden" name="status" value="1">
                                <input type="checkbox" checked class="form-check-input" name="status" id="status"
                                    value="1">
                                <label class="form-check-label" for="status"></label>
                            </div>
                        </div>
                        <div class="form-group col-lg-3 col-md-6  col-12">
                            {!! Form::label('', __('Custom  Field'), ['class' => 'form-label']) !!}
                            <div class="form-check form-switch">
                                <input type="hidden" name="custom_field_status" value="0">
                                <input type="checkbox" class="form-check-input" name="custom_field_status"
                                    id="enable_custom_field" value="1">
                                <label class="form-check-label" for="enable_custom_field"></label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card border">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    {!! Form::label('', __('Product Attribute'), ['class' => 'form-label']) !!}
                                    {!! Form::select('attribute_id[]', $ProductAttribute, null, [
                                        'class' => 'form-control attribute_option attribute_option_data',
                                        'multiple' => 'multiple',
                                        'data-role' => 'tagsinput',
                                        'id' => 'attribute_id',
                                    ]) !!}
                                    <small>{{ __('Choose Existing Attribute') }}</small>
                                </div>
                                <div class="attribute_options" id="attribute_options">
                                </div>
                                <div class="attribute_combination" id="attribute_combination">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12" style="display: none;" id="custom_value">
                    <div class="card border">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <div id="custom_field_repeater_basic">
                                        <!--begin::Form group-->
                                        <div class="form-group">
                                            <div data-repeater-list="custom_field_repeater_basic">
                                                <div data-repeater-item>
                                                    <div class="form-group row">
                                                        <div class="col-md-6">
                                                            {!! Form::label('', __('Custom Field'), ['class' => 'form-label']) !!}
                                                            {!! Form::text('custom_field', null, ['class' => 'form-control']) !!}
                                                        </div>
                                                        <div class="col-md-6">
                                                            {!! Form::label('', __('Custom Value'), ['class' => 'form-label']) !!}
                                                            {!! Form::text('custom_value', null, ['id' => 'answer', 'rows' => 2, 'class' => 'form-control']) !!}

                                                        </div>
                                                        <div class="col-md-4">
                                                            <a href="javascript:;" data-repeater-delete
                                                                class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                                <i class="la la-trash-o"></i>Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Form group-->

                                        <!--begin::Form group-->
                                        <div class="form-group mt-2 mb-0">
                                            <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                                <i class="ti ti-plus"></i>
                                            </a>
                                        </div>
                                        <!--end::Form group-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div style="overflow:auto;">
        <div style="float:right;">
            <button type="button" id="nextBtn" class="btn  btn-primary"
                onclick="nextPrev(1)">{{ __('Next') }}</button>
            <button type="button" class="btn  btn-primary d-none" id="submit-all">{{ __('Save') }}</button>

            <button type="button" class="btn  btn-primary" id="prevBtn"
                onclick="nextPrev(-1)">{{ __('Previous') }}</button>



        </div>
    </div>

    <!-- Circles which indicates the steps of the form: -->
    <div style="text-align:center;margin-top:40px;">
        <span class="step"></span>
        <span class="step"></span>
        <span class="step"></span>
        <span class="step"></span>
    </div>

    {!! Form::close() !!}
@endsection

<style>
    #choice_form {
        background-color: #ffffff;
        margin: 30px auto;
        padding: 20px;
        width: 70%;
        min-width: 300px;
    }

    .choices__item{
        margin-right: 15px;
    }
    /* Style the input fields */
    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #ffdddd;
    }

    /* Hide all steps by default: */
    .tab {
        display: none;
    }

    /* Make circles that indicate the steps of the form: */
    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #86e86e;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    /* Mark the active step: */
    .step.active {
        opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
        background-color: #04AA6D;
    }
</style>
@push('custom-script')
    <script>
        let counter = 0;

        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form ...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            // ... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
                document.getElementById("nextBtn").style.display = 'inline';

            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").style.display = 'none';
                document.getElementById("submit-all").classList.remove("d-none");

            } else {
                document.getElementById("nextBtn").style.display = 'inline';

                document.getElementById("nextBtn").innerHTML = "Next";
            }
            // ... and run a function that displays the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            var x = document.getElementsByClassName("tab");
            if (n == 1 && !validateForm()) return false;
            x[currentTab].style.display = "none";
            currentTab = currentTab + n;
            if (currentTab >= x.length) {
                document.getElementById("choice_form").submit();
                return false;
            }
            if (n == 1) {
                counter++;
            } else {
                counter--;
            }


            showTab(currentTab);
        }

        function validateForm() {

            var x, y, i, valid = true;
            if (counter == 1 && document.getElementById("dropzone-1") && document.getElementById("dropzone-1").files
                .length == 0) {
                valid = false;
                alert('الرجاء اختيار صورة المنتج')
            }
            if (counter == 1 && document.getElementById("cover_image") && document.getElementById("cover_image").files
                .length === 0) {
                valid = false;

                alert('الرجاء اختيار صورة الغلاف')

            }
        

            var currentTab = 0; // Define currentTab variable
            x = document.getElementsByClassName("tab");

            y = x[currentTab].getElementsByTagName("input");

            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {

                if (
                    y[i].name == "name" ||
                    y[i].name == "price" ||
                    y[i].name == "sale_price"

                ) {
                    // If a field is empty...
                    if (y[i].value.trim() == "") {
                        // add an "invalid" class to the field:
                        y[i].className += " invalid";
                        // and set the current valid status to false:
                        valid = false;
                    }
                }

                // If dropdown, check if a value is selected

            }
            var maincategory = document.getElementById("maincategory_id");
            var subsubcategory = document.getElementById("subsubcategory_id");

            if ((maincategory && maincategory.value === '') || (subsubcategory && subsubcategory.value === '')) {

                valid = false;
                alert("الرجاء تعبئة الفئة الرئيسية والفرعية");
            }


            // If the valid status is true, mark the step as finished and valid:
            if (valid) {
                document.getElementsByClassName("step")[currentTab].className += " finish";
            }

            return valid; // return the valid status
        }




        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class to the current step:
            x[n].className += " active";
        }
    </script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/repeater.js') }}"></script>
    <script src="{{ asset('assets/css/summernote/summernote-bs4.js') }}"></script>


    <script>
        $(document).ready(function() {



            // tag

            // main-cat
            $('#maincategory_id').on('change', function() {
                console.log("#$$$$$$#")
                var id = $(this).val();
                var val = $('.subcategory_id_div').attr('data_val');
                var data = {
                    id: id,
                    val: val
                }
                $.ajax({
                    url: "{{ route('get.product.subcategory') }}",
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        console.log(
                            "@@@@@@@@@@@@"
                            )
                        console.log(response)
                        $.each(response, function(key, value) {
                            $("#subcategory-dropdown").append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                        var val = $('.subcategory_id_div').attr('data_val', 0);
                        $('.subcategory_id_div span').html(response.html);
                        comman_function();
                    },


                })
            });

            var link = $('.slug').val();
            var focusOutCalled = false;

            // permalink
            $('.name').on('focusout', function() {
                var nameval = $(this).val();
                console.log("dsfds", nameval);
                if (!focusOutCalled) {
                    $.ajax({
                        url: "{{ route('get.slug') }}",
                        type: 'POST',
                        data: {
                            'value': nameval
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('.slug').val(response.result);
                            $('.parmalink').show();
                            focusOutCalled = true;
                        },
                        error: function(error) {}
                    });
                }


            });

            //stock

            $('#options').hide();
            $('.stock_stats').show();
            $(document).on("change", "#enable_product_stock", function() {
                $('#options').prop('checked', false);
                if ($(this).prop('checked')) {
                    $('.stock_stats').hide();
                    $('#options').show();
                } else {
                    $('.stock_stats').show();
                    $('#options').hide();
                }

            });

            // prview video

            $("#preview_type").change(function() {
                $(this).find("option:selected").each(function() {
                    var optionValue = $(this).attr("value");
                    if (optionValue == 'Video Url') {

                        $('#video_url_div').removeClass('d-none');
                        $('#video_url_div').addClass('d-block');

                        $('#preview-iframe-div').addClass('d-none');
                        $('#preview-iframe-div').removeClass('d-block');

                        $('#preview-video-div').addClass('d-none');
                        $('#preview-video-div').removeClass('d-block');

                    } else if (optionValue == 'iFrame') {
                        $('#video_url_div').addClass('d-none');
                        $('#video_url_div').removeClass('d-block');

                        $('#preview-iframe-div').removeClass('d-none');
                        $('#preview-iframe-div').addClass('d-block');

                        $('#preview-video-div').addClass('d-none');
                        $('#preview-video-div').removeClass('d-block');

                    } else if (optionValue == 'Video File') {

                        $('#video_url_div').addClass('d-none');
                        $('#video_url_div').removeClass('d-block');

                        $('#preview-iframe-div').addClass('d-none');
                        $('#preview-iframe-div').removeClass('d-block');

                        $('#preview-video-div').removeClass('d-none');
                        $('#preview-video-div').addClass('d-block');
                    }
                });
            }).change();
        });
        $(document).on("change", "#enable_custom_field", function() {
            $('#custom_value').hide();
            $('.custom_field').hide();
            if ($(this).prop('checked') == true) {
                $('#custom_value').show();
                $('.custom_field').show();
            }
        });
        $('#custom_field_repeater_basic').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo'
            },

            show: function() {
                $(this).slideDown();
            },

            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    </script>


    <script>
        // display variant hide show
        $(document).on("change", "#enable_product_variant", function() {
            $('.product-price-div').show();
            $('.product-weight').show();
            $('#use_for_variation').addClass("d-none");
            $('.product_price input').prop('readOnly', false);
            $('.product_discount_amount input').prop('readOnly', false);
            $('.product_discount_type input').prop('readOnly', false);
            $('.attribute_options_datas').hide();

            if ($(this).prop('checked') == true) {
                $('.product-price-div').hide();
                $('.product-weight').hide();
                $("#use_for_variation").removeClass("d-none");
                $('.product_price input').prop('readOnly', true);
                $('.product_discount_amount input').prop('readOnly', true);
                $('.product_discount_type input').prop('readOnly', true);
                $('.attribute_options_datas').show();

            }
        });



        $(document).on('change', '#attribute_id', function() {
            $('#attribute_options').html("<h3 class='d-none'>Variation</h3>");
            var selectedOptions = $("#attribute_id option:selected");
            selectedOptions.each(function() {
                var optionValue = $(this).val();
                var optionText = $(this).text();

                add_more_choice_option(optionValue, optionText);

                var attribute_id = optionValue;

                $.ajax({
                    url: '{{ route('products.attribute_option') }}',
                    type: 'POST',
                    data: {
                        "attribute_id": attribute_id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        $('.attribute').empty();
                        $.each(data, function(key, value) {
                            $('.attribute_options_datas').empty();

                            $(".attribute").append(
                                '<option class="option-item" value="' + key + '">' +
                                value + '</option>');
                        });

                        var multipleCancelButton = new Choices('#attribute' + attribute_id, {
                            removeItemButton: true,
                        });
                    }
                });
            });
        });



        function update_attribute() {
            var variant_val = $('.attribute option:selected')
                .toArray().map(item => item.text).join();
            if (variant_val == '') {
                return;
            }
            $.ajax({
                type: "POST",
                url: '{{ route('products.attribute_combination') }}',
                data: $('#choice_form').serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
                success: function(data) {
                    $('#attribute_combination').html(data);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }


                }
            });
        }
        $(document).on("change", ".attribute_option_data", function() {
            var inputValue = $('.attribute_option_data').val();
            if (inputValue != []) {
                var b = $('.attribute_option_data').closest('.parent-clase').find('.input-options');
                var enableVariationValue = b.data('enable-variation');
                var dataid = b.attr('data-id');
                $('.enable_variation_' + dataid).on('change', function() {
                    if ($('.enable_variation_' + dataid).prop('checked') == true) {
                        $('.attribute_combination').show();
                        console.log('sdrdsdfg');
                        update_attribute();
                    } else {
                        $('.attribute_options_datas').empty();
                    }
                });
                if ($('.enable_variation_' + dataid).prop('checked') != true) {
                    $('.attribute_options_datas').empty();
                }

            }
        });

        $(document).on("change", "#enable_product_variant", function() {
            if ($(this).prop('checked') == true) {

                $(document).on('change', '.attribute', function() {
                    var b = $(this).closest('.parent-clase').find('.input-options');
                    var enableVariationValue = b.data('enable-variation');
                    var dataid = b.attr('data-id');
                    if ($('.enable_variation_' + dataid).prop('checked') == true) {
                        update_attribute();
                    }
                });
                var b = $(this).closest('.parent-clase').find('.input-options');
                var enableVariationValue = b.data('enable-variation');
                var dataid = b.attr('data-id');
                console.log(dataid);
                if ($('.enable_variation_' + dataid).prop('checked') == true) {
                    update_attribute();
                }
            }
        });

        $(document).on('change', '#attribute_id', function() {
            $('#attribute_options').html("<h3 class='d-none'>Variation</h3>");
            $.each($("#attribute_id option:selected"), function() {
                add_more_choice_option($(this).val(), $(this).text());
            });
        });
    </script>

    {{-- Dropzones  --}}
    <script>
        var Dropzones = function() {
            var e = $('[data-toggle="dropzone1"]'),
                t = $(".dz-preview");

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            e.length && (Dropzone.autoDiscover = !1, e.each(function() {
                var e, a, n, o, i;
                e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                    url: "{{ route('product.store') }}",
                    headers: {
                        'x-csrf-token': CSRF_TOKEN,
                    },
                    thumbnailWidth: null,
                    thumbnailHeight: null,
                    previewsContainer: n.get(0),
                    previewTemplate: n.html(),
                    maxFiles: 10,
                    parallelUploads: 10,
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    acceptedFiles: a ? null : "image/*",
                    success: function(file, response) {
                        if (response.flag == "success") {
                            show_toastr('success', response.msg, 'success');
                            window.location.href = "{{ route('product.create') }}";
                        } else {
                            show_toastr('Error', response.msg, 'error');
                        }
                    },
                    error: function(file, response) {
                        // Dropzones.removeFile(file);
                        if (response.error) {
                            show_toastr('Error', response.error, 'error');
                        } else {
                            show_toastr('Error', response, 'error');
                        }
                    },
                    init: function() {
                        var myDropzone = this;

                        this.on("addedfile", function(e) {
                            !a && o && this.removeFile(o), o = e
                        })
                    }
                }, n.html(""), e.dropzone(i)
            }))
        }()

        $('#submit-all').on('click', function() {
            $('#submit-all').attr('disabled', true);
            var fd = new FormData();

            var file = document.getElementById('cover_image').files[0];
            var preview_video = document.getElementById('preview_video').files[0];

            var downloadable_product = document.getElementById('downloadable_product').files[0];
            var inputs = $(".downloadable_product_variant");
            var downloadable_product_variant = [];
            for (var i = 0; i < inputs.length; i++) {
                var files = $(inputs[i]).prop('files');
                var dataValue = $(inputs[i]).data('value');
                downloadable_product_variant.push({
                    key: dataValue,
                    file: files
                });
                if (files && files.length > 0) {
                    for (var j = 0; j < files.length; j++) {
                        fd.append(dataValue, files[j]);
                    }
                }
            }
            // Append Summernote content to FormData
            fd.append('description', $('#description').summernote('code'));
            fd.append('specification', $('#specification').summernote('code'));
            fd.append('detail', $('#detail').summernote('code'));
            if (file) {
                fd.append('cover_image', file);
            }
            if (preview_video) {
                fd.append('preview_video', preview_video);
            }
            if (downloadable_product) {
                fd.append('downloadable_product', downloadable_product);
            }



            var files = $('[data-toggle="dropzone1"]').get(0).dropzone.getAcceptedFiles();
            $.each(files, function(key, file) {
                fd.append('product_image[' + key + ']', $('[data-toggle="dropzone1"]')[0].dropzone
                    .getAcceptedFiles()[key]); // attach dropzone image element
            });

            var other_data = $('#choice_form').serializeArray();

            $.each(other_data, function(key, input) {
                fd.append(input.name, input.value);
            });
            $.ajax({
                url: "{{ route('product.store') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fd,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function(data) {
                    if (data.flag == "success") {
                        $('#submit-all').attr('disabled', true);

                        window.location.href = "{{ route('product.index') }}" + '?id=1';

                    } else {
                        show_toastr('Error', data.msg, 'error');
                        $('#submit-all').attr('disabled', false);
                    }
                },
                error: function(data) {
                    $('#submit-all').attr('disabled', false);
                    // Dropzones.removeFile(file);
                    if (data.error) {
                        show_toastr('Error', data.error, 'error');
                    } else {
                        show_toastr('Error', data, 'error');
                    }
                },
            });
        });
        $('.select2').select2({
            tags: true,
            createTag: function(params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        });
    </script>
@endpush
