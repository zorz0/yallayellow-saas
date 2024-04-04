@extends('layouts.app')

@section('page-title', __('Reply Ticket'))


@section('action-button')
    <div class=" text-end d-flex all-button-box justify-content-md-end justify-content-center">
        <a href="#" onclick="saveAsPDF();" id="download-buttons" class="btn btn-sm btn-primary btn-icon d-flex align-items-center"
            data-bs-toggle="tooltip" data-bs-placement="top" title="Print" aria-label="Print">
            <i class="ti ti-printer" style="font-size:20px"></i>
        </a>
        @php
            $btn_class = 'btn-info';
            if($ticket->status == 'open') {
                $btn_class = 'btn-info';
            } else {
                $btn_class = 'btn-success';
            }
        @endphp
        <div class="btn-group mx-1" id="deliver_btn">
            <button class="btn {{ $btn_class }} {{ in_array($ticket->status, ['open', 'In Progress','solved']) ? 'dropdown-toggle' : '' }} " type="button"
                {{ in_array($ticket->status, ['Solved']) ? 'data-bs-toggle="dropdown"' : "" }}
                    data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="true">{{ __('Status') }} : {{ $ticket->status }}
            </button>
            @if(in_array($ticket->status, ['open', 'In Progress','solved']))
                <div class="dropdown-menu" data-popper-placement="bottom-start">
                    <h6 class="dropdown-header">{{ __('Set Ticket status') }}</h6>
                    @if($ticket->status == 'open')
                        <a class="dropdown-item ticket_status" href="#" data-value="In Progress">
                            <i class="fa fa-check text-success"></i> {{ __('In Progress') }}
                        </a>
                        <a class="dropdown-item ticket_status" href="#" data-value="Solved">
                            <i class="fa fa-check text-success"></i> {{ __('Solved') }}
                        </a>
                    @endif
                    @if($ticket->status == 'In Progress')
                        <a class="dropdown-item ticket_status" href="#" data-value="Solved">
                            <i class="fa fa-check text-success"></i> {{ __('Solved') }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('support_ticket.index') }}">{{ __('Support Ticket') }}</a></li>
    <li class="breadcrumb-item"> {{ __('Reply Ticket') }} - {{ $ticket->ticket_id }}</li>
@endsection
@php
    $logo = get_file('/');
@endphp
@section('content')
    <div class="row mt-3">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printableArea">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h6>
                            <span class="text-left">
                                {{ $ticket->UserData->name }} <small>({{ $ticket->created_at->diffForHumans() }})</small>
                                <span class="d-block"><small></small></span>
                            </span>
                        </h6>
                        <span class="text-right">
                            {{ __('Status') }} : <span class="badge rounded @if($ticket->status == 'New Ticket') bg-secondary @elseif($ticket->status == 'In Progress')bg-info  @elseif($ticket->status == 'On Hold') bg-warning @elseif($ticket->status == 'Closed') bg-primary @else bg-success @endif">{{ __($ticket->status) }}</span>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <span><b>{{ $ticket->title }}</b></span>
                        <p>{!! $ticket->description !!}</p>
                    </div>
                    @php $attachments = json_decode($ticket->attachment); @endphp

                        @if (!empty($attachments) && count($attachments))
                            <div class="m-1">
                                <h6>{{ __('Attachments') }} :</h6>

                                <ul class="list-group list-group-flush">

                                    @foreach ($attachments as $index => $attachment)
                                        <li class="list-group-item px-0">
                                            {{ $attachment }} <a download="" href="{{ get_file($attachment,APP_THEME()) }}" class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i class="fas fa-download ms-2"></i></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                </div>
            </div>

            @foreach ($ticket->conversions as $conversion)
                <div class="card">
                    <div class="card-header">
                        <h6>{{ $conversion->replyBy()->name }}
                            <small>({{ $conversion->created_at->diffForHumans() }})</small>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div>{!! $conversion->description !!}</div>
                        @php $attachments = json_decode($conversion->attachments); @endphp
                        @if (!empty($attachments) && count($attachments))
                            <div class="m-1">
                                <h6>{{ __('Attachments') }} :</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach ($attachments as $index => $attachment)
                                    <li class="list-group-item px-0">
                                        {{ $attachment }}<a download="" href="{{ get_file($attachment,APP_THEME()) }}" class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i class="fa fa-download ms-2"></i></a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @if($ticket->status != 'Closed')
                    <div class="card">
                        <div class="card-header row">
                            <div class="col-md-4">
                                <h6>{{ __('Add Reply') }}</h6>
                            </div>
                        </div>

                        <form method="post" action="{{ route('conversion.store', $ticket->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group col-md-7">
                                    {!! Form::label('', __('Description'), ['class' => 'form-label']) !!}
                                        <div class="form-group mt-3">
                                            {!! Form::textarea('reply_description', null, ['id' => 'description', 'rows' => 8, 'class'=>'pc-tinymce-2']) !!}
                                        </div>
                                </div>
                                <div class="form-group file-group mb-5 col-md-7">
                                    <label class="require form-label">{{ __('Attachments') }}</label>
                                    <label class="form-label"><small>({{ __('You can select multiple files') }})</small></label>
                                    <div class="choose-file form-group">
                                        <label for="file" class="form-label d-block">
                                            <div>{{ __('Choose File Here') }}</div>

                                            <input type="file" name="reply_attachments[]" id="file" class="form-control mb-2 {{ $errors->has('reply_attachments') ? ' is-invalid' : '' }}" multiple=""  data-filename="multiple_reply_file_selection" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                            <img src="" id="blah" width="20%"/>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('reply_attachments.*') }}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <p class="multiple_reply_file_selection"></p>
                                <div class="text-end">
                                    <button class="btn btn-primary btn-block mt-2 btn-submit" type="submit">{{ __('Submit') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-script')
    <script src="{{ asset('js/html2pdf.bundle.min.js') }}{{ '?' . time() }}"></script>
    <script>
        var filename = $('#filesname').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();

        }

        function myFunction() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            show_toastr('Success', 'Link copied', 'success');
        }
    </script>
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

        $(document).on('click', '.ticket_status', function() {
            var status = $(this).attr('data-value');
                console.log(status);
            var data = {
                status: status,
                id: "{{ $ticket->id }}",
            }
            $.ajax({
                url: '{{ route('support_ticket.status.change', $ticket->id) }}',
                method: 'POST',
                data: data,
                context: this,
                success: function(data) {
                    if(data.status == 'error') {
                        show_toastr('{{ __('Error') }}', data.message, 'error')
                    } else {
                        show_toastr('{{ __('Success') }}', data.message, 'success')
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });
    </script>

@endpush
