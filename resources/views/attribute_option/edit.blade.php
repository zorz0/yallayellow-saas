{{ Form::model($productAttributeOption, array('route' => array('product-attribute-option.update', $productAttributeOption->id), 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('terms', __('Name'), ['class' => 'form-label']) }}
        {{ Form::text('terms', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
    </div>
    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Update" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
