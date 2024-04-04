@extends('front_end.layouts.app')
@section('page-title')
    {{ ucfirst($page->page_name ?? __('Home Page')) }}
@endsection
@section('content')
    @include('front_end.sections.partision.header_section')

        <section class="blog-page-banner common-banner-section" style="background-image: url({{asset('themes/'.$currentTheme.'/assets/images/banner-inner.png')}});">
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

        <section class="blog-grid-section article-section-home padding-top tabs-wrapper">
            <div class="container">
                <div class="blog-head-row d-flex justify-content-between">
                    <div class="blog-col-left">
                        <ul class="d-flex tabs">
                            @foreach ($BlogCategory as $cat_key => $category)
                             <li class="tab-link on-tab-click {{ $cat_key == 0 ? 'active' : '' }}" data-tab="{{ $cat_key }}"><a href="javascript:;">{{ $category }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="blog-col-right d-flex align-items-center justify-content-end">
                        <span class="select-lbl">{{ __('Sort by') }}</span>
                        <select class="position">
                            <option value="lastest">{{ __('Lastest') }}</option>
                            <option value="new">{{ __('new') }}</option>
                        </select>
                    </div>
                </div>
                <div class="tabs-container">
                    @foreach ($BlogCategory as $cat_k => $category)
                        <div id="{{ $cat_k }}" class="tab-content {{$cat_k == 0 ? 'active' : ''}}">
                            <div class="blog-grid-row row f_blog">
                                @foreach ($blogs as $blog)
                                    @if($cat_k == '0' ||  $blog->maincategory_id == $cat_k)
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 blog-itm">
                                            <div class="blog-inner">
                                                <div class="blog-img">
                                                    <a href="{{ route('page.article', ['storeSlug'=> $slug,$blog->id]) }}">
                                                        <img src="{{asset($blog->cover_image_path)}}"  class="cover_img{{ $blog->id }}" alt="blog-img">
                                                    </a>
                                                </div>
                                                <div class="blog-content">
                                                    <h4><a href="{{ route('page.article', ['storeSlug'=> $slug,$blog->id]) }}">{{$blog->title}}</a> </h4>
                                                    <p>{{$blog->short_description}}</p>
                                                    <a href="{{ route('page.article', ['storeSlug'=> $slug,$blog->id]) }}" class="btn-secondary">
                                                        Read more
                                                    </a>
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
        </section>

    @include('front_end.sections.partision.footer_section')
@endsection
