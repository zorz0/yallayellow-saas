
{{Form::model($contact, array('route' => array('contacts.update', $contact->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('First Name'), ['class' => 'form-label']) !!}
        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Last Name'), ['class' => 'form-label']) !!}
        {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Email'), ['class' => 'form-label']) !!}
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Contact'), ['class' => 'form-label']) !!}
        {!! Form::text('contact', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Subject'), ['class' => 'form-label']) !!}
        {!! Form::text('subject', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('Description'), ['class' => 'form-label']) !!}
        {!! Form::textarea('description', null, ['rows' => 4, 'class'=>'form-control']) !!}
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="Update" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}

