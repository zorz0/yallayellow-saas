{{ Form::open(['route' => 'product-brand.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

<div class="row">
    <div class="form-group  col-md-12">
        {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Image'), ['class' => 'form-label']) !!}
        <label for="upload_image" class="image-upload bg-primary pointer w-100">
            <i class="ti ti-upload px-1"></i> {{ __('Choose file here') }}
        </label>
        <input type="file" name="logo" id="upload_image" class="d-none">
    </div>
    
    <div class="form-group col-md-4">
        {!! Form::label('', __('Status'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" class="form-check-input status" name="status"
                id="status" value="1">
            <label class="form-check-label" for="status"></label>
        </div>
    </div>
    <div class="form-group col-md-4">
        {!! Form::label('', __('Is Popular ?'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="is_popular" value="0">
            <input type="checkbox" name="is_popular" class="form-check-input input-primary" id="customCheckdef1trending" value="1">
            <label class="form-check-label" for="customCheckdef1trending"></label>
        </div>
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Create" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}

