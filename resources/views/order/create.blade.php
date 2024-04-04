
{{ Form::open(['route' => 'coupon.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
        {{ Form::text('coupon_name', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
    </div>

    <div class="form-group col-md-6">
        {!! Form::label('', __('Type'), ['class' => 'form-label']) !!}
        {!! Form::select('coupon_type', ['percentage' => '%', 'flat' => 'flat'], null, ['class' => 'form-control']) !!}
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
                <input class="form-control" name="coupon_code" type="text" id="auto-code"
                    placeholder="{{ __('Generate Code') }}">
            </div>
            <div class="col-md-2" id="autogerate_button">
                <a href="#" class="btn btn-primary" id="code-generate"><i class="fas fa-history"></i></a>
            </div>
        </div>
    </div>

    <div class="form-group col-md-4">
        {!! Form::label('', __('Status'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="status" value="0">
            {!! Form::checkbox('status', 1, 1, ["class" => "form-check-input input-primary", "id" => "customCheckdef1"]) !!}
            <label class="form-check-label" for="customCheckdef1"></label>
        </div>
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Create" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
