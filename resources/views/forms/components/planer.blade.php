<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{
        state: $wire.entangle('{{ $getStatePath() }}'),
        hours: {{ json_encode($getHours()) }},
        days: {{ json_encode($getDays()) }},
        dayLabels: {{ json_encode($getDayLabels()) }},
        fullDayConfig: {{ json_encode($getFullDayConfig()) }},
        isDisabled: {{ $isDisabled() ? 'true' : 'false' }},
        
        isDragging: false,
        dragState: null, // true (selecting) or false (deselecting)
        dragStartDay: null,

        init() {
            if (!this.state || typeof this.state !== 'object') {
                this.state = {};
            }
            // Ensure all days are present in state to avoid undefined errors
            this.days.forEach(day => {
                if (!this.state[day]) {
                    this.state[day] = [];
                }
            });
        },

        toggle(day, hour) {
            if (this.isDisabled) return;

            // Ensure state is an object
            if (!this.state || typeof this.state !== 'object') {
                this.state = {};
            }

            // Ensure day key exists and is an array
            if (!Array.isArray(this.state[day])) {
                this.state[day] = [];
            }
            
            // Clone array to ensure reactivity
            let currentHours = [...this.state[day]];
            let index = currentHours.findIndex(h => h == hour);
            
            if (index > -1) {
                currentHours.splice(index, 1);
            } else {
                currentHours.push(hour);
            }
            
            this.state[day] = currentHours;
        },

        startDrag(day, hour) {
            if (this.isDisabled) return;
            this.isDragging = true;
            this.dragStartDay = day;
            
            // Determine initial action based on current state of the clicked cell
            const isSelected = this.isSelected(day, hour);
            this.dragState = !isSelected; // If selected, we deselect. If not, we select.
            
            this.applyDrag(day, hour);
        },
        
        onMouseOver(day, hour) {
            if (!this.isDragging || this.isDisabled) return;
            // Only allow dragging within the same day for simplicity, or allow across days? 
            // Usually planners are column-based (day based). Let's restrict to same day for now.
            if (day !== this.dragStartDay) return;
            
            this.applyDrag(day, hour);
        },
        
        stopDrag() {
            this.isDragging = false;
            this.dragState = null;
            this.dragStartDay = null;
        },
        
        applyDrag(day, hour) {
            if (this.isDisabled) return;
            
            // Ensure state init
             if (!this.state || typeof this.state !== 'object') {
                this.state = {};
            }
            if (!Array.isArray(this.state[day])) {
                this.state[day] = [];
            }
            
            let currentHours = [...this.state[day]];
            let index = currentHours.findIndex(h => h == hour);
            
            if (this.dragState === true && index === -1) {
                // Select
                currentHours.push(hour);
            } else if (this.dragState === false && index > -1) {
                // Deselect
                currentHours.splice(index, 1);
            } else {
                return; // No change needed
            }
            
            this.state[day] = currentHours;
        },

        toggleDay(day) {
             if (this.isDisabled) return;

             if (!this.state[day]) {
                 this.state[day] = [];
             }
             
             let currentDayHours = this.state[day];
             
             let targetHours = [];
             if (this.fullDayConfig.strategy === 'first_n') {
                 targetHours = this.hours.slice(0, Math.min(this.fullDayConfig.default_hours, this.hours.length));
             } else {
                 targetHours = [...this.hours];
             }

             let hasAllTarget = targetHours.every(h => currentDayHours.some(ch => ch == h));

             if (hasAllTarget) {
                 this.state[day] = []; 
             } else {
                 this.state[day] = targetHours;
              }
         },

        isSelected(day, hour) {
            return this.state[day] && this.state[day].some(h => h == hour);
        },

        get totalSelected() {
            return this.days.reduce((acc, day) => {
                return acc + (this.state[day] ? this.state[day].length : 0);
            }, 0);
        },

        getDayCount(day) {
            return this.state[day] ? this.state[day].length : 0;
        }
    }" 
    @mouseup.window="stopDrag()"
    class="filament-planer relative"
    >
        <div class="absolute right-0 top-0 -mt-8 text-sm font-bold text-gray-600 dark:text-gray-400">
             <span class="ml-2 text-primary-600" x-text="'Sum: ' + totalSelected + ' h'"></span>
        </div>
        <div class="filament-planer-table overflow-hidden border border-gray-200 rounded-lg dark:border-gray-700 select-none">
            <div class="overflow-x-auto">
                <table class="w-full table-fixed text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="w-24 px-4 py-3 sticky left-0 top-0 z-30 bg-gray-50 dark:bg-gray-800">
                                {{ __('filament-planer::planer.labels.hour') }}
                            </th>
                            <template x-for="day in days" :key="day">
                                <th scope="col" class="px-2 py-3 text-center sticky top-0 z-20 bg-gray-50 dark:bg-gray-800 last:border-r-0">
                                    <div class="flex flex-col items-center gap-1">
                                        <div>
                                            <span x-text="dayLabels[day]" class="font-bold"></span>
                                            <span class="text-xs text-gray-500 font-normal" x-text="'(' + getDayCount(day) + ' h)'"></span>
                                        </div>
                                        <template x-if="fullDayConfig.enabled && !isDisabled">
                                            <button 
                                                type="button" 
                                                x-on:click="toggleDay(day)"
                                                class="rounded p-1 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                            >
                                                <template x-if="state[day] && state[day].length > 0">
                                                    <div title="{{ __('filament-planer::planer.actions.deselect_all_day') }}">
                                                        <x-filament::icon
                                                            icon="heroicon-m-x-mark"
                                                            class="w-4 h-4 text-danger-500"
                                                        />
                                                    </div>
                                                </template>
                                                <template x-if="!state[day] || state[day].length === 0">
                                                    <div title="{{ __('filament-planer::planer.actions.select_all_day') }}">
                                                        <x-filament::icon
                                                            icon="heroicon-m-check"
                                                            class="w-4 h-4 text-success-500"
                                                        />
                                                    </div>
                                                </template>
                                            </button>
                                        </template>
                                    </div>
                                </th>
                            </template>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="hour in hours" :key="hour">
                            <tr class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <!-- Godzina (wiersz) -->
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white sticky left-0 bg-white dark:bg-gray-900 z-10">
                                    <span x-text="hour"></span>
                                </th>
                                
                                <!-- Komórki dni (kolumny) -->
                                <template x-for="day in days" :key="day">
                                    <td 
                                        x-on:mousedown="startDrag(day, hour)"
                                        x-on:mouseenter="onMouseOver(day, hour)"
                                        :title="'{{ __('filament-planer::planer.actions.select_hour') }}: ' + dayLabels[day] + ' ' + hour"
                                        :class="{
                                            'sel shadow-sm': isSelected(day, hour),
                                            'hover:bg-gray-50 dark:hover:bg-gray-800': !isSelected(day, hour),
                                            'cursor-not-allowed opacity-70': isDisabled,
                                            'cursor-pointer': !isDisabled
                                        }"
                                        class="p-0 border border-t-0 border-l-0 border-gray-200 dark:border-gray-700 transition-all duration-200"
                                    >
                                    </td>
                                </template>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-dynamic-component>
