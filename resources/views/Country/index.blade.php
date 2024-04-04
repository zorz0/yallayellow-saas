@extends('layouts.app')

@section('page-title', __('Country Settings'))

@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Country Settings') }}</li>
@endsection

@php
    $theme_name = !empty(env('DATA_INSERT_APP_THEME')) ? env('DATA_INSERT_APP_THEME') : APP_THEME();
    $theme_logo = \App\Models\Utility::GetValueByName('theme_logo', $theme_name);
    $theme_logo = get_file($theme_logo, APP_THEME());

    $invoice_logo = \App\Models\Utility::GetValueByName('invoice_logo', $theme_name);
    $invoice_logo = get_file($invoice_logo, APP_THEME());

    $theme_favicon = \App\Models\Utility::GetValueByName('theme_favicon', $theme_name);
    $theme_favicon = get_file($theme_favicon, APP_THEME());

    $metaimage = \App\Models\Utility::GetValueByName('metaimage', $theme_name);
    $metaimage = get_file($metaimage, APP_THEME());

    $enable_storelink = \App\Models\Utility::GetValueByName('enable_storelink', $theme_name);
    $enable_domain = \App\Models\Utility::GetValueByName('enable_domain', $theme_name);
    $domains = \App\Models\Utility::GetValueByName('domains', $theme_name);
    $enable_subdomain = \App\Models\Utility::GetValueByName('enable_subdomain', $theme_name);
    $subdomain = \App\Models\Utility::GetValueByName('subdomain', $theme_name);
    $Additional_notes = \App\Models\Utility::GetValueByName('additional_notes', $theme_name);
