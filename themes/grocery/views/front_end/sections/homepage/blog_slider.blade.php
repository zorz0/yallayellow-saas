@foreach ($landing_blogs as $blog)
    <div class="blog-widget d-flex">

        <div class="blog-widget-inner">
            <div class="blog-media">
                <span class="new-labl bg-second">{{ __('Food') }}</span>
                <a href="{{route('page.article',['storeSlug'=>$slug, 'id'=>$blog->id])}}">
                    <img src=" {{ asset($blog->cover_image_path) }}">
                </a>
            </div>
            <div class="blog-caption">
                <h3><a href="{{route('page.article',['storeSlug'=>$slug, 'id'=>$blog->id])}}" class="descriptions">{{ $blog->title }}</a></h3>
                <p>{{ $blog->short_description }}</p>
                <div class="blog-lbl-row d-flex align-items-center justify-content-between">
                    <a class="btn blog-btn" href="{{route('page.article',['storeSlug'=>$slug, 'id'=>$blog->id])}}">
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
