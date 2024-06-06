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
        <div @if (FilamentView::hasSpaMode()) ax-load="visible"
                @else
                    ax-load @endif
            ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('chart', 'filament/widgets') }}"
            wire:ignore x-data="chart({
                cachedData: @js($this->getCachedData()),
                options: @js($this->getOptions()),
                type: @js($this->getType()),
            })" x-ignore @class([
                match ($color) {
                    'gray' => 'fi-color-gray',
                    default => 'fi-color-custom',
                },
            ])>

            <style>
                .gauge-wrapper {
                }

                .gauge {
                    background: #e7e7e7;
                    box-shadow: 0 -3px 6px 2px rgba(0, 0, 0, 0.50);
                    width: 400px;
                    height: 200px;
                    border-radius: 200px 200px 0 0 !important;
                    position: relative;
                    overflow: hidden;
                }

                .gauge.min-scaled {
                    transform: scale(0.5);
                }

                .gauge-center {
                    content: '';
                    width: 60%;
                    height: 60%;
                    border-radius: 200px 200px 0 0 !important;
                    position: absolute;
                    box-shadow: 0 -13px 15px -10px rgba(0, 0, 0, 0.28);
                    right: 21%;
                    bottom: 0;
                    z-index: 10;
                }
                .needle {
                    width: 160px;
                    height: 14px;
                    border-bottom-left-radius: 100% !important;
                    border-bottom-right-radius: 5px !important;
                    border-top-left-radius: 100% !important;
                    border-top-right-radius: 5px !important;
                    position: absolute;
                    bottom: -4px;
                    left: 40px;
                    transform-origin: 100% 8px;
                    transform: rotate(0deg);
                    box-shadow: 0 2px 2px 1px rgba(0, 0, 0, 0.38);
                    display: none;
                    z-index: 9;
                }

                .four.rischio1 .needle {
                    animation: fourspeed1 2s 1 both;
                    animation-delay: 1s;
                    display: block;
                }

                .slice-colors {
                    height: 100%;
                }

                .slice-colors .st {
                    position: absolute;
                    bottom: 0;
                    width: 0;
                    height: 0;
                    border: 100px solid transparent;
                }


                .four .slice-colors .st.slice-item:nth-child(2) {
                    border-top: 100px #f1c40f solid;
                    border-right: 100px #f1c40f solid;
                    background-color: #1eaa59;
                }

                .four .slice-colors .st.slice-item:nth-child(4) {
                    left: 50%;
                    border-bottom: 100px #E84C3D solid;
                    border-right: 100px #E84C3D solid;
                    background-color: #e67e22;
                }


                @-webkit-keyframes fourspeed1 {
                    0% {
                        transform: rotate(0);
                    }

                    100% {
                        transform: rotate({{$ang}}deg);
                    }
                }
            </style>

            <div class="flex justify-center gauge-wrapper">
                <div class="gauge four rischio1">
                    <div class="slice-colors">
                        <div class="st slice-item"></div>
                        <div class="st slice-item"></div>
                        <div class="st slice-item"></div>
                        <div class="st slice-item"></div>
                    </div>
                    <div class="bg-white needle dark:bg-gray-900"></div>
                    <div class="flex items-end justify-center bg-white gauge-center dark:bg-gray-900">
                        <div class="text-lg font-bold number">{{$iac}}</div>
                    </div>
                </div>
            </div>
            <span x-ref="backgroundColorElement" @class([
                match ($color) {
                    'gray' => 'text-gray-100 dark:text-gray-800',
                    default => 'text-custom-50 dark:text-custom-400/10',
                },
            ]) @style([
                \Filament\Support\get_color_css_variables($color, shades: [50, 400], alias: 'widgets::chart-widget.background') => $color !== 'gray',
            ])></span>

            <span x-ref="borderColorElement" @class([
                match ($color) {
                    'gray' => 'text-gray-400',
                    default => 'text-custom-500 dark:text-custom-400',
                },
            ]) @style([
                \Filament\Support\get_color_css_variables($color, shades: [400, 500], alias: 'widgets::chart-widget.border') => $color !== 'gray',
            ])></span>

            <span x-ref="gridColorElement" class="text-gray-200 dark:text-gray-800"></span>

            <span x-ref="textColorElement" class="text-gray-500 dark:text-gray-400"></span>
        </div>
        </div>
    </x-forms.section>
</x-filament-widgets::widget>
