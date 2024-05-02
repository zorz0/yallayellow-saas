<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{App::setLocale('ar')}}
<div class="container mt-5  align-items-center justify-content-center min-vh-100"
    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="row g-0 justify-content-center">
        <!-- TITLE -->
        <div class="col-lg-5 offset-lg-1 mx-0 px-0">
            <div id="title-container">
                <img class="covid-image" src="https://yallayellow.com/YalayaloLogo.jpg" alt="Yalayalo Logo">

                <h2>Yalla Yellow</h2>
                <h3></h3>
                <header>
                    <!-- Sidebar -->
                    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
                        <div class="position-sticky">
                            <div class="list-group list-group-flush mx-3 mt-4">
                                <a href="#"
                                    class="list-group-item list-group-item-action py-2 ripple navdata active"
                                    aria-current="true">

                                    <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>
                                        {!! Form::label('General data', __('General data'), ['class' => 'form-label']) !!}

                                    </span>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action py-2 ripple navdata "
                                    id="nav_2">
                                    <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>
                                        {!! Form::label('General data', __('Store data'), ['class' => 'form-label']) !!}

                                       </span>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action py-2 ripple navdata "
                                    id="nav_3">
                                    <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>
                                        {!! Form::label('Theme', __('Theme'), ['class' => 'form-label']) !!}


                                    </span>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action py-2 ripple  navdata "
                                    id="nav_4">
                                    <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>
                                        {!! Form::label('WhatsApp Number', __('WhatsApp Number'), ['class' => 'form-label']) !!}

                                      </span>
                                </a>
                            </div>
                       
                    </nav>

                </header>
                <p style="color: white">
                    {{ Form::label('default_language', __('st_1'), ['class' => ' col-form-label']) }}

                </p>
            </div>
        </div>
        <!-- FORMS -->
        <div class="col-lg-6 mx-0 px-0">
            <div class="progress">
                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50"
                    class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                    style="width: 0%"></div>
            </div>
            <div id="qbox-container">
                <form class="needs-validation" id="form-wrapper" method="post" action="{{ route('form.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div id="steps-container">
                        <div class="step">
                            <h4>{{ Form::label('default_language', __('Default Language'), ['class' => ' col-form-label']) }}
                            </h4>
                            <div class="form-check ps-0 q-box">
                                <div class="form-group col switch-width">
                                    <div class="changeLanguage">
                                        <select name="default_language" id="default_language" class="form-control"
                                            data-toggle="select">
                                            @foreach (\App\Models\Utility::languages() as $code => $language)
                                                <option @if (app()->getLocale() == $code) selected @endif
                                                    value="{{ $code }}">
                                                    {{ ucFirst(__($language)) }}
                                                </option>
                                                
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <h4>{{ Form::label('Your Country', __('Your Country'), ['class' => ' col-form-label']) }}
                                </h4>
                                <div class="form-group col mt-3">
                                    <?php
                                    // Load Arabic translations from ar.json file
                                    $ar_translations = json_decode(file_get_contents(resource_path('lang/ar.json')), true);
                                    ?>
                                    <select name="filter_country" id="filter_country" class="form-control">
                                        @foreach (\App\Models\Country::all() as $country)
                                            @if (array_key_exists($country->name, $ar_translations))
                                                <option value="{{ $country->id }}">{{ __($country->name) }}</option>
                                            @endif
                                            <option value="{{ $country->id }}">{{ __($country->name) }}</option>

                                        @endforeach

                                    </select>
                                </div>


                            </div>
                        </div>
                        <div class="step">

                            {!! Form::label('store_name', __('Store Name'), ['class' => 'form-label']) !!}


                            <input class="form-control" placeholder="" name="store_name" type="text"
                                id="store_name">

<br>


                            <h5> {{ Form::label('Store Logo', __('Store Logo'), ['class' => 'form-label']) }}</h5>


                            <div class="choose-files mt-1">
                                <label for="theme_logo">

                                    <input type="file" class="form-control file" name="theme_logo" id="theme_logo"
                                        data-filename="logo_update"
                                        onchange="showImage(event)">                                </label>
                            </div>
                            <img id="storeLogo" style="height: 200px; width: 250px;" class="mt-3 d-none" src="" alt="">
