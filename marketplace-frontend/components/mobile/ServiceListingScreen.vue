<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Top Header -->
    <div class="p-4 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <button @click="$emit('nav', 'home')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <h1 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">All Offered Services</h1>
      <button @click="$emit('nav', 'search')" class="p-1.5 rounded-lg text-slate-500 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </button>
    </div>

    <!-- Filter Chips -->
    <div class="p-3 border-b flex items-center gap-2 overflow-x-auto no-scrollbar text-xs transition-colors" :class="isDarkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white border-slate-200'">
      <button v-for="chip in ['All Services', '★ 4.5+ Rating', 'Express 30-Min', 'Fixed Price']" :key="chip" class="px-3 py-1 rounded-full border transition-colors cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300 hover:border-rose-500' : 'bg-slate-100 border-slate-200 text-slate-700 hover:border-rose-300'">
        {{ chip }}
      </button>
    </div>

    <!-- Services Grid -->
    <div class="flex-1 overflow-y-auto p-4 space-y-3.5 pb-20 scrollbar-none">
      <div v-for="item in servicesList" :key="item.id" @click="$emit('nav', 'service-details')" class="p-3.5 rounded-2xl border space-y-3 cursor-pointer transition-all" :class="isDarkMode ? 'bg-slate-900 border-slate-800 hover:border-rose-500/40' : 'bg-white border-slate-200 hover:border-rose-300 shadow-sm'">
        <div class="h-32 rounded-xl relative overflow-hidden flex items-center justify-center" :class="isDarkMode ? 'bg-slate-800' : 'bg-slate-100'">
          <div class="w-full h-full bg-gradient-to-br from-rose-500/10 to-rose-500/30 flex items-center justify-center">
            <svg class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="item.iconSvg" />
            </svg>
          </div>
          
          <button @click.stop="$emit('toggle-wishlist', item.id)" class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full border flex items-center justify-center text-sm backdrop-blur cursor-pointer" :class="isDarkMode ? 'bg-slate-900/80 border-slate-700 text-rose-500' : 'bg-white/90 border-slate-200 text-rose-600 shadow-sm'">
            <svg class="w-4 h-4" :class="item.isSaved ? 'text-rose-600 fill-rose-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </button>
          
          <span class="absolute bottom-2 left-2.5 bg-rose-600 text-white font-extrabold text-[9px] uppercase px-2 py-0.5 rounded">
            {{ item.category }}
          </span>
        </div>

        <div class="space-y-1">
          <div class="flex justify-between items-start">
            <h3 class="text-xs font-bold line-clamp-1" :class="isDarkMode ? 'text-white' : 'text-slate-900'">{{ item.title }}</h3>
            <span class="text-xs font-extrabold text-rose-600 flex-shrink-0 ml-2">₹{{ item.price }}/hr</span>
          </div>

          <p class="text-[10px] font-medium" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">
            By <strong :class="isDarkMode ? 'text-slate-200' : 'text-slate-800'">{{ item.provider }}</strong> • <span class="text-emerald-600 font-semibold">Verified Pro</span>
          </p>
        </div>

        <div class="flex items-center justify-between pt-2 border-t text-[10px]" :class="isDarkMode ? 'border-slate-800' : 'border-slate-100'">
          <span class="text-amber-500 font-bold">★ {{ item.rating }} ({{ item.reviews }} reviews)</span>
          <button @click.stop="$emit('nav', 'chat-detail')" class="px-3 py-1 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-[10px] cursor-pointer">
            Inquire Now →
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
defineEmits(['nav', 'toggle-wishlist'])

const servicesList = [
  { id: 1, title: 'Deep AC Chemical Wash & Gas Refill', category: 'AC & Cooling', provider: 'Cooling Masters Inc.', rating: '4.9', reviews: 142, price: 49, iconSvg: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', isSaved: true },
  { id: 2, title: 'Residential Electrical Inspection & Wiring', category: 'Electrical', provider: 'Apex Power Services', rating: '4.8', reviews: 98, price: 55, iconSvg: 'M13 10V3L4 14h7v7l9-11h-7z', isSaved: false },
  { id: 3, title: 'Bathroom Pipe Repair & Unclogging', category: 'Plumbing', provider: 'Quick Flow Plumbing', rating: '4.9', reviews: 210, price: 42, iconSvg: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.022.547l-1.16 1.16a2 2 0 000 2.828l1.16 1.16a2 2 0 002.828 0l1.16-1.16a2 2 0 00.547-1.022l.477-2.387a6 6 0 01.517-3.86l.158-.318a6 6 0 00.517-3.86L9.12 5.6a2 2 0 00.547-1.022l1.16-1.16a2 2 0 012.828 0l1.16 1.16a2 2 0 010 2.828l-1.16 1.16z', isSaved: false }
]
</script>
