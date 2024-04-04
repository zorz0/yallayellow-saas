
{{Form::model($question, array('route' => array('product-question.update', $question->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}
    <div class="form-group col-md-12">
        {!! Form::label('', __('Product'), ['class' => 'form-label']) !!}
        {!! Form::text('Product', $question->product->name, ['class' => 'form-control' ,'disabled']) !!}
    </div>
    <div class="form-group">
        <div class="form-group row">
            <div class="form-group col-md-12">
                {!! Form::label('', __('Question'), ['class' => 'form-label']) !!}
                {!! Form::text('question', null, ['class' => 'form-control']) !!}
            </div>
            <div class="col-md-12">
                {!! Form::label('', __('Answer'), ['class' => 'form-label']) !!}
                {!! Form::textarea('answers', null, [ 'rows' => 4, 'class'=>'form-control' ]) !!}

            </div>
        </div>
    </div>
<div class="modal-footer pb-0">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="Update" class="btn btn-primary">
</div>
{!! Form::close() !!}


