<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    tasks: Array,
    events: Array
});

// Calendar State
const currentDate = ref(new Date());
const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

// Fixed Holidays (Repeat every year)
// Note: Movable dates like Carnival/Easter are set for 2026 here for simplicity
const fixedHolidays = [
    { month: 1, day: 1, name: 'Año Nuevo', icon: '🎆' },
    { month: 1, day: 6, name: 'Día de Reyes', icon: '👑' },
    { month: 1, day: 15, name: 'Día del Maestro (VE)', icon: '🍎' },
    { month: 2, day: 14, name: 'San Valentín', icon: '💝' },
    { month: 2, day: 16, name: 'Lunes de Carnaval', icon: '🎭' },
    { month: 2, day: 17, name: 'Martes de Carnaval', icon: '🎊' },
    { month: 3, day: 8, name: 'Día de la Mujer', icon: '👩' },
    { month: 3, day: 19, name: 'Día de San José', icon: '🛠️' },
    { month: 3, day: 21, name: 'Día de la Poesía', icon: '📖' },
    { month: 4, day: 2, name: 'Jueves Santo', icon: '🙏' },
    { month: 4, day: 3, name: 'Viernes Santo', icon: '✝️' },
    { month: 4, day: 19, name: 'Independencia (VE)', icon: '🇻🇪' },
    { month: 4, day: 22, name: 'Día de la Tierra', icon: '🌎' },
    { month: 4, day: 23, name: 'Día del Libro', icon: '📚' },
    { month: 5, day: 1, name: 'Día del Trabajador', icon: '👷' },
    { month: 5, day: 15, name: 'Día de la Familia', icon: '👨‍👩‍👧‍👦' },
    { month: 6, day: 5, name: 'Medio Ambiente', icon: '🌱' },
    { month: 6, day: 8, name: 'Día de los Océanos', icon: '🌊' },
    { month: 6, day: 24, name: 'Batalla de Carabobo', icon: '⚔️' },
    { month: 7, day: 5, name: 'Firma Independencia', icon: '📜' },
    { month: 7, day: 21, name: 'Día del Perro', icon: '🐶' },
    { month: 7, day: 24, name: 'Natalicio de Bolívar', icon: '🐎' },
    { month: 8, day: 19, name: 'Día de la Fotografía', icon: '📸' },
    { month: 9, day: 8, name: 'Virgen del Valle', icon: '⛪' },
    { month: 9, day: 11, name: 'Virgen de Coromoto', icon: '✨' },
    { month: 9, day: 21, name: 'Día de la Paz', icon: '🕊️' },
    { month: 9, day: 27, name: 'Día del Turismo', icon: '✈️' },
    { month: 10, day: 4, name: 'Día de los Animales', icon: '🐾' },
    { month: 10, day: 12, name: 'Resistencia Indígena', icon: '🏹' },
    { month: 10, day: 31, name: 'Halloween', icon: '🎃' },
    { month: 11, day: 1, name: 'Día de Todos los Santos', icon: '🕯️' },
    { month: 11, day: 18, name: 'Día de la Chinita', icon: '🥁' },
    { month: 11, day: 20, name: 'Día de la Infancia', icon: '👶' },
    { month: 12, day: 8, name: 'Inmaculada Concepción', icon: '🌟' },
    { month: 12, day: 17, name: 'Muerte de Bolívar', icon: '🏛️' },
    { month: 12, day: 24, name: 'Nochebuena', icon: '🎄' },
    { month: 12, day: 25, name: 'Navidad', icon: '🎁' },
    { month: 12, day: 31, name: 'Fin de Año', icon: '🍇' },
];

// Seasonal Decoration Logic
const seasonalThemes = {
    winter: { icon: '❄️', gradient: 'from-blue-100/50 to-indigo-100/50 dark:from-blue-900/20 dark:to-indigo-900/20', body: 'bg-blue-100/40 dark:bg-blue-950/40', text: 'text-blue-700' },
    spring: { icon: '🌸', gradient: 'from-pink-100/50 to-emerald-100/50 dark:from-pink-900/20 dark:to-emerald-900/20', body: 'bg-emerald-100/30 dark:bg-emerald-950/30', text: 'text-emerald-700' },
    summer: { icon: '☀️', gradient: 'from-amber-100/50 to-orange-100/50 dark:from-amber-900/20 dark:to-orange-900/20', body: 'bg-amber-100/30 dark:bg-amber-950/30', text: 'text-orange-700' },
    autumn: { icon: '🍂', gradient: 'from-orange-100/50 to-rose-100/50 dark:from-orange-900/20 dark:to-rose-900/20', body: 'bg-rose-100/30 dark:bg-rose-950/30', text: 'text-rose-700' }
};

