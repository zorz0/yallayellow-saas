<div class="row list_height_css">
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Address') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::text('billing_info[delivery_address]', !empty($DeliveryAddress->address) ? $DeliveryAddress->address : '', ["class" => "form-control ", "placeholder" => "address", "required" => true]) !!}
            @if(auth('customers')->user())
            {!! Form::hidden('billing_info[billing_address]', !empty($DeliveryAddress->address) ? $DeliveryAddress->address : '') !!}
            {!! Form::hidden('billing_info[billing_company_name]', null) !!}
            @endif
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Country') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::select('billing_info[delivery_country]', $country_option, !empty($DeliveryAddress->country_id) ? $DeliveryAddress->country_id : '', ['class' => 'form-control country_change', 'placeholder' => 'Select country', 'required' => true]) !!}
            @if(auth('customers')->user())
            {!! Form::hidden('billing_info[billing_country]', !empty($DeliveryAddress->country_id) ? $DeliveryAddress->country_id : '') !!}
            {!! Form::hidden('billing_info[billing_country_name]', !empty($DeliveryAddress) ? $DeliveryAddress->getCountryNameAttribute() : '' ) !!}
            @endif
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Region') }} / {{ __('State') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::select('billing_info[delivery_state]', [], null, ['class' => 'form-control state_name state_chage','placeholder' => 'Select State','required' => true,'data-select' => !empty($DeliveryAddress->state) ? $DeliveryAddress->state : '']) !!}
            @if(auth('customers')->user())
            {!! Form::hidden('billing_info[billing_state]', !empty($DeliveryAddress->state) ? $DeliveryAddress->state : '') !!}
            {!! Form::hidden('billing_info[billing_state_name]', !empty($DeliveryAddress) ? $DeliveryAddress->getStateNameAttribute() : '' ) !!}
            @endif
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('City') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::select('billing_info[delivery_city]', [], null, ['class' => 'form-control city_change','placeholder' => 'Select city','required' => true,'data-select' =>  !empty($DeliveryAddress->city) ? $DeliveryAddress->city : '' ]) !!}
            @if(auth('customers')->user())
            {!! Form::hidden('billing_info[billing_city]',  !empty($DeliveryAddress->city) ? $DeliveryAddress->city : '' ) !!}
            {!! Form::hidden('billing_info[billing_city_name]', !empty($DeliveryAddress) ? $DeliveryAddress->getCityNameAttribute() : '' ) !!}
            @endif
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label>{{ __('Post Code') }}<sup aria-hidden="true">*</sup>:</label>
            {!! Form::number('billing_info[delivery_postcode]', !empty($DeliveryAddress->postcode) ? $DeliveryAddress->postcode : ' ' , ["class" => "form-control", "placeholder" => "post code", "required" => true]) !!}
            @if(auth('customers')->user())
            {!! Form::hidden('billing_info[billing_postecode]', !empty($DeliveryAddress->postcode) ? $DeliveryAddress->postcode : ' ' ) !!}
            @endif
        </div>
    </div>
</div>
