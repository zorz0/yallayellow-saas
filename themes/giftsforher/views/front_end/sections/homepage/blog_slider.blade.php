
@foreach ($landing_blogs as $blog)

    <div class="col-md-4 col-sm-6 col-12">
        <div class="blog-widget-inner">
            <div class="blog-media">
            <div class="decorative-text">Outdoor Gift</div>
                <a href="{{route('page.article',[$slug,$blog->id])}}">
                    {{-- <div class="decorative-text">CHAIRS</div> --}}
                    <img src="{{asset($blog->cover_image_path)}}" alt="blog">
                </a>
            </div>
            <div class="blog-caption">
                <div class="blog-lbl-row d-flex justify-content-between">
                    <div class="blog-labl">
                        {{$blog->category->name}}
                    </div>
                    <div class="blog-labl">
                        {{$blog->created_at->format('d M,Y ')}}
                    </div>
                </div>
                <h4>
                    <a href="{{route('page.article',[$slug,$blog->id])}}">{{$blog->title}}</a>
                </h4>
                <p class="descriptions">{{$blog->short_description}}</p>
                <a href="{{route('page.article',[$slug,$blog->id])}}" class="link-btn dark-link-btn" tabindex="0">
                    {{__('Show More')}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="8" height="10" viewBox="0 0 4 6" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.65976 0.662719C0.446746 0.879677 0.446746 1.23143 0.65976 1.44839L2.18316 3L0.65976 4.55161C0.446747 4.76856 0.446747 5.12032 0.65976 5.33728C0.872773 5.55424 1.21814 5.55424 1.43115 5.33728L3.34024 3.39284C3.55325 3.17588 3.55325 2.82412 3.34024 2.60716L1.43115 0.662719C1.21814 0.445761 0.872773 0.445761 0.65976 0.662719Z" fill=""></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endforeach
