<form method="post" action="{{ route('users.store') }}">
    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('name',__('Name'),array('class'=>'form-label'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('Email',__('Email'),array('class'=>'form-label')) }}
            {{ Form::email('email',null,array('class'=>'form-control','placeholder'=>__('Enter Email'),'required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('Password',__('Password'),array('class'=>'form-label')) }}
            {{ Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter Password'),'required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('User Role',__('User Role'),array('class'=>'form-label')) }}
            {{ Form::select('role',$roles,null,array('class'=>'form-control','placeholder'=>__('Select Role'),'required'=>'required')) }}
        </div>
        <div class="form-group col-12 d-flex justify-content-end col-form-label">
            <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
        </div>
    </div>
</form>
