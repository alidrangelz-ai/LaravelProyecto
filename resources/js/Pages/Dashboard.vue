<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    priority_items: Object
});

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-ES', {
        day: '2-digit',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};
</script>

<template>
    <Head title="Menú Principal" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2">
                <h2 class="font-extrabold text-4xl text-slate-900 dark:text-white tracking-tight">¡Hola de nuevo! ✨</h2>
                <p class="text-slate-600 dark:text-slate-400 text-lg">Aquí tienes un resumen de tu actividad.</p>
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Stat 1: Total Notes -->
                    <div class="card-white group cursor-default">
                        <div class="flex flex-col">
                            <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                📝
                            </div>
                            <h3 class="text-slate-600 dark:text-slate-400 font-bold mb-1">Mis Notas</h3>
                            <p class="text-4xl font-black text-slate-900 dark:text-white">{{ stats.notes_count }}</p>
                        </div>
                    </div>

                    <!-- Stat 2: Pinned Notes -->
                    <div class="card-white group cursor-default">
                        <div class="flex flex-col">
                            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                ⭐
                            </div>
                            <h3 class="text-slate-600 dark:text-slate-400 font-bold mb-1">Destacadas</h3>
                            <p class="text-4xl font-black text-slate-900 dark:text-white">{{ stats.pinned_notes_count }}</p>
                        </div>
                    </div>

                    <!-- Stat 3: Pending Tasks -->
                    <div class="card-white group cursor-default">
                        <div class="flex flex-col">
                            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                ⏳
                            </div>
                            <h3 class="text-slate-600 dark:text-slate-400 font-bold mb-1">Pendientes</h3>
                            <p class="text-4xl font-black text-slate-900 dark:text-white">{{ stats.tasks_pending_count }}</p>
                        </div>
                    </div>

                    <!-- Stat 4: Completed Tasks -->
                    <div class="card-white group cursor-default">
                        <div class="flex flex-col">
                            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                ✅
                            </div>
                            <h3 class="text-slate-600 dark:text-slate-400 font-bold mb-1">Completadas</h3>
                            <p class="text-4xl font-black text-slate-900 dark:text-white">{{ stats.tasks_completed_count }}</p>
                        </div>
                    </div>
                </div>

                <!-- Priority Items Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-rose-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-rose-200 dark:shadow-none animate-pulse">
                            🔥
                        </div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Atención Prioritaria</h2>
                    </div>

                    <div v-if="priority_items.events.length === 0 && priority_items.tasks.length === 0" 
                        class="card-white p-12 text-center border-2 border-dashed border-slate-200 dark:border-slate-800">
                        <p class="text-slate-500 dark:text-slate-400 font-bold">No tienes elementos marcados como prioridad. ⭐</p>
                    </div>

                    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Priority Events -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                Eventos Prioritarios
                            </h3>
                            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 overflow-hidden shadow-sm">
                                <table class="w-full text-left">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr>
                                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500 tracking-widest">Evento</th>
                                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500 tracking-widest">Fecha</th>
                                            <th class="px-6 py-4 text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                                        <tr v-for="event in priority_items.events" :key="event.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group">
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-slate-900 dark:text-white">{{ event.title }}</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-[200px]">{{ event.location || 'Sin ubicación' }}</div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <span class="text-sm font-bold text-slate-600 dark:text-slate-400">{{ formatDate(event.event_date) }}</span>
                                            </td>
                                            <td class="px-6 py-5 text-right">
                                                <span class="opacity-0 group-hover:opacity-100 transition-opacity">✨</span>
                                            </td>
                                        </tr>
                                        <tr v-if="priority_items.events.length === 0">
                                            <td colspan="3" class="px-6 py-10 text-center text-slate-400 italic text-sm">Sin eventos urgentes</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Priority Tasks -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Tareas Prioritarias
                            </h3>
                            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 overflow-hidden shadow-sm">
                                <table class="w-full text-left">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr>
                                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500 tracking-widest">Tarea</th>
                                            <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-500 tracking-widest">Límite</th>
                                            <th class="px-6 py-4 text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                                        <tr v-for="task in priority_items.tasks" :key="task.id" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors group">
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-slate-900 dark:text-white">{{ task.title }}</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-[200px]">{{ task.description || 'Sin detalles' }}</div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <span class="text-sm font-bold text-rose-600 dark:text-rose-400">{{ formatDate(task.due_date) }}</span>
                                            </td>
                                            <td class="px-6 py-5 text-right text-rose-500">
                                                <span class="opacity-0 group-hover:opacity-100 transition-opacity font-black">🔥</span>
                                            </td>
                                        </tr>
                                        <tr v-if="priority_items.tasks.length === 0">
                                            <td colspan="3" class="px-6 py-10 text-center text-slate-400 italic text-sm">Sin tareas críticas</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
