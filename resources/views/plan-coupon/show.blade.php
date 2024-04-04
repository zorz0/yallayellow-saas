<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
        {{ Form::text('name', $planCoupon->name, ['class' => 'form-control', 'placeholder' => __('Enter Name') , 'readonly' => 'readonly']) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('discount', __('Discount'), ['class' => 'col-form-label']) }}
        {{ Form::number('discount', $planCoupon->discount, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter Discount'), 'readonly' => 'readonly']) }}
        <span class="small">{{ __('Note: Discount in Percentage') }}</span>
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('limit', __('Limit'), ['class' => 'col-form-label']) }}
        {{ Form::number('limit', $planCoupon->limit, ['class' => 'form-control', 'placeholder' => __('Enter Limit'), 'readonly' => 'readonly']) }}
    </div>
    <div class="form-group col-md-12" id="auto">
        {{ Form::label('limit', __('Code'), ['class' => 'col-form-label']) }}
        <div class="input-group">
            {{ Form::text('code',  $planCoupon->code, ['class' => 'form-control', 'readonly' => 'readonly']) }}
        </div>
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    </div>
</div>
