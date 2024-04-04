/*  jQuery Nice Select - v1.0
    https://github.com/hernansartorio/jquery-nice-select
    Made by Hernán Sartorio  */
!function(e){e.fn.niceSelect=function(t){function s(t){t.after(e("<div></div>").addClass("nice-select").addClass(t.attr("class")||"").addClass(t.attr("disabled")?"disabled":"").attr("tabindex",t.attr("disabled")?null:"0").html('<span class="current"></span><ul class="list"></ul>'));var s=t.next(),n=t.find("option"),i=t.find("option:selected");s.find(".current").html(i.data("display")||i.text()),n.each(function(t){var n=e(this),i=n.data("display");s.find("ul").append(e("<li></li>").attr("data-value",n.val()).attr("data-display",i||null).addClass("option"+(n.is(":selected")?" selected":"")+(n.is(":disabled")?" disabled":"")).html(n.text()))})}if("string"==typeof t)return"update"==t?this.each(function(){var t=e(this),n=e(this).next(".nice-select"),i=n.hasClass("open");n.length&&(n.remove(),s(t),i&&t.next().trigger("click"))}):"destroy"==t?(this.each(function(){var t=e(this),s=e(this).next(".nice-select");s.length&&(s.remove(),t.css("display",""))}),0==e(".nice-select").length&&e(document).off(".nice_select")):console.log('Method "'+t+'" does not exist.'),this;this.hide(),this.each(function(){var t=e(this);t.next().hasClass("nice-select")||s(t)}),e(document).off(".nice_select"),e(document).on("click.nice_select",".nice-select",function(t){var s=e(this);e(".nice-select").not(s).removeClass("open"),s.toggleClass("open"),s.hasClass("open")?(s.find(".option"),s.find(".focus").removeClass("focus"),s.find(".selected").addClass("focus")):s.focus()}),e(document).on("click.nice_select",function(t){0===e(t.target).closest(".nice-select").length&&e(".nice-select").removeClass("open").find(".option")}),e(document).on("click.nice_select",".nice-select .option:not(.disabled)",function(t){var s=e(this),n=s.closest(".nice-select");n.find(".selected").removeClass("selected"),s.addClass("selected");var i=s.data("display")||s.text();n.find(".current").text(i),n.prev("select").val(s.data("value")).trigger("change")}),e(document).on("keydown.nice_select",".nice-select",function(t){var s=e(this),n=e(s.find(".focus")||s.find(".list .option.selected"));if(32==t.keyCode||13==t.keyCode)return s.hasClass("open")?n.trigger("click"):s.trigger("click"),!1;if(40==t.keyCode){if(s.hasClass("open")){var i=n.nextAll(".option:not(.disabled)").first();i.length>0&&(s.find(".focus").removeClass("focus"),i.addClass("focus"))}else s.trigger("click");return!1}if(38==t.keyCode){if(s.hasClass("open")){var l=n.prevAll(".option:not(.disabled)").first();l.length>0&&(s.find(".focus").removeClass("focus"),l.addClass("focus"))}else s.trigger("click");return!1}if(27==t.keyCode)s.hasClass("open")&&s.trigger("click");else if(9==t.keyCode&&s.hasClass("open"))return!1});var n=document.createElement("a").style;return n.cssText="pointer-events:auto","auto"!==n.pointerEvents&&e("html").addClass("no-csspointerevents"),this}}(jQuery);


