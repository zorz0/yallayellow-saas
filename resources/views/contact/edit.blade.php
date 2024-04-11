{{Form::model($contact, array('route' => array('contacts.update', $contact->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::label('', __('الاسم الأول'), ['class' => 'form-label']) !!}
        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('الاسم الأخير'), ['class' => 'form-label']) !!}
        {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('البريد الإلكتروني'), ['class' => 'form-label']) !!}
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('رقم الاتصال'), ['class' => 'form-label']) !!}
        {!! Form::text('contact', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('الموضوع'), ['class' => 'form-label']) !!}
        {!! Form::text('subject', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-12">
        {!! Form::label('', __('الوصف'), ['class' => 'form-label']) !!}
        {!! Form::textarea('description', null, ['rows' => 4, 'class'=>'form-control']) !!}
    </div>

    <div class="modal-footer pb-0">
        <input type="button" value="إلغاء" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="تحديث" class="btn btn-primary">
    </div>
</div>
{!! Form::close() !!}
