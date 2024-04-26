<div class="flex items-center">
    <div style="height:2px;" class="w-full bg-gray-200 dark:bg-gray-700"></div>
    <div class="px-5 text-center text-gray-950 dark:text-gray-400">&nbsp;&nbsp;{{ __('main.lo2') }}&nbsp;&nbsp;</div>
    <div style="height:2px;" class="w-full bg-gray-200 dark:bg-gray-700"></div>
</div>
<div class="flex flex-col justify-center gap-y-3">
    <div class>
        <form method="post" action="{{ route('oauth.redirect', ['driver' => 'google']) }}"> @csrf
            <button
                class="w-full text-sm rounded-lg inline-flex items-center justify-center
                fi-btn relative font-semibold outline-none transition duration-75 focus-visible:ring-2 fi-color-gray fi-btn-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-action fi-ac-btn-action"
                type="submit">
                <svg class="w-5 h-5" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                {{ __('main.lo3') }}
            </button>
        </form>
    </div>
    <div>
        <form method="post" action="{{ route('oauth.redirect', ['driver' => 'linkedin']) }}"> @csrf
            <button
                class="w-full text-sm rounded-lg inline-flex items-center justify-center
                fi-btn relative font-semibold outline-none transition duration-75 focus-visible:ring-2 fi-color-gray fi-btn-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-action fi-ac-btn-action"
                type="submit">
                <img src="{{ asset('img/li.png') }}" class="h-5" style='padding:1px;' />
                {{ __('main.lo4') }}
            </button>
        </form>
    </div>
    <div>
        <form method="post" wire:submit="authenticate2"> @csrf
            <button
                class="w-full text-sm rounded-lg inline-flex items-center justify-center
                fi-btn relative font-semibold outline-none transition duration-75 focus-visible:ring-2 fi-color-gray fi-btn-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-action fi-ac-btn-action"
                type="submit" wire:loading.attr="disabled">
                <x-filament::icon icon="heroicon-o-user-circle" class="w-6 h-6 text-white" />
                {{ __('main.lo5') }}
                <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    class="animate-spin fi-btn-icon transition duration-75 h-5 w-5 text-white"
                   wire:loading>
                    <path clip-rule="evenodd"
                        d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                        fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                    <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
                </svg>
            </button>
        </form>
    </div>
</div>
<div style="height:2px;" class="w-full bg-gray-200 dark:bg-gray-700"></div>