$(document).ready(function() {
    console.log("hellobabycare");
    /********* On scroll heder Sticky *********/
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if (scroll >= 50) {
            $("header").addClass("head-sticky");
        } else {
            $("header").removeClass("head-sticky");
        }
    });   
    /********* Mobile Menu ********/  
    $('.mobile-menu-button').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
            $('body').addClass('no-scroll active-menu');
            $(".mobile-menu-wrapper").toggleClass("active-menu");
            $('.overlay').addClass('menu-overlay');
        }, 50);
    }); 
    $('body').on('click','.overlay.menu-overlay, .menu-close-icon svg', function(e){
        e.preventDefault(); 
        $('body').removeClass('no-scroll active-menu');
        $(".mobile-menu-wrapper").removeClass("active-menu");
        $('.overlay').removeClass('menu-overlay');
    });
    /********* Cart Popup ********/
    $('.cart-header').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
            $('body').addClass('no-scroll cartOpen');
            $('.overlay').addClass('cart-overlay');
        }, 50);
    }); 
    $('body').on('click','.overlay.cart-overlay, .closecart', function(e){
        e.preventDefault(); 
        $('.overlay').removeClass('cart-overlay');
        $('body').removeClass('no-scroll cartOpen');
    });
    /********* Mobile Filter Popup ********/
    $('.filter-title').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
            $('body').addClass('no-scroll filter-open');
            $('.overlay').addClass('active');
        }, 50);
    }); 
    $('body').on('click','.overlay.active, .close-filter', function(e){
        e.preventDefault(); 
        $('.overlay').removeClass('active');
        $('body').removeClass('no-scroll filter-open');
    }); 
    /******* Cookie Js *******/
    $('.cookie-close').click(function () {
        $('.cookie').slideUp();
    });
    /*********  Header Search Popup  ********/ 
    $(".search-header a").click(function() { 
        $(".search-popup").toggleClass("active"); 
        $("body").toggleClass("no-scroll");
    });
    $(".close-search").click(function() { 
        $(".search-popup").removeClass("active"); 
        $("body").removeClass("no-scroll");
    });
    /******* Subscribe popup Js *******/
    $('.close-sub-btn').click(function () {
        $('.subscribe-popup').slideUp(); 
        $(".subscribe-overlay").removeClass("open");
    });      
    /********* qty spinner ********/
    var quantity = 0;
    $('.quantity-increment').click(function(){;
        var t = $(this).siblings('.quantity');
        var quantity = parseInt($(t).val());
        $(t).val(quantity + 1); 
    }); 
    $('.quantity-decrement').click(function(){
        var t = $(this).siblings('.quantity');
        var quantity = parseInt($(t).val());
        if(quantity > 1){
            $(t).val(quantity - 1);
        }
    });   
    /******  Nice Select  ******/ 
    $('select').niceSelect(); 
    /*********  Multi-level accordion nav  ********/ 
    $('.acnav-label').click(function () {
        var label = $(this);
        var parent = label.parent('.has-children');
        var list = label.siblings('.acnav-list');
        if (parent.hasClass('is-open')) {
            list.slideUp('fast');
            parent.removeClass('is-open');
        }
        else {
            list.slideDown('fast');
            parent.addClass('is-open');
        }
    });  

    // date-labls select 
    $('.date-labls li').on('click', function(){
        $('.date-labls li').removeClass('active')
        $(this).addClass('active');
    });

    $('.time-labls li').on('click', function(){
        $('.time-labls li').removeClass('active')
        $(this).addClass('active');
    });

    /****  TAB Js ****/
    $('ul.tabs li').click(function(){
        var $this = $(this);
        var $theTab = $(this).attr('data-tab');
        console.log($theTab);
        if($this.hasClass('active')){
          // do nothing
        } else{
          $this.closest('.tabs-wrapper').find('ul.tabs li, .tabs-container .tab-content').removeClass('active');
          $('.tabs-container .tab-content[id="'+$theTab+'"], ul.tabs li[data-tab="'+$theTab+'"]').addClass('active');
        }
        $('.bestpro-slider').slick('refresh');
        $('.bestpro-slider-two').slick('refresh');
        $('.bestpro-slider-three').slick('refresh');
    });   
   
    // Initialize Theme Sliders
    initializeThemeSliders();


    /****  SCROLL COUNTER JS ****/
    var isAlreadyRun = false; 
    $(window).scroll(function () {
    $(".counter-show").each(function (i) {
        var bottom_of_object = $(this).position().top + $(this).outerHeight() / 2;
        var bottom_of_window = $(window).scrollTop() + $(window).height();

        if (bottom_of_window > bottom_of_object + 20) {
        if (!isAlreadyRun) {
            $(".count-number").each(function () {
            $(this)
                .prop("Counter", 0)
                .animate(
                {
                    Counter: $(this).text()
                },
                {
                    duration: 3500,
                    easing: "swing",
                    step: function (now) {
                    $(this).text(Math.ceil(now));
                    }
                }
                );
            });
        }
        isAlreadyRun = true;
        }
    });
    }); 
    /****  SCROLL COUNTER JS End ****/  

   

}); 

