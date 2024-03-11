<form action="{{route('lang')}}" method="POST" >
    @csrf
    <select style='--c-50:var(--gray-50);--c-400:var(--gray-400);--c-600:var(--gray-600);'
    name="lang" onchange="this.form.submit()"
    class="p-1 text-xs rounded-full bg-custom-50 text-custom-600 dark:bg-custom-400/10 dark:text-custom-400
    [&_option]:bg-white [&_option]:dark:bg-gray-900 ">
        <option value="en" {{app()->getLocale()=='en'? 'selected':''}}>EN</option>
        <option value="fr" {{app()->getLocale()=='fr'? 'selected':''}}>FR</option>
    </select>
</form>
