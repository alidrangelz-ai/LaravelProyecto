<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    settings: Object
});

const form = useForm({
    settings: [
        { key: 'site_title', value: props.settings['site_title']?.value || 'Mi Bloc de Notas', label: 'Título del Sitio' },
        { key: 'theme_color', value: props.settings['theme_color']?.value || 'blue', label: 'Color Principal' },
        { key: 'items_per_page', value: props.settings['items_per_page']?.value || '10', label: 'Items por Página' },
    ]
});

const isDark = ref(false);

onMounted(() => {
    isDark.value = localStorage.getItem('theme') === 'dark' || 
                   (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
});

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

const submit = () => {
    form.post(route('settings.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Optional: Show success message
        }
    });
};
</script>

<template>
    <Head title="Ajustes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2">
                <h2 class="font-extrabold text-4xl text-slate-900 tracking-tight">Ajustes</h2>
                <p class="text-slate-600 text-lg">Personaliza tu experiencia en la plataforma.</p>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-8">
                <!-- User Section -->
                <div class="card-white dark:bg-slate-900 dark:border-slate-800 border-none ring-1 ring-slate-100 dark:ring-slate-800">
                    <h3 class="text-slate-700 dark:text-slate-300 font-bold uppercase text-xs tracking-widest mb-6">Cuenta de Usuario</h3>
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-[1.5rem] border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-indigo-200 dark:shadow-none">
                                {{ $page.props.auth.user.name[0] }}
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 dark:text-white leading-tight">{{ $page.props.auth.user.name }}</h4>
                                <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">{{ $page.props.auth.user.email }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Link :href="route('profile.edit')" class="px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 font-bold rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-sm">
                                Perfil
                            </Link>
                            <Link :href="route('logout')" method="post" as="button" class="px-5 py-2.5 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 font-bold rounded-xl hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-all text-sm">
                                Salir
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Appearance Section -->
                <div class="card-white dark:bg-slate-900 dark:border-slate-800 border-none ring-1 ring-slate-100 dark:ring-slate-800">
                    <h3 class="text-slate-700 dark:text-slate-300 font-bold uppercase text-xs tracking-widest mb-6">Apariencia</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white">Modo Oscuro</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">Cambia el aspecto visual de la plataforma.</p>
                        </div>
                        <button @click="toggleTheme" 
                            class="w-16 h-8 rounded-full transition-colors relative flex items-center px-1"
                            :class="isDark ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-700'">
                            <div class="w-6 h-6 bg-white rounded-full shadow-md transition-transform"
                                :class="isDark ? 'translate-x-8' : 'translate-x-0'">
                                <span v-if="isDark" class="text-[10px] flex items-center justify-center h-full">🌙</span>
                                <span v-else class="text-[10px] flex items-center justify-center h-full">☀️</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Platform Settings -->
                <div class="card-white dark:bg-slate-900 dark:border-slate-800 border-none ring-1 ring-slate-100 dark:ring-slate-800">
                    <h3 class="text-slate-700 dark:text-slate-300 font-bold uppercase text-xs tracking-widest mb-6">Preferencias del Sitio</h3>
                    <form @submit.prevent="submit" class="space-y-8">
                        
                        <div v-for="(setting, index) in form.settings" :key="index" class="space-y-2">
                            <InputLabel :for="setting.key" :value="setting.label" class="text-slate-700 dark:text-slate-300 font-bold uppercase text-xs tracking-widest" />
                            <TextInput :id="setting.key" v-model="setting.value" type="text" 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none ring-1 ring-slate-200 dark:ring-slate-700 focus:ring-2 focus:ring-indigo-400 rounded-2xl px-5 py-4 transition-all dark:text-white" />
                        </div>

                        <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing" class="btn-primary py-4 px-10">
                                    Guardar Cambios
                                </PrimaryButton>
                                
                                <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 translate-x-2" enter-to-class="opacity-100 translate-x-0">
                                    <p v-if="form.recentlySuccessful" class="text-emerald-600 font-bold flex items-center gap-2">
                                        <span class="text-xl">✅</span> ¡Guardado correctamente!
                                    </p>
                                </transition>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