<br>
                            {!! Form::label('Phone number', __('Personal Contact Number'), ['class' => 'form-label']) !!}


                            <input class="form-control" placeholder="" name="personal_number" type="tel"
                                id="personal_number">

                            {!! Form::label('Support Phone number', __('Support Contact Number'), ['class' => 'form-label']) !!}


                            <input class="form-control" placeholder="" name="support_number" type="tel"
                                id="support_number">

                        </div>
                        <div class="step">
                            <h5>{{ __('Manage Themes') }}</h5>

                            <br>
                            @php
                                $user = auth()->user();
                                $store = App\Models\Store::find($user->current_store); // Change where to find
                                $plan = App\Models\Plan::find($user->plan_id);
                                $themes = ['grocery', 'babycare']; // Default themes
                                if (!empty($plan->themes)) {
                                    $themes = explode(',', $plan->themes);
                                }
                                $currentTheme = Qirolab\Theme\Theme::active();
                            @endphp

                            <div class="container">


                                <div class="row">
                                    @foreach ($themes as $key => $folder)
                                        <div class="col-md-6 col-lg-6 col-sm-6 ml-6">
                                            <label>
                                                <input type="radio" style="display: none" name="theme"
                                                    class="card-input-element" value="{{ Str::ucfirst($folder) }}"
                                                    {{ $key === 0 ? 'checked' : '' }} />
                                                <div class="card card-default card-input">
                                                    <img src="{{ asset('themes/' . $folder . '/theme_img/img_1.png') }}"
                                                        class="card-img-top" style="width: 100%;">
                                                    <div class="card-body">
                                                        <span
                                                            class="font-weight-bold">{{ Str::ucfirst($folder) }}</span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach





                                </div>

                            </div>



                        </div>


                        <div class="step">
                            <div class="col-lg-12 form-group">
                                {!! Form::label('WhatsApp Contact Number', __('WhatsApp Contact Number'), ['class' => 'form-label']) !!}

                                <br>
                                {!! Form::text(
                                    'whatsapp_contact_number',
                                    !empty($setting['whatsapp_contact_number']) ? $setting['whatsapp_contact_number'] : '',
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => 'XXXXXXXXXX',
                                    ],
                                ) !!}
                            </div>
                        </div>

                        <div id="success">
                            <div class="mt-5">
                                <h4>Success! We'll get back to you ASAP!</h4>
                                <p>Meanwhile, clean your hands often, use soap and water, or an alcohol-based hand rub,
                                    maintain a safe distance from anyone who is coughing or sneezing and always wear a
                                    mask when physical distancing is not possible.</p>
                                <a class="back-link" href="">Go back from the beginning ➜</a>
                            </div>
                        </div>
                    </div>
                    <div id="q-box__buttons">
                        <button id="prev-btn" type="button"> {{ __('Previous') }}</button>
                        <button id="next-btn" type="button">{{ __('Next') }}</button>
                        <button id="submit-btn" type="submit">{{ __('Submit') }}</button>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<div id="preloader-wrapper">
    <div id="preloader"></div>
    <div class="preloader-section section-left"></div>
    <div class="preloader-section section-right"></div>
