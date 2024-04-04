@extends('layouts.app')

@section('page-title', __('Add New Theme'))

@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('module.index') }}">{{ __('Add-on Manager') }}</a></li>    
    <li class="breadcrumb-item">{{ __('Add New Theme') }}</li>
@endsection

<link rel="stylesheet" href="{{ asset('public/assets/css/plugins/dropzone.css') }}" type="text/css" />

@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-10 col-xxl-8">
            <div class="card">
                <div class="card-body">
                    <SECTION>
                        <DIV id="dropzone">
                            <FORM class="dropzone needsclick" id="demo-upload">
                                <DIV class="dz-message needsclick">
                                    {{ __('Drop files here or click to upload and install.')}}<BR>
                                </DIV>
                            </FORM>
                        </DIV>
                    </SECTION>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('custom-script')
    <script src="{{ asset('public/assets/js/plugins/dropzone.js') }}"></script>

    <script>
        // Dropzone has been added as a global variable.
        Dropzone.autoDiscover = false;
        var dropzone = new Dropzone('#demo-upload', {
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            maxFilesize: 200,
            acceptedFiles: '.zip',
            url: "{{ route('addon.theme') }}",
            success: function(file, response) {
                if (response.status == 'success')
                {
                    show_toastr('Success', response.message, 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('module.index') }}";
                    }, 1000);
                }
                else
                {
                    show_toastr('Error', response.message, 'error');
                    setTimeout(() => {
                        window.location.href = "{{ route('module.index') }}";
                    }, 1000);
                }
            }
        });
        dropzone.on('sending', function(file, xhr, formData) {
            formData.append('_token', "{{ csrf_token() }}");
        });
    </script>
@endpush