<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
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
                <p class="text-slate-500 text-lg">Personaliza tu experiencia en la plataforma.</p>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="card-white border-none ring-1 ring-slate-100">
                    <form @submit.prevent="submit" class="space-y-8">
                        
                        <div v-for="(setting, index) in form.settings" :key="index" class="space-y-2">
                            <InputLabel :for="setting.key" :value="setting.label" class="text-slate-500 font-bold uppercase text-xs tracking-widest" />
                            <TextInput :id="setting.key" v-model="setting.value" type="text" 
                                class="w-full bg-slate-50 border-none ring-1 ring-slate-200 focus:ring-2 focus:ring-indigo-400 rounded-2xl px-5 py-4 transition-all" />
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
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
