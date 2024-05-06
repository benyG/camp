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
