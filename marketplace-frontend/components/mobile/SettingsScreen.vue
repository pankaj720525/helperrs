<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Header -->
    <div class="p-4 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <button @click="$emit('nav', 'profile')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <h1 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">App Preferences & Settings</h1>
      <div class="w-8"></div>
    </div>

    <!-- Preferences List -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 pb-20 scrollbar-none">
      
      <!-- Theme Setting -->
      <div class="p-4 rounded-2xl border space-y-3 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
        <h3 class="text-xs font-bold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Appearance Theme</h3>
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-2">
            <svg v-if="!isDarkMode" class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <svg v-else class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            <span class="text-xs font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">{{ isDarkMode ? 'Dark Mode' : 'Light Mode (Default)' }}</span>
          </div>
          <button @click="$emit('toggle-theme')" class="w-12 h-6 rounded-full p-1 border transition-colors flex items-center cursor-pointer" :class="isDarkMode ? 'justify-end bg-rose-600 border-rose-500' : 'justify-start bg-slate-200 border-slate-300'">
            <span class="w-4 h-4 rounded-full bg-white shadow-md"></span>
          </button>
        </div>
      </div>

      <!-- General Settings -->
      <div class="p-4 rounded-2xl border space-y-3 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
        <h3 class="text-xs font-bold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Notifications</h3>

        <div class="flex justify-between items-center text-xs">
          <span :class="isDarkMode ? 'text-slate-200' : 'text-slate-800'">Push Notifications</span>
          <input type="checkbox" checked class="rounded border-slate-300 text-rose-600 focus:ring-0" />
        </div>
        <div class="flex justify-between items-center text-xs">
          <span :class="isDarkMode ? 'text-slate-200' : 'text-slate-800'">Chat Messages Alert</span>
          <input type="checkbox" checked class="rounded border-slate-300 text-rose-600 focus:ring-0" />
        </div>
      </div>

      <!-- Language & Location -->
      <div class="p-4 rounded-2xl border space-y-3 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
        <h3 class="text-xs font-bold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Region & Language</h3>

        <div class="flex justify-between items-center text-xs">
          <span :class="isDarkMode ? 'text-slate-200' : 'text-slate-800'">Language</span>
          <span class="font-bold text-rose-600">English (US) ▼</span>
        </div>
        <div class="flex justify-between items-center text-xs">
          <span :class="isDarkMode ? 'text-slate-200' : 'text-slate-800'">Default Zone</span>
          <button @click="$emit('open-location-modal')" class="font-bold text-rose-600 cursor-pointer">Downtown 📍</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({ isDarkMode: Boolean })
defineEmits(['nav', 'toggle-theme', 'open-location-modal'])
</script>
