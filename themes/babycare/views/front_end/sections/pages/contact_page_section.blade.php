<section class="contact-page padding-top padding-bottom" style="position: relative;@if(isset($option) && $option->is_hide == 1) opacity: 0.5; @else opacity: 1; @endif" data-index="{{ $option->order ?? '' }}" data-id="{{ $option->order ?? '' }}" data-value="{{ $option->id ?? '' }}" data-hide="{{ $option->is_hide ?? '' }}" data-section="{{ $option->section_name ?? '' }}"  data-store="{{ $option->store_id ?? '' }}" data-theme="{{ $option->theme_id ?? '' }}">
    <div class="custome_tool_bar"></div>    
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-12 contact-left-column">
                <div class="contact-left-inner row">
                    <ul class="col-sm-6 col-12">
                        <li>
                            <h4>Call us:</h4>
                            <p><a href="tel:+48 0021-32-12">+48 0021-32-12</a></p>
                        </li>
                        <li>
                            <h4>Email:</h4>
                            <p><a href="mailto:shop@company.com">shop@company.com</a></p>
                        </li>
                    </ul>
                    <ul class="col-sm-6 col-12">
                        <li>
                            <h4>Address:</h4>
                            <p class="address">1093 Marigold Lane,<br>
                                Coral Way, Miami,<br>
                                Florida, 33169</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-7 col-12 contact-right-column">
                <div class="contact-right-inner">
                    <div class="section-title">
                        <h2>Contact <b>form</b></h2>
                    </div>
                    <form class="contact-form">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>First Name<sup aria-hidden="true">*</sup>:</label>
                                    <input type="text" class="form-control" placeholder="John" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>Last Name<sup aria-hidden="true">*</sup>:</label>
                                    <input type="text" class="form-control" placeholder="Doe" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>E-mail<sup aria-hidden="true">*</sup>:</label>
                                    <input type="email" class="form-control" placeholder="shop@company.com"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label>Telephone<sup aria-hidden="true">*</sup>:</label>
                                    <input type="number" class="form-control" placeholder="1234567890" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label> Subject:</label>
                                    <select class="form-control">
                                        <option value="2">Customer service</option>
                                        <option value="1">Webmaster</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label> Description:</label>
                                    <textarea class="form-control" name="message" placeholder="How can we help?"
                                        rows="8"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-lg-8   col-12">
                                <div class="checkbox-custom">
                                    <input type="checkbox" id="ch1">
                                    <label for="ch1">
                                        <span>I have read and agree to the <a href="privacy-policy.html">Terms &
                                                Conditions.</a> </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <button class="btn submit-btn" type="submit">
                                    Send message
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="14"
                                        viewBox="0 0 35 14" fill="none">
                                        <path
                                            d="M25.0749 14L35 7L25.0805 0L29.12 6.06667H0V7.93333H29.12L25.0749 14Z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>