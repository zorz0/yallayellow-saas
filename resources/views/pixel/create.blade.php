@php
    use App\Models\Utility;
@endphp

{{ Form::open(['method'=>'POST','route'=>array('pixel-setting.store',$store_settings->slug)]) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('Platform', __('Platform'), ['class' => 'col-form-label']) }}
            {{ Form::select('platform', Utility::pixel_plateforms(),null, ['class' => 'form-control', 'placeholder'=>'Please Select','required'=>'required']) }}
        </div>
        <div class="form-group">
            {{  Form::label('Pixel Id',__('Pixel Id'),['class'=>'col-form-label'])  }}
            {{ Form::text('pixel_id','',array('class'=>'form-control','placeholder'=>'Enter Pixel Id','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="form-group col-12 d-flex justify-content-end col-form-label">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
