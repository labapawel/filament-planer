# Języki
- Polski - główny
- Angielski 
- Niemecki

# specyfika komponemtu
Komponent do typu form z filamentphp, który na na panelu edycji wygląda jak plan lecji z podziałem na dni tygodnia u góry i godziny u lewej strony, można je ustawiać w pliku konfiguracyjnym.
po użytkownik może go edytować lub tylko podglądać, jak ma edycje to może zmieniać planer, jak tylko podglad to może tylko go oglądać

## możliwości modułu

- Zaznaczenie poszczególnych godzin w danym dniu tygodnia
- odznaczenie całego dnia, zaznaczenie całego dnia tzn 8 godzin, możliwość ustawienia w pliku konfiguracyjnym
- plik generuje ustawienia w formie json {planer: [["godzina": "godzina", "godzina": "godzina"]]}
- zaznaczenie też przychodzi w formie json z bazy, musimy podać nazwę pola w tabeli

# Technologie
- Laravel 12.x
- Filament 4.x

# Adresy dokumentacji
- https://filamentphp.com/
- https://laravel.com/docs/12.x

