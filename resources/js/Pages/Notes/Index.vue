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
    notes: Array
});

const form = useForm({
    title: '',
    content: '',
    color: '#ffffff',
    is_pinned: false
});

const showModal = ref(false);
const editingNote = ref(null);

const colors = [
    { name: 'Blanco', value: '#ffffff', class: 'bg-white' },
    { name: 'Rojo', value: '#fee2e2', class: 'bg-red-100' },
    { name: 'Amarillo', value: '#fef3c7', class: 'bg-yellow-100' },
    { name: 'Verde', value: '#dcfce7', class: 'bg-green-100' },
    { name: 'Azul', value: '#dbeafe', class: 'bg-blue-100' },
    { name: 'Indigo', value: '#e0e7ff', class: 'bg-indigo-100' },
    { name: 'Rosa', value: '#fce7f3', class: 'bg-pink-100' },
];

const openModal = (note = null) => {
    if (note) {
        editingNote.value = note;
        form.title = note.title;
        form.content = note.content;
        form.color = note.color || '#ffffff';
        form.is_pinned = !!note.is_pinned;
    } else {
        editingNote.value = null;
        form.reset();
        form.color = '#ffffff';
    }
    showModal.value = true;
};

const submit = () => {
    if (editingNote.value) {
        form.put(route('notes.update', editingNote.value.id), {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('notes.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
    editingNote.value = null;
};

const deleteNote = (id) => {
    if (confirm('¿Estás seguro de eliminar esta nota?')) {
        useForm({}).delete(route('notes.destroy', id));
    }
};
</script>

<template>
    <Head title="Notas Rápidas" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="font-extrabold text-4xl text-slate-900 dark:text-white tracking-tight">Notas Rápidas</h2>
                    <p class="text-slate-600 dark:text-slate-400 mt-1">Organiza tus ideas de forma sencilla.</p>
                </div>
                <PrimaryButton @click="openModal()" class="btn-primary">
                    <span class="text-xl mr-2">+</span> Nueva Nota
                </PrimaryButton>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Pinned Notes -->
                <div v-if="notes.filter(n => n.is_pinned).length > 0" class="mb-12">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="text-amber-500 text-xl">⭐</span>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-200">Notas Destacadas</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="note in notes.filter(n => n.is_pinned)" :key="note.id" 
                            class="group relative card-white border-t-8 transition-all hover:-translate-y-1 hover:shadow-xl"
                            :style="{ borderTopColor: note.color || '#6366f1' }">
                            
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-xl text-slate-900 dark:text-white leading-tight">{{ note.title }}</h4>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="openModal(note)" class="p-2 bg-slate-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/50 text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-colors">
                                        ✏️
                                    </button>
                                    <button @click="deleteNote(note.id)" class="p-2 bg-slate-50 dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-900/50 text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-xl transition-colors">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">{{ note.content }}</p>
                        </div>
                    </div>
                </div>

                <!-- Other Notes -->
                <div>
                    <div class="flex items-center gap-2 mb-6">
                        <span class="text-slate-400 text-xl">📄</span>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-slate-200">Todas las Notas</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="note in notes.filter(n => !n.is_pinned)" :key="note.id" 
                            class="group relative card-white border-t-8 transition-all hover:-translate-y-1 hover:shadow-xl"
                            :style="{ borderTopColor: note.color || '#e2e8f0' }">
                            
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="font-bold text-xl text-slate-900 dark:text-white leading-tight">{{ note.title }}</h4>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="openModal(note)" class="p-2 bg-slate-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/50 text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-xl transition-colors">
                                        ✏️
                                    </button>
                                    <button @click="deleteNote(note.id)" class="p-2 bg-slate-50 dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-900/50 text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-xl transition-colors">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">{{ note.content }}</p>
                        </div>
                        
                        <div v-if="notes.length === 0" class="col-span-full py-20 bg-white dark:bg-slate-900 rounded-[2rem] border-2 border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-slate-500 dark:text-slate-400">
                            <span class="text-6xl mb-4">✨</span>
                            <p class="text-xl font-medium">¡Empieza creando tu primera nota!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <Modal :show="showModal" @close="closeModal">
            <div class="p-8 dark:bg-slate-950">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8">
                    {{ editingNote ? 'Editar Nota' : 'Nueva Nota' }}
                </h2>

                <div class="space-y-6">
                    <div>
                        <InputLabel for="title" value="Título" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="title" v-model="form.title" type="text" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400" 
                            placeholder="¿En qué estás pensando?" autofocus />
                        <div v-if="form.errors.title" class="text-rose-500 text-sm mt-2">{{ form.errors.title }}</div>
                    </div>

                    <div>
                        <InputLabel for="content" value="Contenido" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <textarea id="content" v-model="form.content" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400 min-h-[200px]" 
                            placeholder="Escribe tus detalles aquí..."></textarea>
                        <div v-if="form.errors.content" class="text-rose-500 text-sm mt-2">{{ form.errors.content }}</div>
                    </div>

                    <div>
                        <InputLabel value="Color de identificación" class="text-slate-700 dark:text-slate-300 font-bold mb-4 uppercase text-xs tracking-widest" />
                        <div class="flex flex-wrap gap-3">
                            <button type="button" v-for="color in colors" :key="color.value"
                                @click="form.color = color.value"
                                class="w-10 h-10 rounded-full border-4 border-white dark:border-slate-800 shadow-sm transition-transform hover:scale-110 focus:outline-none"
                                :style="{ backgroundColor: color.value }"
                                :class="{ 'ring-2 ring-indigo-400 scale-110': form.color === color.value }">
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" v-model="form.is_pinned" 
                                class="w-5 h-5 rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-indigo-600 focus:ring-indigo-500 transition-all cursor-pointer" />
                            <span class="ml-3 text-slate-700 dark:text-slate-300 font-bold group-hover:text-indigo-600 transition-colors">Destacar esta nota en la parte superior</span>
                        </label>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <SecondaryButton @click="closeModal" class="flex-1 justify-center py-4 rounded-2xl font-bold uppercase tracking-widest text-xs border-slate-100 dark:border-slate-800 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900 transition-all"> 
                        Cancelar 
                    </SecondaryButton>
                    <PrimaryButton class="flex-1 justify-center py-4 rounded-2xl font-bold uppercase tracking-widest text-xs bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200 dark:shadow-none shadow-lg hover:shadow-xl transition-all" 
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submit">
                        {{ editingNote ? 'Actualizar Nota' : 'Crear Nota' }}
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
