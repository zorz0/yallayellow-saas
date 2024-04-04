    {{ Form::model($user, ['route' => ['users.update', $user->id],'method' => 'PUT']) }}

    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('name',__('Name'),array('class'=>'form-label'))}}
            {{Form::text('name',$user->name,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('Email',__('Email'),array('class'=>'form-label')) }}
            {{ Form::email('email',$user->email,array('class'=>'form-control','placeholder'=>__('Enter Email'),'required'=>'required')) }}
        </div>
        @if (auth()->user()->type != 'super admin')
        <div class="form-group col-md-12">
            {{ Form::label('User Role',__('User Role'),array('class'=>'form-label')) }}
            {{ Form::select('role',$roles,$user->roles,array('class'=>'form-control','placeholder'=>__('Select Role'),'required'=>'required')) }}
        </div>
        @endif
        <div class="form-group col-12 d-flex justify-content-end col-form-label">
            <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
        </div>
    </div>
</form>
