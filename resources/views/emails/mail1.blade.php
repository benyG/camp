<div>

</div>
<section class="max-w-2xl px-6 py-8 mx-auto bg-white dark:bg-gray-900">
    <header>
        <a href="#">
            {{-- <img class="w-auto h-7 sm:h-8" src="https://merakiui.com/images/full-logo.svg" alt=""> --}}
            ITExamBootCamp
        </a>
    </header>

    <main class="mt-8">
        <p class="text-xl text-gray-700 dark:text-gray-200">Hi {{$name}}</p>

        <p class="mt-4 leading-loose text-gray-600 dark:text-gray-300">
{!! $content !!}  </p>

        <hr class="my-6 border-gray-200 dark:border-gray-700">

    </main>


    <footer class="mt-8">
        <p class="text-gray-500 dark:text-gray-400">
            This email was sent to <a href="#" class="text-blue-600 hover:underline dark:text-blue-400" target="_blank">{{$email}}</a>.
        </p>

        <p class="mt-3 text-gray-500 dark:text-gray-400">© 2024 ITExamBootCamp. All Rights Reserved.</p>
    </footer>
</section>