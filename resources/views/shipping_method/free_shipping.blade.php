@php
    $Free_shipping = App\Models\ShippingMethod::freeShipping();
@endphp
{{Form::model($shippingMethod, array('route' => array('free-shipping.update', $shippingMethod->id))) }}

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
        {!! Form::text('method_name', null, ['class' => 'form-control','disabled']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Free shipping requires'), ['class' => 'form-label']) !!}
        {!! Form::select('shipping_requires', $Free_shipping, null, ['class' => 'form-control','id' => 'FreeShipping','placeholder' => 'Select Free Shipping Requires']) !!}
    </div>
    <div class="form-group col-md-12" id="mini_cost">
        {!! Form::label('', __('Minimum order amount'), ['class' => 'form-label']) !!}
        {!! Form::number('cost', null, ['class' => 'form-control','min'=>'0','step'=>'0.01']) !!}
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Update" class="btn btn-primary">
    </div>
</div>

{!! Form::close() !!}

<script>
    $(document).ready(function () {
        $("#FreeShipping").trigger('change');
    });

    $(document).on('change', '#FreeShipping', function()
    {
        var conceptName = $('#FreeShipping').find(":selected").val();
        if(conceptName == '1')
        {
            $('#mini_cost').addClass('d-none');
        }
        if(conceptName == '2')
        {
            $('#mini_cost').addClass('d-none');
        }
        if(conceptName == '3')
        {
            $('#mini_cost').addClass('d-block');
            $('#mini_cost').removeClass('d-none');
        }
        if(conceptName == '4')
        {
            $('#mini_cost').addClass('d-block');
            $('#mini_cost').removeClass('d-none');
        }
        if(conceptName == '5')
        {
            $('#mini_cost').addClass('d-block');
            $('#mini_cost').removeClass('d-none');
        }
    });
</script>
