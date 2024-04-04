
{{Form::model($faq, array('route' => array('faqs.update', $faq->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}
<div id="kt_docs_repeater_basic" >
    <div class="form-group col-md-12">
        {!! Form::label('', __('Topic'), ['class' => 'form-label']) !!}
        {!! Form::text('topic', null, ['class' => 'form-control']) !!}
    </div>
    <!--begin::Form group-->
    <div class="form-group">
        <div data-repeater-list="kt_docs_repeater_basic">
            @if(!empty($faq->description))
            @foreach (json_decode($faq->description, true) as $item)
                <div data-repeater-item>
                    <div class="form-group row">
                        <div class="col-md-12">
                            {!! Form::label('', __('Question'), ['class' => 'form-label']) !!}
                            {!! Form::text('question', $item['question'], ['class' => 'form-control']) !!}
                        </div>
                        <div class="col-md-12">
                            {!! Form::label('', __('Answer'), ['class' => 'form-label']) !!}
                            {!! Form::textarea('answer', $item['answer'], ['id' => 'answer', 'rows' => 4, 'class'=>'form-control']) !!}

                        </div>
                        <div class="col-md-4">
                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                <i class="la la-trash-o"></i>Delete
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            @else
            <div data-repeater-item>
                <div class="form-group row">
                    <div class="col-md-12">
                        {!! Form::label('', __('Question'), ['class' => 'form-label']) !!}
                        {!! Form::text('question', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-md-12">
                        {!! Form::label('', __('Answer'), ['class' => 'form-label']) !!}
                        {!! Form::textarea('answer', null, ['id' => 'answer', 'rows' => 4, 'class'=>'form-control']) !!}

                    </div>
                    <div class="col-md-4">
                        <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                            <i class="la la-trash-o"></i>Delete
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!--end::Form group-->

    <!--begin::Form group-->
    <div class="form-group mt-5">
        <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
            <i class="ti ti-plus"></i>
        </a>
    </div>
    <!--end::Form group-->
</div>
<!--end::Repeater-->
<div class="modal-footer pb-0">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="Update" class="btn btn-primary">
</div>
{!! Form::close() !!}


<script src="{{asset('js/jquery-ui.min.js')}}"></script>
<script src="{{asset('js/repeater.js')}}"></script>


<script>
$('#kt_docs_repeater_basic').repeater({
    initEmpty: false,

    defaultValues: {
        'text-input': 'foo'
    },

    show: function () {
        $(this).slideDown();
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
});
</script>

