<div class="section-title addressbook-form-title">
    <h2>{{ __('Add address') }}</h2>
</div>
<div class="form-wrapper addressbook-form">
    {!! Form::model(auth('customers')->user(), ['route' => ['add.address',$slug],'method' => 'POST','enctype' =>
    'multipart/form-data']) !!}
    <input type="hidden" name="user_id" value="{{auth('customers')->user()->id ?? ''}}">
    <div class="form-container">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label>{{ __('Title') }}<sup aria-hidden="true">*</sup>:</label>
                    {!! Form::text('title', null, ["class"=>"form-control", "placeholder"=>"Company", "required"=>true])
                    !!}
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label>{{ __('Address') }}<sup aria-hidden="true">*</sup>:</label>
                    {!! Form::text('address', null, ["class"=>"form-control", "placeholder"=>"Addresss",
                    "required"=>true]) !!}
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group list_height_css">
                    <label>{{ __('Contry') }}<sup aria-hidden="true">*</sup>:</label>
                    {!! Form::select('country', $country_option, null, ["class"=>"form-control country_change",
                    "placeholder"=>"Select country", "required"=>true]) !!}
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group list_height_css state">
                    <label>{{ __('State') }}<sup aria-hidden="true" class="req">*</sup>:</label>
                    {!! Form::select('state', [], null, ["class"=>"form-control state_name state_chage",
                    "placeholder"=>"Select State", "required"=>true, 'data-select' => 0]) !!}
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group list_height_css city">
                    <label>{{ __('City') }}<sup aria-hidden="true" class="req">*</sup>:</label>
                    <div class="city">
                        {!! Form::select('city', [], null, ["class"=>"form-control city_change", "placeholder"=>"Select
                        city", "required"=>true, 'data-select' => 0]) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label>{{ __('Postcode') }}<sup aria-hidden="true">*</sup>:</label>
                    {!! Form::text('postcode', null, ["class"=>"form-control", "placeholder"=>"postcode",
                    "required"=>true]) !!}
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label>{{ __('Default Address') }}:</label>
                    <div class="row">
                        <div class="col-6">
                            <div class="radio-group">
                                <input type="radio" id="yes" name="default_address" value="1" checked />
                                <label for="yes">{{ __('Yes') }}</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="radio-group">
                                <input type="radio" id="no" name="default_address" value="0" />
                                <label for="no">{{ __('No') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-container">
        <div class="d-flex acc-back-btn-wrp align-items-center justify-content-end">
            <button class="btn continue-btn" type="submit">
                {{ __('Save') }}
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14" viewBox="0 0 35 14" fill="none">
                    <path d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>