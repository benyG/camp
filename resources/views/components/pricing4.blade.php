<div
class="flex flex-col p-6 text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow gap-y-2 dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
<div class="flex items-center justify-center mb-5">
    <div class="p-3 rounded-full fi-color-custom bg-custom-100 dark:bg-custom-500/20 fi-color-danger"
        style="--c-100:var(--danger-100);--c-400:var(--danger-400);--c-500:var(--danger-500);--c-600:var(--danger-600);">
        <svg class="w-6 h-6 fi-modal-icon text-custom-600 dark:text-custom-400"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" aria-hidden="true" data-slot="icon">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88">
            </path>
        </svg>
    </div>
</div>
<h3 class="text-2xl font-semibold">{{ __('form.reg') }}</h3>
<p class="text-sm font-light text-gray-500 dark:text-gray-400">{{ __('form.e34') }}</p>
<a href="{{ \App\Filament\Pages\Guest::getUrl() }}"
    style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);font-variant: small-caps;"
    class="text-lg grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
    {{ __('main.w9') }}</a>
</div>
