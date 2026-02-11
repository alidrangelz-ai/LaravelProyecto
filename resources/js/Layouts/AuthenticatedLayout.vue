<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-[#f8fafc] dark:bg-slate-950 flex transition-colors duration-300">
        <!-- Sidebar Desktop -->
        <aside class="hidden md:flex flex-col w-72 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 sticky top-0 h-screen z-50 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
            <div class="p-8">
                <Link :href="route('dashboard')" class="flex items-center gap-3 group">
                    <div class="bg-indigo-600 p-2.5 rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-none group-hover:scale-110 transition-transform">
                        <ApplicationLogo class="block h-7 w-auto fill-current text-white" />
                    </div>
                    <span class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ $page.props.settings['site_title']?.value || 'BlocApp' }}
                    </span>
                </Link>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-4">
                <Link :href="route('dashboard')" 
                    class="flex items-center gap-3 px-5 py-4 rounded-2xl font-bold transition-all group"
                    :class="route().current('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white'">
                    <span class="text-xl group-hover:scale-110 transition-transform">🏠</span>
                    Menú Principal
                </Link>

                <Link :href="route('notes.index')" 
                    class="flex items-center gap-3 px-5 py-4 rounded-2xl font-bold transition-all group"
                    :class="route().current('notes.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white'">
                    <span class="text-xl group-hover:scale-110 transition-transform">📝</span>
                    Notas Rápidas
                </Link>

                <Link :href="route('tasks.index')" 
                    class="flex items-center gap-3 px-5 py-4 rounded-2xl font-bold transition-all group"
                    :class="route().current('tasks.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white'">
                    <span class="text-xl group-hover:scale-110 transition-transform">📅</span>
                    Calendario
                </Link>

                <Link :href="route('events.index')" 
                    class="flex items-center gap-3 px-5 py-4 rounded-2xl font-bold transition-all group"
                    :class="route().current('events.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white'">
                    <span class="text-xl group-hover:scale-110 transition-transform">🎉</span>
                    Eventos
                </Link>

                <Link :href="route('settings.index')" 
                    class="flex items-center gap-3 px-5 py-4 rounded-2xl font-bold transition-all group"
                    :class="route().current('settings.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white'">
                    <span class="text-xl group-hover:scale-110 transition-transform">⚙️</span>
                    Ajustes
                </Link>
            </nav>

            <div class="p-6 border-t border-slate-50">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-center">Version 1.0.0</p>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            <!-- Mobile Header -->
            <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 dark:border-slate-800 md:hidden">
                <div class="flex items-center justify-between h-16 px-6">
                    <Link :href="route('dashboard')" class="bg-indigo-600 p-1.5 rounded-xl">
                        <ApplicationLogo class="h-6 w-auto fill-current text-white" />
                    </Link>
                    
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" 
                        class="p-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div v-show="showingNavigationDropdown" class="border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <div class="p-4 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Menú Principal</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('notes.index')" :active="route().current('notes.*')">Notas Rápidas</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('tasks.index')" :active="route().current('tasks.*')">Calendario</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('events.index')" :active="route().current('events.*')">Eventos</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('settings.index')" :active="route().current('settings.*')">Ajustes</ResponsiveNavLink>
                    </div>
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                        <div class="px-4 mb-4">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $page.props.auth.user.name }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <ResponsiveNavLink :href="route('profile.edit')">Mi Perfil</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="text-rose-500 font-bold">Cerrar Sesión</ResponsiveNavLink>
                    </div>
                </div>
            </header>

            <!-- Page Heading -->
            <header class="pt-10 pb-6" v-if="$slots.header">
                <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 dark:text-white">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1">
                <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 pb-12">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
