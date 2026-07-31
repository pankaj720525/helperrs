<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Header -->
    <div class="p-4 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <button @click="$emit('nav', 'profile')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <h1 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Error State Showcase</h1>
      <div class="w-8"></div>
    </div>

    <!-- Error Type Switcher -->
    <div class="p-3 border-b flex items-center gap-2 overflow-x-auto text-xs no-scrollbar transition-colors" :class="isDarkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200'">
      <button v-for="tab in ['Offline Network', 'Session Expired', 'Location Access']" :key="tab" @click="activeError = tab" class="px-3 py-1 rounded-full font-bold transition-all cursor-pointer" :class="activeError === tab ? 'bg-rose-600 text-white shadow-xs' : (isDarkMode ? 'bg-slate-800 text-slate-400' : 'bg-slate-100 text-slate-600')">
        {{ tab }}
      </button>
    </div>

    <!-- Error Display -->
    <div class="flex-1 flex flex-col items-center justify-center text-center p-6 space-y-4 my-auto">
      <div class="w-24 h-24 rounded-3xl border flex items-center justify-center shadow-xl transition-colors" :class="isDarkMode ? 'bg-rose-500/10 border-rose-500/30 text-rose-400' : 'bg-rose-50 border-rose-200 text-rose-600'">
        <svg v-if="activeError === 'Offline Network'" class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636a9 9 0 010 12.728m-12.728 0a9 9 0 010-12.728m2.828 2.828a5 5 0 017.072 0m-7.072 7.072a5 5 0 017.072 0M12 12h.01" />
        </svg>
        <svg v-else-if="activeError === 'Session Expired'" class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        <svg v-else class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
        </svg>
      </div>

      <div class="space-y-1.5 max-w-[240px]">
        <h3 class="text-base font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">
          {{ activeError === 'Offline Network' ? 'Internet Connection Lost' : activeError === 'Session Expired' ? 'Session Token Expired' : 'Location Services Disabled' }}
        </h3>
        <p class="text-xs leading-relaxed" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">
          {{ activeError === 'Offline Network' ? 'Please check your Wi-Fi or cellular data connection and try reloading.' : activeError === 'Session Expired' ? 'Your login session has timed out. Please sign in again to access chats.' : 'Please allow location permission to detect service pros in your servicing zone.' }}
        </p>
      </div>

      <button @click="$emit('nav', activeError === 'Session Expired' ? 'login' : 'home')" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-xs shadow-lg shadow-rose-900/20 hover:opacity-95 cursor-pointer">
        {{ activeError === 'Session Expired' ? 'Re-authenticate Login' : 'Try Connection Again' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({ isDarkMode: Boolean })
defineEmits(['nav'])
const activeError = ref('Offline Network')
</script>