if ($(".my-acc-column").length > 0) {
    jQuery(function ($) {
        var topMenuHeight = $("#daccount-nav").outerHeight();
        $("#account-nav").menuScroll(topMenuHeight);
        $(".account-list li:first-child").addClass("active");
    });
    // COPY THE FOLLOWING FUNCTION INTO ANY SCRIPTS
    jQuery.fn.extend({
        menuScroll: function (offset) {
            // Declare all global variables
            var topMenu = this;
            var topOffset = offset ? offset : 0;
            var menuItems = $(topMenu).find("a");
            var lastId;
            // Save all menu items into scrollItems array
            var scrollItems = $(menuItems).map(function () {
                var item = $($(this).attr("href"));
                if (item.length) {
                    return item;
                }
            });
            // When the menu item is clicked, get the #id from the href value, then scroll to the #id element
            $(topMenu).on("click", "a", function (e) {
                var href = $(this).attr("href");
                var offsetTop = href === "#" ? 0 : $(href).offset().top - topOffset;
                function checkWidth() {
                    var windowSize = $(window).width();
                    if (windowSize <= 767) {
                        $('html, body').stop().animate({
                            scrollTop: offsetTop - 200
                        }, 300);
                    }
                    else {
                        $('html, body').stop().animate({
                            scrollTop: offsetTop - 100
                        }, 300);
                    }
                }
                // Execute on load
                checkWidth();
                // Bind event listener
                $(window).resize(checkWidth);
                e.preventDefault();
            });
            // When page is scrolled
            $(window).scroll(function () {
                function checkWidth() {
                    var windowSize = $(window).width();
                    if (windowSize <= 767) {
                        var nm = $("html").scrollTop();
                        var nw = $("body").scrollTop();
                        var fromTop = (nm > nw ? nm : nw) + topOffset;
                        // When the page pass one #id section, return all passed sections to scrollItems and save them into new array current
                        var current = $(scrollItems).map(function () {
                            if ($(this).offset().top - 250 <= fromTop)
                                return this;
                        });
                        // Get the most recent passed section from current array
                        current = current[current.length - 1];
                        var id = current && current.length ? current[0].id : "";
                        if (lastId !== id) {
                            lastId = id;
                            // Set/remove active class
                            $(menuItems)
                                .parent().removeClass("active")
                                .end().filter("[href='#" + id + "']").parent().addClass("active");
                        }
                    }
                    else {
                        var nm = $("html").scrollTop();
                        var nw = $("body").scrollTop();
                        var fromTop = (nm > nw ? nm : nw) + topOffset;
                        // When the page pass one #id section, return all passed sections to scrollItems and save them into new array current
                        var current = $(scrollItems).map(function () {
                            if ($(this).offset().top <= fromTop)
                                return this;
                        });
                        // Get the most recent passed section from current array
                        current = current[current.length - 1];
                        var id = current && current.length ? current[0].id : "";
                        if (lastId !== id) {
                            lastId = id;
                            // Set/remove active class
                            $(menuItems)
                                .parent().removeClass("active")
                                .end().filter("[href='#" + id + "']").parent().addClass("active");
                        }
                    }
                }
                // Execute on load
                checkWidth();
                // Bind event listener
                $(window).resize(checkWidth);
            });
        }
    });
}
$(window).on('load resize orientationchange', function() { 
    /********* Wrapper top space ********/
    var headerHeight = $('header').outerHeight(); // Get the height of the header
    // Select the first section after the header and set its margin-top
   
    $('header').nextAll('section').first().css('margin-top', headerHeight + 'px');
}); 



/******  STEPPY FORM  CSS  ******/
var totalSteps = $(".steps li").length;
$(".submit").on("click", function(){
  return false; 
});

$(".steps li:nth-of-type(1)").addClass("active");
$(".myContainer .step-container:nth-of-type(1)").addClass("active");

$(".step-container").on("click", ".next", function() { 
  $(".steps li").eq($(this).parents(".step-container").index() + 1).addClass("active"); 
  $(this).parents(".step-container").removeClass("active").next().addClass("active"); 
  $('.right-slider').slick('refresh');  
});

$(".step-container").on("click", ".back", function() {  
  $(".steps li").eq($(this).parents(".step-container").index() - totalSteps).removeClass("active"); 
  $(this).parents(".step-container").removeClass("active").prev().addClass("active"); 
  $('.right-slider').slick('refresh');
}); 
/******  STEPPY FORM  CSS  End******/

