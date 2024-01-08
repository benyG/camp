<x-filament-widgets::widget class="fi-filament-info-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <div class="flex-1">
                <span class="font-serif text-lg italic font-bold">Platform</span>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    v1.2.65
                </p>
            </div>
            <div class="flex flex-col items-end gap-y-1">
                <x-filament::link
                    color="gray"
                    href="#"
                    icon="heroicon-m-play-circle"
                    icon-alias="panels::widgets.filament-info.open-documentation-button"
                    rel="noopener noreferrer"
                    target="_blank"
                >
                   Documentation
                </x-filament::link>

                <x-filament::link
                    color="gray"
                    href="#"
                    rel="noopener noreferrer"
                    target="_blank"
                >
                    <x-slot name="icon">
                      <svg xmlns="http://www.w3.org/2000/svg"  class="w-5 h-5 fill-slate-400" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </x-slot>
                    LinkedIn
                </x-filament::link>
                                <x-filament::link
                    color="gray"
                    href="#"
                    rel="noopener noreferrer"
                    target="_blank"
                >
                    <x-slot name="icon">
                        <svg viewBox="0 0 20 20" aria-hidden="true" class="w-5 h-5 fill-slate-400"><path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0 0 20 3.92a8.19 8.19 0 0 1-2.357.646 4.118 4.118 0 0 0 1.804-2.27 8.224 8.224 0 0 1-2.605.996 4.107 4.107 0 0 0-6.993 3.743 11.65 11.65 0 0 1-8.457-4.287 4.106 4.106 0 0 0 1.27 5.477A4.073 4.073 0 0 1 .8 7.713v.052a4.105 4.105 0 0 0 3.292 4.022 4.095 4.095 0 0 1-1.853.07 4.108 4.108 0 0 0 3.834 2.85A8.233 8.233 0 0 1 0 16.407a11.615 11.615 0 0 0 6.29 1.84"></path></svg>
                    </x-slot>
                    Twitter
                </x-filament::link>

            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
