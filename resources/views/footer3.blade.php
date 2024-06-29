<div class="flex items-center">
    <div style="height:2px;" class="w-full bg-gray-200 dark:bg-gray-700"></div>
    <div class="px-5 text-center text-gray-950 dark:text-gray-400">&nbsp;&nbsp;{{ __('main.lo2') }}&nbsp;&nbsp;</div>
    <div style="height:2px;" class="w-full bg-gray-200 dark:bg-gray-700"></div>
</div>
<div class="flex flex-col justify-center gap-y-3">
<x-footer1 />
    <div>
        <form method="post" wire:submit="authenticate2"> @csrf
            <button
                class="w-full text-sm rounded-lg inline-flex items-center justify-center
                fi-btn relative font-semibold outline-none transition duration-75 focus-visible:ring-2 fi-color-gray fi-btn-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-action fi-ac-btn-action"
                type="submit" wire:loading.attr="disabled">
                <x-filament::icon icon="heroicon-o-user-circle" class="w-6 h-6 text-gray-900 dark:text-white" />
                {{ __('main.lo5') }}
                <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-white transition duration-75 animate-spin fi-btn-icon" wire:loading
                    wire:target="authenticate2">
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
