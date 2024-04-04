<div class="container">
    <div class="blog-grid-title d-flex justify-content-between align-items-center ">
        <div class="section-title">
            <span class="sub-title">{{__('ALL PRODUCTS')}}</span>
            <h3>{{__('From our blog')}}</h3>
        </div>
        <a href="#" class="btn">{{__('Read More')}}</a>
    </div>
    <div class="blog-head-row d-flex justify-content-between">
        <div class="blog-col-left">
            <ul class="d-flex tabs">
                @foreach ($MainCategory as $cat_key => $category)
                <li class="tab-link on-tab-click {{ $cat_key == 0 ? 'active' : '' }}" data-tab="{{ $cat_key }}"><a href="javascript:;">{{ $category }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="blog-col-right d-flex align-items-center justify-content-end" style="max-width: 155px">
            <span class="select-lbl"> {{ __('Sort by') }} </span>
            <select class="position">
                <option value="lastest"> {{ __('Lastest') }} </option>
                <option value="new"> {{ __('new') }} </option>
            </select>
        </div>
    </div>


    @foreach ($MainCategory as $cat_k => $category)
    <div class="tabs-container">
        <div id="{{ $cat_k }}" class="tab-content {{$cat_k == 0 ? 'active' : ''}}">
            <div class="row blog-grid f_blog">
                @foreach ($blogs as $blog)
                    @if($cat_k == '0' ||  $blog->category_id == $cat_k)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 blog-widget">
                            <div class="blog-card ">
                                <div class="blog-card-inner">
                                    <div class="blog-card-image">
                                        <a href="{{route('page.article',[$slug,$blog->id])}}">
                                            <img src=" {{asset($blog->cover_image_path)}}" class="default-img">
                                        </a>
                                    </div>
                                    <div class="blog-card-content">
                                        <span class="sub-title">{{__('ARTICLES')}}</span>
                                        <div class="section-title">
                                            <h4>
                                                <a href="{{route('page.article',[$slug,$blog->id])}}" class="name"> {{$blog->title}}
                                                    {{$blog->category->name}}
                                                </a>
                                            </h4>
                                        </div>
                                        <p>
                                            {{$blog->short_description}}
                                        </p>
                                        <div class="blog-card-bottom">
                                            <a href="{{route('page.article',[$slug,$blog->id])}}" class=" btn">
                                               {{__('Read More')}}
                                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8"
                                                    viewBox="0 0 8 8" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M0.18164 3.99989C0.181641 3.82416 0.324095 3.68171 0.499822 3.68171L6.73168 3.68171L4.72946 1.67942C4.60521 1.55516 4.60521 1.3537 4.72947 1.22944C4.85373 1.10519 5.05519 1.10519 5.17945 1.22945L7.72482 3.7749C7.84907 3.89916 7.84907 4.10062 7.72482 4.22487L5.17945 6.77033C5.05519 6.89459 4.85373 6.89459 4.72947 6.77034C4.60521 6.64608 4.60521 6.44462 4.72946 6.32036L6.73168 4.31807L0.499822 4.31807C0.324095 4.31807 0.181641 4.17562 0.18164 3.99989Z"
                                                        fill="white" />
                                                </svg>
                                            </a>
                                            <span class="date">
                                                {{$blog->created_at->format('d M,Y ')}} <br>
                                                <a href="#">
                                                    @john
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

        </div>

    </div>
    @endforeach
</div>
