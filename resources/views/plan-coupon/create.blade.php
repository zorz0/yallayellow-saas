{{ Form::open(['route' => 'plan-coupon.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
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
    <div class="form-group col-12 d-flex justify-content-end col-form-label">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn btn-primary ms-2">
    </div>
</div>
{!! Form::close() !!}