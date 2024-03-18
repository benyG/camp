<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        {{--         <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot> --}}
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}
        <div x-data="{
            oxl() {
                fetch('https://ipapi.co/json/')
                    .then(response => response.json())
                    .then(data => {
                        var date = new Date();
                        var offsetMinutes = date.getTimezoneOffset();
                        var offsetHours = offsetMinutes / 60;
                        var sign = offsetHours >= 0 ? '-' : '+';
                        var offsetString = sign +
                            ('0' + Math.abs(offsetHours)).slice(-2) + ':' +
                            ('0' + (offsetMinutes % 60)).slice(-2);
                        $wire.ox = data;
                    })
                    .catch(error => console.error('Error', error));
            }
        }" x-init="oxl;">
        </div>
        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>
    <div class='-mt-4 text-xs text-right'>Don't have an account ? {{ $this->registerAction }}</div>
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
