<div class=''>
<style>
            #dd1 {
                width:100px !important;

                }
        </style>
<img id='dd1' style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
src='{{asset('img/ac.png')}}' class="mx-auto rounded-full ring-custom-600 dark:ring-custom-400 ring-2" />
<p class="mt-2 text-sm text-center text-gray-500 fi-modal-description dark:text-gray-400">
    Hi {{ explode(' ',auth()->user()->name)[0]}}, this is my point of view: <br><br>
</p>
<span class="hidden" x-ref="tt">{{$txt}}</span>
<div id='rr13' style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
             class="p-2 text-sm ring-custom-600 dark:ring-custom-400 ring-2 rounded-xl dark:bg-gray-950 bg-gray-50"
            x-data='{
                            text: "",
                            charIndex: 0,
                            typeSpeed: 10,
                        }'
                x-init="setInterval(function(){
                            let current = $refs.tt.innerHTML;
                            $data.text = current.substring(0, $data.charIndex);
                            $data.charIndex += 1;
                    }, $data.typeSpeed)">
            <span x-html="text"></span>
            </div>
</div>
