<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Search Header -->
    <div class="p-4 pt-6 border-b sticky top-0 z-20 space-y-3 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <div class="flex items-center gap-2">
        <button @click="$emit('nav', 'home')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>

        <div class="relative flex-1">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search service, keyword or pro..."
            class="w-full pl-9 pr-8 py-2.5 rounded-xl border text-xs focus:outline-none focus:border-rose-600 transition-colors"
            :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-100 border-slate-200 text-slate-900'"
          />
          <svg class="w-4 h-4 absolute left-3 top-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <button v-if="searchQuery" @click="searchQuery = ''" class="absolute right-3 top-2.5 text-slate-400 text-xs">✕</button>
        </div>

        <!-- Filter Sheet Toggle Button -->
        <button @click="showFilter = !showFilter" class="p-2.5 rounded-xl border text-xs text-rose-600 flex items-center gap-1 font-bold cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700' : 'bg-slate-100 border-slate-200'">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 010 4m-6 8a2 2 0 100-4m0 4a2 2 0 010-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 010-4m0 4v2m0-6V4" />
          </svg>
        </button>
      </div>

      <!-- Controls bar: Location + View Mode Toggle (Map vs List) -->
      <div class="flex items-center justify-between text-xs pt-1" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">
        <button @click="$emit('open-location-modal')" class="flex items-center gap-1 text-[11px] font-medium hover:text-rose-600 cursor-pointer">
          <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          </svg>
          <span>Zone: Downtown</span>
          <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>

        <div class="flex items-center p-0.5 rounded-lg border" :class="isDarkMode ? 'bg-slate-800 border-slate-700' : 'bg-slate-100 border-slate-200'">
          <button @click="viewMode = 'list'" class="px-2 py-0.5 rounded text-[10px] font-bold cursor-pointer" :class="viewMode === 'list' ? 'bg-rose-600 text-white' : 'text-slate-500'">
            List View
          </button>
          <button @click="viewMode = 'map'" class="px-2 py-0.5 rounded text-[10px] font-bold cursor-pointer" :class="viewMode === 'map' ? 'bg-rose-600 text-white' : 'text-slate-500'">
            Map View
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 pb-20 scrollbar-none">
      
      <!-- Filter Modal / Slide Drawer -->
      <div v-if="showFilter" class="p-4 rounded-2xl border space-y-3 animate-fade-in" :class="isDarkMode ? 'bg-slate-900 border-rose-500/40' : 'bg-white border-rose-300 shadow-md'">
        <div class="flex justify-between items-center">
          <h3 class="text-xs font-bold uppercase tracking-wider" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Search Filters</h3>
          <button @click="showFilter = false" class="text-xs text-slate-400 font-bold cursor-pointer">Done ✕</button>
        </div>

        <!-- Rating Filter -->
        <div class="space-y-1">
          <label class="text-[10px] font-semibold text-slate-400">Minimum Rating</label>
          <div class="flex gap-2">
            <button v-for="r in ['Any', '4.0+', '4.5+', '4.8+']" :key="r" class="px-3 py-1 rounded-xl border text-xs font-bold cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300 hover:border-rose-500' : 'bg-slate-100 border-slate-200 text-slate-700 hover:border-rose-400'">
              ★ {{ r }}
            </button>
          </div>
        </div>

        <!-- Price Range Slider -->
        <div class="space-y-1">
          <div class="flex justify-between text-[10px] font-semibold text-slate-400">
            <span>Price Range</span>
            <span class="text-rose-600 font-bold">₹10/hr - ₹150/hr</span>
          </div>
          <input type="range" min="10" max="150" value="80" class="w-full accent-rose-600 cursor-pointer" />
        </div>
      </div>

      <!-- Map View Mock -->
      <div v-if="viewMode === 'map'" class="h-72 rounded-2xl border relative overflow-hidden flex items-center justify-center" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-slate-200 border-slate-300'">
        <div class="absolute inset-0 opacity-90 flex flex-col items-center justify-center space-y-3" :class="isDarkMode ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-900'">
          <div class="w-10 h-10 rounded-full bg-rose-500/20 border border-rose-500 flex items-center justify-center text-rose-600 animate-ping">
            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
          </div>
          <span class="text-xs font-bold">14 Service Providers Pinpointed in Downtown</span>
        </div>
      </div>

      <!-- Search Results List -->
      <div v-else class="space-y-3">
        <div class="flex justify-between items-center text-xs text-slate-400">
          <span>Found <strong>8</strong> matching service pros</span>
          <span class="text-rose-600 font-semibold">Sorted by Rating</span>
        </div>

        <div v-for="item in searchResults" :key="item.id" @click="$emit('nav', 'service-details')" class="p-3.5 rounded-2xl border space-y-2 cursor-pointer transition-all" :class="isDarkMode ? 'bg-slate-900 border-slate-800 hover:border-rose-500/40' : 'bg-white border-slate-200 hover:border-rose-300 shadow-sm'">
          <div class="flex justify-between items-start">
            <div>
              <h4 class="text-xs font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">{{ item.title }}</h4>
              <p class="text-[10px] font-medium" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">By {{ item.providerName }} • {{ item.zone }}</p>
            </div>
            <span class="text-xs font-extrabold text-rose-600">₹{{ item.price }}/hr</span>
          </div>

          <div class="flex justify-between items-center pt-2 border-t" :class="isDarkMode ? 'border-slate-800' : 'border-slate-100'">
            <div class="flex items-center gap-2 text-[10px]">
              <span class="text-amber-500 font-bold">★ {{ item.rating }}</span>
              <span class="text-emerald-600 font-semibold">✓ Verified Pro</span>
            </div>
            <button @click.stop="$emit('nav', 'chat-detail')" class="px-3 py-1 rounded-lg bg-rose-600 text-white font-bold text-[10px] cursor-pointer">
              Inquire Chat
            </button>
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
      <button @click="$emit('nav', 'search')" class="flex flex-col items-center gap-1 text-rose-600 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <span class="text-[9px] font-bold">Search</span>
      </button>
      <button @click="$emit('nav', 'services')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <span class="text-[9px] font-medium">Services</span>
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
import { ref } from 'vue'

defineProps({ isDarkMode: Boolean })
defineEmits(['nav', 'open-location-modal'])

const searchQuery = ref('AC Chemical Cleaning')
const viewMode = ref('list')
const showFilter = ref(false)

const searchResults = [
  { id: 101, title: 'Deep AC Chemical Wash & Leak Fix', providerName: 'Cooling Masters Inc.', zone: 'Downtown', rating: '4.9', price: 49 },
  { id: 102, title: 'Residential Electrical Inspection & Repair', providerName: 'Apex Power Services', zone: 'Metro East', rating: '4.8', price: 55 },
  { id: 103, title: 'Express Leak Detection & Pipe Repair', providerName: 'Quick Flow Plumbing', zone: 'Westside', rating: '4.9', price: 42 }
]
</script>
