@if (Auth::check() && auth()->user()->hasVerifiedEmail())
    <div class="grid content-end h-full grid-rows-3 text-xs text-center gap-y-1">
        <p class="m-0 italic">
            {{__('email.m7')}}
        </p>
        <p class="m-0 mb-1 text-[8px]">
            @ 2024 IT EXAM BOOT CAMP. All rights reserved.
        </p>

        <p style='--c-400:var(--primary-400);--c-600:var(--primary-600);' class="cursor-default">
            <a href="{{ env('APP_URL') }}" class="text-custom-600 dark:text-custom-400">{{__('main.home')}}</a>
            &bull;
            <a href="#" class="text-custom-600 dark:text-custom-400">Docs</a>
            &bull;
            <a href="#" class="text-custom-600 dark:text-custom-400">Twitter</a>
            &bull;
            <a href="#" class="text-custom-600 dark:text-custom-400">LinkedIn</a>
        </p>
    </div>
@endif
