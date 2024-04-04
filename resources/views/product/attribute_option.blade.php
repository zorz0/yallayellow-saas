@if (count($Attribute_option) > 0)
{!! Form::select('attribute_id[]', $Attribute_option, null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'data-role' => 'tagsinput', 'id' => 'attribute_tag']) !!}
@endif
