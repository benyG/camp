<div
    class="flex flex-col p-6 text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow gap-y-2 dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
    <div class="flex items-center justify-center mb-5">
        <div class="p-3 rounded-full fi-color-custom bg-custom-100 dark:bg-custom-500/20 fi-color-danger"
            style="--c-100:var(--danger-100);--c-400:var(--danger-400);--c-500:var(--danger-500);--c-600:var(--danger-600);">
            <x-filament::icon icon="heroicon-o-lock-closed" class="w-6 h-6 fi-modal-icon text-custom-600 dark:text-custom-400" />
        </div>
    </div>
    <h3 class="text-2xl font-semibold">{{ __('form.reg') }}</h3>
    <p class="text-sm font-light text-gray-500 dark:text-gray-400">{{ __('form.e34') }}</p>
    <a href="{{ \App\Filament\Pages\Guest::getUrl() }}"
        style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);font-variant: small-caps;"
        class="text-lg grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
        {{ __('form.reg2') }}</a>
    <div class='mt-4 text-xs text-center'>{{ __('main.lo6') }}
    <form action="{{filament()->getLogoutUrl()}}" method="post" class="inline">
    @csrf
        <x-filament::link tag='button' type='submit'>
            {{ __('form.lg') }}
        </x-filament::link>
        </form>
    </div>
</div>
