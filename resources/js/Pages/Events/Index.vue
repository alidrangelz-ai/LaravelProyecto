<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    events: Array,
    tasks: Array
});

const activeTab = ref('events'); // 'events' or 'tasks'
const startDate = ref('');
const endDate = ref('');

const filteredEvents = computed(() => {
    let result = props.events;
    if (startDate.value) {
        result = result.filter(e => new Date(e.event_date) >= new Date(startDate.value));
    }
    if (endDate.value) {
        result = result.filter(e => new Date(e.event_date) <= new Date(endDate.value + 'T23:59:59'));
    }
    return result;
});

const filteredTasks = computed(() => {
    let result = props.tasks;
    if (startDate.value) {
        result = result.filter(t => t.due_date && new Date(t.due_date) >= new Date(startDate.value));
    }
    if (endDate.value) {
        result = result.filter(t => t.due_date && new Date(t.due_date) <= new Date(endDate.value + 'T23:59:59'));
    }
    return result;
});

const resetFilters = () => {
    startDate.value = '';
    endDate.value = '';
};

const form = useForm({
    title: '',
    description: '',
    event_date: '',
    location: '',
    type: 'general',
    is_priority: false
});

const taskForm = useForm({
    title: '',
    description: '',
    due_date: '',
    is_completed: false,
    is_priority: false
});

const showModal = ref(false);
const showTaskModal = ref(false);
const editingEvent = ref(null);
const editingTask = ref(null);

