{{Form::model($coupon, array('route' => array('coupon.update', $coupon->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}

@if (isset(auth()->user()->currentPlan) && auth()->user()->currentPlan->enable_chatgpt == 'on')
<div class="d-flex justify-content-end mb-1">
    <a href="#" class="btn btn-primary me-2 ai-btn" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['coupan']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
        <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
    </a>
</div>
@endif
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
        {{ Form::text('coupon_name', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
    </div>

    <div class="form-group col-md-12">
        {!! Form::label('', __('Type'), ['class' => 'form-label']) !!}
        {!! Form::select('coupon_type', ['percentage' => 'Percentage', 'flat' => 'Flat' , 'fixed product discount' =>'Fixed product discount'], null, ['class' => 'form-control','id' => 'category']) !!}
    </div>
    <div class="form-group col-md-6 format">
        {!! Form::label('applied_product', __('Products'), ['class' => 'form-label']) !!}
        {!! Form::select('applied_product[]', $product, $applied_product, ['class' => 'form-control select2','id' => 'applied_product', 'multiple','data-role'=>'tagsinput']) !!}
    </div>

    <div class="form-group col-md-6 format">
        {!! Form::label('exclude_product', __('Exclude products'), ['class' => 'form-label']) !!}
        {!! Form::select('exclude_product[]', $product, $exclude_product, ['class' => 'form-control select2','id' => 'exclude_product', 'multiple','data-role'=>'tagsinput']) !!}
    </div>

    <div class="form-group col-md-6 format">
        {!! Form::label('applied_categories', __('Product categories'), ['class' => 'form-label']) !!}
        {!! Form::select('applied_categories[]', $category, $applied_categories,['class' => 'form-control select2','id' => 'applied_categories', 'multiple','data-role'=>'tagsinput']) !!}
    </div>
    <div class="form-group col-md-6 format">
        {!! Form::label('exclude_categories', __('Exclude categories'), ['class' => 'form-label']) !!}
        {!! Form::select('exclude_categories[]', $category, $exclude_categories,['class' => 'form-control select2','id' => 'exclude_categories', 'multiple','data-role'=>'tagsinput']) !!}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('Minimum spend', __('Minimum spend'), ['class' => 'form-label']) }}
        {{ Form::number('minimum_spend', null, ['class' => 'form-control']) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('Maximum spend', __('Maximum spend'), ['class' => 'form-label']) }}
        {{ Form::number('maximum_spend', null, ['class' => 'form-control']) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('discount', __('Discount'), ['class' => 'form-label']) }}
        {{ Form::number('discount_amount', null, ['class' => 'form-control', 'required' => 'required', 'min' => '1', 'max' => '100', 'step' => '0.01']) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('limit', __('Limit'), ['class' => 'form-label']) }}
        {{ Form::number('coupon_limit', null, ['class' => 'form-control', 'min' => '1', 'required' => 'required']) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('limit', __('Usage limit per user'), ['class' => 'form-label']) }}
        {{ Form::number('coupon_limit_user', null, ['class' => 'form-control', 'min' => '1']) }}
    </div>
    <div class="form-group col-md-6 format">
        {{ Form::label('limit', __('Limit usage to X items'), ['class' => 'form-label']) }}
        {{ Form::number('coupon_limit_x_item', null, ['class' => 'form-control', 'min' => '1']) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('coupon_expiry_date', __('Expiry Date'), ['class' => 'form-label']) }}
        {{ Form::date('coupon_expiry_date', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select date']) }}
    </div>
    <div class="form-group">
        <div class="d-flex radio-check">
            <div class="form-check m-1">
                <input type="radio" id="manual_code" value="manual" name="icon-input" class="form-check-input code"
                    checked="checked">
                <label class="form-check-label" for="manual_code">{{ __('Manual') }}</label>
            </div>
            <div class="form-check m-1">
                <input type="radio" id="auto_code" value="auto" name="icon-input" class="form-check-input code">
                <label class="form-check-label" for="auto_code">{{ __('Auto Generate') }}</label>
            </div>
        </div>
    </div>
    <div class="form-group col-md-12">
        <div class="row">
            <div class="col-md-10" id="code_text">
                {{ Form::text('coupon_code', null, ['class' => 'form-control', 'required' => 'required', 'id' => 'auto-code', 'placeholder' => __('Generate Code')]) }}
            </div>
            <div class="col-md-2" id="autogerate_button">
                <a href="#" class="btn btn-primary" id="code-generate"><i class="ti ti-history"></i></a>
            </div>
        </div>
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('', __('Status'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="status" value="0">
            {!! Form::checkbox('status', 1, null, ["class" => "form-check-input input-primary", "id" => "customCheckdef1"]) !!}
            <label class="form-check-label" for="customCheckdef1"></label>
        </div>
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('', __('Exclude sale items'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="sale_items" value="0">
            {!! Form::checkbox('sale_items', 1, null, ["class" => "form-check-input input-primary", "id" => "customCheckdef1"]) !!}
            <label class="form-check-label" for="customCheckdef1"></label>
        </div>
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('', __('Free Shipping Status'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="free_shipping_coupon" value="0">
            {!! Form::checkbox('free_shipping_coupon', 1, null, ["class" => "form-check-input input-primary", "id" => "customCheckdef2"]) !!}
            <label class="form-check-label" for="customCheckdef2"></label>
        </div>
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Update" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
<script>
    if ($("#category").val() != "fixed product discount") {
        $('.format').hide();
    }
</script>
<script>
    $(document).ready(function () {
    $("#category").change(function () {
        var ff = $("#category").val()
  if ($("#category").val() == "fixed product discount") {
    $('.format').show();
  }else{
    $('.format').hide();
  }    });
  });

</script>
