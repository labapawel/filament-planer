<?php

namespace LabaPawel\FilamentPlaner\Forms\Components;

use Filament\Forms\Components\Field;

class Planer extends Field
{
    protected string $view = 'filament-planer::forms.components.planer';

    protected array | \Closure | null $hours = null;
    protected array | \Closure | null $days = null;

    protected array | \Closure | null $fullDayConfig = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function fullDayConfig(array | \Closure $config): static
    {
        $this->fullDayConfig = $config;

        return $this;
    }

    public function hours(array | \Closure $hours): static
    {
        $this->hours = $hours;

        return $this;
    }

    public function days(array | \Closure $days): static
    {
        $this->days = $days;

        return $this;
    }

    public function getHours(): array
    {
        if ($this->hours !== null) {
            return $this->evaluate($this->hours);
        }

        return $this->generateHoursList();
    }

    public function getDays(): array
    {
        if ($this->days !== null) {
            return $this->evaluate($this->days);
        }

        return config('filament-planer.days', [1, 2, 3, 4, 5]);
    }

    public function getDayLabels(): array
    {
        $days = $this->getDays();
        $labels = [];
        
        foreach ($days as $day) {
            $labels[$day] = __('filament-planer::planer.days.' . $day);
        }
        
        return $labels;
    }

    protected function generateHoursList(): array
    {
        $start = config('filament-planer.hours.start', '08:00');
        $end = config('filament-planer.hours.end', '16:00');
        $step = config('filament-planer.hours.step', 60);

        $times = [];
        $current = strtotime($start);
        $endTime = strtotime($end);

        while ($current <= $endTime) {
            $times[] = date('H:i', $current);
            $current = strtotime("+{$step} minutes", $current);
        }

        return $times;
    }

    public function getFullDayConfig(): array
    {
        if ($this->fullDayConfig !== null) {
            return $this->evaluate($this->fullDayConfig);
        }

        return config('filament-planer.full_day', [
            'enabled' => true,
            'default_hours' => 8,
            'strategy' => 'all_available',
        ]);
    }
}
