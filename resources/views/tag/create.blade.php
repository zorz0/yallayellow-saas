{{ Form::open(['route' => 'tag.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('name',__('Tag Name'),array('class'=>'form-label'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Tag Name' )))}}
        </div>
    </div>


<div class="form-group col-12 d-flex justify-content-end col-form-label">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
</div>
{{Form::close()}}
