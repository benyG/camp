<x-filament-panels::page.simple>
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}
    <x-ip />
        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
    <div class='-mt-4 text-xs text-center'>{{__('main.lo1')}} {{ $this->registerAction }}</div>
    <x-filament::link :href="filament()->getRequestPasswordResetUrl()" class="-mt-4"> {{ __('filament-panels::pages/auth/login.actions.request_password_reset.label') }}</x-filament::link>

   <div class='-mb-6' ><div style="height:2px;" class="w-full bg-gray-200 dark:bg-gray-700"></div><div class="mt-2"><x-footer2 /></div></div>
</x-filament-panels::page.simple>
