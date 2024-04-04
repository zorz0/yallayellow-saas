{{ Form::open(['route' => 'taxes.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        <div class="modal-footer pb-0">
            <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
            <input type="submit" value="Create" class="btn btn-primary">
        </div>
    </div>
{!! Form::close() !!}
