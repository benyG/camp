<div class='fi-fo-field-wrp'>
    <div class='grid gap-y-2'>
        <div class='flex items-center justify-between gap-x-3'>
            <x-filament-forms::field-wrapper.label>
                {{ $field->getLabel() }}
                </x-filament-forms::field-wrapper.label>
        </div>
        <div class='flex flex-row items-center gap-x-2'>
            <div class='text-sm leading-6 fi-fo-placeholder'>
                {{ $getContent() }}
            </div>
               @if (count($field->getHintActions()))
        <div class="flex items-center gap-3 fi-fo-field-wrp-hint-action">
            @foreach ($field->getHintActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    @endif
        </div>
    </div>
</div>
