{{ Form::model($deliveryBoy, ['route' => ['deliveryboy.update', $deliveryBoy->id],'method' => 'PUT']) }}
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
            {{ Form::label('Contact',__('Contact'),array('class'=>'form-label')) }}
            {{ Form::number('contact',null,array('class'=>'form-control','placeholder'=>__('Enter Contact Number'),'required'=>'required')) }}
        </div>
        <input type="hidden" name="type" value="deliveryboy">

        <div class="form-group col-12 d-flex justify-content-end col-form-label">
            <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
        </div>
    </div>
</form>
