<x-filament-panels::page>
    <div class="select-none">
        <form wire:submit="register">
            <div onmousedown="return false" onselectstart="return false" onpaste="return false;" onCopy="return false"
                onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off
                class='bg-white shadow-sm fi-fo-wizard fi-contained rounded-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10'>
                <ol role="list"
                    class='grid bg-white divide-y divide-gray-200 shadow-sm fi-fo-wizard-header md:grid-flow-col md:divide-y-0 dark:divide-white/5 rounded-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10',
                    <li class="relative flex fi-fo-wizard-header-step">
                    <button type="button"
                        class="flex items-center w-full h-full px-6 py-4 fi-fo-wizard-header-step-button gap-x-4">
                        @if ($qcur2 < $qtot)
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full fi-fo-wizard-header-step-icon-ctn bg-primary-600 dark:bg-primary-500 shrink-0">
                                <x-filament::icon alias="forms::components.wizard.completed-step"
                                    icon="heroicon-o-information-circle"
                                    class="w-6 h-6 text-white fi-fo-wizard-header-step-icon" />
                            </div>
                            <div class="grid justify-items-start">
                                <span class="text-sm font-medium fi-fo-wizard-header-step-label">
                                    {{ __('main.as1') }}
                                </span>
                                <span
                                    class="text-sm text-gray-500 fi-fo-wizard-header-step-description text-start dark:text-gray-400">
                                    {!! $bm1 ? __('main.as2') : __('main.as3') !!}
                                </span>
                            </div>
                        @else
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full fi-fo-wizard-header-step-icon-ctn bg-primary-600 dark:bg-primary-500 shrink-0">
                                <x-filament::icon alias="forms::components.wizard.completed-step"
                                    icon="heroicon-o-check" class="w-6 h-6 text-white fi-fo-wizard-header-step-icon" />
                            </div>
                            <div class="grid justify-items-start">
                                <span class="text-sm font-medium fi-fo-wizard-header-step-label">
                                    {{ __('main.as4') }}
                                </span>
                            </div>
                        @endif
                    </button>

                    {{-- @if (!$loop->last) --}}
                    <div aria-hidden="true"
                        class="absolute hidden w-5 h-full fi-fo-wizard-header-step-separator end-0 md:block">
                        <svg fill="none" preserveAspectRatio="none" viewBox="0 0 22 80"
                            class="w-full h-full text-gray-200 rtl:rotate-180 dark:text-white/5">
                            <path d="M0 -2L20 40L0 82" stroke-linejoin="round" stroke="currentcolor"
                                vector-effect="non-scaling-stroke"></path>
                        </svg>
                    </div>
                    </li>
                    <li class="relative flex fi-fo-wizard-header-step">
                        <button type="button"
                            class="flex items-center w-full h-full px-6 py-4 fi-fo-wizard-header-step-button gap-x-4">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full fi-fo-wizard-header-step-icon-ctn bg-primary-600 dark:bg-primary-500 shrink-0">
                                <x-filament::icon alias="forms::components.wizard.completed-step"
                                    icon="heroicon-o-document-text"
                                    class="w-6 h-6 text-white fi-fo-wizard-header-step-icon" />
                            </div>

                            <div class="grid justify-items-start">
                                <span class="text-sm font-medium fi-fo-wizard-header-step-label">
                                    {{ __('main.as5') }}
                                </span>
                                <span
                                    class="text-sm font-bold text-gray-500 fi-fo-wizard-header-step-description text-start dark:text-gray-400">
                                    {{ $qtot - $qcur }} / {{ $qtot }}
                                </span>
                            </div>
                        </button>
                        <div aria-hidden="true"
                            class="absolute hidden w-5 h-full fi-fo-wizard-header-step-separator end-0 md:block">
                            <svg fill="none" preserveAspectRatio="none" viewBox="0 0 22 80"
                                class="w-full h-full text-gray-200 rtl:rotate-180 dark:text-white/5">
                                <path d="M0 -2L20 40L0 82" stroke-linejoin="round" stroke="currentcolor"
                                    vector-effect="non-scaling-stroke"></path>
                            </svg>
                        </div>
                    </li>
                    <li class="relative flex fi-fo-wizard-header-step">
                        <button type="button"
                            class="flex items-center w-full h-full px-6 py-4 fi-fo-wizard-header-step-button gap-x-4">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full fi-fo-wizard-header-step-icon-ctn bg-primary-600 dark:bg-primary-500 shrink-0">
                                <x-filament::icon alias="forms::components.wizard.completed-step"
                                    icon="heroicon-o-clock" class="w-6 h-6 text-white fi-fo-wizard-header-step-icon" />
                            </div>
                            <script>
                                function timer(expiry2) {
                                    return {
                                        expiry: new Date().getTime() + (expiry2 * 60000),
                                        remaining: null,
                                        init() {
                                            setInterval(() => {
                                                this.setRemaining();
                                            }, 1000);
                                        },
                                        setRemaining() {
                                            const diff = this.expiry - new Date().getTime();
                                            if (diff > 0) this.remaining = parseInt(diff / 1000);
                                            else this.remaining = 0;
                                        },
                                        days() {
                                            return {
                                                value: this.remaining / 86400,
                                                remaining: this.remaining % 86400
                                            };
                                        },
                                        hours() {
                                            return {
                                                value: this.days().remaining / 3600,
                                                remaining: this.days().remaining % 3600
                                            };
                                        },
                                        minutes() {
                                            return {
                                                value: this.hours().remaining / 60,
                                                remaining: this.hours().remaining % 60
                                            };
                                        },
                                        seconds() {
                                            return {
                                                value: this.minutes().remaining,
                                            };
                                        },
                                        format(value) {
                                            return ("0" + parseInt(value)).slice(-2)
                                        },
                                        time() {
                                            return {
                                                //      days:this.format(this.days().value),
                                                hours: this.format(this.hours().value),
                                                minutes: this.format(this.minutes().value),
                                                seconds: this.format(this.seconds().value),
                                            }
                                        },
                                    }
                                }
                            </script>
                            <div class="grid justify-items-start">
                                <span
                                    class="text-sm font-medium fi-fo-wizard-header-step-label text-primary-500 dark:text-primary-400">
                                    {{ __('main.as6') }}
                                </span>
                                @if ($record->type == '0')
                                    <span
                                        class="text-sm font-bold text-gray-500 fi-fo-wizard-header-step-description text-start dark:text-gray-400">{{ __('main.as7') }}</span>
                                @else
                                    <span x-data="timer({{ $tim }})" x-init="init();
                                    setTimeout(() => { $wire.closeComlp() }, {{ $tim }} * 60000);"
                                        :class="parseInt(time().hours) <= 0 && parseInt(time().minutes) < 10 ?
                                            'text-danger-500 dark:text-danger-400' :
                                            'text-gray-500 dark:text-gray-400'"
                                        class="text-sm font-bold fi-fo-wizard-header-step-description text-start">
                                        <span x-text="time().hours >0?time().hours+':':''"></span><span
                                            x-text="time().minutes"></span>:<span x-text="time().seconds"></span></span>
                                @endif
                            </div>
                        </button>
                    </li>
                </ol>

                <div class="p-6 outline-none fi-fo-wizard-step">
                    <div class="text-lg leading-6 fi-fo-placeholder">
                        {!! $qtext !!}
                    </div>
                    <div class="fi-fo-{{ $bm1 ? 'radio' : 'checkbox' }} gap-4 pt-2">
                        @foreach ($aa as $ke => $al)
                            <label class="flex items-center pt-4 gap-x-3">
                                <input value="{{ $ke }}" {{ $bm2 ? 'disabled' : '' }}
                                    id='iiu{{ $ke }}' wire:model="{{ $bm1 ? 'ans' : 'ans2' }}"
                                    type="{{ $bm1 ? 'radio' : 'checkbox' }}"
                                    class="transition duration-75 bg-white border-none shadow-sm fi-radio-input ring-1 checked:ring-0 focus:ring-2 focus:ring-offset-0 disabled:bg-gray-50 disabled:text-gray-50 disabled:checked:bg-current disabled:checked:text-gray-400 dark:bg-white/5 dark:disabled:bg-transparent dark:disabled:checked:bg-gray-600 text-primary-600 ring-gray-950/10 dark:text-primary-500 dark:ring-white/20 dark:checked:bg-primary-500 dark:disabled:ring-white/10"
                                    {{-- 'text-primary-600 ring-gray-950/10 focus:ring-primary-600 checked:focus:ring-primary-500/50 dark:text-primary-500 dark:ring-white/20 dark:checked:bg-primary-500 dark:focus:ring-primary-500 dark:checked:focus:ring-primary-400/50 dark:disabled:ring-white/10' => $valid,
                            'fi-invalid text-danger-600 ring-danger-600 focus:ring-danger-600 checked:focus:ring-danger-500/50 dark:text-danger-500 dark:ring-danger-500 dark:checked:bg-danger-500 dark:focus:ring-danger-500 dark:checked:focus:ring-danger-400/50' => ! $valid, --}} />

                                <div class="text-sm leading-6">
                                    <span class="font-medium text-gray-950 dark:text-white">
                                        {{ $al }}
                                    </span>

                                    {{--   <p class="text-gray-500 dark:text-gray-400">
                               Description
                            </p> --}}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <span style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);'
                        class="text-xs text-custom-600">
                        @error('ans')
                            {{ $message }}
                        @enderror
                    </span>
                    {!! $cans !!}
                    @if (\Illuminate\Support\Str::contains($cans, 'alt='))
                        <br>
                        <style>
                            #dd4 span {
                                font-size: 10px !important;
                            }
                        </style>
                        <div id='dd4' style="font-size:10px"> <br><br>{{ __('main.as8') }}
                            {{ $this->revAction }}</div>
                    @endif
                </div>
                {!! $btext !!}
                @if (!empty($iatext))
                @endif
                <div class='grid grid-flow-col px-6 pb-6 justify-stretch gap-x-3'>
                    @if ($qcur2 < $qtot)
                    @endif
                    <div class='flex {{ $qcur2 < $qtot ? 'justify-end' : 'justify-center' }}'>
                        <x-filament::button icon="{{ $ico }}" type="submit" size="sm">
                            {{ $qcur2 < $qtot ? ($qcur == $qtot ? __('form.rs') : __('form.ne')) : __('form.cp') }}
                        </x-filament::button>
                    </div>
                </div>
            </div>
        </form>
        <x-filament-actions::modals />
    </div>
    @if ($record->type == '0')
        <style>
            #kj5 span {
                font-size: 10px !important;
            }
        </style>
        <div onmousedown="return false" class='flex' onselectstart="return false" onpaste="return false;"
            onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off>
            <div
                class="flex gap-6 p-2 bg-white shadow-sm select-none shrink rounded-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="shrink-0">
                    <div class='flex flex-col' id='kj5'>
                        <div class='flex flex-col justify-center text-center text-primary-500'>
                            <img style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                                src='{{ asset('img/ac.png') }}'
                                class="row-span-2 mx-auto rounded-full ring-custom-600 dark:ring-custom-400 ring-2 w-11 h-11" />
                            <div><span style="font-size:10px">{{ __('main.i2') }}</span></div>
                        </div>
                        <div class='self-center '>
                            {{ $this->invAction }}
                        </div>
                        <div class='self-center '>
                            {{ $this->inaAction }}
                        </div>
                        <div class='self-center '>
                            {{ $this->ssAction }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-col grow">
                    <div style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                        id='rr19'
                        class="p-1 text-sm mb-2
                        @if (empty($iatext3)) hidden @endif
                        grow rounded-xl"
                        x-data='{
                            text: "",
                            charIndex: 0,
                            typeSpeed: 10,
                        }'
                        x-init="setInterval(function() {
                            if (!$wire.iati3) { $data.charIndex = 0; }
                            if ($wire.iati3) {
                                let current = $wire.iatext3;
                                $data.text = current.substring(0, $data.charIndex);
                                $data.charIndex += 1;
                                if ($data.charIndex >= $wire.iatext3.length) { $data.charIndex = $wire.iatext3.length; }
                            }
                        }, $data.typeSpeed)">
                        <span x-html="text"></span>
                    </div>
                    <div style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                        id='rr12'
                        class="p-4 text-sm dark:bg-gray-950 bg-gray-50 ring-custom-600 dark:ring-custom-400 ring-2
                        @if (empty($iatext)) hidden @endif
                        grow rounded-xl"
                        x-data='{
                            text: "",
                            charIndex: 0,
                            typeSpeed: 10,
                        }'
                        x-init="setInterval(function() {
                            if ($wire.iati) {
                                let current = $wire.iatext.replace(/(\d+\. \*\*|- \*\*|- )/g, '<br>$1').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                                $data.text = current.substring(0, $data.charIndex);
                                $data.charIndex += 1;
                                if ($data.charIndex >= $wire.iatext.length) {
                                    $wire.iati = false;
                                    $data.charIndex = 0;
                                }
                            }
                        }, $data.typeSpeed)">
                        <span x-html="text"></span>
                    </div>
                    <div
                        class="px-4 py-1 justify-end w-full grow @if (empty($iatext)) hidden @else flex @endif">
                        <x-filament::link tag="button" wire:click="ssPick1" icon="heroicon-m-play">
                            {{ __('form.rpl') }}
                        </x-filament::link>
                    </div>

                    <div id='rr13'
                        style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                        class="mt-3 p-4 ring-custom-600 dark:ring-custom-400 ring-2
                        @if (empty($iatext2)) hidden @endif
                        grow rounded-xl text-sm dark:bg-gray-950 bg-gray-50"
                        x-data='{
                            text: "",
                            charIndex: 0,
                            typeSpeed: 10,
                        }'
                        x-init="setInterval(function() {
                            if ($wire.iati2) {
                                let current = $wire.iatext2.replace(/(\d+\. \*\*|- \*\*|- )/g, '<br>$1').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                                $data.text = current.substring(0, $data.charIndex);
                                $data.charIndex += 1;
                                if ($data.charIndex >= $wire.iatext2.length) {
                                    $wire.iati2 = false;
                                    $data.charIndex = 0;
                                }
                            }
                        }, $data.typeSpeed)">
                        <span x-html="text"></span>
                    </div>
                    <div
                        class="px-4 py-1 justify-end w-full grow @if (empty($iatext2)) hidden @else flex @endif">
                        <x-filament::link tag="button" wire:click="ssPick2" icon="heroicon-m-play">
                            {{ __('form.rpl') }}
                        </x-filament::link>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
