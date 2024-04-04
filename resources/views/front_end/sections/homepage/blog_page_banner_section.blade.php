<section class="blog-page-banner common-banner-section" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>    
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="common-banner-content">
                    <ul class="blog-cat">
                        <li class="active">Featured</li>
                        <li><b>Category:</b> Fashion</li>
                        <li><b>Date:</b> 12 Mar, 2022</li>
                    </ul>
                    <div class="section-title section-title-white">
                        <h2>What is the best Fashion ever? </h2>
                    </div>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                        has been the industry's standard...</p>
                    <a href="#" class="btn btn-primary">
                        <span class="btn-txt">Read More</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>