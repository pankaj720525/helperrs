<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Header -->
    <div class="p-4 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <div class="flex items-center gap-2">
        <button @click="$emit('nav', 'profile')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <h1 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">My Offered Services</h1>
      </div>

      <button @click="$emit('nav', 'create-service')" class="px-2.5 py-1 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-[10px] cursor-pointer">
        + Post New
      </button>
    </div>

    <!-- Active List -->
    <div class="flex-1 overflow-y-auto p-4 space-y-3 pb-20 scrollbar-none">
      <div v-for="service in myServices" :key="service.id" class="p-3.5 rounded-2xl border space-y-3 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
        <div class="flex gap-3">
          <div class="w-16 h-16 rounded-xl flex items-center justify-center flex-shrink-0" :class="isDarkMode ? 'bg-slate-800 text-rose-400' : 'bg-rose-50 text-rose-600'">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="service.iconSvg"/></svg>
          </div>

          <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start">
              <h3 class="text-xs font-bold truncate" :class="isDarkMode ? 'text-white' : 'text-slate-900'">{{ service.title }}</h3>
              <span class="text-[10px] font-extrabold text-rose-600">₹{{ service.price }}/hr</span>
            </div>
            <p class="text-[10px]" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Category: {{ service.category }}</p>
            
            <div class="flex items-center gap-2 mt-1 text-[9px]">
              <span class="px-2 py-0.2 rounded bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">
                Active Listing
              </span>
              <span :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">👁️ {{ service.views }} Views</span>
            </div>
          </div>
        </div>

        <div class="flex gap-2 pt-2 border-t" :class="isDarkMode ? 'border-slate-800' : 'border-slate-100'">
          <button class="flex-1 py-1.5 rounded-xl border font-bold text-[10px] cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700 hover:bg-slate-200'">
            Edit Listing
          </button>
          <button class="px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-[10px] font-bold cursor-pointer">
            Pause
          </button>
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
      <button @click="$emit('nav', 'services')" class="flex flex-col items-center gap-1 text-rose-600 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <span class="text-[9px] font-bold">Services</span>
      </button>
      <button @click="$emit('nav', 'chats')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <span class="text-[9px] font-medium">Messages</span>
      </button>
      <button @click="$emit('nav', 'profile')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span class="text-[9px] font-medium">Account</span>
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({ isDarkMode: Boolean })
defineEmits(['nav'])

const myServices = [
  { id: 1, title: 'Deep AC Chemical Wash & Gas Refill', category: 'AC & Cooling', price: 49, views: 340, iconSvg: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
  { id: 2, title: 'Split Unit Duct Cleaning & Sanitation', category: 'AC & Cooling', price: 65, views: 180, iconSvg: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' }
]
</script>
