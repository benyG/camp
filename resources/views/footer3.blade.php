<div class='w-full text-sm text-center text-gray-400'>or<br>You can also sign in via</div>
<div class="flex justify-center gap-x-3">
    <div>
        <form method="POST" action="{{ route('oauth.redirect', ['driver' => 'google']) }}">
            @csrf
            <button type="submit"><img src="{{ asset('img/gg.svg') }}" /></button>
        </form>
    </div>
    <div>
        <form method="POST" action="{{ route('oauth.redirect', ['driver' => 'linkedin']) }}">
            @csrf
            <button type="submit"><img src="{{ asset('img/li.svg') }}" /></button>
        </form>
    </div>
</div>
<x-footer2 />
