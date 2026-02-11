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
    tasks: Array
});

// Calendar State
const currentDate = ref(new Date());
const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

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
        days.push({ 
            day: i, 
            date: dateStr,
            tasks: dayTasks
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
const editingTask = ref(null);

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
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="font-extrabold text-4xl text-slate-900 dark:text-white tracking-tight">Calendario</h2>
                    <p class="text-slate-600 dark:text-slate-400 mt-1">Gestiona tus tareas y fechas límite.</p>
                </div>
                <PrimaryButton @click="openModal()" class="btn-primary">
                    <span class="text-xl mr-2">+</span> Nueva Tarea
                </PrimaryButton>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Navigation -->
                <div class="flex items-center justify-between mb-8 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4 rounded-[2rem] shadow-sm">
                    <button @click="prevMonth" class="w-12 h-12 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800 rounded-2xl transition-all text-2xl text-slate-600 dark:text-slate-400 hover:text-indigo-600">
                        &larr;
                    </button>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ months[currentDate.getMonth()] }} <span class="text-indigo-600">{{ currentDate.getFullYear() }}</span>
                    </h3>
                    <button @click="nextMonth" class="w-12 h-12 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800 rounded-2xl transition-all text-2xl text-slate-600 dark:text-slate-400 hover:text-indigo-600">
                        &rarr;
                    </button>
                </div>

                <!-- Calendar Grid -->
                <div class="card-white !p-0 overflow-hidden border-none ring-1 ring-slate-100 dark:ring-slate-800">
                    <div class="grid grid-cols-7 text-center font-bold bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                        <div v-for="day in daysOfWeek" :key="day" class="py-4 text-slate-600 dark:text-slate-400 text-xs uppercase tracking-[0.2em]">
                            {{ day }}
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-7 divide-x divide-y divide-slate-100 dark:divide-slate-800">
                        <div v-for="(dayObj, index) in calendarDays" :key="index" 
                            class="min-h-[140px] p-3 bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer group"
                            @click="dayObj.day && openModal(null, dayObj.date)">
                            
                            <div v-if="dayObj.day" class="flex justify-between items-start mb-3">
                                <span class="text-sm font-bold transition-transform group-hover:scale-110" :class="[
                                    dayObj.date === new Intl.DateTimeFormat('en-CA', {timeZone: 'America/Caracas'}).format(new Date()) 
                                    ? 'bg-indigo-600 text-white w-7 h-7 flex items-center justify-center rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none' 
                                    : 'text-slate-600 dark:text-slate-400'
                                ]">
                                    {{ dayObj.day }}
                                </span>
                            </div>

                            <div v-if="dayObj.tasks?.length" class="space-y-2">
                                <div v-for="task in dayObj.tasks" :key="task.id"
                                    @click.stop="openModal(task)"
                                    class="text-[11px] font-bold p-2 rounded-xl border transition-all truncate flex items-center gap-2 shadow-sm hover:shadow-md"
                                    :class="[
                                        task.is_completed 
                                        ? 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-100 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 opacity-60' 
                                        : 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-100 dark:border-indigo-800 text-indigo-700 dark:text-indigo-400'
                                    ]">
                                    <span v-if="task.is_completed" class="text-[10px]">✅</span>
                                    {{ task.title }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showModal" @close="closeModal">
            <div class="p-8 dark:bg-slate-950">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8">
                    {{ editingTask ? 'Editar Tarea' : 'Nueva Tarea' }}
                </h2>

                <div class="space-y-6">
                    <div>
                        <InputLabel for="title" value="Título" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="title" v-model="form.title" type="text" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400" 
                            placeholder="¿Qué hay que hacer?" autofocus />
                        <div v-if="form.errors.title" class="text-rose-500 text-sm mt-2">{{ form.errors.title }}</div>
                    </div>

                    <div>
                        <InputLabel for="description" value="Descripción" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <textarea id="description" v-model="form.description" 
                            class="input-friendly w-full py-3 placeholder:text-slate-400 min-h-[120px]" 
                            placeholder="Añade más detalles..."></textarea>
                    </div>

                    <div>
                        <InputLabel for="due_date" value="Fecha y Hora" class="text-slate-700 dark:text-slate-300 font-bold mb-2 uppercase text-xs tracking-widest" />
                        <TextInput id="due_date" v-model="form.due_date" type="datetime-local" 
                            class="input-friendly w-full py-3" />
                    </div>

                    <div v-if="editingTask" class="flex items-center bg-slate-50 dark:bg-slate-900 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <label class="flex items-center cursor-pointer group">
                            <Checkbox name="is_completed" v-model:checked="form.is_completed" 
                                class="w-5 h-5 rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-emerald-600 focus:ring-emerald-500 transition-all cursor-pointer" />
                            <span class="ml-3 text-slate-700 dark:text-slate-300 font-bold group-hover:text-emerald-600 transition-colors">Marcar como completada</span>
                        </label>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <DangerButton v-if="editingTask" @click="deleteTask" class="bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/40 py-4 px-6 rounded-2xl font-bold uppercase tracking-widest text-xs border-none shadow-none"> 
                        Eliminar 
                    </DangerButton>
                    <div class="flex-1 flex gap-4 ml-auto">
                        <SecondaryButton @click="closeModal" class="flex-1 justify-center py-4 rounded-2xl font-bold uppercase tracking-widest text-xs border-slate-100 dark:border-slate-800 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900 transition-all"> 
                            Cancelar 
                        </SecondaryButton>
                        <PrimaryButton class="flex-1 justify-center py-4 rounded-2xl font-bold uppercase tracking-widest text-xs bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200 dark:shadow-none shadow-lg hover:shadow-xl transition-all" 
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submit">
                            {{ editingTask ? 'Actualizar' : 'Guardar' }}
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
