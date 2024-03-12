<div x-data="{
    toggle: function(event) {
        $refs.panel.toggle(event)
    },

    open: function(event) {
        $refs.panel.open(event)
    },

    close: function(event) {
        $refs.panel.close(event)
    },
}" class="fi-dropdown fi-user-menu">
    <div x-on:click="toggle" class="flex cursor-pointer fi-dropdown-trigger" aria-expanded="false">
        <button type="button" class="shrink-0" x-tooltip="{
                content: 'Translation',
                theme: $store.theme,
            }">
            <span
                class='object-cover object-center rounded-full fi-avatar fi-circular fi-user-avatar'>{{ strtoupper(app()->getLocale()) }}</span>
        </button>
    </div>
    <div x-float.placement.bottom-end.flip.teleport.offset="{ offset: 4 }" x-ref="panel"
        x-transition:enter-start="opacity-0" x-transition:leave-end="opacity-0"
        class="absolute z-10 transition bg-white divide-y divide-gray-100 rounded-lg shadow-lg fi-dropdown-panel ring-1 ring-gray-950/5 dark:divide-white/5 dark:bg-gray-900 dark:ring-white/10"
        style="position: fixed; display: none; left: 0px; top: 0px;">
        <div class="p-1 fi-dropdown-list">
            <form action="{{ route('lang') }}" method="post">
                @csrf
                <input type="hidden" name="lang" value="{{ app()->getLocale() == 'en' ? 'fr' : 'en' }}" />
                <button type="submit" style=";"
                    class="flex items-center w-full gap-1 p-1 text-sm transition-colors duration-75 rounded-md outline-none fi-dropdown-list-item whitespace-nowrap disabled:pointer-events-none disabled:opacity-70 fi-color-gray fi-dropdown-list-item-color-gray hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5">
                    <span
                        class="flex-1 text-gray-700 truncate fi-dropdown-list-item-label text-start dark:text-gray-200">
                        {{ strtoupper(app()->getLocale() == 'en' ? 'fr' : 'en') }}
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>
