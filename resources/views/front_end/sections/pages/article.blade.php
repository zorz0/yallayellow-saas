@extends('front_end.layouts.app')
@section('page-title')
    {{ __('Article Page') }}
@endsection
@section('content')
    @include('front_end.sections.partision.header_section')
    @foreach ($blogs as $blog)
        <section class="blog-page-banner article-banner common-banner-section" style="background-image:url({{asset('themes/'.$currentTheme.'/assets/images/blog-banner.jpg') }});">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-xl-7 col-md-8 col-12">
                        <div class="common-banner-content">
                            <a href="{{route('page.blog',['storeSlug' => $slug])}}" class="back-btn">
                                <span class="svg-ic">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="5" viewBox="0 0 11 5" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5791 2.28954C10.5791 2.53299 10.3818 2.73035 10.1383 2.73035L1.52698 2.73048L2.5628 3.73673C2.73742 3.90636 2.74146 4.18544 2.57183 4.36005C2.40219 4.53467 2.12312 4.53871 1.9485 4.36908L0.133482 2.60587C0.0480403 2.52287 -0.000171489 2.40882 -0.000171488 2.2897C-0.000171486 2.17058 0.0480403 2.05653 0.133482 1.97353L1.9485 0.210321C2.12312 0.0406877 2.40219 0.044729 2.57183 0.219347C2.74146 0.393966 2.73742 0.673036 2.5628 0.842669L1.52702 1.84888L10.1383 1.84875C10.3817 1.84874 10.5791 2.04609 10.5791 2.28954Z" fill="white"></path>
                                    </svg>
                                </span>
                                {{__('Back to Blog')}}
                            </a>
                            <div class="section-title text-center">
                                <h2>{{$blog->title}} </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="article-section padding-bottom padding-top">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        @php
                                $store = App\Models\Store::find($blog->store_id);
                                $user = App\Models\User::find($store->created_by);
                            @endphp
                        <div class="about-user d-flex align-items-center">
                            <div class="abt-user-img">
                                <img src="{{ asset('themes/'.$currentTheme.'/assets/images/john.png') }}">
                            </div>
                            <h6>
                                <span>{{ $user->name }}</span>
                                {{-- {{ __('company.com')}} --}}
                            </h6>
                            <div class="post-lbl"><b>{{__('Category:')}}</b> {{$blog->category->name}}</div>
                            <div class="post-lbl"><b>{{__('Date:')}}</b>{{$blog->created_at->format('d M, Y ')}}</div>
                        </div>
                    </div>
                    <div class="col-md-8 col-12">
                        <div class="aticleleftbar">
                            <h5></h5>
                            <p> {!! html_entity_decode($blog->content) !!} </p>

                            {{-- <img src="{{asset($blog->cover_image_path)}}" alt="article"> --}}
                            <div class="art-auther"><b>{{ $user->name }}</b>,
                                {{-- <a href="company.com">{{__('company.com')}}</a> --}}
                            </div>

                            <div class="art-auther"><b>{{__('Tags:')}}</b> {{$blog->category->name}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="articlerightbar">
                            <div class="section-title">
                                <h3>{{__('Related articles')}}</h3>
                            </div>
                            <div class="row blog-grid">
                                @foreach ($datas->take(1) as $data)
                                <div class="blog-widget col-md-12 col-sm-6 col-12">
                                    <div class="blog-widget-inner">
                                        <div class="blog-media">
                                            <span class="new-labl bg-second">{{__('Food')}}</span>
                                            <a href="{{route('page.article',['storeSlug'=> $slug, $data->id])}}">
                                                <img src="{{asset($data->cover_image_path )}}">
                                            </a>
                                        </div>
                                        <div class="blog-caption">
                                            <h4><a href="{{route('page.article',['storeSlug'=> $slug, $data->id])}} " class ="descriptions">{{$data->title}}</a></h4>
                                                <p>{{$data->short_description}}</p>
                                            <div class="blog-lbl-row d-flex align-items-center justify-content-between">
                                                <a class="btn blog-btn" href="{{route('page.article',['storeSlug'=> $slug, $data->id])}}">
                                                {{__('Read more')}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="3" height="6" viewBox="0 0 3 6" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.15976 0.662719C-0.0532536 0.879677 -0.0532536 1.23143 0.15976 1.44839L1.68316 3L0.15976 4.55161C-0.0532533 4.76856 -0.0532532 5.12032 0.15976 5.33728C0.372773 5.55424 0.718136 5.55424 0.931149 5.33728L2.84024 3.39284C3.05325 3.17588 3.05325 2.82412 2.84024 2.60716L0.931149 0.662719C0.718136 0.445761 0.372773 0.445761 0.15976 0.662719Z" fill="white"/>
                                                        </svg>
                                                </a>
                                                <div class="author-info">
                                                    <strong class="auth-name">{{__('John Doe')}},</strong>
                                                    <span class="date">{{$data->created_at->format('d M, Y ')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="latest-article-slider-section padding-bottom">
            <div class="container">
                <div class="section-title">
                    <h2>{{__('Last')}} <b>{{__('articles')}}</b></h2>
                </div>
                <div class="latest-article-slider blog-grid common-arrows flex-slider">
                    @foreach ($l_articles as $blog)
                        <div class=" blog-widget d-flex">
                            <div class="blog-widget-inner">
                                <div class="blog-media">
                                    <span class="new-labl bg-second">{{ __('Food') }}</span>
                                    <a href="{{ route('page.article', [$currentTheme,$data->id]) }}">
                                        <img src=" {{ asset($blog->cover_image_path) }}">
                                    </a>
                                </div>
                                <div class="blog-caption">
                                    <h4><a href="{{ route('page.article', [$currentTheme,$data->id]) }}" class="descriptions">{{ $blog->title }}</a></h4>
                                    <p>{{ $blog->short_description }}</p>
                                    <div class="blog-lbl-row d-flex align-items-center justify-content-between">
                                        <a class="btn blog-btn" href="{{ route('page.article', [$currentTheme,$data->id]) }}">
                                            {{ __('Read more') }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="3" height="6" viewBox="0 0 3 6"
                                                fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M0.15976 0.662719C-0.0532536 0.879677 -0.0532536 1.23143 0.15976 1.44839L1.68316 3L0.15976 4.55161C-0.0532533 4.76856 -0.0532532 5.12032 0.15976 5.33728C0.372773 5.55424 0.718136 5.55424 0.931149 5.33728L2.84024 3.39284C3.05325 3.17588 3.05325 2.82412 2.84024 2.60716L0.931149 0.662719C0.718136 0.445761 0.372773 0.445761 0.15976 0.662719Z"
                                                    fill="white" />
                                            </svg>
                                        </a>
                                        <div class="author-info">
                                            {{-- <strong class="auth-name">{{ __('John Doe,') }}</strong> --}}
                                            <span class="date">{{ $blog->created_at->format('d M,Y ') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach
    @include('front_end.sections.partision.footer_section')
@endsection

