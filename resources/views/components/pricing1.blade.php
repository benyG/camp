    <section class="bg-white dark:bg-gray-900"
        x-data='{month: true,bpma:{{ $ix->bp_amm }},bpya:{{ $ix->bp_amy }},spma:{{ $ix->sp_amm }},spya:{{ $ix->sp_amy }},ppma:{{ $ix->pp_amm }},ppya:{{ $ix->pp_amy }}}'>
        <div class="px-0 py-0">
            @if (auth()->user()->ex == 9)
                <x-pricing4 />
            @else
                <style>

                </style>
                <div class="flex items-center justify-center py-4 gap-x-2 md:py-0">
                    <p class="font-light text-gray-500 dark:text-gray-400">Plan</p>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <button x-on:click="month = true"
                        x-bind:class="month? 'text-white':'text-gray-500 dark:text-gray-400'"
                            x-bind:style="month ?
                                '--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);' : ''"
                            class="font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-gray-400 hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50">
                            {{ __('form.mon') }}
                        </button>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <button x-on:click="month = false"
                        x-bind:class="!month? 'text-white':'text-gray-500 dark:text-gray-400'"
                            x-bind:style="!month ?
                                '--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);' : ''"
                            class="font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-gray-400  hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50">
                            {{ __('form.ann') }}
                        </button>
                    </div>
                </div>
                <div
                    class="container hidden p-6 mx-auto overflow-x-auto text-gray-900 bg-gray-800 dark:bg-gray-100 dark:text-white md:block">
                    <table class="w-full table-auto">
                        <caption class="sr-only">Pricing plan comparison</caption>
                        <thead>
                            <tr>
                                <th></th>
                                <th scope="col">
                                    <h2 class="text-lg font-medium">
                                        <span
                                            style='--c-50:var(--info-50);--c-400:var(--info-400);--c-600:var(--info-600);'
                                            class="mx-1 rounded-lg ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1  bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30">
                                            Free
                                        </span>
                                    </h2>
                                    <p class="mb-3">
                                        <span
                                            class="text-lg font-bold sm:text-xl text-gray-50 dark:text-gray-900">0$</span>
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-600">/mo</span>
                                    </p>
                                </th>
                                <th scope="col">
                                    <h2 class="text-lg font-medium">
                                        <span
                                            style='--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);'
                                            class=" mx-1 rounded-lg ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1  bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30">
                                            Basic
                                        </span>
                                    </h2>
                                    <p class="mb-3">
                                        <span class="text-lg font-bold sm:text-xl text-gray-50 dark:text-gray-900"><span
                                                x-text="month? '$'+bpma:'$'+bpya"></span>$</span>
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-600">/mo</span>
                                    </p>
                                </th>
                                <th scope="col">
                                    <h2 class="text-lg font-medium">
                                        <span
                                            style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);'
                                            class="mx-1 rounded-lg ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1  bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30">
                                            Standard
                                        </span>
                                    </h2>
                                    <p class="mb-3">
                                        <span class="text-lg font-bold sm:text-xl text-gray-50 dark:text-gray-900"><span
                                                x-text="month? '$'+spma:'$'+spya"></span>$</span>
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-600">/mo</span>
                                    </p>
                                </th>
                                <th scope="col">
                                    <h2 class="text-lg font-medium">
                                        <span
                                            style='--c-50:var(--warning-50);--c-400:var(--warning-400);--c-600:var(--warning-600);'
                                            class="mx-1 rounded-lg ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1  bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30">
                                            Premium
                                        </span>
                                    </h2>
                                    <p class="mb-3">
                                        <span class="text-lg font-bold sm:text-xl text-gray-50 dark:text-gray-900"><span
                                                x-text="month? '$'+ppma:'$'+ppya"></span>$</span>
                                        <span class="text-xs font-medium text-gray-400 dark:text-gray-600">/mo</span>
                                    </p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="space-y-6 text-center divide-y divide-gray-700 dark:divide-gray-300">
                            <tr>
                                <th scope="row">
                                </th>
                                <td>
                                    <span class="block text-sm"></span>
                                </td>
                                <td>
                                    <a target="_blank"
                                        x-bind:href="month ?
                                            '{{ $ix->bp_ml . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}' :
                                            '{{ $ix->bp_yl . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}'"
                                        style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);font-variant: small-caps;"
                                        class="my-2 text-sm grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                                        {{ __('form.sub1') }}</a>
                                </td>
                                <td>
                                    <a target="_blank"
                                        x-bind:href="month ?
                                            '{{ $ix->sp_ml . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}' :
                                            '{{ $ix->sp_yl . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}'"
                                        style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);font-variant: small-caps;"
                                        class="my-2 text-sm grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                                        {{ __('form.sub1') }}</a>
                                </td>
                                <td>
                                    <a target="_blank"
                                        x-bind:href="month ?
                                            '{{ $ix->pp_ml . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}' :
                                            '{{ $ix->pp_yl . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}'"
                                        style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);font-variant: small-caps;"
                                        class="my-2 text-sm grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action">
                                        {{ __('form.sub1') }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.eca2') }}</h3>
                                </th>
                                <td>
                                    <span class="block text-sm">{{ $ix->eca_f }}</span>
                                </td>
                                <td>
                                    <span class="block text-sm">{{ $ix->eca_b }}</span>
                                </td>
                                <td>
                                    <span class="block text-sm">{{ $ix->eca_s }}</span>
                                </td>
                                <td>
                                    <span class="block text-sm">{{ $ix->eca_p }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.iac2') }}</h3>
                                </th>
                                <td>
                                    <span class="block text-sm">{{ $ix->iac_f }}</span>
                                </td>
                                <td>
                                    <span class="block text-sm">{{ $ix->iac_b }}</span>
                                </td>
                                <td>
                                    <span class="block text-sm">{{ $ix->iac_s }}</span>
                                </td>
                                <td>
                                    <span class="block text-sm">{{ $ix->iac_p }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.saa2') }}</h3>
                                </th>
                                <td>{{ $ix->saa_f }}</td>
                                <td>{{ $ix->saa_b }}</td>
                                <td>{{ $ix->saa_s }}</td>
                                <td>{{ $ix->saa_p }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.mcde') }}</h3>
                                </th>
                                <td>{{ $ix->maxts }} min</td>
                                <td>{{ $ix->maxtu }} min</td>
                                <td>{{ $ix->maxtp }} min</td>
                                <td>{{ $ix->maxtv }} min</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.mqt') }}</h3>
                                </th>
                                <td>{{ $ix->maxs }}</td>
                                <td>{{ $ix->maxu }}</td>
                                <td>{{ $ix->maxp }}</td>
                                <td>{{ $ix->maxv }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.mqe') }}</h3>
                                </th>
                                <td>{{ $ix->maxes }}</td>
                                <td>{{ $ix->maxeu }}</td>
                                <td>{{ $ix->maxep }}</td>
                                <td>{{ $ix->maxev }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.tec2') }}</h3>
                                </th>
                                <td>
                                    @if ($ix->tec_f)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->tec_b)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->tec_s)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->tec_p)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.sta2') }}</h3>
                                </th>
                                <td>
                                    @if ($ix->sta_f)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->sta_b)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->sta_s)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->sta_p)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.ftg2') }}</h3>
                                </th>
                                <td>
                                    @if ($ix->ftg_f)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->ftg_b)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->ftg_s)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->ftg_p)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.pa2') }}</h3>
                                </th>
                                <td>
                                    @if ($ix->pa_f)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->pa_b)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->pa_s)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->pa_p)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.tga2') }}</h3>
                                </th>
                                <td>
                                    @if ($ix->tga_f)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->tga_b)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->tga_s)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->tga_p)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.ecl') }}</h3>
                                </th>
                                <td>
                                    @if ($ix->ecl_f)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->ecl_b)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->ecl_s)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->ecl_p)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-left">
                                    <h3 class="py-3 text-xs">{{ __('form.ss') }}</h3>
                                </th>
                                <td>
                                    @if ($ix->ss_f)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->ss_b)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->ss_s)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                                <td>
                                    @if ($ix->ss_p)
                                        <x-filament::icon icon="heroicon-m-check-circle"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @else
                                        <x-filament::icon icon="heroicon-o-minus"
                                            class="w-6 h-6 mx-auto text-gray-900 dark:text-white" />
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                        </tbody>
                    </table>
                </div>
                <div class="grid gap-1 md:grid-flow-col md:hidden">
                    <!-- Pricing Card -->
                    <div
                        class="flex flex-col p-6 text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow gap-y-2 dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                        <h3 class="text-2xl font-semibold">Free</h3>
                        <p class="text-sm font-light text-gray-500 dark:text-gray-400">{{ __('main.w44') }}</p>
                        <div class="flex flex-row gap-2 mx-auto text-left">
                            <div class="mr-4 text-3xl font-extrabold text-black dark:text-white">$0</div>
                            <div class="flex flex-col">
                                <div class="text-xs text-gray-500 dark:text-gray-400">&nbsp;</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('form.fv') }}</div>
                            </div>
                        </div>
                        <!-- List -->
                        <ul role="list" class="flex flex-col mx-auto mb-8 text-xs gap-y-4">
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->eca_f, 2, '0', 0) }}</span>
                                    {{ __('form.eca2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->iac_f, 2, '0', 0) }}</span>
                                    {{ __('form.iac2') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->saa_f, 2, '0', 0) }}</span>
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
                            @if ($ix->tec_f)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.tec2') }}</span>
                                </li>
                            @endif
                            @if ($ix->ftg_f)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.ftg2') }}</span>
                                </li>
                            @endif
                            @if ($ix->pa_f)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.pa2') }}</span>
                                </li>
                            @endif
                            @if ($ix->tga_f)
                                <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                    <!-- Icon -->
                                    <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                    <span>{{ __('form.tga2') }}</span>
                                </li>
                            @endif

                            @if ($ix->sta_f)
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
                                '{{ $ix->bp_ml . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}' :
                                '{{ $ix->bp_yl . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}'"
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
                                <span><span class="font-bold">{{ str_pad($ix->maxtu, 2, '0', 0) }}</span> min
                                    {{ __('form.mcde') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->maxu, 2, '0', 0) }}</span>
                                    {{ __('form.mqt') }}</span>
                            </li>
                            <li class="grid justify-start grid-flow-col text-left gap-x-1">
                                <!-- Icon -->
                                <x-filament::icon icon="heroicon-m-check-circle" class="w-4 h-4 text-white" />
                                <span><span class="font-bold">{{ str_pad($ix->maxeu, 2, '0', 0) }}</span>
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
                                '{{ $ix->sp_ml . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}' :
                                '{{ $ix->sp_yl . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}'"
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
                                '{{ $ix->pp_ml . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}' :
                                '{{ $ix->pp_yl . '?locale=' . app()->getLocale() . '&prefilled_email=' . urlencode(auth()->user()->email) }}'"
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
