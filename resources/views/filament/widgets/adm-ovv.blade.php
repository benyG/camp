<x-filament-widgets::widget class="fi-filament-info-widget">
    <x-filament::section>
        <div class="flex gap-x-5 gap-y-3 " style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);">
            <div class="grid p-2 gap-y-2">
                <div class="flex items-center gap-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400 ">
                        Classes
                    </span>
                </div>
                <div
                  class="text-3xl font-semibold tracking-tight text-custom-500 dark:text-custom-400">
                    {{$va}}
                </div>
            </div>
            <div class="grid p-2 gap-y-2">
                <div class="flex items-center gap-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                       Certifications
                    </span>
                </div>
                <div class="text-3xl font-semibold tracking-tight text-custom-500 dark:text-custom-400">
                    {{$co}}
                </div>
            </div>
            <div class="grid p-2 gap-y-2">
                <div class="flex items-center gap-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                       Modules
                    </span>
                </div>
                <div class="text-3xl font-semibold tracking-tight text-custom-500 dark:text-custom-400">
                    {{$mo}}
                </div>
            </div>
            <div class="grid p-2 gap-y-2">
                <div class="flex items-center gap-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                       Questions
                    </span>
                </div>
                <div class="text-3xl font-semibold tracking-tight text-custom-500 dark:text-custom-400">
                    {{$qu}}
                </div>
            </div>
            <div class="grid p-2 gap-y-2">
                <div class="flex items-center gap-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                       AI Calls
                    </span>
                </div>
                <div class="text-3xl font-semibold tracking-tight text-custom-500 dark:text-custom-400">
                    {{$iac}}
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
