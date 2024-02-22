<?php
if (! function_exists('mtz')) {
    function mtz() {
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

        usort($timezones, function($a, $b) {
            return $a['sort'] - $b['sort'] ?: strcmp($a['name'], $b['name']);
        });

        return $timezones;
    }
}
?>