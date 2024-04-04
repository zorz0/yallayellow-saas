<section class="article-section padding-top" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>            
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="about-user d-flex align-items-center">
                    <div class="abt-user-img">
                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/john.png') }}">
                    </div>
                    <h6>
                        <span>John Doe,</span>
                        company.com
                    </h6>
                    <div class="post-lbl"><b>Category:</b> Fashion</div>
                    <div class="post-lbl"><b>Date:</b> 12 Mar, 2022</div>
                </div>
                <div class="section-title">
                    <h2>Article title first with light weight</h2>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="aticleleftbar">
                    <h5>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                        has been the industry's
                        standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                        and scrambled it to make a type specimen book.</h5>
                    <p> It has survived not only five centuries, but also the leap into electronic typesetting,
                        remaining essentially unchanged. It was popularised in the 1960s with the release of
                        Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                        publishing software like Aldus PageMaker including versions of Lorem Ipsum.Lorem Ipsum
                        is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                        industry's standard dummy text ever since the 1500s, when an unknown printer took a
                        galley of type and scrambled it to make a type specimen book. It has survived not only
                        five centuries, but also the leap into electronic typesetting, remaining essentially
                        unchanged. It was popularised in the 1960s with the release of Letraset sheets
                        containing Lorem Ipsum passages, and more recently with desktop publishing software like
                        Aldus PageMaker including versions of Lorem Ipsum.Lorem Ipsum is simply dummy text of
                        the printing and typesetting industry. Lorem Ipsum has been the industry's standard
                        dummy text ever since the 1500s, when an unknown printer took a galley of type and
                        scrambled it to make a type specimen book. It has survived not only five centuries, but
                        also the leap into electronic typesetting, remaining essentially unchanged. It was
                        popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                        passages, and more recently with desktop publishing software like Aldus PageMaker
                        including versions of Lorem Ipsum.</p>
                    <div class="article-banner-img">
                        <img src="{{ asset('themes/' . $currentTheme . '/assets/images/article-img.jpg') }}" alt="article">
                    </div>
                    <div class="art-auther"><b>John Doe</b>, <a href="company.com">company.com</a></div>
                    <p> It has survived not only five centuries, but also the leap into electronic typesetting,
                        remaining essentially unchanged. It was popularised in the 1960s with the release of
                        Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                        publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                    <div class="quote-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="39" height="32" viewBox="0 0 39 32"
                            fill="none">
                            <path
                                d="M17.0493 23.1822C17.0493 19.4844 14.347 16.0711 10.0804 15.36C10.7915 11.0933 14.6315 6.82666 17.0493 5.12L11.787 0C5.67149 5.26222 0.835938 12.6578 0.835938 22.6133C0.835938 27.8756 4.53371 31.2889 9.22705 31.2889C13.6359 31.2889 17.0493 27.5911 17.0493 23.1822ZM38.0982 23.1822C38.0982 19.4844 35.3959 16.0711 31.1293 15.36C31.8404 11.0933 35.6804 6.82666 38.0982 5.12L32.8359 0C26.7204 5.26222 21.8848 12.6578 21.8848 22.6133C21.8848 27.8756 25.5826 31.2889 30.2759 31.2889C34.6848 31.2889 38.0982 27.5911 38.0982 23.1822Z"
                                fill="#ffffff" />
                        </svg>
                        <h3>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                            Ipsum has been the industry's
                            standard dummy text ever since the 1500s, when an unknown printer took a galley of
                            type and scrambled it to make a type specimen book.</h3>
                    </div>
                    <div class="art-auther"><b>Tags:</b> fashion, clothes</div>
                    <ul class="article-socials d-flex align-items-center">
                        <li><span>Share:</span></li>
                        <li>
                            <a target="_blank" rel="noopener"
                                href="//www.facebook.com/sharer.php?u=https://style-workdo.myshopify.com"
                                class="share-facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11"
                                    viewBox="0 0 11 11" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M2.59131 2.23909C3.27183 2.11998 4.32047 2.00996 5.87035 2.00996C7.42023 2.00996 8.46887 2.11998 9.14939 2.23909C9.60942 2.3196 9.93616 2.77372 9.93616 3.42342V7.45757C9.93616 8.10727 9.60942 8.56138 9.14939 8.6419C8.46887 8.761 7.42023 8.87102 5.87035 8.87102C4.32047 8.87102 3.27183 8.761 2.59131 8.6419C2.13128 8.56138 1.80454 8.10727 1.80454 7.45757V3.42342C1.80454 2.77372 2.13128 2.3196 2.59131 2.23909ZM5.87035 0.866455C4.27902 0.866455 3.17701 0.979313 2.43505 1.10917C1.37337 1.29498 0.788086 2.34061 0.788086 3.42342V7.45757C0.788086 8.54038 1.37337 9.586 2.43505 9.77182C3.17701 9.90167 4.27902 10.0145 5.87035 10.0145C7.46168 10.0145 8.56369 9.90167 9.30564 9.77182C10.3673 9.586 10.9526 8.54038 10.9526 7.45757V3.42342C10.9526 2.34061 10.3673 1.29498 9.30564 1.10917C8.56369 0.979313 7.46168 0.866455 5.87035 0.866455ZM5.12231 3.79288C5.28757 3.69339 5.48808 3.70429 5.64404 3.82126L7.16872 4.96477C7.3101 5.07081 7.39503 5.24933 7.39503 5.44049C7.39503 5.63166 7.3101 5.81018 7.16872 5.91622L5.64404 7.05973C5.48808 7.1767 5.28757 7.1876 5.12231 7.0881C4.95706 6.98861 4.8539 6.79486 4.8539 6.584V4.29698C4.8539 4.08612 4.95706 3.89238 5.12231 3.79288Z"
                                        fill="white" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" rel="noopener"
                                href="//twitter.com/share?text=&amp;url=https://style-workdo.myshopify.com"
                                class="share-twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="10"
                                    viewBox="0 0 11 10" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M5.213 0.572998C4.25741 0.572998 3.07645 0.863855 2.12129 1.52701C1.14637 2.20388 0.413086 3.26767 0.413086 4.75497C0.413086 5.66158 0.741096 6.34886 1.07262 6.80921C1.21571 7.0079 1.35955 7.16463 1.47851 7.27985L0.994642 9.07154C0.941156 9.26959 1.01711 9.47835 1.18913 9.60609C1.36116 9.73383 1.59777 9.75718 1.79502 9.66588L3.8328 8.72268C4.98711 9.08069 6.56858 8.95863 7.88401 8.39913C9.32616 7.78575 10.5776 6.58561 10.5776 4.75497C10.5776 3.13648 9.80643 2.05718 8.71186 1.4072C7.65196 0.777791 6.33135 0.572998 5.213 0.572998ZM2.65501 7.23412C2.70978 7.0313 2.62874 6.81791 2.44925 6.69142L2.44725 6.68995C2.44376 6.68736 2.43655 6.68193 2.42614 6.67368C2.40531 6.65715 2.3719 6.62945 2.33001 6.59067C2.24595 6.51286 2.12964 6.39217 2.01233 6.22928C1.77916 5.9055 1.54248 5.41661 1.54248 4.75497C1.54248 3.62854 2.07976 2.86272 2.79893 2.36341C3.53785 1.85038 4.47451 1.61849 5.213 1.61849C6.21199 1.61849 7.29134 1.80576 8.10206 2.28719C8.87812 2.74804 9.44822 3.49836 9.44822 4.75497C9.44822 6.06081 8.58206 6.95167 7.41249 7.44912C6.21558 7.9582 4.8323 7.99315 4.02371 7.67235C3.87103 7.61178 3.69729 7.6165 3.54872 7.68527L2.38809 8.22248L2.65501 7.23412ZM5.28342 3.82403C5.05873 3.65764 4.73482 3.67424 4.53135 3.86259L3.40196 4.90809C3.18143 5.11223 3.18143 5.44322 3.40196 5.64736C3.62249 5.85151 3.98004 5.85151 4.20056 5.64736L4.97256 4.93271L5.98963 5.68592C6.21432 5.85232 6.53823 5.83571 6.74169 5.64736L7.87109 4.60187C8.09161 4.39772 8.09161 4.06674 7.87109 3.86259C7.65056 3.65845 7.29301 3.65845 7.07249 3.86259L6.30048 4.57724L5.28342 3.82403Z"
                                        fill="white" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" rel="noopener"
                                href="//pinterest.com/pin/create/button/?url=https://style-workdo.myshopify.com&amp;media=//cdn.shopify.com/shopifycloud/shopify/assets/no-image-2048-5e88c1b20e087fb7bbe9a3771824e743c244f437e4f8ba93bbf7b11b53f7824c_1024x1024.gif&amp;description="
                                class="share-pinterest">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="13"
                                    viewBox="0 0 11 13" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0.31543 4.32341C0.31543 2.47088 1.68067 0.969116 3.36479 0.969116H7.4306C9.11471 0.969116 10.48 2.47088 10.48 4.32341V8.7958C10.48 10.6483 9.11471 12.1501 7.4306 12.1501H3.36479C1.68067 12.1501 0.31543 10.6483 0.31543 8.7958V4.32341ZM3.36479 2.08721C2.24205 2.08721 1.33188 3.08839 1.33188 4.32341V8.7958C1.33188 10.0308 2.24205 11.032 3.36479 11.032H7.4306C8.55334 11.032 9.4635 10.0308 9.4635 8.7958V4.32341C9.4635 3.08839 8.55334 2.08721 7.4306 2.08721H3.36479ZM4.38124 6.55961C4.38124 7.17711 4.83632 7.6777 5.39769 7.6777C5.95906 7.6777 6.41415 7.17711 6.41415 6.55961C6.41415 5.9421 5.95906 5.44151 5.39769 5.44151C4.83632 5.44151 4.38124 5.9421 4.38124 6.55961ZM5.39769 4.32341C4.27495 4.32341 3.36479 5.32459 3.36479 6.55961C3.36479 7.79462 4.27495 8.7958 5.39769 8.7958C6.52044 8.7958 7.4306 7.79462 7.4306 6.55961C7.4306 5.32459 6.52044 4.32341 5.39769 4.32341ZM7.68471 3.20531C7.26368 3.20531 6.92237 3.58075 6.92237 4.04389C6.92237 4.50702 7.26368 4.88246 7.68471 4.88246C8.10574 4.88246 8.44705 4.50702 8.44705 4.04389C8.44705 3.58075 8.10574 3.20531 7.68471 3.20531Z"
                                        fill="white" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a target="_blank" rel="noopener"
                                href="//pinterest.com/pin/create/button/?url=https://style-workdo.myshopify.com&amp;media=//cdn.shopify.com/shopifycloud/shopify/assets/no-image-2048-5e88c1b20e087fb7bbe9a3771824e743c244f437e4f8ba93bbf7b11b53f7824c_1024x1024.gif&amp;description="
                                class="share-pinterest">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="10"
                                    viewBox="0 0 12 10" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.74307 1.98957C6.42993 2.48631 6.32423 3.06903 6.32423 3.34561C6.32423 3.52784 6.23323 3.69819 6.08132 3.80035C5.92941 3.90251 5.73638 3.92317 5.56605 3.8555C5.45465 3.81123 5.33053 3.76623 5.19633 3.71758C4.84866 3.59153 4.4333 3.44093 3.99595 3.21503C3.55179 2.9856 3.06779 2.67402 2.51957 2.21137C2.52637 2.4179 2.5723 2.60705 2.64948 2.78234C2.82964 3.19157 3.21167 3.59616 3.84426 3.97296C4.01734 4.07605 4.12011 4.26463 4.11239 4.46494C4.10468 4.66525 3.98769 4.84548 3.80719 4.93512L3.80658 4.93542L3.80592 4.93575L3.80445 4.93647L3.80087 4.93822L3.79122 4.94284C3.78369 4.9464 3.77397 4.95091 3.76211 4.95623C3.73842 4.96686 3.70617 4.98073 3.66593 4.99673C3.58551 5.02868 3.47262 5.06928 3.33173 5.10926C3.18357 5.15131 3.00316 5.19304 2.79604 5.22311C2.91422 5.63998 3.17763 5.90019 3.46147 6.09675C3.62838 6.21233 3.78986 6.2974 3.95215 6.38291C3.99252 6.40417 4.03294 6.42547 4.0735 6.44726C4.16213 6.49489 4.27865 6.55925 4.37501 6.63582C4.44938 6.69491 4.66566 6.87838 4.66566 7.18975C4.66566 7.38884 4.57476 7.54399 4.51721 7.62691C4.4499 7.72392 4.36493 7.81147 4.27598 7.88801C4.09651 8.04242 3.8556 8.1908 3.56997 8.31235C3.24109 8.45231 2.83959 8.56237 2.38062 8.60201C2.41821 8.62204 2.45817 8.64117 2.50057 8.65942C2.90297 8.83262 3.49147 8.90351 4.20042 8.8395C5.60602 8.71259 7.27352 8.07544 8.41963 7.07659C9.29823 6.06859 9.67033 5.07122 9.8252 4.33205C9.90354 3.95813 9.92638 3.6495 9.93015 3.43882C9.93203 3.33359 9.92915 3.25316 9.92612 3.20171C9.92461 3.176 9.92307 3.15757 9.92208 3.14696L9.92108 3.13698C9.90481 3.0028 9.93889 2.86706 10.0169 2.75627L9.92123 3.13819L9.92135 3.13914L10.0169 2.75627L11.0339 3.22193C11.0255 3.2347 11.0415 3.2105 11.0339 3.22193C11.0339 3.22193 11.0373 3.36682 11.0357 3.45833C11.0309 3.72817 11.002 4.10599 10.9077 4.55583C10.7188 5.45772 10.2674 6.64608 9.22903 7.82581C9.21374 7.84318 9.19737 7.85957 9.18 7.87489C7.83747 9.05959 5.92533 9.78661 4.30052 9.93332C3.48893 10.0066 2.69519 9.94019 2.06094 9.6672C1.40419 9.38452 0.897647 8.86396 0.799196 8.08161C0.777543 7.90955 0.838977 7.73743 0.96491 7.61734C1.09084 7.49725 1.26648 7.4433 1.43867 7.47181C2.15791 7.59088 2.74232 7.46968 3.13454 7.30277C3.17365 7.28613 3.24498 7.25221 3.24498 7.25221C3.24498 7.25221 2.97162 7.09642 2.82912 6.99774C2.24574 6.59375 1.62494 5.91093 1.62494 4.71852C1.62494 4.41522 1.87246 4.16935 2.1778 4.16935C2.218 4.16935 2.25742 4.1686 2.29603 4.16717C2.01438 3.88404 1.78948 3.57012 1.6364 3.22242C1.32606 2.51752 1.34228 1.74511 1.66448 0.944998C1.7335 0.773604 1.88459 0.64812 2.06664 0.611004C2.24868 0.573888 2.43732 0.630105 2.56873 0.760635C3.36335 1.54995 3.98651 1.9722 4.50608 2.24057C4.80456 2.39475 5.06053 2.49634 5.31654 2.59067C5.40528 2.21642 5.56036 1.79621 5.80592 1.40667C6.25949 0.687149 7.04213 0.0506287 8.25923 0.0506287C9.01422 0.0506287 9.50003 0.420204 9.80593 0.673497C9.84279 0.704021 9.87477 0.730719 9.90285 0.754175C9.97358 0.813234 10.0197 0.851735 10.0573 0.878966C10.3086 0.760637 10.5355 0.677728 10.7373 0.639532C10.9518 0.598928 11.261 0.586478 11.5155 0.788909C11.7833 1.00191 11.8143 1.30829 11.8051 1.48975C11.7955 1.67966 11.7386 1.86985 11.6787 2.02831C11.5565 2.352 11.362 2.69608 11.2103 2.94418C11.1439 3.05284 11.0825 3.14816 11.0339 3.22193C11.0436 3.34182 11.0328 3.21061 11.0339 3.22193L10.0169 2.75627L10.0175 2.75537L10.0207 2.75082L10.0345 2.7309C10.0468 2.713 10.065 2.68621 10.0877 2.65212C10.1333 2.58376 10.1962 2.48692 10.2652 2.37414C10.3631 2.21386 10.4654 2.03394 10.5474 1.86374C10.5141 1.87921 10.4789 1.89616 10.4416 1.91469C10.4341 1.91841 10.4265 1.92197 10.4188 1.92534C10.0806 2.07472 9.77581 1.98716 9.56184 1.86681C9.41411 1.78371 9.2633 1.65643 9.15443 1.56456C9.13403 1.54734 9.1151 1.53136 9.09794 1.51715C8.83907 1.3028 8.60996 1.14896 8.25923 1.14896C7.50085 1.14896 7.03956 1.51923 6.74307 1.98957Z"
                                        fill="white" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="articlerightbar blog-grid-section">
                    <div class="section-title">
                        <h3>Related articles</h3>
                    </div>
                    <div class="blog-itm">
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
                    <div class=" blog-itm">
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
        </div>
    </div>
</section>