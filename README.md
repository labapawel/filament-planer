# Filament Planer

A Filament PHP form component that creates an interactive weekly timetable, allowing users to select specific hours for each day of the week.

## Features

- **Weekly Timetable UI:** Responsive grid layout with hours on the left and days on top.
- **Interactive Selection:**
  - Drag & drop support for selecting multiple cells.
  - Click to toggle individual hours.
  - "Select All" / "Deselect All" icon buttons for each day.
- **Visual Feedback:**
  - Real-time counters: Total selected hours (global) and daily totals.
  - customizable selection colors via CSS.
- **Configurable:** Customizable hours range, time steps, meaningful days labels, and "full day" selection strategies.
- **Data Storage:** Saves selection as a clean JSON object (e.g., `{"1":["08:00","09:00"],"2":["10:00"]}`).
- **Multilingual:** Built-in support for Polish, English, and German.
- **Dark Mode:** Fully compatible with Filament's dark mode.
- **Read-only Support:** Respects Filament's `disabled()` state.

## Installation

You can install the package via composer:

```bash
composer require labapawel/filament-planer
```

The service provider will automatically register itself. You can publish the configuration file with:

```bash
php artisan vendor:publish --tag="filament-planer-config"
```

## Usage

Add the `Planer` component to your Filament form schema:

```php
use Labapawel\FilamentPlaner\Forms\Components\Planer;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Planer::make('schedule')
                ->label('Weekly Plan')
                // Optional customizations:
                ->days([1, 2, 3, 4, 5]) // Define visible days (1=Mon to 7=Sun)
                ->hours(['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00']) // Custom hour slots
                ->fullDayConfig([
                    'enabled' => true,      // Enable "Select All" buttons
                    'default_hours' => 8,   // Max hours to select when using 'first_n' strategy
                    'strategy' => 'first_n', // Strategies: 'all_available' or 'first_n'
                ])
                ->disabled(fn ($record) => $record?->is_locked),
        ]);
}
```

## Styling & Customization

The component uses a dedicated CSS class `.sel` for selected cells, allowing for easy color customization outside of the standard Filament theme.

To customize the selection color, you can modify or override the CSS variables or classes in your project's stylesheet, or edit `resources/css/filament-planer.css` if you have published the assets.

**Default values (`filament-planer.css`):**
```css
/* Light mode */
.filament-planer-table .sel {
    background-color: #93c5fd; /* blue-300 */
}

/* Dark mode */
:is(.dark .filament-planer-table) .sel {
    background-color: #3b82f6; /* blue-500 */
}
```

## Configuration

The default settings can be globally modified in `config/filament-planer.php`:

```php
return [
    'hours' => [
        'start' => '08:00',
        'end' => '16:00',
        'step' => 60, // Interval in minutes
    ],
    'days' => [1, 2, 3, 4, 5], // Default: Monday to Friday
    'full_day' => [
        'enabled' => true,
        'default_hours' => 8,
        'strategy' => 'all_available',
    ],
];
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