</div>
<style>
    card-input-element {
        display: none;
    }

    .card-input {
        margin: 10px;
        padding: 0px;
    }

    .card-input:hover {
        cursor: pointer;
    }

    .card-input-element:checked+.card-input {
        box-shadow: 0 0 1px 1px #2ecc71;
    }


    .theme-card {
        border: 1px solid #ccc;
        padding: 10px;
        cursor: pointer;
    }

    .theme-card.selected {
        border: 2px solid black;
    }

    body {
        background: #f7f9ff;
        font-family: 'Josefin Sans', sans-serif;
        font-size: 16px;
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.4;
        color: #555;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        color: #00011c;
    }

    p {
        margin-bottom: 24px;
        line-height: 1.9;
    }

    label {
        font-size: 16px;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: #00011c;
    }

    #title-container {
        height: 100%;
        color: #000000;
        text-align: center;
        padding: 10px 28px 28px 28px;
        box-sizing: border-box;
        position: relative;
        box-shadow: 10px 8px 21px 0px rgba(204, 204, 204, 0.75);
        -webkit-box-shadow: 10px 8px 10px 0px rgba(204, 204, 204, 0.75);
        -moz-box-shadow: 10px 8px 21px 0px rgba(204, 204, 204, 0.75);
    }

    #title-container h2 {
        font-size: 45px;
        font-weight: 800;
        color: #000000;
        padding: 0;
        margin-bottom: 0px;
    }

    #title-container h3 {
        font-size: 25px;
        font-weight: 600;
        color: #82000a;
        padding: 0;
    }

    #title-container p {
        font-size: 13px;
        padding: 0 25px;
        line-height: 20px;
    }

    .covid-image {
        width: 214px;
        margin-bottom: 15px;
    }

    #qbox-container {
        background: url('https://mariadb.com/wp-content/webp-express/webp-images/doc-root/wp-content/themes/mariadb-sage/public/images/wave-break-2.868c70.png.webp');
        background-repeat: repeat;
        position: relative;
        padding: 20px;
        box-shadow: 10px 8px 21px 0px rgba(204, 204, 204, 0.75);
        -webkit-box-shadow: 10px 8px 21px 0px rgba(204, 204, 204, 0.75);
        -moz-box-shadow: 10px 8px 21px 0px rgba(204, 204, 204, 0.75);
    }

    #steps-container {
        margin: auto;
        width: 100%;
        min-height: 420px;
        display: flex;
        vertical-align: middle;
        align-items: center;
        justify-content: center
    }

    .step {
        display: none;
    }

    .step h4 {
        margin: 0 0 26px 0;
        padding: 0;
        position: relative;
        font-weight: 500;
        font-size: 23px;
        font-size: 1.4375rem;
        line-height: 1.6;
    }

    button#prev-btn,
    button#next-btn,
    button#submit-btn {
        font-size: 17px;
        font-weight: bold;
        position: relative;
        width: 130px;
        height: 50px;
        background: #FFC700;
        margin: 0 auto;
        margin-top: 40px;
        overflow: hidden;
        z-index: 1;
        cursor: pointer;
        transition: color .3s;
        text-align: center;
        color: #fff;
        border: 0;
        -webkit-border-bottom-right-radius: 5px;
        -webkit-border-bottom-left-radius: 5px;
        -moz-border-radius-bottomright: 5px;
        -moz-border-radius-bottomleft: 5px;
        border-bottom-right-radius: 5px;
        border-bottom-left-radius: 5px;
    }

    button#prev-btn:after,
    button#next-btn:after,
    button#submit-btn:after {
        position: absolute;
        top: 90%;
        left: 0;
        width: 100%;
        height: 100%;
        background: #FFF455;
        content: "";
        z-index: -2;
        transition: transform .3s;
    }

    button#prev-btn:hover::after,
    button#next-btn:hover::after,
    button#submit-btn:hover::after {
        transform: translateY(-80%);
        transition: transform .3s;
    }

    .progress {
        border-radius: 0px !important;
    }

    .q__question {
        position: relative;
    }

    .q__question:not(:last-child) {
        margin-bottom: 10px;
    }

    .question__input {
        position: absolute;
        left: -9999px;
    }

    .question__label {
        position: relative;
        display: block;
        line-height: 40px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background-color: #fff;
        padding: 5px 20px 5px 50px;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }

    .question__label:hover {
        border-color: #FFC700;
    }

    .question__label:before,
    .question__label:after {
        position: absolute;
        content: "";
    }

    .question__label:before {
        top: 12px;
        left: 10px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background-color: #fff;
        box-shadow: inset 0 0 0 1px #ced4da;
        -webkit-transition: all 0.15s ease-in-out;
        -moz-transition: all 0.15s ease-in-out;
        -o-transition: all 0.15s ease-in-out;
        transition: all 0.15s ease-in-out;
    }

    .question__input:checked+.question__label:before {
        background-color: #FFC700;
        box-shadow: 0 0 0 0;
    }

    .question__input:checked+.question__label:after {
        top: 22px;
        left: 18px;
        width: 10px;
        height: 5px;
        border-left: 2px solid #fff;
        border-bottom: 2px solid #fff;
        transform: rotate(-45deg);
    }

    .form-check-input:checked,
    .form-check-input:focus {
        background-color: #FFC700 !important;
        outline: none !important;
        border: none !important;
    }

    input:focus {
        outline: none;
    }

    #input-container {
        display: inline-block;
        box-shadow: none !important;
        margin-top: 36px !important;
    }

    label.form-check-label.radio-lb {
        margin-right: 15px;
    }

    #q-box__buttons {
        text-align: center;
    }

    input[type="text"],
    input[type="email"] {
        padding: 8px 14px;
    }

    input[type="text"]:focus,
    input[type="email"]:focus {
        border: 1px solid #FFC700;
        border-radius: 5px;
        outline: 0px !important;
        -webkit-appearance: none;
        box-shadow: none !important;
        -webkit-transition: all 0.15s ease-in-out;
        -moz-transition: all 0.15s ease-in-out;
        -o-transition: all 0.15s ease-in-out;
        transition: all 0.15s ease-in-out;
    }

    .form-check-input:checked[type=radio],
    .form-check-input:checked[type=radio]:hover,
    .form-check-input:checked[type=radio]:focus,
    .form-check-input:checked[type=radio]:active {
        border: none !important;
        -webkit-outline: 0px !important;
        box-shadow: none !important;
    }

    .form-check-input:focus,
    input[type="radio"]:hover {
        box-shadow: none;
        cursor: pointer !important;
    }

    #success {
        display: none;
    }

    #success h4 {
        color: #f3bc23;
    }

    .back-link {
        font-weight: 700;
        color: #f3bc23;
        text-decoration: none;
        font-size: 18px;
    }

    .back-link:hover {
        color: #82000a;
    }

    .active {
        background-color: #f3bc23 !important;
        color: :white;
    }

    #preloader-wrapper {
        width: 100%;
        height: 100%;
        z-index: 1000;
        display: none;
        position: fixed;
        top: 0;
        left: 0;
    }

    #preloader {
        background-image: url('../img/preloader.png');
        width: 120px;
        height: 119px;
        border-top-color: #fff;
        border-radius: 100%;
        display: block;
        position: relative;
        top: 50%;
        left: 50%;
        margin: -75px 0 0 -75px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        z-index: 1001;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    #preloader-wrapper .preloader-section {
        width: 51%;
        height: 100%;
        position: fixed;
        top: 0;
        background: #F7F9FF;
        z-index: 1000;
    }

    #preloader-wrapper .preloader-section.section-left {
        left: 0
    }

    #preloader-wrapper .preloader-section.section-right {
        right: 0;
    }

    .loaded #preloader-wrapper .preloader-section.section-left {
        transform: translateX(-100%);
        transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    }

    .loaded #preloader-wrapper .preloader-section.section-right {
        transform: translateX(100%);
        transition: all 0.7s 0.3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    }

    .loaded #preloader {
        opacity: 0;
        transition: all 0.3s ease-out;
    }

    .loaded #preloader-wrapper {
        visibility: hidden;
        transform: translateY(-100%);
        transition: all 0.3s 1s ease-out;
    }

    @media (min-width: 990px) and (max-width: 1199px) {
        #title-container {
            padding: 80px 28px 28px 28px;
        }

        #steps-container {
            width: 85%;
        }
    }

    @media (max-width: 991px) {
        #title-container {
            padding: 30px;
            min-height: inherit;
        }
    }

    @media (max-width: 767px) {
        #qbox-container {
            padding: 30px;
        }

        #steps-container {
            width: 100%;
            min-height: 400px;
        }

        #title-container {
            padding-top: 50px;
        }
    }

    @media (max-width: 560px) {
        #qbox-container {
            padding: 40px;
        }

        #title-container {
            padding-top: 45px;
        }
    }
