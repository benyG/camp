<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Exam Boot Camp - {{ __('main.w1') }}</title>
    <x-meta_name />
    <x-css1 />
</head>

<body data-spy="scroll" data-target=".header-nav-main nav" data-offset="80">

    <div class="body">
        <header id="header" class="header-transparent header-effect-shrink"
            data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': true, 'stickyChangeLogo': false, 'stickyStartAt': 30, 'stickyHeaderContainerHeight': 70}">
            <div class="header-body border-top-0 appear-animation" data-appear-animation="fadeIn"
                data-appear-animation-delay="200">
                <div class="header-container container-fluid px-lg-4">
                    <div class="header-row px-lg-3">
                        <div class="header-column">
                            <div class="header-row">
                                <div class="header-logo header-logo-sticky-change" style="width: 82px; height: 40px;">
                                    <a href="index.html">
                                        <img class="opacity-0 header-logo-non-sticky" alt="Porto"
                                            height="40" src="{{ asset('img/logo-default-slim-dark.png')}}">
                                        <img class="opacity-0 header-logo-sticky" alt="Porto"
                                            height="40" src="{{ asset('img/logo-default-slim.png')}}">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="header-column justify-content-center">
                            <div class="header-row">
                                <div
                                    class="header-nav header-nav-line header-nav-bottom-line header-nav-light-text justify-content-center">
                                    <div
                                        class="header-nav-main header-nav-main-square header-nav-main-dropdown-no-borders header-nav-main-effect-2 header-nav-main-sub-effect-1">
                                        <nav class="collapse">
                                            <ul class="nav nav-pills" id="mainNav">
                                                <li class="active"><a data-hash data-hash-offset="60" href="#intro"
                                                        class="dropdown-item active">HOME</a></li>
                                                <li><a data-hash data-hash-offset="60" href="#features"
                                                        class="dropdown-item">FEATURES</a></li>
                                                <li><a data-hash data-hash-offset="60" href="#overview"
                                                        class="dropdown-item">OVERVIEW</a></li>
                                                <li><a data-hash data-hash-offset="60" href="#reviews"
                                                        class="dropdown-item">REVIEWS</a></li>
                                                <li><a data-hash data-hash-offset="60" href="#pricing"
                                                        class="dropdown-item">PRICING</a></li>
                                                <li><a data-hash data-hash-offset="60" href="#faqs"
                                                        class="dropdown-item">FAQ'S</a></li>
                                                <li><a data-hash data-hash-offset="60" href="#trial"
                                                        class="dropdown-item">CONTACT</a></li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="header-column">
                            <div class="header-row justify-content-end">
                                <ul
                                    class="header-social-icons social-icons d-none d-sm-block social-icons-clean social-icons-icon-light">
                                    <li class="social-icons-facebook"><a href="http://www.facebook.com/" target="_blank"
                                            title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li class="social-icons-twitter"><a href="http://www.twitter.com/" target="_blank"
                                            title="Twitter"><i class="fab fa-twitter"></i></a></li>
                                    <li class="social-icons-linkedin"><a href="http://www.linkedin.com/" target="_blank"
                                            title="Linkedin"><i class="fab fa-linkedin-in"></i></a></li>
                                </ul>
                                <button class="btn header-btn-collapse-nav" data-toggle="collapse"
                                    data-target=".header-nav-main nav">
                                    <i class="fas fa-bars"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div role="main" class="main">

            <section class="m-0 border-0 section bg-primary curved-border position-relative" style="height: 100vh;"
                id="intro">

                <div class="appear-animation position-absolute" data-appear-animation="zoomIn"
                    data-appear-animation-delay="1400" style="top: 25%; left: 16%;">
                    <img src="{{ asset('img/demos/sass/icons/icon-1.png')}}" class="img-fluid" alt=""
                        data-plugin-float-element
                        data-plugin-options="{'startPos': 'none', 'speed': 3, 'transition': true}" />
                </div>
                <div class="appear-animation position-absolute" data-appear-animation="zoomIn"
                    data-appear-animation-delay="2000" style="top: 42%; left: 10%;">
                    <img src="{{ asset('img/demos/sass/icons/icon-2.png')}}" class="img-fluid" alt=""
                        data-plugin-float-element
                        data-plugin-options="{'startPos': 'none', 'speed': 3.5, 'transition': true, 'horizontal': true}" />
                </div>
                <div class="appear-animation position-absolute" data-appear-animation="zoomIn"
                    data-appear-animation-delay="800" style="top: 60%; left: 20%;">
                    <img src="{{ asset('img/demos/sass/icons/icon-3.png')}}" class="img-fluid" alt=""
                        data-plugin-float-element
                        data-plugin-options="{'startPos': 'none', 'speed': 2.5, 'transition': true}" />
                </div>
                <div class="appear-animation position-absolute" data-appear-animation="zoomIn"
                    data-appear-animation-delay="1100" style="top: 22%; right: 25%;">
                    <img src="{{ asset('img/demos/sass/icons/icon-4.png')}}" class="img-fluid" alt=""
                        data-plugin-float-element
                        data-plugin-options="{'startPos': 'none', 'speed': 2, 'transition': true, 'horizontal': true}" />
                </div>
                <div class="appear-animation position-absolute" data-appear-animation="zoomIn"
                    data-appear-animation-delay="1700" style="top: 49%; right: 16%;">
                    <img src="{{ asset('img/demos/sass/icons/icon-5.png')}}" class="img-fluid" alt=""
                        data-plugin-float-element
                        data-plugin-options="{'startPos': 'none', 'speed': 3, 'transition': true}" />
                </div>
                <div class="appear-animation position-absolute" data-appear-animation="zoomIn"
                    data-appear-animation-delay="2300" style="top: 71%; right: 13%;">
                    <img src="{{ asset('img/demos/sass/icons/icon-6.png')}}" class="img-fluid" alt=""
                        data-plugin-float-element
                        data-plugin-options="{'startPos': 'none', 'speed': 2.5, 'transition': true, 'horizontal': true}" />
                </div>

                <div class="container pt-5 mt-5">
                    <div class="pt-5 mt-5 row justify-content-center">
                        <div class="pt-3 text-center col-lg-7">
                            <h1 class="text-color-light font-weight-extra-bold text-12 line-height-2 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="500">Modern AI powered exam simulation platform</h1>
                            <div class="appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="700">
                                <p class="pb-3 mb-4 text-color-light opacity-6 text-5">Porto is used by several
                                    companies to manage their projects.</p>
                            </div>
                            <a href="#"
                                class="px-5 py-3 btn btn-light text-color-dark custom-secondary-font font-weight-bold text-3 appear-animation"
                                data-appear-animation="fadeInRightShorter" data-appear-animation-delay="900">GET
                                STARTED</a>
                            <a href="#"
                                class="px-5 py-3 ml-4 btn btn-dark custom-secondary-font font-weight-bold text-3 appear-animation"
                                data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="900">VIEW OUR
                                PLANS</a>
                        </div>
                    </div>
                </div>

                <div class="custom-animated-circles">
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                </div>

            </section>

            <div class="custom-screens-carousel">
                <div class="container">
                    <div class="text-center row justify-content-center">
                        <div class="col appear-animation" data-appear-animation="fadeInUpShorter"
                            data-appear-animation-delay="200">
                            <div class="carousel-ipad">
                                <div class="carousel-ipad-camera"></div>
                                <div class="m-0 owl-carousel owl-theme nav-style-1"
                                    data-plugin-options="{'autoHeight': true, 'items': 1, 'margin': 10, 'nav': false, 'dots': false, 'stagePadding': 0, 'animateOut': 'fadeOut', 'autoplay': true, 'autoplayTimeout': 3000}">
                                    <div>
                                        <img alt="" class="img-fluid rounded-0"
                                            src="{{ asset('img/demos/sass/screens/screen-1.jpg')}}">
                                    </div>
                                    <div>
                                        <img alt="" class="img-fluid rounded-0"
                                            src="{{ asset('img/demos/sass/screens/screen-2.jpg')}}">
                                    </div>
                                    <div>
                                        <img alt="" class="img-fluid rounded-0"
                                            src="{{ asset('img/demos/sass/screens/screen-3.jpg')}}">
                                    </div>
                                </div>
                                <div class="carousel-ipad-home"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container my-5">

                <div class="pb-3 mt-5 text-center row">

                    <p class="lead">Exam simulation for these IT certification providers is available on ExamBoot. Connect and Test yourself.</p>

                    <div class="owl-carousel owl-theme carousel-center-active-item"
                        data-plugin-options="{'responsive': {'0': {'items': 1}, '476': {'items': 1}, '768': {'items': 5}, '992': {'items': 7}, '1200': {'items': 7}}, 'autoplay': true, 'autoplayTimeout': 3000, 'dots': false}">
                        <div>
                            <img class="img-fluid" src="{{ asset('img/logos/logo-1.png')}}" alt="">
                        </div>
                        <div>
                            <img class="img-fluid" src="{{ asset('img/logos/logo-2.png')}}" alt="">
                        </div>
                        <div>
                            <img class="img-fluid" src="{{ asset('img/logos/logo-3.png')}}" alt="">
                        </div>
                        <div>
                            <img class="img-fluid" src="{{ asset('img/logos/logo-4.png')}}" alt="">
                        </div>
                        <div>
                            <img class="img-fluid" src="{{ asset('img/logos/logo-5.png')}}" alt="">
                        </div>
                        <div>
                            <img class="img-fluid" src="{{ asset('img/logos/logo-6.png')}}" alt="">
                        </div>
                        <div>
                            <img class="img-fluid" src="{{ asset('img/logos/logo-7.png')}}" alt="">
                        </div>
                        <div>
                            <img class="img-fluid" src="{{ asset('img/logos/logo-8.png')}}" alt="">
                        </div>
                        <div>
                            <img class="img-fluid" src="{{ asset('img/logos/logo-9.png')}}" alt="">
                        </div>
                        <div>
                            <img class="img-fluid" src="{{ asset('img/logos/logo-10.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <section class="m-0 mt-4 border-0 section p-relative" id="features">
                <div class="container">
                    <div class="py-2 row align-items-center justify-content-center">
                        <div class="text-center col-lg-6">
                            <img class="img-fluid" src="{{ asset('img/demos/sass/features/feature-1.png')}}"" alt="">
                        </div>
                        <div class="text-center col-lg-6 text-lg-left">
                            <h2 class="mb-1 text-color-dark font-weight-bold text-6 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="0">Unique, AI-connected exam simulation engine.</h2>
                            <p class="lead appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="200">

                            </p>
                            <p class="appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="400">
                                 <ul>
                                    <li>Access exam dumps database of over 30,000 questions through a configurable environment designed to replicate REAL exam conditions.</li>
                                    <li>With our exam engine, you can continuously simulate exams and test your readiness.</li>
                                    <li>Be ready for several professional certifications (CISSP, CISM, CISA, CCSP, CompTIA Security+, PMP and more).</li>
                                </ul>
                            </p>
                            <a href="https://examboot.net/boss/login"
                                class="px-5 py-3 mt-3 btn btn-dark custom-secondary-font font-weight-bold text-3 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="600">TEST YOUR SKILLS
                            </a>
                        </div>
                    </div>
                </div>

                <div
                    class="custom-animated-circles custom-animated-circles-primary custom-animated-circles-pos-3 d-none d-block-md">
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                </div>
            </section>

            <section class="m-0 bg-transparent border-0 section">
                <div class="container">
                    <div class="py-2 row align-items-center justify-content-center">
                        <div class="text-center col-lg-6 text-lg-left">
                            <h2 class="mb-1 text-color-dark font-weight-bold text-6 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="0">Drag n’ Drop
                                Features</h2>
                            <p class="lead appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="200">
                                Real-time performance analysis
                            </p>
                            <p class="appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="400">
                                <ul>
                                    <li>Track and visualize your progress over time, providing detailed statistics on your performance across different IT skill categories and subcategories.</li>
                                    <li>Enjoy AI-powered technology to help you analyze your performances.</li>
                               </ul>
                                                           </p>
                            <a href="https://examboot.net/boss/login"
                                class="px-5 py-3 mt-3 btn btn-dark custom-secondary-font font-weight-bold text-3 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="600">VIEW OUR
                                PLANS</a>
                        </div>
                        <div class="text-center col-lg-6">
                            <img class="img-fluid" src="{{ asset('img/demos/sass/features/feature-2.png')}}"" alt="">
                        </div>
                    </div>
                </div>
            </section>

            <section class="m-0 border-0 section">
                <div class="container">
                    <div class="py-2 row align-items-center justify-content-center">
                        <div class="text-center col-lg-6">
                            <img class="img-fluid" src="{{ asset('img/demos/sass/features/feature-3.png')}}"" alt="">
                        </div>
                        <div class="text-center col-lg-6 text-lg-left">
                            <h2 class="mb-1 text-color-dark font-weight-bold text-6 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="0">Advanced
                                Reporting Features</h2>
                            <p class="lead appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="200">Lorem ipsum dolor sit amet, consectetur adipiscing
                                elit massa enim.</p>
                            <p class="appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="400">Lorem ipsum dolor sit amet, consectetur adipiscing
                                elit. Phasellus blandit massa enim. Nullam id varius nunc.</p>
                            <a href="#"
                                class="px-5 py-3 mt-3 btn btn-dark custom-secondary-font font-weight-bold text-3 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="600">VIEW OUR
                                PLANS</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="m-0 bg-transparent border-0 section">
                <div class="container">
                    <div class="py-2 row align-items-center justify-content-center">
                        <div class="text-center col-lg-6 text-lg-left">
                            <h2 class="mb-1 text-color-dark font-weight-bold text-6 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="0">Drag n’ Drop
                                Features</h2>
                            <p class="lead appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="200">Lorem ipsum dolor sit amet, consectetur adipiscing
                                elit massa enim. Nullam id varius nunc.</p>
                            <p class="appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="400">Lorem ipsum dolor sit amet, consectetur adipiscing
                                elit. Phasellus blandit massa enim. Nullam id varius nunc. Vivamus bibendum magna ex, et
                                faucibus lacus venenatis eget.</p>
                            <a href="#"
                                class="px-5 py-3 mt-3 btn btn-dark custom-secondary-font font-weight-bold text-3 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="600">VIEW OUR
                                PLANS</a>
                        </div>
                        <div class="text-center col-lg-6">
                            <img class="img-fluid" src="{{ asset('img/demos/sass/features/feature-4.png')}}"" alt="">
                        </div>
                    </div>
                </div>
            </section>

            <!--
            <section class="m-0 border-0 section bg-dark section-height-3" id="overview">
                <div class="container-fluid">
                    <div class="row">
                        <div class="text-center col">
                            <h2 class="mb-1 text-color-light font-weight-bold text-7">Control your Business like a Pro
                            </h2>
                            <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="py-4 col-11">

                        <div class="mt-4 mb-3 owl-carousel owl-theme stage-margin stage-margin-lg nav-style-2"
                            data-plugin-options="{'responsive': {'0': {'items': 1}, '479': {'items': 1}, '768': {'items': 2}, '979': {'items': 2}, '1199': {'items': 2}}, 'margin': 50, 'loop': true, 'nav': true, 'dots': false, 'stagePadding': 100, 'autoplay': true, 'autoplayTimeout': 5000}">
                            <div>
                                <div class="carousel-ipad carousel-ipad-sm">
                                    <img alt="" class="img-fluid rounded-0"
                                        src="{{ asset('img/demos/sass/screens/screen-1.jpg')}}">
                                </div>
                            </div>
                            <div>
                                <div class="carousel-ipad carousel-ipad-sm">
                                    <img alt="" class="img-fluid rounded-0"
                                        src="{{ asset('img/demos/sass/screens/screen-2.jpg')}}">
                                </div>
                            </div>
                            <div>
                                <div class="carousel-ipad carousel-ipad-sm">
                                    <img alt="" class="img-fluid rounded-0"
                                        src="{{ asset('img/demos/sass/screens/screen-3.jpg')}}">
                                </div>
                            </div>
                            <div>
                                <div class="carousel-ipad carousel-ipad-sm">
                                    <img alt="" class="img-fluid rounded-0"
                                        src="{{ asset('img/demos/sass/screens/screen-4.jpg')}}">
                                </div>
                            </div>
                            <div>
                                <div class="carousel-ipad carousel-ipad-sm">
                                    <img alt="" class="img-fluid rounded-0"
                                        src="{{ asset('img/demos/sass/screens/screen-5.jpg')}}">
                                </div>
                            </div>
                            <div>
                                <div class="carousel-ipad carousel-ipad-sm">
                                    <img alt="" class="img-fluid rounded-0"
                                        src="{{ asset('img/demos/sass/screens/screen-6.jpg')}}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </section>
            -->

            <section class="m-0 border-0 section section-height-3" id="reviews">
                <div class="container">
                    <div class="py-2 row">
                        <div class="text-center col">
                            <h2 class="mb-1 text-color-dark font-weight-bold text-7">User Reviews</h2>
                            <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit massa enim.</p>
                        </div>
                    </div>
                    <div class="pt-3 row">
                        <div class="col-md-4 appear-animation" data-appear-animation="fadeInLeftShorter"
                            data-appear-animation-delay="500">
                            <div class="testimonial testimonial-light">
                                <blockquote class="blockquote-default">
                                    <p class="mb-0 text-default">Lorem ipsum dolor sit amet, consectetur adipiscing
                                        elit. Donec hendrerit vehicula.</p>
                                </blockquote>
                                <div class="testimonial-arrow-down"></div>
                                <div class="testimonial-author">
                                    <div class="testimonial-author-thumbnail">
                                        <img src="{{ asset('img/clients/client-1.jpg')}}" class="rounded-circle" alt="">
                                    </div>
                                    <p><strong class="font-weight-extra-bold text-dark">John Smith</strong><span
                                            class="text-default">CEO & Founder - Okler</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 appear-animation" data-appear-animation="fadeIn"
                            data-appear-animation-delay="300">
                            <div class="testimonial testimonial-primary">
                                <blockquote>
                                    <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec
                                        hendrerit vehicula est, in consequat.</p>
                                </blockquote>
                                <div class="testimonial-arrow-down"></div>
                                <div class="testimonial-author">
                                    <div class="testimonial-author-thumbnail">
                                        <img src="{{ asset('img/clients/client-2.jpg')}}" class="rounded-circle" alt="">
                                    </div>
                                    <p><strong class="font-weight-extra-bold">Jessica Smith</strong><span>CEO & Founder
                                            - Okler</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 appear-animation" data-appear-animation="fadeInRightShorter"
                            data-appear-animation-delay="500">
                            <div class="testimonial testimonial-light">
                                <blockquote class="blockquote-default">
                                    <p class="mb-0 text-default">Lorem ipsum dolor sit amet, consectetur adipiscing
                                        elit. Donec hendrerit vehicula.</p>
                                </blockquote>
                                <div class="testimonial-arrow-down"></div>
                                <div class="testimonial-author">
                                    <div class="testimonial-author-thumbnail">
                                        <img src="{{ asset('img/clients/client-3.jpg')}}" class="rounded-circle" alt="">
                                    </div>
                                    <p><strong class="font-weight-extra-bold text-dark">Paul Smith</strong><span
                                            class="text-default">CEO & Founder - Okler</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="m-0 bg-transparent border-0 section section-height-3" id="pricing">
                <div class="container">
                    <div class="py-2 row">
                        <div class="text-center col">
                            <h2 class="mb-1 text-color-dark font-weight-bold text-7">Pricing</h2>
                            <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit massa enim.</p>
                        </div>
                    </div>

                    <div class="py-5 pricing-table">
                        <div class="col-md-6 col-lg-3">
                            <div class="plan">
                                <div class="plan-header">
                                    <h3>Enterprise</h3>
                                </div>
                                <div class="plan-price">
                                    <span class="price"><span class="price-unit">$</span>59</span>
                                    <label class="price-label">PER MONTH</label>
                                </div>
                                <div class="plan-features">
                                    <ul>
                                        <li>10GB Disk Space</li>
                                        <li>100GB Monthly Bandwith</li>
                                        <li>20 Email Accounts</li>
                                        <li>Unlimited Subdomains</li>
                                    </ul>
                                </div>
                                <div class="plan-footer">
                                    <a href="#" class="px-4 py-2 btn btn-dark btn-modern btn-outline">Sign
                                        Up</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="plan">
                                <div class="plan-header">
                                    <h3>Professional</h3>
                                </div>
                                <div class="plan-price">
                                    <span class="price"><span class="price-unit">$</span>29</span>
                                    <label class="price-label">PER MONTH</label>
                                </div>
                                <div class="plan-features">
                                    <ul>
                                        <li>5GB Disk Space</li>
                                        <li>50GB Monthly Bandwith</li>
                                        <li>10 Email Accounts</li>
                                        <li>Unlimited Subdomains</li>
                                    </ul>
                                </div>
                                <div class="plan-footer">
                                    <a href="#" class="px-4 py-2 btn btn-dark btn-modern btn-outline">Sign
                                        Up</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="plan plan-featured">
                                <div class="plan-header bg-primary">
                                    <h3>Standard</h3>
                                </div>
                                <div class="plan-price">
                                    <span class="price"><span class="price-unit">$</span>17</span>
                                    <label class="price-label">PER MONTH</label>
                                </div>
                                <div class="plan-features">
                                    <ul>
                                        <li>3GB Disk Space</li>
                                        <li>25GB Monthly Bandwith</li>
                                        <li>5 Email Accounts</li>
                                        <li>Unlimited Subdomains</li>
                                    </ul>
                                </div>
                                <div class="plan-footer">
                                    <a href="#" class="px-4 py-2 btn btn-primary btn-modern">Sign Up</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="plan">
                                <div class="plan-header">
                                    <h3>Basic</h3>
                                </div>
                                <div class="plan-price">
                                    <span class="price"><span class="price-unit">$</span>9</span>
                                    <label class="price-label">PER MONTH</label>
                                </div>
                                <div class="plan-features">
                                    <ul>
                                        <li>1GB Disk Space</li>
                                        <li>10GB Monthly Bandwith</li>
                                        <li>2 Email Accounts</li>
                                        <li>Unlimited Subdomains</li>
                                    </ul>
                                </div>
                                <div class="plan-footer">
                                    <a href="#" class="px-4 py-2 btn btn-dark btn-modern btn-outline">Sign
                                        Up</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <section class="m-0 border-0 section section-height-3 curved-border" id="faqs">
                <div class="container">
                    <div class="py-2 row">
                        <div class="text-center col">
                            <h2 class="mb-1 text-color-dark font-weight-bold text-7">Frequent Asked Questions</h2>
                            <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit massa enim.</p>
                        </div>
                    </div>
                    <div class="py-3 row align-items-center justify-content-center">
                        <div class="col-sm-9">

                            <div class="toggle toggle-minimal toggle-primary" data-plugin-toggle>
                                <section class="toggle active">
                                    <a class="toggle-title">Curabitur eget leo at velit imperdiet vague iaculis
                                        vitaes?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                            Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at
                                            nunc <a href="#">vehicula</a> lacinia. Proin adipiscing porta tellus,
                                            ut feugiat nibh adipiscing sit amet. Lorem ipsum dolor sit amet, consectetur
                                            adipiscing elit. Curabitur pellentesque neque eget diam posuere porta.
                                            Quisque ut nulla at nunc <a href="#">vehicula</a> lacinia. Proin
                                            adipiscing porta tellus, ut feugiat nibh adipiscing sit amet.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">Curabitur eget leo at imperdiet vague iaculis vitaes?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                            Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue
                                            iaculis vitae at risus. Nullam tortor nunc, bibendum vitae semper a,
                                            volutpat eget massa. Lorem ipsum dolor sit amet, consectetur adipiscing
                                            elit. Integer fringilla, orci sit amet posuere auctor.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">Eget leo at imperdiet vague iaculis vitaes?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                            Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue
                                            iaculis vitae at risus. Nullam tortor nunc, bibendum vitae semper a,
                                            volutpat eget massa. Lorem ipsum dolor sit amet, consectetur adipiscing
                                            elit. Integer fringilla, orci sit amet posuere auctor.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">Leo at imperdiet vague iaculis vitaes?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                            Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue
                                            iaculis vitae at risus. Nullam tortor nunc, bibendum vitae semper a,
                                            volutpat eget massa. Lorem ipsum dolor sit amet, consectetur adipiscing
                                            elit. Integer fringilla, orci sit amet posuere auctor.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">Eget leo at imperdiet vague iaculis vitaes?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                            Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue
                                            iaculis vitae at risus. Nullam tortor nunc, bibendum vitae semper a,
                                            volutpat eget massa. Lorem ipsum dolor sit amet, consectetur adipiscing
                                            elit. Integer fringilla, orci sit amet posuere auctor.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">Imperdiet vague iaculis vitaes?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                            Curabitur eget leo at velit imperdiet varius. In eu ipsum vitae velit congue
                                            iaculis vitae at risus. Nullam tortor nunc, bibendum vitae semper a,
                                            volutpat eget massa. Lorem ipsum dolor sit amet, consectetur adipiscing
                                            elit. Integer fringilla, orci sit amet posuere auctor.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">Curabitur eget leo at velit imperdiet vague iaculis
                                        vitaes?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                            Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at
                                            nunc <a href="#">vehicula</a> lacinia. Proin adipiscing porta tellus,
                                            ut feugiat nibh adipiscing sit amet.</p>
                                    </div>
                                </section>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <section class="m-0 bg-transparent border-0 section section-height-3">
                <div class="container">
                    <div class="pt-2 row justify-content-center counters counters-lg">
                        <div class="col-md-3 appear-animation" data-appear-animation="fadeInLeftShorter"
                            data-appear-animation-delay="500">
                            <div class="counter counter-with-unit counter-unit-on-top">
                                <strong class="text-color-dark font-weight-extra-bold text-13 text-lg-15"
                                    data-to="100">0</strong>
                                <strong class="unit text-color-dark font-weight-bold text-5 text-lg-8">%</strong>
                                <label class="px-5 font-weight-normal text-3 text-lg-4 px-lg-4">Percent of happy users
                                    using Porto</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 appear-animation" data-appear-animation="fadeIn"
                            data-appear-animation-delay="300">
                            <div class="counter counter-with-unit counter-unit-on-top">
                                <strong class="text-color-dark font-weight-extra-bold text-13 text-lg-15"
                                    data-to="50">0</strong>
                                <strong class="unit text-color-dark font-weight-bold text-5 text-lg-8">+</strong>
                                <label class="px-5 font-weight-normal text-3 text-lg-4">Projects managed with
                                    Porto</label>
                            </div>
                        </div>
                        <div class="col-md-3 appear-animation" data-appear-animation="fadeInRightShorter"
                            data-appear-animation-delay="500">
                            <div class="counter counter-with-unit counter-unit-on-top">
                                <strong class="text-color-dark font-weight-extra-bold text-13 text-lg-15"
                                    data-to="350">0</strong>
                                <strong class="unit text-color-dark font-weight-bold text-5 text-lg-8">+</strong>
                                <label class="px-5 font-weight-normal text-3 text-lg-4">Number of clients around the
                                    world</label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                class="m-0 overflow-hidden border-0 section section-height-5 bg-primary curved-border curved-border-top"
                id="trial">
                <div class="custom-animated-circles custom-animated-circles-pos-2">
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                    <div class="circle"></div>
                </div>

                <img src="{{ asset('img/demos/sass/icons/icon-2.png')}}" class="img-fluid position-absolute" alt=""
                    data-plugin-float-element
                    data-plugin-options="{'startPos': 'none', 'speed': 3, 'transition': true}"
                    style="top: 29%; left: 26%;" />
                <img src="{{ asset('img/demos/sass/icons/icon-5.png')}}" class="img-fluid position-absolute" alt=""
                    data-plugin-float-element
                    data-plugin-options="{'startPos': 'none', 'speed': 3.5, 'transition': true, 'horizontal': true}"
                    style="top: 36%; left: 80%;" />
                <img src="{{ asset('img/demos/sass/icons/icon-6.png')}}" class="img-fluid position-absolute" alt=""
                    data-plugin-float-element
                    data-plugin-options="{'startPos': 'none', 'speed': 2.5, 'transition': true}"
                    style="top: 57%; left: 73%;" />

                <div class="container pb-5 mb-5">
                    <div class="pb-2 mb-4 row">
                        <div class="text-center col">
                            <h2 class="mb-2 text-color-light font-weight-bold text-10">Start Free Trial Now - 30 Days
                            </h2>
                            <p class="text-color-light opacity-6 text-4">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. </p>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <form class="contact-form custom-form-style-1 form-errors-light"
                                action="php/contact-form.php" method="POST">
                                <div class="mt-4 contact-form-success alert alert-success d-none">
                                    <strong>Success!</strong> Your message has been sent to us.
                                </div>

                                <div class="mt-4 contact-form-error alert alert-danger d-none">
                                    <strong>Error!</strong> There was an error sending your message.
                                    <span class="mail-error-message text-1 d-block"></span>
                                </div>

                                <input type="hidden" name="subject" id="subject"
                                    value="Contact Form - Start Free Trial" />
                                <div class="form-row">
                                    <div class="mb-2 form-group col">
                                        <input type="text" value=""
                                            data-msg-required="Please enter your name." maxlength="100"
                                            class="form-control" name="name" placeholder="Name" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="mb-2 form-group col">
                                        <input type="text" value=""
                                            data-msg-required="Please enter the phone." maxlength="100"
                                            class="form-control" name="phone" id="phone" placeholder="Phone"
                                            required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="mb-5 form-group col">
                                        <input type="email" value=""
                                            data-msg-required="Please enter your email address."
                                            data-msg-email="Please enter a valid email address." maxlength="100"
                                            class="form-control" name="email" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="text-center form-group col">
                                        <input type="submit" value="GET STARTED"
                                            class="px-5 py-3 btn btn-dark btn-modern custom-secondary-font text-3"
                                            data-loading-text="Loading...">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <!--
            <div class="container">
                <div class="row" style="margin-top: -130px;">

                    <div class="col-md-4">
                        <a href="#" class="text-decoration-none">
                            <div class="border-0 card border-radius-0 custom-box-shadow-1">
                                <div class="p-5 my-2 text-center card-body">
                                    <i class="mb-3 icon-screen-smartphone icons text-color-dark text-11 d-block"></i>
                                    <h4 class="pb-1 mb-2 text-color-primary font-weight-bold text-4">APP AVAILABLE</h4>
                                    <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit
                                        phasellus.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="#" class="text-decoration-none">
                            <div class="border-0 card border-radius-0 custom-box-shadow-1">
                                <div class="p-5 my-2 text-center card-body">
                                    <i class="mb-3 icon-magnifier icons text-color-dark text-11 d-block"></i>
                                    <h4 class="pb-1 mb-2 text-color-primary font-weight-bold text-4">KNOWLEDGE BASE
                                    </h4>
                                    <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit
                                        phasellus.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="#" class="text-decoration-none">
                            <div class="border-0 card border-radius-0 custom-box-shadow-1">
                                <div class="p-5 my-2 text-center card-body">
                                    <i class="mb-3 icon-screen-desktop icons text-color-dark text-11 d-block"></i>
                                    <h4 class="pb-1 mb-2 text-color-primary font-weight-bold text-4">USERS FORUM</h4>
                                    <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit
                                        phasellus.</p>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
            -->

        </div>

        <footer id="footer" class="border-0 bg-light">
            <div class="container my-4">
                <div class="py-5 row">
                    <div class="mb-5 col-md-6 col-lg-3 mb-lg-0">
                        <a href="#" class="text-decoration-none">
                            <img class="mt-4" alt="Porto Website Template" src="{{ asset('img/logo-footer-dark.png')}}"
                                height="42">
                        </a>
                    </div>
                    <div class="mb-5 col-6 col-lg-2 mb-lg-0">
                        <ul class="list list-icons list-icons-sm">
                            <li><i class="fas fa-angle-right text-color-default"></i><a href="page-services.html"
                                    class="ml-1 link-hover-style-1 text-color-default"> Our Services</a></li>
                            <li><i class="fas fa-angle-right text-color-default"></i><a href="about-us.html"
                                    class="ml-1 link-hover-style-1 text-color-default"> About Us</a></li>
                            <li><i class="fas fa-angle-right text-color-default"></i><a href="contact-us.html"
                                    class="ml-1 link-hover-style-1 text-color-default"> Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="mb-5 col-6 col-lg-2 mb-lg-0">
                        <ul class="list list-icons list-icons-sm">
                            <li><i class="fas fa-angle-right text-color-default"></i><a href="page-faq.html"
                                    class="ml-1 link-hover-style-1 text-color-default"> FAQ's</a></li>
                            <li><i class="fas fa-angle-right text-color-default"></i><a href="sitemap.html"
                                    class="ml-1 link-hover-style-1 text-color-default"> Sitemap</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div id="tweet" class="twitter" data-plugin-tweets
                            data-plugin-options="{'username': 'oklerthemes', 'count': 1, 'iconColor': 'text-color-primary'}">
                            <p>Please wait...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-copyright bg-light">
                <div class="container py-2">
                    <hr class="my-2">
                    <div class="pt-4 pb-5 row justify-content-between">
                        <div class="col-auto">
                            <p>© Copyright 2020. All Rights Reserved.</p>
                        </div>
                        <div class="col-auto">
                            <nav id="sub-menu">
                                <ul>
                                    <li><a href="page-faq.html" class="ml-1 text-color-default text-decoration-none">
                                            Terms and Conditions</a></li>
                                    <li><a href="sitemap.html" class="ml-1 text-color-default text-decoration-none">
                                            Privacy Policy</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
<x-f_js />
</body>

</html>
