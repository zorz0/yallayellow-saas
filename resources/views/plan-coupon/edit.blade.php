{{ Form::model($planCoupon, ['route' => ['plan-coupon.update', $planCoupon->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('discount', __('Discount'), ['class' => 'col-form-label']) }}
        {{ Form::number('discount', null, ['class' => 'form-control', 'step' => '0.01', 'placeholder' => __('Enter Discount'), 'required' => 'required']) }}
        <span class="small">{{ __('Note: Discount in Percentage') }}</span>
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('limit', __('Limit'), ['class' => 'col-form-label']) }}
        {{ Form::number('limit', null, ['class' => 'form-control', 'placeholder' => __('Enter Limit'), 'required' => 'required']) }}
    </div>
    <div class="form-group col-md-12" id="auto">
        {{ Form::label('limit', __('Code'), ['class' => 'col-form-label']) }}
        <div class="input-group">
            {{ Form::text('code', null, ['class' => 'form-control', 'id' => 'auto-code', 'required' => 'required']) }}
            <button class="btn btn-outline-primary" type="button" id="code-generate"><i
                    class="fa fa-history pr-1"></i>{{ __(' Generate') }}</button>
        </div>
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Update" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
