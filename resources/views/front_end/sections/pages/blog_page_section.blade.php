@extends('front_end.layouts.app')
@section('page-title')
    {{ __('Blog Page') }}
@endsection
@section('content')
    @include('front_end.sections.partision.header_section')
    <section class="blog-page-banner common-banner-section" style="background-image: url({{asset('themes/'.$currentTheme.'/assets/images/blog-banner.jpg')}});">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 col-12">
                        <div class="common-banner-content">
                            <div class="section-title">
                                <h2> {{ __('Blog & Articles') }} </h2>
                            </div>
                            <p> {{ __('The blog and article section serves as a treasure trove of valuable information.') }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <section class="blog-grid-section padding-bottom padding-top">
        <div class="container">
            <div class="tabs-wrapper">
                <div class="blog-head-row tab-nav d-flex justify-content-between">
                    <div class="blog-col-left ">
                        <ul class="d-flex tabs">
                            @foreach ($BlogCategory as $cat_key => $category)
                             <li class="tab-link on-tab-click {{ $cat_key == 0 ? 'active' : '' }}" data-tab="{{ $cat_key }}"><a href="javascript:;">{{ $category }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="blog-col-right d-flex align-items-center justify-content-end">
                        <span class="select-lbl">{{__('Sort by')}}</span>
                        <select class="position">
                            <option value="lastest">{{ __('Lastest')}}</option>
                            <option value="new"> {{ __('new')}}</option>
                        </select>
                    </div>
                </div>
                <div class="tabs-container">
                    @foreach ($BlogCategory as $cat_k => $category)
                    <div id="{{ $cat_k }}" class="tab-content {{ $cat_k == 0 ? 'active' : '' }}">
                        <div class="row blog-grid f_blog">
                            @foreach ($blogs as $key => $blog)
                                @if($cat_k == '0' || $blog->BlogCategory_id == $cat_k)

                               <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 blog-widget">
                                <div class="blog-widget-inner" >
                                    <div class="blog-media">
                                        <span class="new-labl bg-second">{{__('Food')}}</span>
                                        <a href="{{ route('page.article', ['storeSlug'=> $slug,$blog->id]) }}">
                                            <img src="{{asset($blog->cover_image_path)}}">
                                        </a>
                                    </div>
                                    <div class="blog-caption">
                                        <h4><a href="{{ route('page.article', ['storeSlug'=> $slug,$blog->id]) }}">{{ $blog->title }}</a></h4>
                                            <p>{{$blog->short_description }}</p>
                                        <div class="blog-lbl-row d-flex align-items-center justify-content-between">
                                            <a class="btn blog-btn" href="{{ route('page.article', ['storeSlug'=> $slug,$blog->id]) }}">
                                                {{__('Read more')}}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="3" height="6" viewBox="0 0 3 6" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.15976 0.662719C-0.0532536 0.879677 -0.0532536 1.23143 0.15976 1.44839L1.68316 3L0.15976 4.55161C-0.0532533 4.76856 -0.0532532 5.12032 0.15976 5.33728C0.372773 5.55424 0.718136 5.55424 0.931149 5.33728L2.84024 3.39284C3.05325 3.17588 3.05325 2.82412 2.84024 2.60716L0.931149 0.662719C0.718136 0.445761 0.372773 0.445761 0.15976 0.662719Z" fill="white"/>
                                                    </svg>
                                            </a>
                                            <div class="author-info">
                                                {{-- <strong class="auth-name">{{__('John Doe,')}}</strong> --}}
                                                <span class="date">{{$blog->created_at->format('d M,Y ')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @include('front_end.sections.partision.footer_section')
@endsection