const currentTheme = computed(() => {
    const month = currentDate.value.getMonth();
    if ([11, 0, 1].includes(month)) return seasonalThemes.winter;
    if ([2, 3, 4].includes(month)) return seasonalThemes.spring;
    if ([5, 6, 7].includes(month)) return seasonalThemes.summer;
    return seasonalThemes.autumn;
});

const getDaysInMonth = (year, month) => new Date(year, month + 1, 0).getDate();
const getFirstDayOfMonth = (year, month) => new Date(year, month, 1).getDay();

const calendarDays = computed(() => {
    const year = currentDate.value.getFullYear();
    const month = currentDate.value.getMonth();
    const daysInMonth = getDaysInMonth(year, month);
    const firstDay = getFirstDayOfMonth(year, month);
    
    const days = [];
    
    // Fill empty days from previous month
    for (let i = 0; i < firstDay; i++) {
        days.push({ day: null, date: null });
    }
    
    // Fill actual month days
    for (let i = 1; i <= daysInMonth; i++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const dayTasks = props.tasks.filter(t => t.due_date && t.due_date.startsWith(dateStr));
        const dayEvents = props.events.filter(e => e.event_date && e.event_date.startsWith(dateStr));
        const dayHolidays = fixedHolidays.filter(h => h.month === (month + 1) && h.day === i);
        
        days.push({ 
            day: i, 
            date: dateStr,
            tasks: dayTasks,
            events: dayEvents,
            holidays: dayHolidays
        });
    }
    
    return days;
});

const nextMonth = () => {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1);
};

const prevMonth = () => {
    currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1);
};

const form = useForm({
    title: '',
    description: '',
    due_date: '',
    is_completed: false
});

const showModal = ref(false);
const showEventModal = ref(false);
const showDayDetailsModal = ref(false);
const selectedDay = ref(null);
const editingTask = ref(null);
const editingEvent = ref(null);

const eventForm = useForm({
    title: '',
    description: '',
    event_date: '',
    location: '',
    type: 'general',
    is_priority: false
});

const openEventModal = (event = null, dateStr = null) => {
    if (event) {
        editingEvent.value = event;
        eventForm.title = event.title;
        eventForm.description = event.description;
        eventForm.event_date = event.event_date ? event.event_date.substring(0, 16) : '';
        eventForm.location = event.location;
        eventForm.type = event.type;
        eventForm.is_priority = event.is_priority;
    } else {
        editingEvent.value = null;
        eventForm.reset();
        if (dateStr) {
            // Pre-fill date from calendar click (setting to noon by default)
            eventForm.event_date = `${dateStr}T12:00`;
        }
    }
    showEventModal.value = true;
};

const closeEventModal = () => {
    showEventModal.value = false;
    eventForm.reset();
    editingEvent.value = null;
};

const submitEvent = () => {
    if (editingEvent.value) {
        eventForm.put(route('events.update', editingEvent.value.id), {
            onSuccess: () => closeEventModal(),
        });
    } else {
        eventForm.post(route('events.store'), {
            onSuccess: () => closeEventModal(),
        });
    }
};

const openDayDetails = (dayObj) => {
    if (!dayObj.day) return;
    selectedDay.value = dayObj;
    showDayDetailsModal.value = true;
};

const openModal = (task = null, dateStr = null) => {
    if (task) {
        editingTask.value = task;
        form.title = task.title;
        form.description = task.description;
        form.due_date = task.due_date ? task.due_date.substring(0, 16) : '';
        form.is_completed = !!task.is_completed;
    } else {
        editingTask.value = null;
        form.reset();
        if (dateStr) {
            form.due_date = `${dateStr}T12:00`;
        }
    }
    showModal.value = true;
};

