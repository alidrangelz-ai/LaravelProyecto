<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    stats: Object
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2">
                <h2 class="font-extrabold text-4xl text-slate-900 tracking-tight">¡Hola de nuevo! ✨</h2>
                <p class="text-slate-500 text-lg">Aquí tienes un resumen de tu actividad.</p>
            </div>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Stat 1: Total Notes -->
                    <div class="card-white group cursor-default">
                        <div class="flex flex-col">
                            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                📝
                            </div>
                            <h3 class="text-slate-500 font-medium mb-1">Mis Notas</h3>
                            <p class="text-4xl font-black text-slate-900">{{ stats.notes_count }}</p>
                        </div>
                    </div>

                    <!-- Stat 2: Pinned Notes -->
                    <div class="card-white group cursor-default">
                        <div class="flex flex-col">
                            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                ⭐
                            </div>
                            <h3 class="text-slate-500 font-medium mb-1">Destacadas</h3>
                            <p class="text-4xl font-black text-slate-900">{{ stats.pinned_notes_count }}</p>
                        </div>
                    </div>

                    <!-- Stat 3: Pending Tasks -->
                    <div class="card-white group cursor-default">
                        <div class="flex flex-col">
                            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                ⏳
                            </div>
                            <h3 class="text-slate-500 font-medium mb-1">Pendientes</h3>
                            <p class="text-4xl font-black text-slate-900">{{ stats.tasks_pending_count }}</p>
                        </div>
                    </div>

                    <!-- Stat 4: Completed Tasks -->
                    <div class="card-white group cursor-default">
                        <div class="flex flex-col">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                                ✅
                            </div>
                            <h3 class="text-slate-500 font-medium mb-1">Completadas</h3>
                            <p class="text-4xl font-black text-slate-900">{{ stats.tasks_completed_count }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
