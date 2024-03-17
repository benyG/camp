<div class="flex items-center">
    <div style="height:2px;" class="w-full bg-gray-200 dark:bg-gray-700"></div>
    <div class="px-5 text-center text-gray-950 dark:text-gray-400">&nbsp;&nbsp;or&nbsp;&nbsp;</div>
    <div style="height:2px;" class="w-full bg-gray-200 dark:bg-gray-700"></div>
</div>
<div class="flex flex-col justify-center gap-y-3">
    <div class>
        <form method="post" action="{{ route('oauth.redirect', ['driver' => 'google']) }}"> @csrf
            <button
                class="w-full text-sm rounded-lg inline-flex items-center justify-center
                fi-btn relative font-semibold outline-none transition duration-75 focus-visible:ring-2 fi-color-gray fi-btn-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-action fi-ac-btn-action"
                type="submit">
                <svg class="w-5 h-5 mr-4" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_13183_10121)">
                        <path
                            d="M20.3081 10.2303C20.3081 9.55056 20.253 8.86711 20.1354 8.19836H10.7031V12.0492H16.1046C15.8804 13.2911 15.1602 14.3898 14.1057 15.0879V17.5866H17.3282C19.2205 15.8449 20.3081 13.2728 20.3081 10.2303Z"
                            fill="#3F83F8"></path>
                        <path
                            d="M10.7019 20.0006C13.3989 20.0006 15.6734 19.1151 17.3306 17.5865L14.1081 15.0879C13.2115 15.6979 12.0541 16.0433 10.7056 16.0433C8.09669 16.0433 5.88468 14.2832 5.091 11.9169H1.76562V14.4927C3.46322 17.8695 6.92087 20.0006 10.7019 20.0006V20.0006Z"
                            fill="#34A853"></path>
                        <path
                            d="M5.08857 11.9169C4.66969 10.6749 4.66969 9.33008 5.08857 8.08811V5.51233H1.76688C0.348541 8.33798 0.348541 11.667 1.76688 14.4927L5.08857 11.9169V11.9169Z"
                            fill="#FBBC04"></path>
                        <path
                            d="M10.7019 3.95805C12.1276 3.936 13.5055 4.47247 14.538 5.45722L17.393 2.60218C15.5852 0.904587 13.1858 -0.0287217 10.7019 0.000673888C6.92087 0.000673888 3.46322 2.13185 1.76562 5.51234L5.08732 8.08813C5.87733 5.71811 8.09302 3.95805 10.7019 3.95805V3.95805Z"
                            fill="#EA4335"></path>
                    </g>
                    <defs>
                        <clipPath id="clip0_13183_10121">
                            <rect width="20" height="20" fill="white" transform="translate(0.5)"></rect>
                        </clipPath>
                    </defs>
                </svg>
               Continue with Google
            </button>
        </form>
    </div>
    <div>
        <form method="post" action="{{ route('oauth.redirect', ['driver' => 'linkedinopenid']) }}"> @csrf
            <button
                class="w-full text-sm rounded-lg inline-flex items-center justify-center
                fi-btn relative font-semibold outline-none transition duration-75 focus-visible:ring-2 fi-color-gray fi-btn-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-action fi-ac-btn-action"
                type="submit">
<svg
                                xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-4" x="0px" y="0px"
                                viewBox="0,0,256,256">
                                <g transform="translate(-26.66667,-26.66667) scale(1.20833,1.20833)">
                                    <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1"
                                        stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10"
                                        stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none"
                                        font-size="none" text-anchor="none" style="mix-blend-mode:normal">
                                        <g transform="scale(5.33333,5.33333)">
                                            <path
                                                d="M42,37c0,2.762 -2.238,5 -5,5h-26c-2.761,0 -5,-2.238 -5,-5v-26c0,-2.762 2.239,-5 5,-5h26c2.762,0 5,2.238 5,5z"
                                                fill="#0288d1"></path>
                                            <path
                                                d="M12,19h5v17h-5zM14.485,17h-0.028c-1.492,0 -2.457,-1.112 -2.457,-2.501c0,-1.419 0.995,-2.499 2.514,-2.499c1.521,0 2.458,1.08 2.486,2.499c0,1.388 -0.965,2.501 -2.515,2.501zM36,36h-5v-9.099c0,-2.198 -1.225,-3.698 -3.192,-3.698c-1.501,0 -2.313,1.012 -2.707,1.99c-0.144,0.35 -0.101,1.318 -0.101,1.807v9h-5v-17h5v2.616c0.721,-1.116 1.85,-2.616 4.738,-2.616c3.578,0 6.261,2.25 6.261,7.274l0.001,9.726z"
                                                fill="#ffffff"></path>
                                        </g>
                                    </g>
                                </g>
                            </svg>              Continue with LinkedIn
            </button>
        </form>
    </div>
</div>
<x-footer2 />
