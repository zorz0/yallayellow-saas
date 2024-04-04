@php
    if(auth()->user()->type == 'admin') {
        $setting = getAdminAllSetting();
    } else {
        $setting = getSuperAdminAllSetting();
    }
 @endphp
 <footer class="dash-footer">
     <div class="footer-wrapper">
         <div class="py-1">
             <span class="text-muted"> &copy; {{ date('Y') .' '. ($setting['footer_text'] ?? (env('APP_NAME') ?? 'Ecommercego saas') )}}  </span>
         </div>
     </div>
 </footer>

<!-- Required Js -->


<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js')}}"></script>
<script src="{{ asset('assets/js/dash.js') }}"></script>
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/plugins/notifier.js') }}"></script>
<script src="{{ asset('assets/js/pages/ac-notification.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}{{ "?".time() }}"></script>
<script src="{{ asset('assets/css/summernote/summernote-bs4.js')}}"></script>
<script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
<script src="{{ asset('js/socialSharing.js') }}"></script>
<script src="{{ asset('js/custom.js') }}{{ "?".time() }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script src="{{ asset('public/assets/js/plugins/dropzone.min.js') }}"></script>
{{-- select2 --}}

<script src="{{ asset('public/assets/js/plugins/select2.min.js') }}"></script>



<script>
    feather.replace();
    var pctoggle = document.querySelector("#pct-toggler");
    if (pctoggle) {
        pctoggle.addEventListener("click", function() {
            if (
                !document.querySelector(".pct-customizer").classList.contains("active")
            ) {
                document.querySelector(".pct-customizer").classList.add("active");
            } else {
                document.querySelector(".pct-customizer").classList.remove("active");
            }
        });
    }

    var themescolors = document.querySelectorAll(".themes-color > a");
    for (var h = 0; h < themescolors.length; h++) {
        var c = themescolors[h];

        c.addEventListener("click", function(event) {
            var targetElement = event.target;
            if (targetElement.tagName == "SPAN") {
                targetElement = targetElement.parentNode;
            }
            var temp = targetElement.getAttribute("data-value");
            removeClassByPrefix(document.querySelector("body"), "theme-");
            document.querySelector("body").classList.add(temp);
        });
    }

    var custthemebg = document.querySelector("#cust_theme_bg");
    if ($("#cust_theme_bg").length > 0) {
        custthemebg.addEventListener("click", function() {
            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
        });
    }

    var custdarklayout = document.querySelector("#cust-darklayout");
    if ($("#cust-darklayout").length > 0) {
        custdarklayout.addEventListener("click", function() {
            if (custdarklayout.checked) {
                document.querySelector(".m-header > .b-brand > .logo-lg").setAttribute("src",
                    "{{ asset(Storage::url('uploads/logo/logo-light.png')) }}");
                document.querySelector("#main-style-link").setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
            } else {
                document.querySelector(".m-header > .b-brand > .logo-lg").setAttribute("src",
                    "{{ asset(Storage::url('uploads/logo/logo-dark.png')) }}");
                document.querySelector("#main-style-link").setAttribute("href", "{{ asset('assets/css/style.css') }}");
            }
        });
    }

    function removeClassByPrefix(node, prefix) {
        for (let i = 0; i < node.classList.length; i++) {
            let value = node.classList[i];
            if (value.startsWith(prefix)) {
                node.classList.remove(value);
            }
        }
    }


</script>


