@php
    $ix = cache()->rememberForever('settings', function () {
        return \App\Models\Info::findOrFail(1);
    });
@endphp
@if ($ix->var1 == 1)
    <div class="flex justify-center">
        <span style='--c-50:var(--warning-50);--c-400:var(--warning-400);--c-600:var(--warning-600);'
            class="rounded-full text-sm font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1  bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30">
            DEV Environment
        </span>
    </div>
@endif
    <div class="">
        <x-lang />
    </div>

