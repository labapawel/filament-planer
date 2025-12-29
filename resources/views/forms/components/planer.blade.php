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
            let index = currentHours.indexOf(hour);
            
            if (index > -1) {
                currentHours.splice(index, 1);
            } else {
                currentHours.push(hour);
            }
            
            // Reassign to trigger update
            // Reassign to trigger update
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
            let index = currentHours.indexOf(hour);
            
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

             let hasAllTarget = targetHours.every(h => currentDayHours.includes(h));

             if (hasAllTarget) {
                 this.state[day] = []; 
             } else {
                 this.state[day] = targetHours;
             }
             
             this.state = {...this.state};
        },

        isSelected(day, hour) {
            return this.state[day] && this.state[day].includes(hour);
        }
    }" 
    @mouseup.window="stopDrag()"
    class="filament-planer"
    >
        <div class="filament-planer-table overflow-hidden border border-gray-200 rounded-lg dark:border-gray-700 select-none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3 sticky left-0 top-0 z-30 bg-gray-50 dark:bg-gray-800 min-w-[100px]">
                                {{ __('filament-planer::planer.labels.hour') }}
                            </th>
                            <template x-for="day in days" :key="day">
                                <th scope="col" class="px-2 py-3 text-center min-w-[120px] sticky top-0 z-20 bg-gray-50 dark:bg-gray-800 last:border-r-0">
                                    <div class="flex flex-col items-center gap-1">
                                        <span x-text="dayLabels[day]" class="font-bold"></span>
                                        <template x-if="fullDayConfig.enabled && !isDisabled">
                                            <button 
                                                type="button" 
                                                x-on:click="toggleDay(day)"
                                                class="text-[10px] px-2 py-0.5 rounded bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 transition-colors"
                                            >
                                                <span x-text="state[day] && state[day].length > 0 ? '{{ __('filament-planer::planer.actions.deselect_all_day') }}' : '{{ __('filament-planer::planer.actions.select_all_day') }}'"></span>
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
                                    <td class="p-0 border border-t-0 border-l-0 border-gray-200 dark:border-gray-700">
                                        <button 
                                            type="button"
                                            x-on:mousedown="startDrag(day, hour)"
                                            x-on:mouseenter="onMouseOver(day, hour)"
                                            x-on:keydown.enter.prevent="toggle(day, hour)"
                                            x-on:keydown.space.prevent="toggle(day, hour)"
                                            :disabled="isDisabled"
                                            :title="'{{ __('filament-planer::planer.actions.select_hour') }}: ' + dayLabels[day] + ' ' + hour"
                                            :class="{
                                                'bg-primary-600 dark:bg-primary-500 shadow-sm': isSelected(day, hour),
                                                'hover:bg-gray-50 dark:hover:bg-gray-800': !isSelected(day, hour),
                                                'cursor-not-allowed opacity-70': isDisabled,
                                                'cursor-pointer': !isDisabled
                                            }"
                                            class="w-full h-12 block transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset"
                                        >
                                            <template x-if="isSelected(day, hour)">
                                                <x-filament::icon
                                                    icon="heroicon-m-check"
                                                    class="w-5 h-5 mx-auto text-white"
                                                />
                                            </template>
                                        </button>
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
