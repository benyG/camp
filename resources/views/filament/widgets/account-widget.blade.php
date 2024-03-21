@php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-2">
            <x-filament-panels::avatar.user size="lg" :user="$user" />

            <div class="flex-1">
                <h2 class="grid text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ __('main.wel') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ filament()->getUserName($user) }}
                </p>
                <p class="text-xs italic text-gray-500 dark:text-gray-400">
                   {{ __('main.si') }} {{ \Illuminate\Support\Carbon::parse($user->created_at)->locale('fr')->isoFormat('llll') }}
                </p>
                {{-- <a style='--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);'
                href="https://www.termsfeed.com/live/2214b8f2-eb0d-4feb-9afa-f4bdce142c37" target='_blank' class="text-xs italic text-custom-600 dark:text-custom-400">
                   {{ __('main.pol') }}
                </a> --}}
            </div>
<div class="flex-1">
                <h2 class="grid flex-1 text-xs font-semibold leading-6 text-center text-gray-950 dark:text-white">{{ __('main.rk') }}</h2>
               <div class="flex justify-center"  >
                <span
                {!! match ($user->ex) {0 => "style='--c-50:var(--warning-50);--c-400:var(--warning-400);--c-600:var(--warning-600);'",
                1 => "style='--c-50:var(--gray-50);--c-400:var(--gray-400);--c-600:var(--gray-600);'",
                2 => "style='--c-50:var(--info-50);--c-400:var(--info-400);--c-600:var(--info-600);'",
                3 => "style='--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);'",
                4 => "style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);'",
                5 => "style='--c-50:var(--warning-50);--c-400:var(--warning-400);--c-600:var(--warning-600);'"} !!}
                class="rounded-full text-sm font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1  bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30"
                >
                    {{ match ($user->ex) {0 => 'S. Admin',1 => 'Admin',2 => 'Starter',3 => 'User',4 => 'Pro',5 => 'VIP'} }}
                </span>
               </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
