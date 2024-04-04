{{ Form::open(['route' => 'theme-section.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

<div class="row">
    <div class="form-group col-md-12">
        <input type="hidden" name="theme_id" value="{{ $theme_id }}">
        {!! Form::label('', __('Section Name'), ['class' => 'form-label']) !!}
        {!! Form::text('section_name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Create" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
