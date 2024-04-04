{{Form::model($slug, array('route' => array('product_question' ,$slug), 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Question'), ['class' => 'form-label']) !!}
        {!! Form::textarea('question', null, ['class' => 'form-control','placeholder' => "Write your question here..." ,'rows' => "3"]) !!}
    </div>

    <input type="hidden" name="product_id" value="{{ $id }}">

    <div class="form-footer">
        <input type="submit" value="Submit" class="btn btn-primary bg-primary">
    </div>
</div>
{!! Form::close() !!}
