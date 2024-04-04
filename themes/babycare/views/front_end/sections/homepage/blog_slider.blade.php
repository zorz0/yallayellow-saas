<div class="blog-slider">
    @foreach ($landing_blogs as $blog)
    <div class="blog-itm">
        <div class="blog-inner">
            <div class="blog-img">
                <a href="#">
                    <img src="{{asset($blog->cover_image_path )}}" alt="blog-img">
                </a>
            </div>
            <div class="blog-content">
                <h4><a href="#">{{$blog->title}}</a> </h4>
                <p>{{$blog->short_description}}</p>
                <a href="#" class="btn-secondary">
                    {{ __('Read more') }}
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>