<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Header -->
    <div class="p-4 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <button @click="$emit('nav', 'profile')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <h1 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Empty State Showcase</h1>
      <div class="w-8"></div>
    </div>

    <!-- Empty State Switcher -->
    <div class="p-3 border-b flex items-center gap-2 overflow-x-auto text-xs no-scrollbar transition-colors" :class="isDarkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200'">
      <button v-for="tab in ['Wishlist', 'Messages', 'Services Search']" :key="tab" @click="activeState = tab" class="px-3 py-1 rounded-full font-bold transition-all cursor-pointer" :class="activeState === tab ? 'bg-rose-600 text-white shadow-xs' : (isDarkMode ? 'bg-slate-800 text-slate-400' : 'bg-slate-100 text-slate-600')">
        {{ tab }}
      </button>
    </div>

    <!-- Empty Content Display -->
    <div class="flex-1 flex flex-col items-center justify-center text-center p-6 space-y-4 my-auto">
      <div class="w-24 h-24 rounded-3xl border flex items-center justify-center shadow-xl transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800 text-rose-400' : 'bg-white border-slate-200 text-rose-600'">
        <svg v-if="activeState === 'Wishlist'" class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <svg v-else-if="activeState === 'Messages'" class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <svg v-else class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>

      <div class="space-y-1.5 max-w-[240px]">
        <h3 class="text-base font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">
          {{ activeState === 'Wishlist' ? 'No Saved Services Yet' : activeState === 'Messages' ? 'No Active Conversations' : 'No Matching Services Found' }}
        </h3>
        <p class="text-xs leading-relaxed" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">
          {{ activeState === 'Wishlist' ? 'Explore top-rated service pros and tap the heart icon to save services.' : activeState === 'Messages' ? 'Start a direct chat with verified specialists to ask questions or request quotes.' : 'Try adjusting your search keywords or switching your servicing location zone.' }}
        </p>
      </div>

      <button @click="$emit('nav', 'services')" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-xs shadow-lg shadow-rose-900/20 hover:opacity-95 cursor-pointer">
        Browse Available Services →
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({ isDarkMode: Boolean })
defineEmits(['nav'])
const activeState = ref('Wishlist')
</script>
