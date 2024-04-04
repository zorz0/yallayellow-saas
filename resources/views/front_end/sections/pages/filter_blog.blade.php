
@foreach ($blogs as $key => $blog)
@if($request->cat_id == '0' || $blog->category_id == $request->cat_id)

        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 blog-widget">
            <div class="blog-widget-inner">
                <div class="blog-media">
                    <span class="new-labl bg-second">{{__('Food')}}</span>
                    <a href="#">
                        <img src="{{asset($blog->cover_image_path)}}">
                    </a>
                </div>
                <div class="blog-caption">
                    <h4><a href="#">{{ $blog->title }}</a></h4>
                        <p>{{$blog->short_description }}</p>
                    <div class="blog-lbl-row d-flex align-items-center justify-content-between">
                        <a class="btn blog-btn" href="#">
                            {{__('Read more')}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="3" height="6" viewBox="0 0 3 6" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.15976 0.662719C-0.0532536 0.879677 -0.0532536 1.23143 0.15976 1.44839L1.68316 3L0.15976 4.55161C-0.0532533 4.76856 -0.0532532 5.12032 0.15976 5.33728C0.372773 5.55424 0.718136 5.55424 0.931149 5.33728L2.84024 3.39284C3.05325 3.17588 3.05325 2.82412 2.84024 2.60716L0.931149 0.662719C0.718136 0.445761 0.372773 0.445761 0.15976 0.662719Z" fill="white"/>
                                </svg>
                        </a>
                        <div class="author-info">
                            <span class="date">{{$blog->created_at->format('d M,Y ')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endforeach