const submit = () => {
    if (editingTask.value) {
        form.put(route('tasks.update', editingTask.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('tasks.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
    editingTask.value = null;
};

const deleteTask = () => {
    if (confirm('¿Eliminar esta tarea?')) {
        form.delete(route('tasks.destroy', editingTask.value.id), {
            onSuccess: () => closeModal(),
        });
    }
};
</script>

<template>
    <Head title="Calendario de Tareas" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-end relative overflow-hidden p-8 rounded-[2rem] bg-indigo-50/30 dark:bg-indigo-900/10 border border-indigo-100/50 dark:border-indigo-800/50">
                <!-- Seasonal Accent for Header -->
                <div class="absolute right-10 top-1/2 -translate-y-1/2 flex gap-4 opacity-10 blur-[1px]">
                    <span class="text-7xl animate-bounce" style="animation-duration: 4s">{{ currentTheme.icon }}</span>
                    <span class="text-5xl animate-pulse" style="animation-duration: 6s">{{ currentTheme.icon }}</span>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full bg-white dark:bg-slate-800 text-[10px] font-black uppercase tracking-widest text-indigo-600 shadow-sm border border-indigo-50 dark:border-indigo-900/50">
                            Vista Mensual
                        </span>
                        <span class="text-lg opacity-50">{{ currentTheme.icon }}</span>
                    </div>
                    <h2 class="font-extrabold text-5xl text-slate-900 dark:text-white tracking-tighter">
                        Calendario
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 mt-2 text-lg font-medium">Gestiona tus tareas y fechas límite con estilo.</p>
                </div>
            </div>
        </template>

        <div class="py-6 min-h-screen transition-colors duration-1000" :class="currentTheme.body">
            <!-- Floating Decorative Icons for Background -->
            <div class="fixed inset-0 pointer-events-none overflow-hidden opacity-[0.02] dark:opacity-[0.04]">
                <!-- Large Background Elements -->
                <div class="absolute top-20 left-10 text-9xl animate-bounce" style="animation-duration: 12s">{{ currentTheme.icon }}</div>
                <div class="absolute bottom-40 right-10 text-9xl animate-pulse" style="animation-duration: 18s">{{ currentTheme.icon }}</div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[45rem] font-black blur-3xl opacity-20">
                    {{ currentTheme.icon }}
                </div>

                <!-- Small Scattered Elements -->
                <div class="absolute top-1/3 right-[15%] text-6xl animate-pulse" style="animation-duration: 7s">{{ currentTheme.icon }}</div>
                <div class="absolute bottom-20 left-[20%] text-7xl animate-bounce" style="animation-duration: 15s">{{ currentTheme.icon }}</div>
                <div class="absolute top-10 right-1/4 text-4xl animate-pulse" style="animation-duration: 5s">{{ currentTheme.icon }}</div>
                <div class="absolute bottom-1/3 left-5 text-8xl animate-bounce" style="animation-duration: 20s">{{ currentTheme.icon }}</div>
                <div class="absolute top-1/4 left-1/3 text-3xl animate-pulse" style="animation-duration: 9s">{{ currentTheme.icon }}</div>
                <div class="absolute bottom-10 right-1/3 text-5xl animate-bounce" style="animation-duration: 11s">{{ currentTheme.icon }}</div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">
                <!-- Navigation -->
                <div class="relative overflow-hidden mb-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-[2rem] shadow-sm transition-all duration-500">
                    <!-- Seasonal Background Decor -->
                    <div class="absolute inset-0 bg-gradient-to-r opacity-40 transition-all duration-700" :class="currentTheme.gradient"></div>
                    <div class="absolute -right-4 -top-4 text-6xl opacity-10 blur-[2px] select-none animate-pulse">
                        {{ currentTheme.icon }}
                    </div>
                    
                    <div class="relative flex items-center justify-between">
                        <button @click="prevMonth" class="w-12 h-12 flex items-center justify-center hover:bg-white/50 dark:hover:bg-slate-800/50 rounded-2xl transition-all text-2xl text-slate-400 hover:text-indigo-600">
                            &larr;
                        </button>
                        <div class="text-center">
                            <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center justify-center gap-3">
                                <span class="text-2xl opacity-40 group-hover:scale-125 transition-transform">{{ currentTheme.icon }}</span>
                                {{ months[currentDate.getMonth()] }} 
                                <span :class="currentTheme.text" class="opacity-80">{{ currentDate.getFullYear() }}</span>
                            </h3>
                        </div>
                        <button @click="nextMonth" class="w-12 h-12 flex items-center justify-center hover:bg-white/50 dark:hover:bg-slate-800/50 rounded-2xl transition-all text-2xl text-slate-400 hover:text-indigo-600">
                            &rarr;
                        </button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="card-white !p-0 overflow-hidden border-none ring-1 ring-slate-100/50 dark:ring-slate-800/50 rounded-[3rem] shadow-2xl shadow-slate-200/50 dark:shadow-none">
                    <div class="grid grid-cols-7 text-center font-bold bg-white/40 dark:bg-slate-800/20 backdrop-blur-md border-b border-slate-100/50 dark:border-slate-800/50">
                        <div v-for="day in daysOfWeek" :key="day" class="py-4 text-slate-600 dark:text-slate-400 text-xs uppercase tracking-[0.2em]">
                            {{ day }}
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-7 divide-x divide-y divide-slate-100/30 dark:divide-slate-800/30">
                        <div v-for="(dayObj, index) in calendarDays" :key="index" 
                            class="min-h-[140px] p-4 bg-white/40 dark:bg-slate-900/40 backdrop-blur-sm border-slate-100/20 dark:border-slate-800/20 hover:bg-white/60 dark:hover:bg-slate-800/60 transition-all cursor-pointer group"
                            @click="openDayDetails(dayObj)">
                            
                            <div v-if="dayObj.day" class="flex justify-between items-start mb-3">
                                <span class="text-sm font-bold transition-transform group-hover:scale-110" :class="[
                                    dayObj.date === new Intl.DateTimeFormat('en-CA', {timeZone: 'America/Caracas'}).format(new Date()) 
                                    ? 'bg-indigo-600 text-white w-7 h-7 flex items-center justify-center rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none' 
                                    : 'text-slate-600 dark:text-slate-400'
                                ]">
                                    {{ dayObj.day }}
                                </span>
                            </div>

                            <div v-if="dayObj.tasks?.length || dayObj.events?.length || dayObj.holidays?.length" class="space-y-1.5 overflow-y-auto max-h-[100px] scrollbar-hide">
                                <!-- Holidays -->
                                <div v-for="holiday in dayObj.holidays" :key="'holiday-'+holiday.month+'-'+holiday.day"
                                    class="text-[9px] font-black p-1 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 truncate flex items-center gap-1 shadow-sm uppercase tracking-tighter">
                                    <span>{{ holiday.icon || '🌟' }}</span>
                                    {{ holiday.name }}
                                </div>

                                <!-- Events -->
                                <div v-for="event in dayObj.events" :key="'event-'+event.id"
                                    class="text-[10px] font-black p-1.5 rounded-lg border bg-amber-50 dark:bg-amber-900/30 border-amber-100 dark:border-amber-800 text-amber-700 dark:text-amber-400 truncate flex items-center gap-1.5 shadow-sm"
                                    title="Evento">
                                    <span>🎉</span>
                                    {{ event.title }}
                                </div>

                                <!-- Tasks -->
                                <div v-for="task in dayObj.tasks" :key="'task-'+task.id"
                                    @click.stop="openModal(task)"
                                    class="text-[10px] font-bold p-1.5 rounded-lg border transition-all truncate flex items-center gap-1.5 shadow-sm hover:shadow-md"
                                    :class="[
                                        task.is_completed 
                                        ? 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-100 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 opacity-60' 
                                        : 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-100 dark:border-indigo-800 text-indigo-700 dark:text-indigo-400'
                                    ]">
                                    <span>{{ task.is_completed ? '✅' : '📌' }}</span>
                                    {{ task.title }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Day Details Modal -->
        <Modal :show="showDayDetailsModal" @close="showDayDetailsModal = false">
            <div class="p-8 dark:bg-slate-950">
                <div v-if="selectedDay" class="space-y-8">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-6">
                        <div>
                            <p class="text-indigo-600 dark:text-indigo-400 font-black uppercase text-xs tracking-[0.2em] mb-1">
                                {{ new Date(selectedDay.date + 'T12:00:00').toLocaleDateString('es-ES', { weekday: 'long' }) }}
                            </p>
                            <h2 class="text-3xl font-black text-slate-900 dark:text-white">
                                {{ new Date(selectedDay.date + 'T12:00:00').toLocaleDateString('es-ES', { day: 'numeric', month: 'long' }) }}
                            </h2>
                        </div>
                        <button @click="showDayDetailsModal = false" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-slate-900 rounded-full text-slate-400 hover:text-slate-600 transition-colors">
                            ✕
                        </button>
                    </div>

                    <!-- Botones de Acción Rápida -->
                    <div class="grid grid-cols-2 gap-4">
                        <button @click="() => { showDayDetailsModal = false; openModal(null, selectedDay.date); }" 
                            class="flex items-center justify-center gap-2 p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-bold hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-all border border-indigo-100 dark:border-indigo-800">
                            <span class="text-xl">📌</span>
                            Añadir Tarea
                        </button>
                        <button @click="() => { showDayDetailsModal = false; openEventModal(null, selectedDay.date); }" 
                            class="flex items-center justify-center gap-2 p-4 rounded-2xl bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 font-bold hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-all border border-amber-100 dark:border-amber-800">
                            <span class="text-xl">🎉</span>
                            Añadir Evento
                        </button>
                    </div>

                    <!-- Holidays Section -->
                    <div v-if="selectedDay.holidays?.length" class="space-y-4">
                        <div v-for="holiday in selectedDay.holidays" :key="holiday.name"
                            class="flex items-center gap-4 p-5 bg-rose-50/50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-800/50 rounded-3xl">
                            <span class="text-3xl animate-pulse">{{ holiday.icon || '🌟' }}</span>
                            <div>
                                <h4 class="font-black text-rose-700 dark:text-rose-400 uppercase text-xs tracking-[0.2em] mb-1">Día Feriado</h4>
                                <p class="text-xl font-bold text-slate-900 dark:text-white">{{ holiday.name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Events Section -->
                    <div class="space-y-4">
                        <h3 class="flex items-center gap-2 text-slate-900 dark:text-white font-bold">
                            <span class="w-8 h-8 bg-amber-100 dark:bg-amber-900/40 rounded-lg flex items-center justify-center text-sm">🎉</span>
                            Eventos del Día
                        </h3>
                        
                        <div v-if="selectedDay.events?.length" class="grid gap-3">
                            <div v-for="event in selectedDay.events" :key="event.id" 
                                class="p-4 bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100/50 dark:border-amber-800/50 rounded-2xl">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-slate-900 dark:text-white">{{ event.title }}</h4>
                                    <span class="text-[10px] font-black uppercase bg-amber-100 dark:bg-amber-900/40 px-2 py-0.5 rounded-full text-amber-700 dark:text-amber-400">
                                        {{ event.type }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ event.description }}</p>
                                <div v-if="event.location" class="flex items-center gap-1.5 mt-3 text-[11px] font-bold text-slate-500">
                                    <span>📍</span> {{ event.location }}
                                </div>
                            </div>
                        </div>
                        <div v-else class="p-8 text-center bg-slate-50/50 dark:bg-slate-900/50 rounded-2xl border-2 border-dashed border-slate-100 dark:border-slate-800 text-slate-400 italic text-sm">
                            No hay eventos para este día.
                        </div>
                    </div>

                    <!-- Tasks Section -->
                    <div class="space-y-4">
                        <h3 class="flex items-center gap-2 text-slate-900 dark:text-white font-bold">
                            <span class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg flex items-center justify-center text-sm">📌</span>
                            Tareas Programadas
                        </h3>

                        <div v-if="selectedDay.tasks?.length" class="grid gap-3">
                            <div v-for="task in selectedDay.tasks" :key="task.id"
                                @click="() => { showDayDetailsModal = false; openModal(task); }"
                                class="flex items-center gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl hover:border-indigo-200 dark:hover:border-indigo-800 transition-all cursor-pointer group">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl transition-transform group-hover:scale-110"
                                    :class="task.is_completed ? 'bg-emerald-50 dark:bg-emerald-900/30' : 'bg-indigo-50 dark:bg-indigo-900/30'">
                                    {{ task.is_completed ? '✅' : '⏳' }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-slate-900 dark:text-white" :class="{ 'line-through opacity-50': task.is_completed }">
                                        {{ task.title }}
                                    </h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ task.description }}</p>
                                </div>
                                <span class="text-slate-300 dark:text-slate-700">→</span>
                            </div>
                        </div>
                        <div v-else class="p-8 text-center bg-slate-50/50 dark:bg-slate-900/50 rounded-2xl border-2 border-dashed border-slate-100 dark:border-slate-800 text-slate-400 italic text-sm">
                            No hay tareas programadas.
                        </div>
                    </div>
                </div>
            </div>
        </Modal>

        <!-- Task Modal -->
        <Modal :show="showModal" @close="closeModal">
            <div class="p-8 dark:bg-slate-950">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8">
                    {{ editingTask ? 'Editar Tarea' : 'Nueva Tarea desde Calendario' }}
                </h2>
                
                <div class="space-y-6">
                    <div>
                        <InputLabel for="task_title" value="Título de la Tarea" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="task_title" v-model="form.title" type="text" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400" 
                            placeholder="¿Qué tienes pendiente?" autofocus />
                    </div>

                    <div>
                        <InputLabel for="task_due_date" value="Fecha Límite" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="task_due_date" v-model="form.due_date" type="datetime-local" 
                            class="input-friendly w-full py-3" />
                    </div>

                    <div>
                        <InputLabel for="task_description" value="Descripción" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <textarea id="task_description" v-model="form.description" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400 min-h-[100px]" 
                            placeholder="Detalles de la tarea..."></textarea>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-rose-50/50 dark:bg-rose-900/10 rounded-2xl border border-rose-100 dark:border-rose-800/50 cursor-pointer" @click="form.is_priority = !form.is_priority">
                        <Checkbox v-model:checked="form.is_priority" class="w-5 h-5" />
                        <div>
                            <p class="text-sm font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest">Marcar como Prioritario</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <SecondaryButton @click="closeModal" class="flex-1 justify-center py-4 rounded-2xl"> 
                        Cancelar 
                    </SecondaryButton>
                    <PrimaryButton class="flex-1 justify-center py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200" 
                        :disabled="form.processing" @click="submit">
                        {{ editingTask ? 'Actualizar' : 'Crear Tarea' }}
                    </PrimaryButton>
                </div>
            </div>
        </Modal>

        <!-- Event Modal (Shortcut) -->
        <Modal :show="showEventModal" @close="closeEventModal">
            <div class="p-8 dark:bg-slate-950">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8">
                    {{ editingEvent ? 'Editar Evento' : 'Nuevo Evento desde Calendario' }}
                </h2>

                <div class="space-y-6">
                    <div>
                        <InputLabel for="event_title" value="Título" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="event_title" v-model="eventForm.title" type="text" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400" 
                            placeholder="Nombre del evento" autofocus />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="event_date_modal" value="Fecha y Hora" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                            <TextInput id="event_date_modal" v-model="eventForm.event_date" type="datetime-local" 
                                class="input-friendly w-full py-3" />
                        </div>
                        <div>
                            <InputLabel for="event_type" value="Categoría" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                            <select id="event_type" v-model="eventForm.type" 
                                class="input-friendly w-full py-3 dark:bg-slate-800 dark:text-white">
                                <option value="general">General</option>
                                <option value="reunion">Reunión</option>
                                <option value="personal">Personal</option>
                                <option value="trabajo">Trabajo</option>
                                <option value="importante">Importante</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <InputLabel for="event_location" value="Ubicación" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="event_location" v-model="eventForm.location" type="text" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400" 
                            placeholder="¿Dónde será?" />
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-rose-50/50 dark:bg-rose-900/10 rounded-2xl border border-rose-100 dark:border-rose-800/50 cursor-pointer" @click="eventForm.is_priority = !eventForm.is_priority">
                        <input type="checkbox" v-model="eventForm.is_priority" class="w-5 h-5 rounded border-rose-300 text-rose-600 focus:ring-rose-500" />
                        <div>
                            <p class="text-sm font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest">Prioridad Alta</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <SecondaryButton @click="closeEventModal" class="flex-1 justify-center py-4 rounded-2xl"> 
                        Cancelar 
                    </SecondaryButton>
                    <PrimaryButton class="flex-1 justify-center py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200" 
                        :disabled="eventForm.processing" @click="submitEvent">
                        {{ editingEvent ? 'Actualizar' : 'Crear Evento' }}
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
