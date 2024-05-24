<x-filament-panels::page>
<div>
    <form wire:submit="create">
        {{ $this->form }}
<br/><br/>
<x-filament::button type="submit" wire:target="create" color="success">
    {{__('form.sa')}}
</x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
</x-filament-panels::page>
