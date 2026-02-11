<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    events: Array
});

const form = useForm({
    title: '',
    description: '',
    event_date: '',
    location: '',
    type: 'general'
});

const showModal = ref(false);
const editingEvent = ref(null);

const openModal = (event = null) => {
    if (event) {
        editingEvent.value = event;
        form.title = event.title;
        form.description = event.description;
        form.event_date = event.event_date ? event.event_date.substring(0, 16) : '';
        form.location = event.location;
        form.type = event.type;
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

const deleteEvent = (id) => {
    if (confirm('¿Estás seguro de eliminar este evento?')) {
        useForm({}).delete(route('events.destroy', id));
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
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="font-extrabold text-4xl text-slate-900 dark:text-white tracking-tight">Eventos</h2>
                    <p class="text-slate-600 dark:text-slate-400 mt-1">Agenda tus reuniones y momentos importantes.</p>
                </div>
                <PrimaryButton @click="openModal()" class="btn-primary">
                    <span class="text-xl mr-2">+</span> Nuevo Evento
                </PrimaryButton>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="events.length === 0" class="py-20 bg-white dark:bg-slate-900 rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-slate-500 dark:text-slate-400">
                    <span class="text-6xl mb-4">🎈</span>
                    <p class="text-xl font-medium">No hay eventos próximos. ¡Crea uno nuevo!</p>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="event in events" :key="event.id" 
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
                                    <span>📅</span>
                                    {{ formatDate(event.event_date) }}
                                </div>
                                <div v-if="event.location" class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-sm font-bold mt-1">
                                    <span>📍</span>
                                    {{ event.location }}
                                </div>
                            </div>

                            <p class="text-slate-600 dark:text-slate-300 mb-6 flex-1 line-clamp-3">
                                {{ event.description }}
                            </p>

                            <div class="flex gap-2">
                                <SecondaryButton @click="openModal(event)" class="flex-1 justify-center py-3 rounded-xl dark:border-slate-800 dark:text-slate-400">
                                    Editar
                                </SecondaryButton>
                                <button @click="deleteEvent(event.id)" 
                                    class="p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-colors">
                                    🗑️
                                </button>
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
    </AuthenticatedLayout>
</template>
