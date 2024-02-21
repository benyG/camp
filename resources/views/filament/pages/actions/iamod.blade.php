<div class=''>
<style>
            #dd1 {
                width:100px !important;
                border-color:green;
                }
        </style>
<img id='dd1' src='{{asset('img/ac.png')}}' class="mx-auto border-2 rounded-full" />
<p class="mt-2 text-sm text-center text-gray-500 fi-modal-description dark:text-gray-400">
    Hey {{ explode(' ',auth()->user()->name)[0]}}, {{$txt}}
</p>
</div>