function initializeThemeSliders () {
    // HOME BANNER SLIDER JS END // 
    $('.hero-slider-item').slick({
        arrows:false,
        dots: true,
        infinite: true, 
        vertical: false,
        autoplay: true,
        fade: true,
        verticalSwiping: false,
        speed: 2000,
        slidesToShow: 1,
        slidesToScroll: 1, 
        prevArrow: '<button class="slick-prev slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
        nextArrow: '<button class="slick-next slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
    });  
    // HOME BANNER SLIDER JS END // 
    if($('.client-logo-slider').length > 0 ){
        $('.client-logo-slider').slick({
            autoplay: true, 
            slidesToShow: 5,
            speed: 1000,
            slidesToScroll: 1,  
            arrows:true,
            centerMode: true,
            centerPadding: 0,
            prevArrow: '<button class="slide-arrow slick-prev"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
            nextArrow: '<button class="slide-arrow slick-next"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
            dots: false,
            buttons: false,
            responsive: [ 
                {
                    breakpoint: 1200,
                    settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1   
                    }
                },  
                {
                breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1 
                    }
                }
            ]
        });
    }

    //best-seller-slider
    if($('.best-seller-slider').length > 0 ){
        $('.best-seller-slider').slick({
            autoplay: false, 
            slidesToShow: 4,
            speed: 1000,
            slidesToScroll: 1,  
            prevArrow: '<button class="slide-arrow slick-prev"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
            nextArrow: '<button class="slide-arrow slick-next"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
            dots: false,
            buttons: false,
            responsive: [  
                {
                breakpoint:992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1 
                    }
                } ,
                {
                breakpoint: 768,
                    settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1   
                    }
                },
                {
                breakpoint:576,
                    settings: {
                        slidesToShow:1,
                        slidesToScroll: 1 
                    }
                }
            ]
        });
    }

    // Best Product slider
    if($('.two-colum-slider').length > 0 ){
        $('.two-colum-slider').slick({
            autoplay: false, 
            slidesToShow: 2,
            speed: 1000,
            slidesToScroll: 1,  
            prevArrow: '<button class="slide-arrow slick-prev"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
            nextArrow: '<button class="slide-arrow slick-next"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
            dots: false,
            buttons: false,
            responsive: [  
                {
                breakpoint:1200,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1 
                    }
                } ,
                {
                breakpoint: 992,
                    settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1   
                    }
                },
                {
                    breakpoint:576,
                        settings: {
                            slidesToShow:1,
                            slidesToScroll: 1 
                        }
                    }
            ]
        });
    }

    // testimonials-slider
    $('.testimonial-slider').slick({
        slidesToShow: 3,
        dots: false,
        infinite: true,
        speed: 1200,
        loop: true,
        arrows: true,
        prevArrow: '<button class="slide-arrow slick-prev"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
        nextArrow: '<button class="slide-arrow slick-next"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
        autoplay: true,
        responsive: [
        {
            breakpoint: 1200,
            settings: {
            slidesToShow: 2,
            slidesToScroll: 1,
            },
        },
        {
            breakpoint: 768,
            settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            },
        }
        
    ],
    });

    // blog-slider
    if($('.blog-slider').length > 0 ){
        $('.blog-slider').slick({
            autoplay: true, 
            slidesToShow: 4,
            speed: 1000,
            slidesToScroll: 1,  
            centerMode: false,
            centerPadding: 0,
            prevArrow: '<button class="slide-arrow slick-prev"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
            nextArrow: '<button class="slide-arrow slick-next"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
            dots: false,
            buttons: false,
            responsive: [  
                {
                breakpoint:1200,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1 
                    }
                },
                {
                breakpoint:768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1 
                    }
                } , 
                {
                breakpoint:576,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1 
                        }
                    }  
            ]
        });
    }

    /** PDP slider **/
    $('.pdp-main-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        prevArrow: '<button class="slick-prev slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
        nextArrow: '<button class="slick-next slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
        infinite: true,
        speed: 1000,
        loop: true,
        asNavFor: '.pdp-thumb-slider',
        autoplay: false,
    });

    $('.pdp-thumb-slider').slick({
        prevArrow: '<button class="slide-arrow slick-prev"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
        nextArrow: '<button class="slide-arrow slick-next"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
        slidesToShow: 3,
        asNavFor: '.pdp-main-slider',
        dots: false,
        touchMove: true,
        speed: 1000,
        slidesToScroll: 1,
        touchMove: true,
        focusOnSelect: true,
        loop: true,
        infinite: true,
        // vertical: false,
        // verticalSwiping: false,
        appendArrows:'.pdp-thumb-nav',
        responsive: [
            {
            breakpoint: 768,
                settings: {
                    vertical: false,
                }
            }
        ]
    });

    $('.pdp-review-slider').slick({
        dots: false,
        arrows: true,
        slidesToShow: 2,
        slidesToScroll1:1,
        infinite: true,
        speed: 1200,
        autoplay:true,
        loop: false,
        prevArrow: '<button class="slide-arrow slick-prev"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
        nextArrow: '<button class="slide-arrow slick-next"><svg width="14" height="6" viewBox="0 0 14 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.48504e-08 3.00044C-4.9681e-06 3.32261 0.261158 3.58378 0.583324 3.58378L11.9791 3.58396L10.6083 4.91557C10.3773 5.14006 10.3719 5.50937 10.5964 5.74045C10.8209 5.97153 11.1902 5.97688 11.4213 5.75239L13.8232 3.41906C13.9362 3.30922 14 3.15829 14 3.00065C14 2.84301 13.9362 2.69208 13.8232 2.58224L11.4213 0.24891C11.1902 0.0244262 10.8209 0.0297744 10.5964 0.260855C10.3719 0.491935 10.3773 0.861243 10.6083 1.08573L11.979 2.41729L0.583343 2.41712C0.261176 2.41711 5.07257e-06 2.67827 3.48504e-08 3.00044Z" fill="#DDE7DE"/></svg></button>',
    
    });
}