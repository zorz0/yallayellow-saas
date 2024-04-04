{{ Form::open(['route' => ['support.ticket.store',$slug], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        <div class="form-group col-md-6 col-12">
            {!! Form::label('', __('Title'), ['class' => 'form-label']) !!}
            {!! Form::text('title', null, ['class' => 'form-control','placeholder' => 'Enter Ticket Title']) !!}
        </div>
        <div class="form-group col-md-6 col-12 col-12">
            {{ Form::label('order_id', __('Select Order'),['class'=>'form-label']) }}
            {{ Form::select('order_id', $orders,null, array('class' => 'form-control select','required'=>'required','placeholder'=>'Select Order')) }}
        </div>
        <div class="form-group  col-md-6 col-12 col-12">
            <label class="d-block form-label">{{ __('Attachments') }}:<small>({{__('You can select multiple files')}})</small></label>
            <div class="input-group file-select-set mb-3">
                <input type="text" class="form-control p-2 rounded" readonly="" placeholder="Choose file" id="attachments">
                <input type="file" class="form-control file-opc {{ $errors->has('attachments') ? ' is-invalid' : '' }}" name="attachments[]" id="file" aria-label="Upload" multiple=""  data-filename="multiple_file_selection" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                <label class="input-group-text file-opc bg-primary" for="attachments"><i
                        class="ti ti-circle-plus"></i>{{__('Browse')}}</label>
                <img src="" id="blah" width="20%"/>
                <div class="invalid-feedback">
                    {{ $errors->first('attachments.*') }}
                </div>
            </div>
            <p class="multiple_file_selection mx-4"></p>
        </div>
        <div class="form-group col-12">
            {!! Form::label('', __('Description'), ['class' => 'form-label']) !!}
            <div class="form-group mt-3">
                <textarea class="pc-tinymce-2" name="description" id="description" rows="4"></textarea>
            </div>
        </div>
        <div class="modal-footer pb-0">
            <button type="button" value="Cancel" class="btn-secondary cancel-btn" data-bs-dismiss="modal">{{__('Cancel')}}</button>
            <button type="submit" value="Create" class="btn continue-btn">{{__('Create')}}</button>
        </div>
    </div>
{!! Form::close() !!}

<script src="{{ asset('assets/css/summernote/summernote-bs4.js') }}"></script>
<script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>
<script>
    if ($(".pc-tinymce-2").length) {
        tinymce.init({
            selector: '.pc-tinymce-2',
            toolbar: 'link image',
            plugins: 'image code',
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.onload = function() {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            },
            height: "400",
            content_style: 'body { font-family: "Inter", sans-serif; }'
        });
    }
    document.addEventListener('focusin', function(e) {
        if (e.target.closest('.tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !== null) {
            e.stopImmediatePropagation();
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200,
        });
    });
</script>
