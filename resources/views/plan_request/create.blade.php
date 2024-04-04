{{ Form::open(['route' => 'tax.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('Name'), ['class' => 'form-label']) !!}
        {!! Form::text('tax_name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group  col-md-6">
        {!! Form::label('', __('Type'), ['class' => 'form-label']) !!}
        {!! Form::select('tax_type', ['percentage' => '%', 'flat' => 'flat'], null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('', __('Amount'), ['class' => 'form-label']) !!}
        {!! Form::number('tax_amount', 0, ['class' => 'form-control', 'min' => '0', 'step' => '0.10']) !!}
    </div>


    <div class="form-group col-md-4">
        {!! Form::label('', __('Status'), ['class' => 'form-label']) !!}
        <div class="form-check form-switch">
            <input type="hidden" name="status" value="0">
            {!! Form::checkbox('status', 1, 1, ["class" => "form-check-input input-primary", "id" => "customCheckdef1"]) !!}
            <label class="form-check-label" for="customCheckdef1"></label>
        </div>
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Create" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
