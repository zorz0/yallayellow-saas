{{Form::model($plan, array('route' => array('plan.update', $plan->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}

@if (auth()->user()->type == 'super admin' && isset($setting['chatgpt_key']))
<div class="d-flex justify-content-end">
        <a href="#" class="btn btn-primary me-2 ai-btn" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['plan']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
        </a>
    </div>
@endif

<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control','placeholder' => __('Enter Name'),'required' => 'required']) }}
    </div>
    @if ($plan->id != 1)
    <div class="form-group col-md-6">
        {{Form::label('price',__('Price') ,array('class'=>'col-form-label')) }}
        {{Form::number('price',null,array('class'=>'form-control','step'=>'0.01','placeholder'=>__('Enter price'),'required'=>'required'))}}
    </div>
    @endif
    <div class="form-group col-md-6">
        {{ Form::label('duration', __('Duration'), ['class' => 'col-form-label']) }}
        {!! Form::select('duration', $arrDuration, null, ['class' => 'form-control ', 'required' => 'required']) !!}
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('max_stores', __('Maximum Store'), ['class' => 'col-form-label']) }}
            {{ Form::number('max_stores', null, ['class' => 'form-control','id' => 'max_stores','placeholder' => __('Enter Max Store'),'required' => 'required']) }}
            <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('max_products', __('Maximum Products Per Store'), ['class' => 'col-form-label']) }}
            {{ Form::number('max_products', null, ['class' => 'form-control','id' => 'max_products','placeholder' => __('Enter Max Products'),'required' => 'required']) }}
            <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('max_users', __('Maximum Users Per Store'), ['class' => 'col-form-label']) }}
            {{ Form::number('max_users', null, ['class' => 'form-control','id' => 'max_users','placeholder' => __('Enter Max User'),'required' => 'required']) }}
            <span><small>{{ __("Note: '-1' for Unlimited") }}</small></span>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('storage_limit', __('Storage Limit'), ['class' => 'col-form-label']) }}
            <div class ='input-group'>
                {{ Form::number('storage_limit', null, ['class' => 'form-control','id' => 'storage_limit','placeholder' => __('Enter Storage Limit'),'required' => 'required' ,'min' => '0']) }}
                <span class="input-group-text bg-transparent">{{ __('MB')}}</span>
            </div>
            <span><small>{{ __("Note: upload size ( In MB)") }}</small></span>
        </div>
    </div>

    <div class="col-6">
        <div class="custom-control form-switch pt-2 ps-0">
            <input type="checkbox" class="form-check-input" name="enable_domain" id="enable_domain"
            {{ $plan['enable_domain'] == 'on' ? 'checked=checked' : '' }}>
            <label class="custom-control-label form-check-label"
                for="enable_domain">{{ __('Enable Domain') }}</label>
        </div>
    </div>
    <div class="col-6">
        <div class="custom-control form-switch pt-2 ps-0">
            <input type="checkbox" class="form-check-input" name="enable_subdomain" id="enable_subdomain"
            {{ $plan['enable_subdomain'] == 'on' ? 'checked=checked' : '' }}>
            <label class="custom-control-label form-check-label"
                for="enable_subdomain">{{ __('Enable Sub Domain') }}</label>
        </div>
    </div>

    <div class="col-6">
        <div class="custom-control form-switch pt-2 ps-0">
            <input type="checkbox" class="form-check-input" name="enable_chatgpt" id="enable_chatgpt"
            {{ $plan['enable_chatgpt'] == 'on' ? 'checked=checked' : '' }}>
            <label class="custom-control-label form-check-label"
                for="enable_chatgpt">{{ __('Enable Chatgpt') }}</label>
        </div>
    </div>

    <div class="col-6">
        <div class="custom-control form-switch pt-2 ps-0">
            <input type="checkbox" class="form-check-input" name="pwa_store" id="pwa_store"
            {{ $plan['pwa_store'] == 'on' ? 'checked=checked' : '' }}>
        <label class="custom-control-label form-check-label"
            for="pwa_store">{{ __('Progressive Web App (PWA)') }}</label>
        </div>
    </div>
    <div class="col-6">
        <div class="custom-control form-switch pt-2 ps-0">
            <input type="checkbox" class="form-check-input" name="shipping_method" id="shipping_method"
            {{ $plan['shipping_method'] == 'on' ? 'checked=checked' : '' }}>
            <label class="custom-control-label form-check-label"
                for="shipping_method">{{ __('Shipping Method') }}</label>
        </div>
    </div>
    <div class="col-6">
        <div class="custom-control form-switch pt-2 ps-0">
            <input type="checkbox" class="form-check-input" name="enable_tax" id="enable_tax"
            {{ $plan['enable_tax'] == 'on' ? 'checked=checked' : '' }}>
            <label class="custom-control-label form-check-label"
                for="enable_tax">{{ __('Enable Taxes') }}</label>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-check-label" for="trial"></label>
        <div class="form-group">
            <label for="trial" class="form-label">{{ __('Trial is enable(on/off)') }}</label>
            <div class="form-check form-switch custom-switch-v1 float-end">
                <input type="checkbox" name="trial" class="form-check-input input-primary pointer" value="1" id="trial"  {{ $plan['trial'] == 1 ? 'checked="checked"' : '' }}>
                <label class="form-check-label" for="trial"></label>
            </div>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="form-group plan_div  {{ $plan['trial'] == 1 ? 'd-block' : 'd-none' }}">
            {{ Form::label('trial_days', __('Trial Days'), ['class' => 'form-label']) }}
            {{ Form::number('trial_days',null, ['class' => 'form-control trial_days','placeholder' => __('Enter Trial days'),'step' => '1','min'=>'1']) }}
        </div>
    </div>
    
    <div class="horizontal mt-3">
        <div class="verticals twelve">
            <div class="form-group col-md-6">
              {{ Form::label('Select Themes', __('Select Themes'),['class'=>'form-label']) }}
            </div>
            <ul class="uploaded-pics row">
                @foreach($theme as $key => $v)
                    @php
                        if(in_array($v->theme_id,$plan->getThemes())){
                            $checked = 'checked';
                        }else{
                            $checked = '';
                        }
                    @endphp
                <li class="col-xxl-4 col-md-6">
                    <input type="checkbox" id="checkthis{{$v->theme_id}}" value="{{$v->theme_id}}" name="themes[]" {{$checked}}/>
                    <label for="checkthis{{$v->theme_id}}"><img src="{{ asset('themes/'.$v->theme_id.'/theme_img/img_1.png') }}" /></label>
                </li>
               @endforeach
            </ul>
            {{-- <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('themes', __('themes'), ['class' => 'col-form-label']) }}
                    {{ Form::textarea('themes',$theme, ['class' => 'form-control','id' => 'description','rows' => 2,'placeholder' => __('Enter Description')]) }}
                </div>
            </div> --}}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control','id' => 'description','rows' => 2,'placeholder' => __('Enter Description')]) }}
        </div>
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Update" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}