</style>
<script>
    function showImage(event) {
    // Get the file input
    var input = event.target;

    // Get the image element
    var img = document.getElementById('storeLogo');

    // Remove the 'd-none' class
    img.classList.remove('d-none');

    // Set the image source
    img.src = window.URL.createObjectURL(input.files[0]);
  }
    document.addEventListener('DOMContentLoaded', function() {
        const radioButtons = document.querySelectorAll('input[name="theme"]');
        const selectedThemeName = document.getElementById('selectedThemeName');

        radioButtons.forEach(function(radioButton) {
            radioButton.addEventListener('change', function() {
                if (this.checked) {
                    selectedThemeName.textContent = this.value;
                }
            });
        });
    });

    let step = document.getElementsByClassName('step');
    let prevBtn = document.getElementById('prev-btn');
    let nextBtn = document.getElementById('next-btn');
    let submitBtn = document.getElementById('submit-btn');
    let form = document.getElementsByTagName('form')[0];
    let preloader = document.getElementById('preloader-wrapper');
    let bodyElement = document.querySelector('body');
    let succcessDiv = document.getElementById('success');

    form.onsubmit = () => {}
    let current_step = 0;
    let stepCount = 3
    step[current_step].classList.add('d-block');
    if (current_step == 0) {
        prevBtn.classList.add('d-none');
        submitBtn.classList.add('d-none');
        nextBtn.classList.add('d-inline-block');
    }
    const progress = (value) => {
        document.getElementsByClassName('progress-bar')[0].style.width = `${value}%`;
    }

    nextBtn.addEventListener('click', () => {
        let StopEvent = false;
        console.log(current_step);
        if (current_step == 0) {
            document.querySelectorAll('.navdata').forEach(element => {
                element.classList.remove('active');
                document.getElementById('nav_2').classList.add('active');
            });
        }
        if (current_step == 2) {
            document.querySelectorAll('.navdata').forEach(element => {
                element.classList.remove('active');
                document.getElementById('nav_4').classList.add('active');
            });
        }

        if (current_step == 1) {
            document.querySelectorAll('.navdata').forEach(element => {
                element.classList.remove('active');
                document.getElementById('nav_3').classList.add('active');


            });
            const storeNameInput = document.getElementById('store_name');
            const personalNumberInput = document.getElementById('personal_number');
            const supportNumberInput = document.getElementById('support_number');

            const themeLogoInput = document.getElementById('theme_logo');
            const storeLogoImage = document.getElementById('storeLogo');

            const inputs = [storeNameInput, personalNumberInput, supportNumberInput];
            inputs.forEach(element => {
                if (element.value == '') {
                    console.log("############")
                    StopEvent = true

                }
                if (themeLogoInput.files.length === 0) {
                    StopEvent = true


                }
            });
        }
        if (!StopEvent) {
            current_step++;

            let previous_step = current_step - 1;
            if ((current_step > 0) && (current_step <= stepCount)) {
                prevBtn.classList.remove('d-none');
                prevBtn.classList.add('d-inline-block');
                step[current_step].classList.remove('d-none');
                step[current_step].classList.add('d-block');
                step[previous_step].classList.remove('d-block');
                step[previous_step].classList.add('d-none');
                if (current_step == stepCount) {
                    submitBtn.classList.remove('d-none');
                    submitBtn.classList.add('d-inline-block');
                    nextBtn.classList.remove('d-inline-block');
                    nextBtn.classList.add('d-none');
                }
            } else {
                if (current_step > stepCount) {
                    form.onsubmit = () => {
                        return true
                    }
                }
            }
            progress((100 / stepCount) * current_step);
        } else {

            alert('يرجى ملء جميع الحقول');
        }
    });


    prevBtn.addEventListener('click', () => {
        
        if (current_step > 0) {
            current_step--;
            
            let previous_step = current_step + 1;
            prevBtn.classList.add('d-none');
            prevBtn.classList.add('d-inline-block');
            step[current_step].classList.remove('d-none');
            step[current_step].classList.add('d-block')
            step[previous_step].classList.remove('d-block');
            step[previous_step].classList.add('d-none');
            if (current_step < stepCount) {
                submitBtn.classList.remove('d-inline-block');
                submitBtn.classList.add('d-none');
                nextBtn.classList.remove('d-none');
                nextBtn.classList.add('d-inline-block');
                prevBtn.classList.remove('d-none');
                prevBtn.classList.add('d-inline-block');
            }
        }

        if (current_step == 0) {
            prevBtn.classList.remove('d-inline-block');
            prevBtn.classList.add('d-none');
        }
        progress((100 / stepCount) * current_step);
    });


    submitBtn.addEventListener('click', () => {
        
        preloader.classList.add('d-block');

       /*  const timer = ms => new Promise(res => setTimeout(res, ms));

        timer(3000)
            .then(() => {
                bodyElement.classList.add('loaded');
            }).then(() => {
                step[stepCount].classList.remove('d-block');
                step[stepCount].classList.add('d-none');
                prevBtn.classList.remove('d-inline-block');
                prevBtn.classList.add('d-none');
                submitBtn.classList.remove('d-inline-block');
                submitBtn.classList.add('d-none');
                succcessDiv.classList.remove('d-none');
                succcessDiv.classList.add('d-block');
            }) */

    });
    document.getElementById('default_language').addEventListener('change', function() {
        var selectedLanguage = this.value;
        window.location.href = "{{ route('change.languagestore', ':code') }}".replace(':code',
            selectedLanguage);
    });

    $(document).ready(function() {
        $('#filter_country').select2();
    });
</script>
