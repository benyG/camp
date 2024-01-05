@php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-2">
            <x-filament-panels::avatar.user size="lg" :user="$user" />

            <div class="flex-1">
                <h2 class="grid text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    Welcome
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ filament()->getUserName($user) }}
                </p>
            </div>
<div class="flex-1">
                <h2 class="grid flex-1 text-xs font-semibold leading-6 text-center text-gray-950 dark:text-white">Rank</h2>
               <div class="flex justify-center"  >
                <span class="text-sm italic border rounded-full
                {{ match ($user->ex) {0 => 'bg-gray-100 text-gray-800 font-medium me-2 px-2.5 py-0.5 dark:bg-gray-700 dark:text-gray-400 border border-gray-500',
                1 => 'bg-purple-100 text-pu font-medium me-2 px-2.5 py-0.5 dark:bg-gray-700 dark:text-purple-400 border-purple-400',
                2 => 'bg-green-100 text-g font-medium me-2 px-2.5 py-0.5 dark:bg-gray-700 dark:text-green-400 border-green-400',
                3 => 'bg-blue-100 text- font-medium me-2 px-2.5 py-0.5 dark:bg-gray-700 dark:text-blue-400 border-blue-400',
                4 => 'bg-red-100 text font-medium me-2 px-2.5 py-0.5 dark:bg-gray-700 dark:text-red-400 border-red-400',
                5 => 'bg-yellow-100 text-ye font-medium me-2 px-2.5 py-0.5 dark:bg-gray-700 dark:text-yellow-300 border-yellow-300'} }}">
                    {{ match ($user->ex) {0 => 'S. Admin',1 => 'Admin',2 => 'Starter',3 => 'User',4 => 'Pro',5 => 'VIP'} }}
                </span>
               </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
