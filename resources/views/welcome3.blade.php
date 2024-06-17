<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Exam Boot- {{ __('main.w1') }}</title>
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
                                        <img class="opacity-0 header-logo-non-sticky" alt="ExamBoot"
                                            height="40" src="{{ asset('img/logo-default-slim-dark.png')}}">
                                        <img class="opacity-0 header-logo-sticky" alt="ExamBoot"
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
                                            <!--<li><a data-hash data-hash-offset="60" href="#overview"
                                                        class="dropdown-item">OVERVIEW</a></li>-->
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
                    data-appear-animation-delay="1100" style="top: 1%; right: 1%;">
                    <img src="{{ asset('img/demos/sass/icons/home-1.png')}}" class="img-fluid" alt=""
                        data-plugin-float-element
                        data-plugin-options="{'startPos': 'none', 'speed': 2, 'transition': true, 'horizontal': true}" />
                </div>
                <!--
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
                -->
                <div class="container pt-5 mt-5">
                    <div class="pt-5 mt-5 row justify-content-center">
                        <div class="pt-3 text-center col-lg-7">
                            <h1 class="text-color-light font-weight-extra-bold text-12 line-height-2 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="500">Modern <label class="text-color-green">AI connected</label> exam simulation platform</h1>
                            <div class="appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="700">
                                <p class="pb-3 mb-4 text-color-light opacity-6 text-5">Access a large database of realistic exam questions and unlock the power of AI to PASS your certification.</p>
                            </div>
                            <a href="https://examboot.net/boss/login"
                                class="px-5 py-3 ml-4 btn btn-dark custom-secondary-font font-weight-bold text-3 appear-animation"
                                data-appear-animation="fadeInLeftShorter" data-appear-animation-delay="900">SIGN UP FREE</a>
                            <a href="#pricing"
                                class="px-5 py-3 btn btn-light text-color-dark custom-secondary-font font-weight-bold text-3 appear-animation"
                                data-appear-animation="fadeInRightShorter" data-appear-animation-delay="900">VIEW OUR PLANS</a>

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
                                         <!--
                                         <img alt="" class="img-fluid rounded-0"
                                            src="{{ asset('img/demos/sass/screens/screen-1.jpg')}}">
                                          -->
                                            <iframe width="1007" height="592" src="https://www.youtube.com/embed/CeeR41CHzAA?si=N-GgPqPdpXSVDdOt" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

                                    </div>
                                     <!--
                                    <div>
                                        <img alt="" class="img-fluid rounded-0"
                                            src="{{ asset('img/demos/sass/screens/screen-2.jpg')}}">
                                    </div>
                                    <div>
                                        <img alt="" class="img-fluid rounded-0"
                                            src="{{ asset('img/demos/sass/screens/screen-3.jpg')}}">
                                    </div>
                                    -->
                                </div>
                                <div class="carousel-ipad-home"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container my-5">

                <div class="pb-3 mt-5 text-center row">

                    <p class="lead">Exam simulation for these certification providers is available on ExamBoot. Connect and Test yourself.</p>

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

            <section class="m-0 mt-4 bg-transparent border-0 section p-relative" id="features">
                <div class="container">
                    <div class="py-2 row align-items-center justify-content-center">
                        <div class="text-center col-lg-6">
                            <img class="img-fluid appear-animation custom-image-size"
                                data-appear-animation="fadeInUpShorter" src="{{ asset('img/demos/sass/features/feature-1.png')}}"" alt="">
                        </div>
                        <div class="text-center col-lg-6 text-lg-left">
                            <h2 class="mb-1 text-color-dark font-weight-bold text-6 appear-animation "
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="0">Unique, AI-connected <label class="text-color-green">exam simulation engine</label>.</h2>
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
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="0">Exciting and hands-on <label class="text-color-green">dashboard</label> experience.</h2>
                            <p class="lead appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="200">
                                <label class="text-color-green">Real-time performance analysis</label>
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
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="600">GET FREE ACCESS</a>
                        </div>
                        <div class="text-center col-lg-6">
                            <img class="img-fluid appear-animation custom-image-size"
                                data-appear-animation="fadeInUpShorter" src="{{ asset('img/demos/sass/features/feature-2.png')}}"" alt="">
                        </div>
                    </div>
                </div>
            </section>

            <section class="m-0 bg-transparent border-0 section">
                <div class="container">
                    <div class="py-2 row align-items-center justify-content-center">
                        <div class="text-center col-lg-6">
                            <img class="img-fluid appear-animation custom-image-size"
                                data-appear-animation="fadeInUpShorter" src="{{ asset('img/demos/sass/features/feature-3.png')}}"" alt="">
                        </div>
                        <div class="text-center col-lg-6 text-lg-left">
                            <h2 class="mb-1 text-color-dark font-weight-bold text-6 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="0">Get prepped like a pro with your <label class="text-color-green">AI coachs!</label></h2>
                            <p class="lead appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">
                                <label class="text-color-green">Instant Feedback and Explanations:</label>
                            </p>
                            <p class="appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="400">During your simulation, coach Ben and coach Becky will assist you vocaly and will provide you instant feedback to explain questions and answers.
                            </p>
                            <p class="lead appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">
                               <label class="text-color-green">Advices and anticipated actions:</label>
                            </p>
                            <p class="appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="400">Your coach analyzes your dashboard. He identifies your knowledge gaps, makes personalized recommendations on how best to prepare for the exam. Coach Ben or coach Becky can also create add assign you tests to do, based on your gaps and weaknesses.
                            </p>
                             <a href="https://examboot.net/boss/login"
                                class="px-5 py-3 mt-3 btn btn-dark custom-secondary-font font-weight-bold text-3 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="600">GET STARTED NOW</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="m-0 bg-transparent border-0 section">
                <div class="container">
                    <div class="py-2 row align-items-center justify-content-center">
                        <div class="text-center col-lg-6 text-lg-left">
                            <h2 class="mb-1 text-color-dark font-weight-bold text-6 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="0">Create customized exam and <label class="text-color-green">share the joy!</label></h2>
                            <p class="lead appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="200"><label class="text-color-green">Take advantage of the AI features of our exam engine with your network.</label></p>
                            <p class="appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="400">Examboot gives you the ability to import your own questions, create a group,
                                invite users or friends and share tests with them or on social networks. Questions are imported in JSON format.</p>
                            <a href="https://examboot.net/boss/login"
                                class="px-5 py-3 mt-3 btn btn-dark custom-secondary-font font-weight-bold text-3 appear-animation"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="600">JOIN AND SHARE</a>
                        </div>
                        <div class="text-center col-lg-6">
                            <img class="img-fluid appear-animation custom-image-size"
                                data-appear-animation="fadeInUpShorter" src="{{ asset('img/demos/sass/features/feature-4.png')}}"" alt="">
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
                            <p class="lead">They tried and they adopted</p>
                        </div>
                    </div>
                    <div class="pt-3 row">
                        <div class="col-md-4 appear-animation" data-appear-animation="fadeInLeftShorter"
                            data-appear-animation-delay="500">
                            <div class="testimonial testimonial-light">
                                <blockquote class="blockquote-default">
                                    <p class="mb-0 text-default">As an IT professional aiming for multiple certifications, ExamBoot has been a game-changer. The AI assistant is incredibly intuitive and provides clear explanations for complex questions. The simulation tests are spot on and mimic the real exam conditions perfectly. Highly recommend it to anyone serious about IT certifications!</p>
                                </blockquote>
                                <div class="testimonial-arrow-down"></div>
                                <div class="testimonial-author">
                                    <div class="testimonial-author-thumbnail">
                                        <img src="{{ asset('img/clients/client-1.jpg')}}" class="rounded-circle" alt="">
                                    </div>
                                    <p><strong class="font-weight-extra-bold text-dark">Jennifer Yol</strong><span
                                            class="text-default">Project Manager</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 appear-animation" data-appear-animation="fadeIn"
                            data-appear-animation-delay="300">
                            <div class="testimonial testimonial-primary">
                                <blockquote>
                                    <p class="mb-0">ExamBoot offers a comprehensive set of features for exam preparation. I especially appreciate the performance dashboard which helps me track my progress. The only reason I’m not giving it five stars is that I encountered a few minor bugs, but the support team was quick to address them. Overall, a solid tool for anyone preparing for IT exams.</p>
                                </blockquote>
                                <div class="testimonial-arrow-down"></div>
                                <div class="testimonial-author">
                                    <div class="testimonial-author-thumbnail">
                                        <img src="{{ asset('img/clients/client-2.jpg')}}" class="rounded-circle" alt="">
                                    </div>
                                    <p><strong class="font-weight-extra-bold">Joel Ngaska</strong><span>CIO Cameroon Airports </span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 appear-animation" data-appear-animation="fadeInRightShorter"
                            data-appear-animation-delay="500">
                            <div class="testimonial testimonial-light">
                                <blockquote class="blockquote-default">
                                    <p class="mb-0 text-default">ExamBoot is hands down the best platform I've used for certification preparation. The large database of questions and the ability to create and share exams with peers is fantastic. I passed my CISSP on the first try thanks to ExamBoot. Highly recommended!</p>
                                </blockquote>
                                <div class="testimonial-arrow-down"></div>
                                <div class="testimonial-author">
                                    <div class="testimonial-author-thumbnail">
                                        <img src="{{ asset('img/clients/client-3.jpg')}}" class="rounded-circle" alt="">
                                    </div>
                                    <p><strong class="font-weight-extra-bold text-dark">Luck Stanford</strong><span
                                            class="text-default">Information Security Architect</span></p>
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
                            <p class="lead">Choose the plan that fits your needs</p>
                        </div>
                    </div>

                    <div class="py-5 pricing-table">
                        <div class="col-md-6 col-lg-3">
                            <div class="plan">
                                <div class="plan-header">
                                    <h3>Free</h3>
                                </div>
                                <div class="plan-price">
                                    <span class="price"><span class="price-unit">$</span>0</span>
                                    <label class="price-label">FOREVER</label>
                                </div>
                                <div class="plan-features">
                                    <ul>
										<li>1 ECA Units</li>
										<li>15 AI Call credits/month</li>
										<li>10 Max questions per Test.</li>
										<li>AI: Assistance during test</li>
										<li>AI: Speech synthesis.</li>
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
                                    <h3>Basic</h3>
                                </div>
                                <div class="plan-price">
                                    <span class="price"><span class="price-unit">$</span>20</span>
                                    <label class="price-label">PER MONTH</label>
                                </div>
                                <div class="plan-features">
                                    <ul>
										<li>1 ECA Units</li>
										<li>50 AI Call credits/month</li>
										<li>30 Max questions per Test.</li>
										<li>AI: Assistance during test</li>
										<li>AI:Speech synthesis.</li>
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
                                <div class="plan-header bg-primary-green">
                                    <h3>Standard</h3>
                                </div>
                                <div class="plan-price">
                                    <span class="price"><span class="price-unit">$</span>25</span>
                                    <label class="price-label">PER MONTH</label>
                                </div>
                                <div class="plan-features">
                                    <ul>
										<li>1 ECA Units</li>
										<li>100 AI Call credits/month</li>
										<li>60 Max questions per Test.</li>
										<li>Access to Real world-Like exam simulation.</li>
										<li>AI: Assistance during test</li>
										<li>AI: Speech synthesis.</li>
										<li>AI: Assign tests based on failures.</li>
										<li>AI: Performance Analysis</li>
									</ul>
                                </div>
                                <div class="plan-footer">
                                    <a href="#" class="px-4 py-2 btn btn-primary btn-modern">Sign Up</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="plan">
                                <div class="plan-header bg-primary-gold">
                                    <h3>Premium</h3>
                                </div>
                                <div class="plan-price">
                                    <span class="price"><span class="price-unit">$</span>35</span>
                                    <label class="price-label">PER MONTH</label>
                                </div>
                                <div class="plan-features">
                                    <ul>
										<li>2 ECA Units</li>
										<li>200 AI Call credits/month</li>
										<li>100 Max questions per Test.</li>
										<li>Access to Real world-Like exam simulation</li>
										<li>AI: Assistance during test</li>
										<li>AI: Speech synthesis.</li>
										<li>AI: Assign tests based on failures.</li>
										<li>AI: Performance Analysis</li>
										<li>AI: Test assignment.</li>
										<li>Exam creation Laboratory.</li>
										<li>Support Service.</li>
									</ul>
                                </div>
                                <div class="plan-footer">
                                    <a href="#" class="px-4 py-2 btn btn-dark btn-modern btn-outline bg-primary-gold"><b>Start Free Trial</b></a>
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
                            <p class="lead">ExamBoot is a modern platform connected to artificial intelligence technology. It is designed to help you prepare for the most popular IT certification exams. ExamBoot allows you to run realistic exam simulations thanks to its simple and efficient exam-engine. it offers you dynamic performance monitoring and personalized assistance thanks to your integrated virtual coach. With more than 25,000 questions from exams delivered by multiple providers, ExamBoot guarantees you in-depth and optimal preparation for a wide range of IT certifications.</p>
                        </div>
                    </div>
                    <div class="py-3 row align-items-center justify-content-center">
                        <div class="col-sm-9">

                            <div class="toggle toggle-minimal toggle-primary" data-plugin-toggle>
                                <section class="toggle active">
                                    <a class="toggle-title">What is ExamBoot and how does it help with IT certification exams?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">ExamBoot is a modern platform integrated with artificial intelligence designed to help users prepare for IT certification exams. It offers realistic exam simulations, performance tracking, and personalized assistance through an AI coach. With over 25,000 questions from various certification providers, ExamBoot ensures thorough preparation for a wide range of IT certifications.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">How do I interact with the AI coach?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">Actions for interacting with the AI coach are visible in various parts of your user interface. To interact with the AI Coach, you will need enough “AI Call Credits”. These credits allow you to invoke the Coach API, allowing you to get voice explanations of questions, performance analytics, and personalized test recommendations. You can purchase IA Call credits through your ExamBoot account.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">What are ECA units and how do they work?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">“Exam Config Activation Units” (ECA Units) are required to add a certification exam setup to your portfolio. Using ECA units, you can activate a specific certification exam, such as CISSP, CISM, PMP. Once the configuration is present in your portfolio, you will be able to generate and execute several exam simulations for this certification. For each certification exam you wish to prepare for, an ECA unit will be required. When you pay for a plan, that plan comes with a fixed number of non-renewable ECA units.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">How can I purchase IA Call credits and ECA units?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">You can purchase IA Call credits and ECA units directly through your ExamBoot account. Go to 'Settings' or 'Billings', and select 'ADD' button to start the process.
                                        Various payment options are available for your convenience. The payment process is outsourced and processed by the renowned Stripe platform. ExamBoot does not store any of your data and benefits from all the security of Stripe.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">What happens if I run out of IA Call credits or ECA units?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">If you run out of IA Call credits, you will not be able to interact with AI features until you purchase more credits. Similarly, if you run out of ECA units, you won't be able to add new certification exam configuration to your portfolio. You can easily purchase additional credits or units at any time through your account 'Settings' or 'Billings' section.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">Can I share my exam simulations and results with others?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">Yes, ExamBoot allows you to share your exam simulations and results with others. You can create custom exams, invite members to join your group, and share your tests on social media. This feature not only helps in collaborative learning but also adds a fun and interactive dimension to your exam preparation.</p>
                                    </div>
                                </section>
                                <section class="toggle">
                                    <a class="toggle-title">How does the performance dashboard work?</a>
                                    <div class="toggle-content">
                                        <p class="pb-3">The performance dashboard provides a detailed overview of your exam preparation progress. It displays your performance metrics, such as scores and completion rates, in an easy-to-understand graphical format. You can filter your results by certification and domain to identify areas where you need to focus more. Your AI coach also uses this data to offer personalized recommendations and to generate tests to help you improve.</p>
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
                                <strong class="text-color-green font-weight-extra-bold text-13 text-lg-15"
                                    data-to="98" style="color: #22C45D;">0</strong>
                                <strong class="unit text-color-dark font-weight-bold text-5 text-lg-8" style="color: #22C45D;">%</strong>
                                <label class="px-5 font-weight-normal text-3 text-lg-4 px-lg-4">Percent of happy users using ExamBoot</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-3 appear-animation" data-appear-animation="fadeIn"
                            data-appear-animation-delay="300">
                            <div class="counter counter-with-unit counter-unit-on-top">
                                <strong class="text-color-green font-weight-extra-bold text-13 text-lg-15"
                                    data-to="25232" style="color: #22C45D;">0</strong>
                                <strong class="unit text-color-dark font-weight-bold text-5 text-lg-8" style="color: #22C45D;">+</strong>
                                <label class="px-5 font-weight-normal text-3 text-lg-4">Number of questions in our database</label>
                            </div>
                        </div>
                        <div class="col-md-3 appear-animation" data-appear-animation="fadeInRightShorter"
                            data-appear-animation-delay="500">
                            <div class="counter counter-with-unit counter-unit-on-top">
                                <strong class="text-color-green font-weight-extra-bold text-13 text-lg-15"
                                    data-to="132" style="color: #22C45D;">0</strong>
                                <strong class="unit text-color-dark font-weight-bold text-5 text-lg-8" style="color: #22C45D;" >+</strong>
                                <label class="px-5 font-weight-normal text-3 text-lg-4">Popular certifications available</label>
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
                <!--
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
                 -->
                <div class="container pb-5 mb-5">
                    <div class="pb-2 mb-4 row">
                        <div class="text-center col">
                            <h2 class="mb-2 text-color-light font-weight-bold text-10">Contact Us
                            </h2>
                            <p class="text-color-light opacity-6 text-4"> </p>
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
                                    value="Contact Form" />
                                <div class="form-row">
                                    <div class="mb-2 form-group col">
                                        <input type="text" value=""
                                            data-msg-required="Please enter your name." maxlength="100"
                                            class="form-control" name="name" placeholder="Name" required>
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
                                        <input type="submit" value="GET IN TOUCH"
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
                            <img class="mt-4" alt="ExamBoot Website Template" src="{{ asset('img/logo-footer-dark.png')}}"
                                height="42">
                        </a>
                    </div>
                    <div class="mb-5 col-6 col-lg-2 mb-lg-0">
                        <ul class="list list-icons list-icons-sm">
                            <li><i class="fas fa-angle-right text-color-default"></i><a href="#features"
                                    class="ml-1 link-hover-style-1 text-color-default"> Our Services</a></li>
                            <li><i class="fas fa-angle-right text-color-default"></i><a href="#intro"
                                    class="ml-1 link-hover-style-1 text-color-default"> About Us</a></li>
                            <li><i class="fas fa-angle-right text-color-default"></i><a href="#trial"
                                    class="ml-1 link-hover-style-1 text-color-default"> Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="mb-5 col-6 col-lg-2 mb-lg-0">
                        <ul class="list list-icons list-icons-sm">
                            <li><i class="fas fa-angle-right text-color-default"></i><a href="#faqs"
                                    class="ml-1 link-hover-style-1 text-color-default"> FAQ's</a></li>
                            <li><i class="fas fa-angle-right text-color-default"></i><a href="#reviews"
                                    class="ml-1 link-hover-style-1 text-color-default"> Sitemap</a></li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="footer-copyright bg-light">
                <div class="container py-2">
                    <hr class="my-2">
                    <div class="pt-4 pb-5 row justify-content-between">
                        <div class="col-auto">
                            <p>© Copyright 2024. All Rights Reserved.</p>
                        </div>
                        <div class="col-auto">
                            <nav id="sub-menu">
                                <ul>
                                    <li><a href="condition.html" class="ml-1 text-color-green text-decoration-none">
                                            Terms and Conditions</a></li>
                                    <li><a href="privacy.html" class="ml-1 text-color-green text-decoration-none">
                                            Privacy Policy</a></li>
                                    <li><a href="dmca.html" class="ml-1 text-color-green text-decoration-none">
                                            DMCA Copyright</a></li>
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
