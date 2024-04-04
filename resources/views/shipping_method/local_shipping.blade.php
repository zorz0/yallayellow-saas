
{{Form::model($shippingMethod, array('route' => array('local-shipping.update', $shippingMethod->id))) }}

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
        {!! Form::text('method_name', null, ['class' => 'form-control','disabled']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Cost'), ['class' => 'form-label']) !!}
        {!! Form::number('cost', null, ['class' => 'form-control','min'=>0,'step'=>'0.01']) !!}
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Update" class="btn btn-primary">
    </div>
</div>

{!! Form::close() !!}
