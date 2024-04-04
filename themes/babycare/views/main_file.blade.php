@if($currentTheme != null)
    @extends('front_end.layouts.app')
@endif

@section('page-title')
    {{ __('Home Page') }}
@endsection
@if (!(\Request::route()->getName() == 'login' || \Request::route()->getName() == 'register'))
    @section('content')
        @if(isset($theme_section) && count($theme_section) > 0)
            @foreach($theme_section as $option)
                @if ($option->section_name == 'header')
                    @include('front_end.sections.partision.header_section')
                @elseif ($option->section_name == 'slider')
                    
                        @include('front_end.sections.homepage.slider_section')
                   
                @elseif ($option->section_name == 'category')
                    
                        @include('front_end.sections.homepage.category_section')
                   
                @elseif ($option->section_name == 'variant_background')
                    
                        @include('front_end.sections.homepage.variant_section')
                   
                @elseif ($option->section_name == 'bestseller_slider')
                    
                        @include('front_end.sections.homepage.bestseller_slider_section')
                   
                @elseif ($option->section_name == 'logo_slider')
                    
                        @include('front_end.sections.homepage.logo_slider')
                   
                @elseif ($option->section_name == 'best_product')
                    
                        @include('front_end.sections.homepage.best_product_section')
                   
                @elseif ($option->section_name == 'product_category')
                    
                        @include('front_end.sections.homepage.product_category_section')
                   
                @elseif ($option->section_name == 'product_banner_slider')
                    
                        @include('front_end.sections.homepage.product_banner_slider')
                   
                @elseif ($option->section_name == 'review')
                    
                        @include('front_end.sections.homepage.review_section')
                   
                @elseif ($option->section_name == 'blog')
                    
                        @include('front_end.sections.homepage.blog_section')
                   
                @elseif ($option->section_name == 'footer')
                   @include('front_end.sections.partision.footer_section')
                @elseif ($option->section_name == 'subscribe')
                
                   @include('front_end.sections.homepage.subscribe_section')
                  
                @elseif ($option->section_name == 'best_selling_slider')
                    
                        @include('front_end.sections.homepage.best_selling_slider')
                   
                @elseif ($option->section_name == 'best_product_second')
                    
                        @include('front_end.sections.homepage.best_product_second')
                   
                @endif
            @endforeach
        @endif
        
    @endsection
@endif