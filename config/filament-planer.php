<?php

return [
    // Zakres godzin wyświetlany w planerze
    'hours' => [
        'start' => '08:00',
        'end' => '16:00',
        'step' => 60, // w minutach, np. 60 min = 1 godzina
    ],

    // Dni tygodnia (klucze zgodne z ISO-8601 lub nazwami PHP date('N'))
    // 1 (Poniedziałek) - 7 (Niedziela)
    'days' => [1, 2, 3, 4, 5], 

    // Ustawienia dla funkcji "Zaznacz cały dzień"
    'full_day' => [
        'enabled' => true,
        'default_hours' => 8, // Ile godzin zaznaczyć domyślnie
        'strategy' => 'all_available', // 'all_available' lub 'first_n' (np. pierwsze 8h)
    ],
];
