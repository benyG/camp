<x-filament-panels::page>
<div>
    <form wire:submit="register">
<div
    class='fi-fo-wizard fi-contained rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10'>
    <ol
        role="list"
        class='fi-fo-wizard-header grid divide-y divide-gray-200 md:grid-flow-col md:divide-y-0 dark:divide-white/5 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10',
            {{-- 'border-b border-gray-200 dark:border-white/10' => $isContained, --}}>
            <li class="fi-fo-wizard-header-step relative flex">
                <button
                    type="button"
                    class="fi-fo-wizard-header-step-button flex h-full w-full items-center gap-x-4 px-6 py-4">
                    @if ($qcur<$qtot)
                    <div class="fi-fo-wizard-header-step-icon-ctn flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-600 dark:bg-primary-500">
                           <span class="fi-fo-wizard-header-step-indicator text-sm font-medium">
                                {{ str_pad($qcur+1<=$qtot?$qcur+1:$qtot, Str::length(''.$qtot), '0', STR_PAD_LEFT) }}
                            </span>
                    </div>
                    <div class="grid justify-items-start">
                            <span class="fi-fo-wizard-header-step-label text-sm font-medium">
                               For this question
                            </span>
                            <span class="fi-fo-wizard-header-step-description text-start text-sm text-gray-500 dark:text-gray-400">
                               {!! $bm1? "choose only <i>one</i> answer":'you may choose many answers' !!}
                            </span>
                    </div>
                    @else
                    <div class="fi-fo-wizard-header-step-icon-ctn bg-primary-600 dark:bg-primary-500 flex h-10 w-10 shrink-0 items-center justify-center rounded-full">
                        <x-filament::icon
                            alias="forms::components.wizard.completed-step"
                            icon="heroicon-o-check"
                            class="fi-fo-wizard-header-step-icon h-6 w-6 text-white"
                        />
                    </div>
                    <div class="grid justify-items-start">
                            <span class="fi-fo-wizard-header-step-label text-sm font-medium">
                              Completed
                            </span>
                    </div>
                    @endif
               </button>

                {{-- @if (! $loop->last) --}}
                    <div
                        aria-hidden="true"
                        class="fi-fo-wizard-header-step-separator absolute end-0 hidden h-full w-5 md:block">
                        <svg
                            fill="none"
                            preserveAspectRatio="none"
                            viewBox="0 0 22 80"
                            class="h-full w-full text-gray-200 rtl:rotate-180 dark:text-white/5"                        >
                            <path
                                d="M0 -2L20 40L0 82"
                                stroke-linejoin="round"
                                stroke="currentcolor"
                                vector-effect="non-scaling-stroke"
                            ></path>
                        </svg>
                    </div>
            </li>
            <li class="fi-fo-wizard-header-step relative flex">
                <button
                    type="button"
                    class="fi-fo-wizard-header-step-button flex h-full w-full items-center gap-x-4 px-6 py-4"
                >
                    <div class="fi-fo-wizard-header-step-icon-ctn bg-primary-600 dark:bg-primary-500 flex h-10 w-10 shrink-0 items-center justify-center rounded-full">
                        <x-filament::icon
                            alias="forms::components.wizard.completed-step"
                            icon="heroicon-o-document-text"
                            class="fi-fo-wizard-header-step-icon h-6 w-6 text-white"
                        />
                    </div>

                    <div class="grid justify-items-start">
                            <span class="fi-fo-wizard-header-step-label text-sm font-medium">
                               Questions
                            </span>
                            <span class="fi-fo-wizard-header-step-description font-bold text-start text-sm text-gray-500 dark:text-gray-400">
                                {{$qtot}}
                            </span>
                    </div>
                </button>
                    <div
                        aria-hidden="true"
                        class="fi-fo-wizard-header-step-separator absolute end-0 hidden h-full w-5 md:block"
                    >
                        <svg
                            fill="none"
                            preserveAspectRatio="none"
                            viewBox="0 0 22 80"
                            class="h-full w-full text-gray-200 rtl:rotate-180 dark:text-white/5"
                        >
                            <path
                                d="M0 -2L20 40L0 82"
                                stroke-linejoin="round"
                                stroke="currentcolor"
                                vector-effect="non-scaling-stroke"
                            ></path>
                        </svg>
                    </div>
            </li>
            <li class="fi-fo-wizard-header-step relative flex">
                <button
                    type="button"
                    class="fi-fo-wizard-header-step-button flex h-full w-full items-center gap-x-4 px-6 py-4">
                    <div class="fi-fo-wizard-header-step-icon-ctn flex bg-primary-600 dark:bg-primary-500 h-10 w-10 shrink-0 items-center justify-center rounded-full">
                        <x-filament::icon
                            alias="forms::components.wizard.completed-step"
                            icon="heroicon-o-clock"
                            class="fi-fo-wizard-header-step-icon h-6 w-6 text-white"
                        />
                    </div>
                    <div class="grid justify-items-start">
                            <span class="fi-fo-wizard-header-step-label text-sm font-medium  text-primary-500 dark:text-primary-400">
                               Timer (minutes left)
                            </span>
                            @if ($record->type=='0')
                            <span class="fi-fo-wizard-header-step-description font-bold text-start text-sm text-gray-500 dark:text-gray-400">Unlimited</span>
                            @else
                            <span x-data="{ tii: {{$tim}} }" x-text='tii' x-init="setInterval(function(){if(tii>0)tii--;}, 60000);setTimeout(() => { $wire.closeComp() }, tii*60000);" class="fi-fo-wizard-header-step-description font-bold text-start text-sm text-gray-500 dark:text-gray-400" />
                            @endif
                    </div>
                </button>
            </li>
    </ol>

   <div class="fi-fo-wizard-step outline-none p-6">
        <div class="fi-fo-placeholder text-lg leading-6">
        {!! $qtext !!}
        </div>
        <div class="fi-fo-{{$bm1?'radio':'checkbox'}} gap-4 pt-2" >
        @foreach ($aa as $ke=> $al)
            <label class="flex gap-x-3 pt-4 items-center" >
                    <input value="{{$ke}}" {{$bm2? 'disabled':''}}
          id='iiu{{$ke}}' wire:model="{{$bm1?'ans':'ans2'}}"
                type="{{$bm1?'radio':'checkbox'}}"

                            class='fi-radio-input border-none bg-white shadow-sm ring-1 transition duration-75 checked:ring-0 focus:ring-2 focus:ring-offset-0 disabled:bg-gray-50 disabled:text-gray-50 disabled:checked:bg-current disabled:checked:text-gray-400 dark:bg-white/5 dark:disabled:bg-transparent dark:disabled:checked:bg-gray-600'
                            {{-- 'text-primary-600 ring-gray-950/10 focus:ring-primary-600 checked:focus:ring-primary-500/50 dark:text-primary-500 dark:ring-white/20 dark:checked:bg-primary-500 dark:focus:ring-primary-500 dark:checked:focus:ring-primary-400/50 dark:disabled:ring-white/10' => $valid,
                            'fi-invalid text-danger-600 ring-danger-600 focus:ring-danger-600 checked:focus:ring-danger-500/50 dark:text-danger-500 dark:ring-danger-500 dark:checked:bg-danger-500 dark:focus:ring-danger-500 dark:checked:focus:ring-danger-400/50' => ! $valid, --}}
            />

                    <div class="text-sm leading-6">
                        <span class="font-medium text-gray-950 dark:text-white">
                            {{$al}}
                        </span>

                          {{--   <p class="text-gray-500 dark:text-gray-400">
                               Description
                            </p> --}}
                    </div>
                </label>
        @endforeach
        </div>
        <span style='--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);' class="text-custom-600 text-xs">@error('ans'){{ $message }}@enderror</span>
         {!! $cans !!}
   </div>
    {!! $btext !!}
    <div class='flex {{$qcur<$qtot ? 'justify-end':'justify-center'}} gap-x-3 px-6 pb-6'>
       <x-filament::button
        icon="{{$ico}}"
                            type="submit"
                            size="sm"
                        >
                            {{$qcur<$qtot ? 'Next':'Complete'}}
                        </x-filament::button>
    </div>
</div>
    </form>
    <x-filament-actions::modals />
</div>
</x-filament-panels::page>