@endphp

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card sticky-top " style="top:60px">
                <div class="list-group list-group-flush theme-set-tab" id="useradd-sidenav">
                    <ul class="nav nav-pills w-100 gap-3" id="pills-tab" role="tablist">
                        <li class="nav-item " role="presentation">
                            <a href="#Country_Setting"
                                class="nav-link active  list-group-item list-group-item-action border-0 rounded-1 text-center f-w-600"
                                id="pills-country-tab" data-bs-toggle="pill" data-bs-target="#pills-country" type="button"
                                role="tab" aria-controls="pills-country" aria-selected="true">
                                {{ __('Country Settings') }}
                            </a>

                        </li>
                        <li class="nav-item " role="presentation">
                            <a href="#State_Setting"
                                class="nav-link   list-group-item list-group-item-action border-0 rounded-1 text-center f-w-600"
                                id="pills-state-tab" data-bs-toggle="pill" data-bs-target="#pills-state" type="button"
                                role="tab" aria-controls="pills-state" aria-selected="true">
                                {{ __('State Settings') }}
                            </a>

                        </li>
                        <li class="nav-item " role="presentation">
                            <a href="#City_Setting"
                                class="nav-link   list-group-item list-group-item-action border-0 rounded-1 text-center f-w-600"
                                id="pills-city-tab" data-bs-toggle="pill" data-bs-target="#pills-city" type="button"
                                role="tab" aria-controls="pills-city" aria-selected="true">
                                {{ __('City Settings') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-country" role="tabpanel"
                    aria-labelledby="pills-country-tab">
                    <div id="Country_Setting">
                        <div class="col-md-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5 class="mt-2">{{ __('Country Settings') }}</h5>
                                        </div>
                                        <div class="col-6 text-end ">
                                            <div class="">
                                                <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true"
                                                    data-size="md" data-title="{{ __('Create Country') }}"
                                                    data-url="{{ route('countries.create') }}" data-toggle="tooltip"
                                                    title="{{ __('Create Country') }}"
                                                    data-bs-original-title="{{ __('Create Country') }}">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 g-0">
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table mb-0 dataTable-5 ">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Name') }}</th>
                                                        <th class="text-end">{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($countries as $country)
                                                        <tr>
                                                            <td>{{ $country->name }}</td>
                                                            <td class="text-end">
                                                                <button class="btn btn-sm btn-primary me-2"
                                                                    data-url="{{ route('countries.edit', $country->id) }}"
                                                                    data-size="md" data-ajax-popup="true"
                                                                    data-title="{{ __('Edit Country') }}">
                                                                    <i class="ti ti-pencil py-1" data-bs-toggle="tooltip"
                                                                        title="edit"></i>
                                                                </button>

                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['countries.destroy', $country->id],
                                                                    'class' => 'd-inline',
                                                                ]) !!}
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger show_confirm">
                                                                    <i class="ti ti-trash text-white py-1"
                                                                        data-bs-toggle="tooltip" title="Delete"></i>
                                                                </button>
                                                                {!! Form::close() !!}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-state" role="tabpanel" aria-labelledby="pills-state-tab">
                    <div id="State_Setting">
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="mt-2">{{ __('State Settings') }}</h5>
                                    </div>
                                    <div class="col-6 text-end row">
                                        <form method="GET" action="{{ route('countries.index') }}" accept-charset="UTF-8"
                                            id="customer_submit"> @csrf
                                            @csrf
                                            <div class=" d-flex align-items-center justify-content-end">
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">
                                                    <div class="btn-box">

                                                        {{ Form::label('country', __('Country: '), ['class' => 'col-form-label mr-2']) }}
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                                    <div class="btn-box">

                                                        {!! Form::select('country_id', $get_country, $country_id, [
                                                            'class' => 'form-control country',
                                                            'name' => 'country_id',
                                                        ]) !!}

                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                                    <a href="#" class="btn btn-sm btn-primary"
                                                        data-ajax-popup="true" data-size="md"
                                                        data-title="{{ __('Create State') }}"
                                                        data-url="{{ route('state.create') }}" data-toggle="tooltip"
                                                        title="{{ __('Create State') }}"
                                                        data-bs-original-title="{{ __('Create State') }}">
                                                        <i class="ti ti-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table mb-0  dataTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th class="text-center">{{ __('country') }}</th>
                                                <th class="text-end">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="font-style" id="stateDataTable">
                                            @foreach ($states as $stat)
                                                <tr>
                                                    <td>{{ ucwords($stat['name']) }}</td>
                                                    <td class="text-center">{{ $stat->country->name }}</td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-primary me-2"
                                                            data-url="{{ route('state.edit', $stat['id']) }}"
                                                            data-size="md" data-ajax-popup="true"
                                                            data-title="{{ __('Edit State') }}">
                                                            <i class="ti ti-pencil py-1" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="edit"></i>
                                                        </button>

                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['state.destroy', $stat['id']],
                                                            'class' => 'd-inline',
                                                        ]) !!}
                                                        <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                            <i class="ti ti-trash text-white py-1"
                                                                data-bs-toggle="tooltip" title="Delete"></i>
                                                        </button>
                                                        {!! Form::close() !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-city" role="tabpanel" aria-labelledby="pills-city-tab">
                    <div id="City_Setting">
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="mt-2">{{ __('City Settings') }}</h5>
                                    </div>
                                    <div class="col-6 text-end row">
                                        <form method="GET" action="{{ route('countries.index') }}"
                                            accept-charset="UTF-8" id="state_filter_submit"> @csrf
                                            <div class=" d-flex align-items-center justify-content-end">
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">
                                                    <div class="btn-box">

                                                        {{ Form::label('city', __('State: '), ['class' => 'col-form-label mr-2']) }}
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                                    <div class="btn-box">

                                                        {!! Form::select('state_id', $get_state, $state_id, [
                                                            'class' => 'form-control State',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                                    <a href="#" class="btn btn-sm btn-primary"
                                                        data-ajax-popup="true" data-size="md"
                                                        data-title="{{ __('Create City') }}"
                                                        data-url="{{ route('city.create') }}" data-toggle="tooltip"
                                                        title="{{ __('Create City') }}"
                                                        data-bs-original-title="{{ __('Create City') }}">
                                                        <i class="ti ti-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table dataTable-6">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th class="text-center">{{ __('state') }}</th>
                                                <th class="text-end">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="font-style" id="cityDataTable">
                                            @foreach ($cities as $cit)
                                                <tr>
                                                    <td>{{ $cit->name }}</td>
                                                    <td class="text-center">{{ $cit->state->name }}</td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-primary me-2"
                                                            data-url="{{ route('city.edit', $cit['id']) }}"
                                                            data-size="md" data-ajax-popup="true"
                                                            data-title="{{ __('Edit City') }}">
                                                            <i class="ti ti-pencil py-1" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="edit"></i>
                                                        </button>

                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['city.destroy', $cit['id']],
                                                            'class' => 'd-inline',
                                                        ]) !!}
                                                        <button type="button" class="btn btn-sm btn-danger show_confirm">
                                                            <i class="ti ti-trash text-white py-1"
                                                                data-bs-toggle="tooltip" title="Delete"></i>
                                                        </button>
                                                        {!! Form::close() !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-script')


    @if (\Auth::user()->type == 'super admin')
        <script type="text/javascript">
            $(document).on("change", '.country', function() {
                var country = $(this).val();

                $.ajax({
                    url: '{{ route('get.country') }}',
                    type: 'POST',
                    headers: {
                        // 'X-CSRF-TOKEN': jQuery('#token').val()
                    },
                    data: {
                        'country': country,
                        "_token": "{{ csrf_token() }}",
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data);
                        $('#stateDataTable').html('');
                        $('#stateDataTable').html(data);
                    }
                });
            });



            $(document).on("change", '.State', function() {
                var state = $(this).val();

                $.ajax({
                    url: '{{ route('get.state') }}',
                    type: 'POST',
                    headers: {
                        // 'X-CSRF-TOKEN': jQuery('#token').val()
                    },
                    data: {
                        'state': state,
                        "_token": "{{ csrf_token() }}",
                    },
                    cache: false,
                    success: function(data) {
                        console.log(data);
                        $('#cityDataTable').html('');
                        $('#cityDataTable').html(data);
                    }
                });
            });
            $(document).on("click", 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]',
                function() {

                    $.ajax({
                        url: "{{ route('get.country') }}",
                        type: "POST",
                        success: function(result) {

                            $.each(result.data, function(key, value) {
                                setTimeout(function() {
                                    $("#state_country").append('<option value="' + value.id +
                                        '" >' + value.name + '</option>');
                                }, 1000);

                            });


                        },
                    });



                });




            $(document).on('change', '#country_id', function() {
                var country_id = $(this).val();
                getState(country_id);
            });

            function getState(country_id) {
                var data = {
                    "country_id": country_id,
                    "_token": "{{ csrf_token() }}",
                }

                $.ajax({
                    url: '{{ route('getcitystate') }}',
                    method: 'POST',
                    data: data,
                    success: function(data) {
                        $('#state_id').empty();
                        $('#state_id').append('<option value="" disabled>{{ __('Select State') }}</option>');

                        $.each(data, function(key, value) {
                            $('#state_id').append('<option value="' + key + '">' + value + '</option>');
                        });
                        $('#state_id').val('');
                    }
                });
            }


            $('#country').on('change', function() {
                $('#customer_submit').trigger('submit');
                return false;
            })
            $('#state_filter').on('change', function() {
                $('#state_filter_submit').trigger('submit');
                return false;
            })
        </script>
    @endif


@endpush
