<?php

if (! function_exists('mtz')) {
    function mtz()
    {
        $timezones = [];

        foreach (timezone_identifiers_list() as $timezone) {
            $datetime = new \DateTime('now', new DateTimeZone($timezone));
            $timezones[] = [
                'sort' => str_replace(':', '', $datetime->format('P')),
                'offset' => $datetime->format('P'),
                'name' => str_replace('_', ' ', implode(', ', explode('/', $timezone))),
                'timezone' => $timezone,
            ];
        }

        usort($timezones, function ($a, $b) {
            return $a['sort'] - $b['sort'] ?: strcmp($a['name'], $b['name']);
        });

        return $timezones;
    }
}
if (! function_exists('dynColors')) {
    function dynColors(): string
    {
        $col = '#';
        $ar = ['A', 'B', 'C', 'D', 'E', 'F', '0', '1', '2', '3', '4',
            '5', '6', '7', '8', '9'];
        for ($i = 0; $i < 6; $i++) {
            $col .= Arr::random($ar);
        }

        return $col;
    }
}
if (! function_exists('iac_decr')) {
    function iac_decr(): void
    {
        if (auth()->user()->ix > 0) {
            if(\App\Models\User::where('id', auth()->id())->first()->ix>0){
            \App\Models\User::where('id', auth()->id())->decrement('ix');
            \App\Models\User::where('id', auth()->id())->update(['icx' => now()]);
            iac_incr();
            }
        } elseif (auth()->user()->ix2 > 0) {
            if(\App\Models\User::where('id', auth()->id())->first()->ix2>0){
            \App\Models\User::where('id', auth()->id())->decrement('ix2');
            \App\Models\User::where('id', auth()->id())->update(['icx' => now()]);
            iac_incr();}
        }
    }
}
if (! function_exists('iac_incr')) {
    function iac_incr(): void
    {
        \App\Models\Info::first()->increment('iac');
    }
}
