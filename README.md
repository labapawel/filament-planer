# Filament Planer

A Filament PHP form component that looks like a weekly timetable, allowing users to select specific hours for each day of the week.

## Features

- **Weekly Timetable UI:** Hours on the left, days of the week on top.
- **Interactive Selection:** Toggle individual hours or entire days.
- **Configurable:** Customizable hours range, step, days, and "full day" selection strategy.
- **JSON Storage:** Saves selection as a JSON object (e.g., `{"1":["08:00","09:00"],"2":["10:00"]}`).
- **Multilingual:** Supports Polish, English, and German.
- **Dark Mode:** Fully compatible with Filament's dark mode.
- **Read-only Support:** respects Filament's `disabled()` state.

## Installation

You can install the package via composer:

```bash
composer require labapawel/filament-planer
```

The service provider will automatically register itself. You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-planer-config"
```

## Usage

Add the `Planer` component to your Filament form:

```php
use LabaPawel\FilamentPlaner\Forms\Components\Planer;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Planer::make('schedule')
                ->label('Weekly Schedule')
                // Optional customizations:
                ->days([1, 2, 3, 4, 5]) // 1 (Mon) to 7 (Sun)
                ->hours(['08:00', '09:00', '10:00', '11:00', '12:00'])
                ->fullDayConfig([
                    'enabled' => true,
                    'default_hours' => 8,
                    'strategy' => 'first_n', // 'all_available' or 'first_n'
                ])
                ->disabled(fn ($record) => $record?->is_locked),
        ]);
}
```

## Configuration

The default settings can be modified in `config/filament-planer.php`:

```php
return [
    'hours' => [
        'start' => '08:00',
        'end' => '16:00',
        'step' => 60, // in minutes
    ],
    'days' => [1, 2, 3, 4, 5], 
    'full_day' => [
        'enabled' => true,
        'default_hours' => 8,
        'strategy' => 'all_available',
    ],
];
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
# filament-planer
