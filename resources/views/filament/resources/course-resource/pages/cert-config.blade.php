<x-filament-panels::page>
<div>
    <form wire:submit="create">
        {{ $this->form }}
<br/><br/>
<x-filament::button type="submit" color="success">
    Save
</x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
</x-filament-panels::page>
