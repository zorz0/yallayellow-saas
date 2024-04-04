{{ Form::open(['route' => 'review.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('User'), ['class' => 'form-label']) !!}
        {!! Form::select('user_id', $users, null, ['class' => 'form-control', 'data-role' => 'tagsinput', 'id' => 'user_id']) !!}
    </div>
    <div class="form-group  col-md-12">
        {!! Form::label('', __('Description'), ['class' => 'form-label']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control autogrow', 'rows' => '3']) !!}
    </div>
    <div class="form-group  col-md-6">
        {!! Form::label('', __('Category'), ['class' => 'form-label']) !!}
        {!! Form::select('category_id', $main_categorys, null, ['class' => 'form-control', 'data-role' => 'tagsinput', 'id' => 'category_id']) !!}
    </div>
    <div class="form-group  col-md-6 product_id_div" data_val='0'>
        {!! Form::label('', __('Product'), ['class' => 'form-label']) !!}
        <span>
            {!! Form::select('product_id', [], null, ['class' => 'form-control', 'data-role' => 'tagsinput', 'id' => 'product_id']) !!}
        </span>
    </div>
    <div class="form-group  col-md-6">
        {!! Form::label('', __('Rating'), ['class' => 'form-label']) !!}
        {!! Form::select('rating_no', ['1' => 1,'2' => 2,'3' => 3,'4' => 4,'5' => 5,], null, ['class' => 'form-control', 'data-role' => 'tagsinput', 'id' => 'rating_no']) !!}
    </div>

    <div class="form-group col-md-4">
        {!! Form::label('', __('Status'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" name="status" class="form-check-input input-primary" id="customCheckdef1" value="1"
                checked>
            <label class="form-check-label" for="customCheckdef1"></label>
        </div>
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Create" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
