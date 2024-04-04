<section class="home-article-blog-section padding-top" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>    
    <div class="container">
        <div class="w-100 d-flex align-items-center justify-content-between">
                <div class="section-title-left">
                    @if(isset($section->article_blog->section->title))
                        <div class="section-title">
                            <h2 id="{{ $section->article_blog->section->title->slug ?? '' }}_preview"> {!! $section->article_blog->section->title->text ?? '' !!} </h2>
                        </div>
                    @endif
                    @if(isset($section->article_blog->section->description))
                        <p id="{{ $section->article_blog->section->description->slug ?? '' }}_preview">{!! $section->article_blog->section->description->text ?? '' !!}</p>
                    @endif
                </div>
                <div class="section-title-right">
                    @if(isset($section->article_blog->section->button))
                        <a href="product-list.html" id="{{ $section->article_blog->section->button->slug ?? '' }}_preview" class="link-btn btn">{!! $section->article_blog->section->button->slug ?? '' !!}</a>
                    @endif
                </div>
        </div>
        <div class="row home-main-blog">
            <div class="col-lg-4 col-md-6 col-sm-6 col-cx-12 col-12">
                <div class="home-article-blog">
                    <div class="home-article-blog-image">
                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/article-blog-1.png') }}" alt="article blog">
                    </div>
                    <div class="home-article-blog-content" style="background-color: var(--second-color);">
                        <div class="section-title">
                            <h3> <b> Fall in love with </b> <br> amazing clothes </h3>
                        </div>
                        <a href="article.html" class="article-blog-show-more-link">Show more</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-cx-12 col-12">
                <div class="home-article-blog">
                    <div class="home-article-blog-image">
                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/article-blog-2.png') }}" alt="article blog">
                    </div>
                    <div class="home-article-blog-content blog-content-top"
                        style="background-color: var(--theme-color);">
                        <div class="section-title">
                            <h3> <b> Fall in love with </b> <br> amazing clothes </h3>
                        </div>
                        <a href="article.html" class="article-blog-show-more-link">Show more</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-cx-12 col-12">
                <div class="home-article-blog">
                    <div class="home-article-blog-image">
                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/article-blog-3.png') }}" alt="article blog">
                    </div>
                    <div class="home-article-blog-content" style="background-color: var(--second-color);">
                        <div class="section-title">
                            <h3> <b> Fall in love with </b> <br> amazing clothes </h3>
                        </div>
                        <a href="article.html" class="article-blog-show-more-link">Show more</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>