const openModal = (event = null) => {
    if (event) {
        editingEvent.value = event;
        form.title = event.title;
        form.description = event.description;
        form.event_date = event.event_date ? event.event_date.substring(0, 16) : '';
        form.location = event.location;
        form.type = event.type;
        form.is_priority = event.is_priority;
    } else {
        editingEvent.value = null;
        form.reset();
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
    editingEvent.value = null;
};

const openTaskModal = (task = null) => {
    if (task) {
        editingTask.value = task;
        taskForm.title = task.title;
        taskForm.description = task.description;
        taskForm.due_date = task.due_date ? task.due_date.substring(0, 16) : '';
        taskForm.is_completed = task.is_completed;
        taskForm.is_priority = task.is_priority;
    } else {
        editingTask.value = null;
        taskForm.reset();
    }
    showTaskModal.value = true;
};

const closeTaskModal = () => {
    showTaskModal.value = false;
    taskForm.reset();
    editingTask.value = null;
};

const submit = () => {
    if (editingEvent.value) {
        form.put(route('events.update', editingEvent.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('events.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const submitTask = () => {
    if (editingTask.value) {
        taskForm.put(route('tasks.update', editingTask.value.id), {
            onSuccess: () => closeTaskModal(),
        });
    } else {
        taskForm.post(route('tasks.store'), {
            onSuccess: () => closeTaskModal(),
        });
    }
};

const deleteElement = (id, type) => {
    if (confirm(`¿Estás seguro de eliminar est${type === 'event' ? 'e evento' : 'a tarea'}?`)) {
        if (type === 'event') {
            useForm({}).delete(route('events.destroy', id));
        } else {
            useForm({}).delete(route('tasks.destroy', id));
        }
    }
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-ES', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};
</script>

<template>
    <Head title="Eventos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                <div>
                    <h2 class="font-extrabold text-4xl text-slate-900 dark:text-white tracking-tight">Agenda Personal</h2>
                    <p class="text-slate-600 dark:text-slate-400 mt-1">Gestiona tus eventos y tareas pendientes.</p>
                    
                    <!-- Tab Switcher -->
                    <div class="flex gap-2 mt-6 bg-slate-100 dark:bg-slate-800 p-1.5 rounded-2xl w-fit">
                        <button @click="activeTab = 'events'" 
                            class="px-6 py-2 rounded-xl text-sm font-black transition-all"
                            :class="activeTab === 'events' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'">
                            Eventos
                        </button>
                        <button @click="activeTab = 'tasks'" 
                            class="px-6 py-2 rounded-xl text-sm font-black transition-all"
                            :class="activeTab === 'tasks' ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'">
                            Tareas
                        </button>
                    </div>
                </div>
                <div class="flex gap-4">
                    <SecondaryButton v-if="activeTab === 'tasks'" @click="openTaskModal()" class="btn-secondary py-3 px-6 rounded-2xl border-slate-200 dark:border-slate-800 dark:text-slate-400">
                        <span class="mr-2">📌</span> Nueva Tarea
                    </SecondaryButton>
                    <PrimaryButton v-if="activeTab === 'events'" @click="openModal()" class="btn-primary">
                        <span class="text-xl mr-2">+</span> Nuevo Evento
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filter Panel -->
                <div class="mb-8 p-6 bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                    <div class="flex flex-col md:flex-row items-end gap-6">
                        <div class="flex-1 w-full">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Desde</label>
                            <input type="date" v-model="startDate" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 text-slate-900 dark:text-white transition-all" />
                        </div>
                        <div class="flex-1 w-full">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Hasta</label>
                            <input type="date" v-model="endDate" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-indigo-500/20 text-slate-900 dark:text-white transition-all" />
                        </div>
                        <button @click="resetFilters" 
                            class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center gap-2">
                            <span>🧹</span> Limpiar
                        </button>
                    </div>
                </div>

                <!-- Events Tab Content -->
                <div v-if="activeTab === 'events'">
                    <div v-if="filteredEvents.length === 0" class="py-20 bg-white dark:bg-slate-900 rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-slate-500 dark:text-slate-400">
                        <span class="text-6xl mb-4">🔍</span>
                        <p class="text-xl font-medium">No se encontraron eventos en este rango.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="event in filteredEvents" :key="event.id" 
                            class="card-white group relative overflow-hidden transition-all hover:-translate-y-1 hover:shadow-xl">
                            <div class="absolute top-0 right-0 p-4">
                                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-full">
                                    {{ event.type }}
                                </span>
                            </div>
                            <div class="flex flex-col h-full">
                                <div class="mb-4">
                                    <h3 class="font-black text-2xl text-slate-900 dark:text-white mb-2">{{ event.title }}</h3>
                                    <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-sm font-bold">
                                        <span>📅</span> {{ formatDate(event.event_date) }}
                                    </div>
                                    <div v-if="event.is_priority" class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 text-[10px] font-black uppercase tracking-widest border border-rose-100 dark:border-rose-800">
                                        <span class="animate-pulse">🔥</span> Prioritario
                                    </div>
                                    <div v-if="event.location" class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-sm font-bold mt-1">
                                        <span>📍</span> {{ event.location }}
                                    </div>
                                </div>
                                <p class="text-slate-600 dark:text-slate-300 mb-6 flex-1 line-clamp-3">{{ event.description }}</p>
                                <div class="flex gap-2">
                                    <SecondaryButton @click="openModal(event)" class="flex-1 justify-center py-3 rounded-xl">Editar</SecondaryButton>
                                    <button @click="deleteElement(event.id, 'event')" class="p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl">🗑️</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks Tab Content -->
                <div v-if="activeTab === 'tasks'">
                    <div v-if="filteredTasks.length === 0" class="py-20 bg-white dark:bg-slate-900 rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-slate-500 dark:text-slate-400">
                        <span class="text-6xl mb-4">�</span>
                        <p class="text-xl font-medium">No se encontraron tareas en este rango.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="task in filteredTasks" :key="task.id" 
                            class="card-white group relative overflow-hidden transition-all hover:-translate-y-1 hover:shadow-xl"
                            :class="{ 'opacity-60 grayscale-[0.5]': task.is_completed }">
                            
                            <div class="absolute top-0 right-0 p-4">
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full"
                                    :class="task.is_completed ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400'">
                                    {{ task.is_completed ? 'Completado' : 'Pendiente' }}
                                </span>
                            </div>

                            <div class="flex flex-col h-full">
                                <div class="mb-4">
                                    <h3 class="font-black text-2xl text-slate-900 dark:text-white mb-2">{{ task.title }}</h3>
                                    <div v-if="task.due_date" class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-sm font-bold">
                                        <span>⏰</span> {{ formatDate(task.due_date) }}
                                    </div>
                                    <div v-if="task.is_priority" class="inline-flex items-center gap-1.5 mt-2 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 text-[10px] font-black uppercase tracking-widest border border-rose-100 dark:border-rose-800">
                                        <span class="animate-pulse">🔥</span> Prioritario
                                    </div>
                                </div>
                                <p class="text-slate-600 dark:text-slate-300 mb-6 flex-1 line-clamp-3">{{ task.description }}</p>
                                <div class="flex gap-2">
                                    <div class="flex-1 flex items-center gap-2">
                                        <SecondaryButton @click="openTaskModal(task)" class="py-2.5 px-4 rounded-xl text-xs">
                                            Editar
                                        </SecondaryButton>
                                        <button @click="deleteElement(task.id, 'task')" class="p-2.5 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl">🗑️</button>
                                    </div>
                                    <Link :href="route('tasks.index')" class="text-indigo-600 text-xs font-black hover:underline py-2.5">Ver en Calendario →</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Modal -->
        <Modal :show="showModal" @close="closeModal">
            <div class="p-8 dark:bg-slate-950">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8">
                    {{ editingEvent ? 'Editar Evento' : 'Nuevo Evento' }}
                </h2>

                <div class="space-y-6">
                    <div>
                        <InputLabel for="title" value="Título" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="title" v-model="form.title" type="text" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400" 
                            placeholder="Nombre del evento" autofocus />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="event_date" value="Fecha y Hora" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                            <TextInput id="event_date" v-model="form.event_date" type="datetime-local" 
                                class="input-friendly w-full py-3" />
                        </div>
                        <div>
                            <InputLabel for="type" value="Categoría" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                            <select id="type" v-model="form.type" 
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
                        <InputLabel for="location" value="Ubicación" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="location" v-model="form.location" type="text" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400" 
                            placeholder="¿Dónde será?" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Descripción" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <textarea id="description" v-model="form.description" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400 min-h-[120px]" 
                            placeholder="Añade detalles adicionales..."></textarea>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-rose-50/50 dark:bg-rose-900/10 rounded-2xl border border-rose-100 dark:border-rose-800/50 cursor-pointer" @click="form.is_priority = !form.is_priority">
                        <input type="checkbox" v-model="form.is_priority" class="w-5 h-5 rounded border-rose-300 text-rose-600 focus:ring-rose-500" />
                        <div>
                            <p class="text-sm font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest">Marcar como Prioritario</p>
                            <p class="text-xs text-rose-600/70 dark:text-rose-500/70">Aparecerá resaltado en el menú principal</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <SecondaryButton @click="closeModal" class="flex-1 justify-center py-4 rounded-2xl font-bold uppercase tracking-widest text-xs border-slate-100 dark:border-slate-800 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900 transition-all"> 
                        Cancelar 
                    </SecondaryButton>
                    <PrimaryButton class="flex-1 justify-center py-4 rounded-2xl font-bold uppercase tracking-widest text-xs bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200 dark:shadow-none shadow-lg hover:shadow-xl transition-all" 
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submit">
                        {{ editingEvent ? 'Actualizar Evento' : 'Crear Evento' }}
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
        <!-- Task Modal -->
        <Modal :show="showTaskModal" @close="closeTaskModal">
            <div class="p-8 dark:bg-slate-950">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8">Nueva Tarea</h2>
                
                <div class="space-y-6">
                    <div>
                        <InputLabel for="task_title" value="Título de la Tarea" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="task_title" v-model="taskForm.title" type="text" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400" 
                            placeholder="¿Qué tarea tienes pendiente?" />
                    </div>

                    <div>
                        <InputLabel for="task_due_date" value="Fecha Límite" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="task_due_date" v-model="taskForm.due_date" type="datetime-local" 
                            class="input-friendly w-full py-3" />
                    </div>

                    <div>
                        <InputLabel for="task_description" value="Descripción" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <textarea id="task_description" v-model="taskForm.description" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400 min-h-[100px]" 
                            placeholder="Detalles de la tarea..."></textarea>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-rose-50/50 dark:bg-rose-900/10 rounded-2xl border border-rose-100 dark:border-rose-800/50 cursor-pointer" @click="taskForm.is_priority = !taskForm.is_priority">
                        <input type="checkbox" v-model="taskForm.is_priority" class="w-5 h-5 rounded border-rose-300 text-rose-600 focus:ring-rose-500" />
                        <div>
                            <p class="text-sm font-black text-rose-700 dark:text-rose-400 uppercase tracking-widest">Marcar como Prioritario</p>
                            <p class="text-xs text-rose-600/70 dark:text-rose-500/70">Aparecerá resaltado en el menú principal</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <SecondaryButton @click="closeTaskModal" class="flex-1 justify-center py-4 rounded-2xl font-bold uppercase tracking-widest text-xs"> 
                        Cancelar 
                    </SecondaryButton>
                    <PrimaryButton class="flex-1 justify-center py-4 rounded-2xl font-bold uppercase tracking-widest text-xs bg-emerald-600 hover:bg-emerald-700" 
                        :disabled="taskForm.processing" @click="submitTask">
                        {{ editingTask ? 'Actualizar Tarea' : 'Crear Tarea' }}
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
