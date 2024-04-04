
{{Form::model($shippingMethod, array('route' => array('shipping-method.update', $shippingMethod->id), 'method' => 'PUT')) }}

@php
    $value = $shippingMethod['product_cost'];
    $product_cost = json_decode($value, true);
@endphp
<div class="row">
    <div class="form-group col-md-6">
        {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
        {!! Form::text('method_name', null, ['class' => 'form-control','disabled']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('Cost'), ['class' => 'form-label']) !!}
        {!! Form::number('cost', null, ['class' => 'form-control','min'=>'0','step'=>'0.01']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('calculation_type', __('Calculation type'), ['class' => 'form-label']) !!}
        {{ Form::select('calculation_type',
        ['1'=>'Per class: Charge shipping for each shipping class individually',
        '2'=>'Per order: Charge shipping for the most expensive shipping class'],
        null, array('class' => 'form-control','required'=>'required','placeholder'=>'Select calculation type')) }}
    </div>
    @if($shippings_count != 0)
        @foreach ($shippings as $key => $shipping)
            <div class="form-group col-md-6">
                <label for="">"{{ $shipping }}" {{ __('shipping class cost')}}</label>
            </div>
            <div class="form-group col-md-6">
                @php
                    $cost = 0;
                    if(!empty($product_cost) && array_key_exists('product_cost',$product_cost) && array_key_exists($key,$product_cost['product_cost']))
                    {
                        $cost = $product_cost['product_cost'][$key];
                    }
                @endphp
                {{ Form::number('product_cost['.$key.']',$cost, ['class' => 'form-control','min'=>'0','step'=>'0.01']) }}
            </div>
        @endforeach
        <div class="form-group col-md-6">
            <label for="">{{ __('No shipping class cost')}}</label>
        </div>
        <div class="form-group col-md-6">
            {{ Form::number('product_no_cost',(!empty($product_cost) && array_key_exists('product_no_cost',$product_cost)) ? $product_cost["product_no_cost"] : 0, ['class' => 'form-control','step'=>'0.01']) }}
        </div>
    @endif
    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Update" class="btn btn-primary">
    </div>
</div>

{!! Form::close() !!}
