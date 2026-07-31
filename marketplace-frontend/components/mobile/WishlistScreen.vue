<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Header -->
    <div class="p-4 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <div class="flex items-center gap-2">
        <button @click="$emit('nav', 'profile')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <h1 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Wishlist / Saved Services</h1>
      </div>
      <span class="text-xs text-rose-600 font-bold">2 Saved</span>
    </div>

    <!-- Saved List -->
    <div class="flex-1 overflow-y-auto p-4 space-y-3 pb-20 scrollbar-none">
      <div v-for="item in wishlistItems" :key="item.id" @click="$emit('nav', 'service-details')" class="p-3.5 rounded-2xl border flex gap-3 cursor-pointer transition-all" :class="isDarkMode ? 'bg-slate-900 border-slate-800 hover:border-rose-500/40' : 'bg-white border-slate-200 hover:border-rose-300 shadow-sm'">
        <div class="w-20 h-20 rounded-xl flex items-center justify-center flex-shrink-0" :class="isDarkMode ? 'bg-slate-800 text-rose-400' : 'bg-rose-50 text-rose-600'">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.iconSvg"/></svg>
        </div>

        <div class="flex-1 flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-start">
              <h3 class="text-xs font-bold line-clamp-1" :class="isDarkMode ? 'text-white' : 'text-slate-900'">{{ item.title }}</h3>
              <button @click.stop="removeItem(item.id)" class="text-rose-600 text-xs cursor-pointer">
                <svg class="w-4 h-4 text-rose-600 fill-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
              </button>
            </div>
            <p class="text-[10px] font-medium" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">By {{ item.provider }}</p>
          </div>

          <div class="flex justify-between items-end mt-1">
            <span class="text-amber-500 font-bold text-[10px]">★ {{ item.rating }}</span>
            <span class="text-xs font-extrabold text-rose-600">₹{{ item.price }}/hr</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Nav -->
    <div class="h-16 border-t flex items-center justify-around px-2 absolute bottom-0 inset-x-0 z-30 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-lg'">
      <button @click="$emit('nav', 'home')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span class="text-[9px] font-medium">Home</span>
      </button>
      <button @click="$emit('nav', 'search')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <span class="text-[9px] font-medium">Search</span>
      </button>
      <button @click="$emit('nav', 'services')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <span class="text-[9px] font-medium">Services</span>
      </button>
      <button @click="$emit('nav', 'chats')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <span class="text-[9px] font-medium">Messages</span>
      </button>
      <button @click="$emit('nav', 'profile')" class="flex flex-col items-center gap-1 text-rose-600 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span class="text-[9px] font-bold">Account</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({ isDarkMode: Boolean })
defineEmits(['nav'])

const wishlistItems = ref([
  { id: 1, title: 'Deep AC Chemical Cleaning & Jet Wash', provider: 'Cooling Masters Inc.', rating: '4.9', price: 49, iconSvg: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
  { id: 2, title: 'Bathroom Plumbing & Leakage Repair', provider: 'Quick Flow Plumbing', rating: '4.9', price: 42, iconSvg: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.022.547l-1.16 1.16a2 2 0 000 2.828l1.16 1.16a2 2 0 002.828 0l1.16-1.16a2 2 0 00.547-1.022l.477-2.387a6 6 0 01.517-3.86l.158-.318a6 6 0 00.517-3.86L9.12 5.6a2 2 0 00.547-1.022l1.16-1.16a2 2 0 012.828 0l1.16 1.16a2 2 0 010 2.828l-1.16 1.16z' }
])

function removeItem(id) {
  wishlistItems.value = wishlistItems.value.filter(i => i.id !== id)
}
</script>
