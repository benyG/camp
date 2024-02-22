<x-filament-widgets::widget class="mx-auto fi-filament-info-widget">
    <x-filament::section>
        <div class="flex gap-x-6 gap-y-3 ">
            <div class="grid gap-y-2 ">
                <div class="flex items-center gap-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Classes
                    </span>
                </div>
                <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    {{$va}}
                </div>
            </div>
            <div class="grid gap-y-2">
                <div class="flex items-center gap-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                       Certifications
                    </span>
                </div>
                <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    {{$co}}
                </div>
            </div>
            <div class="grid gap-y-2">
                <div class="flex items-center gap-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                       Modules
                    </span>
                </div>
                <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    {{$mo}}
                </div>
            </div>
            <div class="grid gap-y-2">
                <div class="flex items-center gap-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                       Questions
                    </span>
                </div>
                <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    {{$qu}}
                </div>
            </div>
            <div class="grid gap-y-2">
                <div class="flex items-center gap-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                       AI Calls
                    </span>
                </div>
                <div class="text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    {{$iac}}
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
