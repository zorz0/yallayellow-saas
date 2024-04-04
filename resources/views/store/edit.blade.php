{{Form::model($store, array('route' => array('stores.update', $store->id), 'method' => 'PUT')) }}

@if((auth()->user()->type == 'super admin') && (!empty($setting['chatgpt_key'])))
    <div class="d-flex justify-content-end">
        <a href="#" class="btn btn-primary me-2 ai-btn" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['store']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
        </a>
    </div>
@endif
@if((auth()->user()->type == 'admin') && $plan && ($plan->enable_chatgpt == 'on'))
    <div class="d-flex justify-content-end">
        <a href="#" class="btn btn-primary me-2 ai-btn mb-3" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['store']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
        </a>
    </div>
@endif
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('name',__('Name'),array('class'=>'form-label'))}}
            {{Form::text('name',$user->name,array('class'=>'form-control','placeholder'=>__('Enter Name')))}}
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            {{Form::label('store_name',__('Store Name'),array('class'=>'form-label'))}}
            {{Form::text('storename',$store->name,array('class'=>'form-control','placeholder'=>__('Store Name')))}}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{Form::label('email',__('Email'),array('class'=>'form-label'))}}
            {{Form::email('email',null,array('class'=>'form-control','placeholder'=>__('Enter Email')))}}
        </div>
    </div>

</div>
<div class="form-group col-12 d-flex justify-content-end col-form-label">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
</div>
{{Form::close()}}
