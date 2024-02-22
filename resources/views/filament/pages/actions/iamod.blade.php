<div class=''>
<style>
            #dd1 {
                width:100px !important;

                }
        </style>
<img id='dd1' style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
src='{{asset('img/ac.png')}}' class="mx-auto rounded-full ring-custom-600 dark:ring-custom-400 ring-2" />
<p class="mt-2 text-sm text-center text-gray-500 fi-modal-description dark:text-gray-400">
    Hi {{ explode(' ',auth()->user()->name)[0]}}, {{$txt}}
</p>
</div>
