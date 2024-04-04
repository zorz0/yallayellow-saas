{{ Form::open(['route' => 'faqs.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        <div class="form-group col-md-12">
            {!! Form::label('', __('Topic'), ['class' => 'form-label']) !!}
            {!! Form::text('topic', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Create" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}



