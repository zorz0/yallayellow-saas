<section class="blog-grid-section padding-top padding-bottom tabs-wrapper {{ $option->class_name }}" style="position: relative;@if($option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order }}" data-id="{{ $option->order }}" data-value="{{ $option->id }}" data-hide="{{ $option->is_hide  }}">
    <div class="custome_tool_bar"></div>    
    <div class="container">
        <div class="section-title">
            <div class="subtitle">ALL BLOGS</div>
            <h2>From <b> our blog</b></h2>
        </div>
        <div class="blog-head-row d-flex justify-content-between">
            <div class="blog-col-left">
                <ul class="d-flex tabs">
                    <li class="tab-link active" data-tab="blog-1"><a href="javascript:;">All</a></li>
                    <li class="tab-link" data-tab="blog-2"><a href="javascript:;">Fashion </a> </li>
                    <li class="tab-link" data-tab="blog-3"><a href="javascript:;">Clothes</a></li>
                </ul>
            </div>
            <div class="blog-col-right d-flex align-items-center justify-content-end">
                <span class="select-lbl">Sort by</span>
                <select>
                    <option>Lastest</option>
                    <option>new</option>
                </select>
            </div>
        </div>
        <div class="tabs-container">
            <div id="blog-1" class="tab-content active">
                <div class="blog-grid-row row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 blog-itm">
                        <div class="blog-itm-inner">
                            <div class="blog-img">
                                <a href="article.html">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/1.jpg') }}" alt="product-banner1" class="product-banner">
                                    <h6 class="banner-date text-white">Article</h6>
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-content-top">
                                    <h6> 25 May, Friday </h6>
                                    <h4> <a href="article.html"> Quisque tempus eros sit amet elit malesuada
                                        </a> </h4>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                        industry. Lorem Ipsum has been the industry's. Lorem Ipsum is simply
                                        dummy text of the printing and typesetting industry. Lorem Ipsum is
                                        simply dummy text of the printing and typesetting industry. </p>
                                </div>
                                <div class="blog-contnt-bottom">
                                    <a href="article.html" class="addtocart-btn btn btn-primary w-100"
                                        tabindex="0">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 blog-itm">
                        <div class="blog-itm-inner">
                            <div class="blog-img">
                                <a href="article.html">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/1.jpg') }}" alt="product-banner1" class="product-banner">
                                    <h6 class="banner-date text-white">Article</h6>
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-content-top">
                                    <h6> 25 May, Friday </h6>
                                    <h4> <a href="article.html"> Quisque tempus eros sit amet elit malesuada
                                        </a> </h4>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                        industry. Lorem Ipsum has been the industry's. Lorem Ipsum is simply
                                        dummy text of the printing and typesetting industry. Lorem Ipsum is
                                        simply dummy text of the printing and typesetting industry. </p>
                                </div>
                                <div class="blog-contnt-bottom">
                                    <a href="article.html" class="addtocart-btn btn btn-primary w-100"
                                        tabindex="0">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 blog-itm">
                        <div class="blog-itm-inner">
                            <div class="blog-img">
                                <a href="article.html">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/1.jpg') }}" alt="product-banner1" class="product-banner">
                                    <h6 class="banner-date text-white">Article</h6>
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-content-top">
                                    <h6> 25 May, Friday </h6>
                                    <h4> <a href="article.html"> Quisque tempus eros sit amet elit malesuada
                                        </a> </h4>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                        industry. Lorem Ipsum has been the industry's. Lorem Ipsum is simply
                                        dummy text of the printing and typesetting industry. Lorem Ipsum is
                                        simply dummy text of the printing and typesetting industry. </p>
                                </div>
                                <div class="blog-contnt-bottom">
                                    <a href="article.html" class="addtocart-btn btn btn-primary w-100"
                                        tabindex="0">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 blog-itm">
                        <div class="blog-itm-inner">
                            <div class="blog-img">
                                <a href="article.html">
                                    <img src="{{ asset('themes/' . $currentTheme . '/assets/images/1.jpg') }}" alt="product-banner1" class="product-banner">
                                    <h6 class="banner-date text-white">Article</h6>
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-content-top">
                                    <h6> 25 May, Friday </h6>
                                    <h4> <a href="article.html"> Quisque tempus eros sit amet elit malesuada
                                        </a> </h4>
                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                        industry. Lorem Ipsum has been the industry's. Lorem Ipsum is simply
                                        dummy text of the printing and typesetting industry. Lorem Ipsum is
                                        simply dummy text of the printing and typesetting industry. </p>
                                </div>
                                <div class="blog-contnt-bottom">
                                    <a href="article.html" class="addtocart-btn btn btn-primary w-100"
                                        tabindex="0">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="blog-2" class="tab-content">
            </div>
            <div id="blog-3" class="tab-content">
            </div>
        </div>
    </div>
</section>