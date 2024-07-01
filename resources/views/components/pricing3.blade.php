    <section class="bg-white dark:bg-gray-900">
        @if (auth()->user()->ex == 9)
           <x-pricing4/>
        @else
            <div
                class="flex flex-col p-6 text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow gap-y-2 dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                <img src="{{ asset('img/eca.png') }}" class="mx-auto" />
                <h3 class="text-lg font-semibold">{{ __('form.eca2') }}</h3>
                <p class="text-sm font-light text-gray-500 dark:text-gray-400">{{ __('form.eca3') }}</p>
                <div class="flex flex-row gap-2 mx-auto text-left">
                    <div class="text-3xl font-extrabold text-black dark:text-white">${{ $ix->eca_am }}</div>
                    <div class="flex flex-col">
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('form.per') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('form.un') }}</div>
                    </div>
                </div>
                <a  href="{{ $ix->eca_li . '?locale='.app()->getLocale().'&prefilled_email=' . urlencode(auth()->user()->email) }}"
                    style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);font-variant: small-caps;"
                    class="text-xl grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                    {{ __('form.pay') }}</a>
            </div>
        @endif
    </section>
