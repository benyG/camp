@php
    use Filament\Support\Facades\FilamentView;

    $color = $this->getColor();
    $heading = $this->getHeading();
    $description = $this->getDescription();
    $filters = $this->getFilters();
@endphp

<x-filament-widgets::widget class="row-span-2 my-auto fi-wi-chart">
    <x-forms.section :description="$description" :heading="$heading">
            @if ($pollingInterval = $this->getPollingInterval())
                wire:poll.{{ $pollingInterval }}="updateChartData"
            @endif
            <div
                @if (FilamentView::hasSpaMode())
                    ax-load="visible"
                @else
                    ax-load
                @endif
                ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('chart', 'filament/widgets') }}"
                wire:ignore
                x-data="chart({
                            cachedData: @js($this->getCachedData()),
                            options: @js($this->getOptions()),
                            type: @js($this->getType()),
                        })"
                x-ignore
                @class([
                    match ($color) {
                        'gray' => 'fi-color-gray',
                        default => 'fi-color-custom',
                    },
                ])
            >
                <canvas
                    x-ref="canvas"
                    @if ($maxHeight = $this->getMaxHeight())
                        style="max-height: {{ $maxHeight }}"
                    @endif
                ></canvas>

                <span
                    x-ref="backgroundColorElement"
                    @class([
                        match ($color) {
                            'gray' => 'text-gray-100 dark:text-gray-800',
                            default => 'text-custom-50 dark:text-custom-400/10',
                        },
                    ])
                    @style([
                        \Filament\Support\get_color_css_variables(
                            $color,
                            shades: [50, 400],
                            alias: 'widgets::chart-widget.background',
                        ) => $color !== 'gray',
                    ])
                ></span>

                <span
                    x-ref="borderColorElement"
                    @class([
                        match ($color) {
                            'gray' => 'text-gray-400',
                            default => 'text-custom-500 dark:text-custom-400',
                        },
                    ])
                    @style([
                        \Filament\Support\get_color_css_variables(
                            $color,
                            shades: [400, 500],
                            alias: 'widgets::chart-widget.border',
                        ) => $color !== 'gray',
                    ])
                ></span>

                <span
                    x-ref="gridColorElement"
                    class="text-gray-200 dark:text-gray-800"
                ></span>

                <span
                    x-ref="textColorElement"
                    class="text-gray-500 dark:text-gray-400"
                ></span>
            </div>
        </div>
    </x-forms.section>
</x-filament-widgets::widget>
