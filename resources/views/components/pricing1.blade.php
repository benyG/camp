    <section class="bg-white dark:bg-gray-900"
        x-data='{month: true,bpma:{{ $ix->bp_amm }},bpya:{{ $ix->bp_amy }},spma:{{ $ix->sp_amm }},spya:{{ $ix->sp_amy }},ppma:{{ $ix->pp_amm }},ppya:{{ $ix->pp_amy }}}'>
        <div class="px-0 py-0">
            @if (auth()->user()->ex == 9)
            <div
                        class="flex flex-col p-6 text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow gap-y-2 dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                        <h3 class="text-2xl font-semibold">Basic</h3>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">{{ __('form.bp_t1') }}</p>
                        <div class="flex flex-row gap-2 mx-auto text-left">
                            <div class="mr-4 text-3xl font-extrabold text-black dark:text-white"
                                x-text="month? '$'+bpma:'$'+bpya"></div>
                        </div>
                        <a target="_blank"
                            x-bind:href="month ?
                                '{{ $ix->bp_ml . '?prefilled_email=' . urlencode(auth()->user()->email) }}' :
                                '{{ $ix->bp_yl . '?prefilled_email=' . urlencode(auth()->user()->email) }}'"
                            style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);font-variant: small-caps;"
                            class="text-xl grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                            {{ __('form.sub1') }}</a>
                    </div>
            @else
                <div class="flex items-center justify-center pb-4 gap-x-2">
                    <p class="font-light text-gray-500 dark:text-gray-400">Plan</p>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <button x-on:click="month = true"
                            x-bind:style="month ?
                                '--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);' : ''"
                            class="font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50">
                            {{ __('form.mon') }}
                        </button>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <button x-on:click="month = false"
                            x-bind:style="!month ?
                                '--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);' : ''"
                            class="font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50">
                            {{ __('form.ann') }}
                        </button>
                    </div>
                </div>
                <div class="grid gap-1 md:grid-flow-col">
                    <!-- Pricing Card -->
                    <div
                        class="flex flex-col p-6 text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow gap-y-2 dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                        <h3 class="text-2xl font-semibold">Basic</h3>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">{{ __('form.bp_t1') }}</p>
                        <div class="flex flex-row gap-2 mx-auto text-left">
                            <div class="mr-4 text-3xl font-extrabold text-black dark:text-white"
                                x-text="month? '$'+bpma:'$'+bpya"></div>
                            <div class="flex flex-col">
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('form.per') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('form.mo') }}</div>
                            </div>
                        </div>
                        <a target="_blank"
                            x-bind:href="month ?
                                '{{ $ix->bp_ml . '?prefilled_email=' . urlencode(auth()->user()->email) }}' :
                                '{{ $ix->bp_yl . '?prefilled_email=' . urlencode(auth()->user()->email) }}'"
                            style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);font-variant: small-caps;"
                            class="text-xl grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                            {{ __('form.sub1') }}</a>

                        <!-- List -->
                        <ul role="list" class="flex flex-col mx-auto mb-8 text-xs gap-y-4">
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->eca_b, 2, '0', 0) }}</span>
                                    {{ __('form.eca2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->iac_b, 2, '0', 0) }}</span>
                                    {{ __('form.iac2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->saa_b, 2, '0', 0) }}</span>
                                    {{ __('form.saa2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->maxts, 2, '0', 0) }}</span> min
                                    {{ __('form.mcde') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->maxs, 2, '0', 0) }}</span>
                                    {{ __('form.mqt') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->maxes, 2, '0', 0) }}</span>
                                    {{ __('form.mqe') }}</span>
                            </li>
                            @if ($ix->tec_b)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.tec2') }}</span>
                                </li>
                            @endif
                            @if ($ix->ftg_b)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.ftg2') }}</span>
                                </li>
                            @endif
                            @if ($ix->pa_b)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.pa2') }}</span>
                                </li>
                            @endif
                            @if ($ix->tga_b)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.tga2') }}</span>
                                </li>
                            @endif

                            @if ($ix->sta_b)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.sta2') }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <!-- Pricing Card -->

                    <div
                        class="flex flex-col p-6 text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow gap-y-2 dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                        <h3 class="text-2xl font-semibold">Standard</h3>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">{{ __('form.sp_t1') }}</p>
                        <div class="flex flex-row gap-2 mx-auto text-left">
                            <div class="mr-4 text-3xl font-extrabold text-black dark:text-white"
                                x-text="month? '$'+spma:'$'+spya"></div>
                            <div class="flex flex-col">
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('form.per') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('form.mo') }}</div>
                            </div>
                        </div>
                        <a target="_blank"
                            x-bind:href="month ?
                                '{{ $ix->sp_ml . '?prefilled_email=' . urlencode(auth()->user()->email) }}' :
                                '{{ $ix->sp_yl . '?prefilled_email=' . urlencode(auth()->user()->email) }}'"
                            style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);font-variant: small-caps;"
                            class="text-xl grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                            {{ __('form.sub1') }}</a>

                        <!-- List -->
                        <ul role="list" class="flex flex-col mx-auto mb-8 text-xs gap-y-4">
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span> <span class="font-bold">{{ str_pad($ix->eca_s, 2, '0', 0) }}</span>
                                    {{ __('form.eca2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->iac_s, 2, '0', 0) }}</span>
                                    {{ __('form.iac2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->saa_s, 2, '0', 0) }}</span>
                                    {{ __('form.saa2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->maxtv, 2, '0', 0) }}</span> min
                                    {{ __('form.mcde') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span> <span class="font-bold">{{ str_pad($ix->maxv, 2, '0', 0) }}</span>
                                    {{ __('form.mqt') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->maxev, 2, '0', 0) }}</span>
                                    {{ __('form.mqe') }}</span>
                            </li>
                            @if ($ix->tec_s)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.tec2') }}</span>
                                </li>
                            @endif
                            @if ($ix->ftg_s)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.ftg2') }}</span>
                                </li>
                            @endif
                            @if ($ix->pa_s)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.pa2') }}</span>
                                </li>
                            @endif

                        </ul>
                    </div>
                    <!-- Pricing Card -->
                    <div
                        class="flex flex-col p-6 text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow gap-y-2 dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                        <h3 class="text-2xl font-semibold">Premium</h3>

                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">{{ __('form.pp_t1') }}</p>
                        <div class="flex flex-row gap-2 mx-auto">
                            <div class="mr-4 text-3xl font-extrabold text-black dark:text-white"
                                x-text="month? '$'+ppma:'$'+ppya"></div>
                            <div class="flex flex-col">
                                <div class="text-xs text-left text-gray-500 dark:text-gray-400">{{ __('form.per') }}
                                </div>
                                <div class="text-xs text-left text-gray-500 dark:text-gray-400">{{ __('form.mo') }}
                                </div>
                            </div>
                        </div>
                        <a target="_blank"
                            x-bind:href="month ?
                                '{{ $ix->pp_ml . '?prefilled_email=' . urlencode(auth()->user()->email) }}' :
                                '{{ $ix->pp_yl . '?prefilled_email=' . urlencode(auth()->user()->email) }}'"
                            style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);font-variant: small-caps;"
                            class="text-xl grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                            {{ __('form.sub1') }}</a>

                        <!-- List -->
                        <ul role="list" class="flex flex-col mx-auto mb-8 text-xs gap-y-4">
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span class="font-bold">{{ str_pad($ix->eca_s, 2, '0', 0) }}</span>
                                <span>{{ __('form.eca2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span class="font-bold">{{ str_pad($ix->iac_s, 2, '0', 0) }}</span>
                                <span>{{ __('form.iac2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span class="font-bold">{{ str_pad($ix->saa_s, 2, '0', 0) }}</span>
                                <span>{{ __('form.saa2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span class="font-bold">{{ str_pad($ix->maxtv, 2, '0', 0) }}</span> min
                                <span>{{ __('form.mcde') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span class="font-bold">{{ str_pad($ix->maxv, 2, '0', 0) }}</span>
                                <span>{{ __('form.mqt') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span class="font-bold">{{ str_pad($ix->maxev, 2, '0', 0) }}</span>
                                <span>{{ __('form.mqe') }}</span>
                            </li>
                            @if ($ix->tec_p)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.tec2') }}</span>
                                </li>
                            @endif
                            @if ($ix->ftg_p)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.ftg2') }}</span>
                                </li>
                            @endif
                            @if ($ix->pa_p)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.pa2') }}</span>
                                </li>
                            @endif
                            @if ($ix->pa_p)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.pa2') }}</span>
                                </li>
                            @endif
                            @if ($ix->tga_p)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.tga2') }}</span>
                                </li>
                            @endif

                            @if ($ix->sta_p)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.sta2') }}</span>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </section>
