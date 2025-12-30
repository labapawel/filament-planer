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

## Obecny stan (Aktualizacja)
- **Interaktywność**: Zaznaczanie godzin metodą "przeciągnij i upuść" (drag & drop) oraz kliknięciem.
- **Ikony**: Zastąpiono tekstowe przyciski "Zaznacz/Odznacz" ikonami (X/Check).
- **Wygląd**: 
    - Tabela ma równe szerokości kolumn (dni) dzięki `table-fixed`.
    - Zaznaczone pola wyróżnione kolorem definiowanym w CSS (niebieski).
- **Liczniki**:
    - Wyświetlanie sumy godzin dla całego tygodnia nad tabelą.
    - Wyświetlanie sumy godzin dla każdego dnia w nagłówku kolumny.
- **Dostosowanie**: Usunięto zbędne elementy (ikony w komórkach), uproszczono strukturę DOM (`td` zamiast `button`).

# Technologie
- Laravel 12.x
- Filament 4.x

# Adresy dokumentacji
- https://filamentphp.com/
- https://laravel.com/docs/12.x
