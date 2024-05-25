<x-filament-widgets::widget class="fi-filament-info-widget">
    <x-filament::section>
        <div class="flex gap-x-1">
            <div class="grid gap-y-2">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400 ">
                         {{ __('main.qa') }}
                    </span>
                </div>
                <div style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                  class="text-3xl font-semibold tracking-tight text-custom-500 dark:text-custom-400">
                    {{$va}}
                </div>
            </div>
            <div class="grid gap-y-2">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('main.el') }}
                    </span>
                </div>
                <div style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                class="text-3xl font-semibold tracking-tight text-custom-500 dark:text-custom-400">
                    {{$co}}
                </div>
            </div>
            <div class="grid gap-y-2">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('main.tl') }}
                    </span>
                </div>
                <div style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                class="text-3xl font-semibold tracking-tight text-custom-500 dark:text-custom-400">
                    {{$mo}}
                </div>
            </div>
            <div class="grid gap-y-2">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('main.es') }}
                    </span>
                </div>
                <div style="--c-400:var(--{{$co1}}-400);--c-500:var(--{{$co1}}-500);--c-600:var(--{{$co1}}-600);"
                class="text-3xl font-semibold tracking-tight text-custom-500 dark:text-custom-400">
                    {{$qu}}
                </div>
            </div>
            <div class="grid gap-y-2">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                     % {{ __('main.ga') }}
                    </span>
                </div>
                <div style="--c-400:var(--{{$co2}}-400);--c-500:var(--{{$co2}}-500);--c-600:var(--{{$co2}}-600);"
                class="text-2xl font-semibold tracking-tight text-custom-500 dark:text-custom-400">
                    {{$iac}}
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
