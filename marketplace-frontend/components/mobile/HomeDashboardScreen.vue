<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Scrollable Body -->
    <div class="flex-1 overflow-y-auto pb-20 scrollbar-none">
      
      <!-- Ultra-Compact Sticky Header (Redesigned for Maximum Viewport Height) -->
      <div class="px-4 pt-3 pb-2.5 border-b sticky top-0 z-20 backdrop-blur-xl transition-colors shadow-xs" :class="isDarkMode ? 'bg-slate-900/95 border-slate-800' : 'bg-white/95 border-slate-200/80'">
        
        <!-- Row 1: Compact Location Pill + Brand Badge + Notification Bell -->
        <div class="flex items-center justify-between gap-2 mb-2">
          <!-- Compact Location Selector Pill -->
          <button
            @click="$emit('open-location-modal')"
            class="flex items-center gap-2 px-3 py-1.5 rounded-full border text-xs transition-all cursor-pointer group"
            :class="isDarkMode ? 'bg-slate-800/90 border-slate-700 hover:border-rose-500/50' : 'bg-slate-100/90 border-slate-200 hover:border-rose-300 hover:bg-rose-50/50'"
          >
            <!-- Live Pulse Dot -->
            <span class="relative flex h-2 w-2 flex-shrink-0">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            
            <!-- Location Pin Icon -->
            <svg class="w-3.5 h-3.5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            </svg>
            
            <div class="flex items-center gap-1 text-left truncate">
              <span class="text-[11px] font-extrabold truncate" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Downtown</span>
              <span class="text-[10px] font-medium hidden sm:inline" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">& 50+ Cities</span>
            </div>

            <!-- Dropdown Caret -->
            <svg class="w-3 h-3 text-rose-600 flex-shrink-0 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <!-- Right Action Bar: Brand Badge & Notification Bell -->
          <div class="flex items-center gap-2">
            <!-- Compact DO Logo -->
            <div class="w-7 h-7 rounded-xl bg-gradient-to-tr from-[#B20537] to-[#F43F5E] flex items-center justify-center font-black text-white text-[10px] shadow-sm">
              DO
            </div>

            <!-- Notification Bell with Red Badge -->
            <button
              @click="$emit('nav', 'notifications')"
              class="relative p-1.5 rounded-full border transition-colors cursor-pointer"
              :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300 hover:text-white' : 'bg-slate-100 border-slate-200 text-slate-700 hover:text-slate-900 shadow-2xs'"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
              </svg>
              <span class="absolute top-0.5 right-0.5 w-2 h-2 rounded-full bg-rose-600 border border-white"></span>
            </button>
          </div>
        </div>

        <!-- Row 2: Search Input with Integrated Filter Badge -->
        <div @click="$emit('nav', 'search')" class="relative cursor-pointer">
          <div
            class="w-full pl-3.5 pr-2.5 py-2 rounded-xl border text-xs flex items-center justify-between transition-all"
            :class="isDarkMode ? 'bg-slate-800/90 border-slate-700 text-slate-400 hover:border-slate-600' : 'bg-slate-100/90 border-slate-200 text-slate-500 hover:border-slate-300 shadow-2xs'"
          >
            <div class="flex items-center gap-2 truncate">
              <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <span class="truncate text-[11px] font-medium">Search electrician, plumber, AC service...</span>
            </div>

            <!-- Embedded Filter Icon Pill -->
            <span
              class="p-1 rounded-lg border text-rose-600 flex items-center justify-center flex-shrink-0 ml-2 shadow-2xs"
              :class="isDarkMode ? 'bg-slate-700 border-slate-600 text-rose-400' : 'bg-white border-slate-200/80 text-rose-600'"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 010 4m-6 8a2 2 0 100-4m0 4a2 2 0 010-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 010-4m0 4v2m0-6V4" />
              </svg>
            </span>
          </div>
        </div>

      </div>

      <!-- Quick Hash Chips -->
      <div class="px-4 pt-3 flex items-center gap-1.5 overflow-x-auto no-scrollbar text-[11px]" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">
        <span class="font-bold flex-shrink-0" :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">Trending:</span>
        <button
          v-for="chip in ['#AC Repair', '#Plumber', '#Electrician', '#Cleaning']"
          :key="chip"
          @click="$emit('nav', 'search')"
          class="px-2.5 py-1 rounded-full border transition-colors flex-shrink-0 cursor-pointer"
          :class="isDarkMode ? 'bg-slate-900 border-slate-800 hover:text-rose-400' : 'bg-white border-slate-200 hover:text-rose-600 shadow-2xs'"
        >
          {{ chip }}
        </button>
      </div>

      <!-- Hero Promo Banner Card -->
      <div class="p-4 pt-3">
        <div class="rounded-2xl bg-gradient-to-r from-[#B20537] via-[#8A042B] to-slate-900 p-4 border border-rose-500/30 shadow-xl relative overflow-hidden text-white">
          <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-rose-500/20 rounded-full blur-2xl"></div>
          
          <div class="relative z-10 space-y-2">
            <span class="text-[9px] font-black uppercase tracking-wider text-amber-300 bg-amber-950/60 px-2 py-0.5 rounded border border-amber-500/30">
              ⚡ Express Emergency Fix
            </span>
            <h3 class="text-base font-heading font-extrabold text-white leading-tight">
              30-Min On-Site Technician Guarantee
            </h3>
            <p class="text-[11px] text-slate-200">Fixed rates with 30-day warranty on all repairs</p>
            
            <button @click="$emit('nav', 'services')" class="mt-2 px-3.5 py-1.5 rounded-xl bg-white text-slate-950 font-bold text-xs hover:bg-slate-100 transition-all shadow-md cursor-pointer">
              Book Pro Service →
            </button>
          </div>
        </div>
      </div>

      <!-- Categories Grid Section -->
      <div class="p-4 space-y-3">
        <div class="flex justify-between items-center">
          <h2 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Top Categories</h2>
          <button @click="$emit('nav', 'categories')" class="text-xs font-bold text-rose-600 hover:underline cursor-pointer">View All</button>
        </div>

        <div class="grid grid-cols-4 gap-2.5 text-center">
          <div
            v-for="cat in categories"
            :key="cat.name"
            @click="$emit('nav', 'search')"
            class="p-2.5 rounded-2xl border transition-all cursor-pointer flex flex-col items-center gap-1.5"
            :class="isDarkMode ? 'bg-slate-900 border-slate-800 hover:border-rose-500/40' : 'bg-white border-slate-200 hover:border-rose-300 shadow-sm'"
          >
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" :class="isDarkMode ? 'bg-slate-800 text-rose-400' : 'bg-rose-50 text-rose-600'">
              <component :is="cat.iconComponent" class="w-5 h-5" />
            </div>
            <span class="text-[10px] font-semibold truncate w-full" :class="isDarkMode ? 'text-slate-300' : 'text-slate-700'">{{ cat.name }}</span>
          </div>
        </div>
      </div>

      <!-- Top Rated Pro Services -->
      <div class="p-4 pt-1 space-y-3">
        <div class="flex justify-between items-center">
          <h2 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Verified Specialists Near You</h2>
          <button @click="$emit('nav', 'services')" class="text-xs font-bold text-rose-600 hover:underline cursor-pointer">Explore</button>
        </div>

        <div class="space-y-3">
          <div
            v-for="service in topServices"
            :key="service.id"
            @click="$emit('nav', 'service-details')"
            class="p-3 rounded-2xl border transition-all cursor-pointer flex gap-3"
            :class="isDarkMode ? 'bg-slate-900 border-slate-800 hover:border-slate-700' : 'bg-white border-slate-200 hover:border-rose-300 shadow-sm'"
          >
            <div class="w-20 h-20 rounded-xl overflow-hidden relative flex-shrink-0" :class="isDarkMode ? 'bg-slate-800' : 'bg-slate-100'">
              <div class="w-full h-full bg-gradient-to-br from-rose-600/10 to-rose-600/30 flex items-center justify-center">
                <component :is="service.iconComponent" class="w-8 h-8 text-rose-600" />
              </div>
              <span class="absolute top-1 left-1 bg-amber-400 text-slate-950 font-extrabold text-[8px] px-1 rounded">PRO</span>
            </div>

            <div class="flex-1 flex flex-col justify-between">
              <div>
                <div class="flex justify-between items-start">
                  <h4 class="text-xs font-bold line-clamp-1" :class="isDarkMode ? 'text-white' : 'text-slate-900'">{{ service.title }}</h4>
                  <button @click.stop="$emit('toggle-wishlist', service.id)" class="text-xs cursor-pointer">
                    <svg class="w-4 h-4" :class="service.isWishlist ? 'text-rose-600 fill-rose-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </div>
                <p class="text-[10px] font-medium flex items-center gap-1 mt-0.5" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">
                  <span>👤 {{ service.providerName }}</span>
                  <span class="text-emerald-600 font-semibold">✓ Verified</span>
                </p>
              </div>

              <div class="flex justify-between items-end mt-2">
                <div class="flex items-center gap-1 text-[10px] text-amber-500 font-bold">
                  ★ {{ service.rating }} <span :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">({{ service.reviewsCount }})</span>
                </div>
                <div class="text-right">
                  <span class="text-xs font-extrabold text-rose-600">₹{{ service.price }}/hr</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Navigation Bar with Clean SVG Icons -->
    <div class="h-16 border-t flex items-center justify-around px-2 absolute bottom-0 inset-x-0 z-30 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-lg'">
      <button @click="$emit('nav', 'home')" class="flex flex-col items-center gap-1 text-rose-600 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span class="text-[9px] font-bold">Home</span>
      </button>

      <button @click="$emit('nav', 'search')" class="flex flex-col items-center gap-1 transition-colors cursor-pointer" :class="isDarkMode ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900'">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <span class="text-[9px] font-medium">Search</span>
      </button>

      <button @click="$emit('nav', 'services')" class="flex flex-col items-center gap-1 transition-colors cursor-pointer" :class="isDarkMode ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900'">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <span class="text-[9px] font-medium">Services</span>
      </button>

      <button @click="$emit('nav', 'chats')" class="flex flex-col items-center gap-1 transition-colors cursor-pointer relative" :class="isDarkMode ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900'">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <span class="text-[9px] font-medium">Messages</span>
        <span class="absolute top-0 right-1 w-2 h-2 rounded-full bg-rose-600"></span>
      </button>

      <button @click="$emit('nav', 'profile')" class="flex flex-col items-center gap-1 transition-colors cursor-pointer" :class="isDarkMode ? 'text-slate-400 hover:text-white' : 'text-slate-500 hover:text-slate-900'">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span class="text-[9px] font-medium">Account</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { h } from 'vue'

defineProps({ isDarkMode: Boolean })
defineEmits(['nav', 'open-location-modal', 'toggle-wishlist'])

// Clean SVG Icon render functions
const AcIcon = () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' })])
const PlumbIcon = () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.022.547l-1.16 1.16a2 2 0 000 2.828l1.16 1.16a2 2 0 002.828 0l1.16-1.16a2 2 0 00.547-1.022l.477-2.387a6 6 0 01.517-3.86l.158-.318a6 6 0 00.517-3.86L9.12 5.6a2 2 0 00.547-1.022l1.16-1.16a2 2 0 012.828 0l1.16 1.16a2 2 0 010 2.828l-1.16 1.16z' })])
const ElecIcon = () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M13 10V3L4 14h7v7l9-11h-7z' })])
const CleanIcon = () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z' })])
const PaintIcon = () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01' })])
const WrenchIcon = () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' })])
const GridIcon = () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z' })])

const categories = [
  { name: 'AC Repair', iconComponent: AcIcon },
  { name: 'Plumbing', iconComponent: PlumbIcon },
  { name: 'Electrical', iconComponent: ElecIcon },
  { name: 'Cleaning', iconComponent: CleanIcon },
  { name: 'Painting', iconComponent: PaintIcon },
  { name: 'Appliance', iconComponent: WrenchIcon },
  { name: 'Carpentry', iconComponent: WrenchIcon },
  { name: 'All Services', iconComponent: GridIcon }
]

const topServices = [
  { id: 1, title: 'Deep AC Chemical Cleaning & Jet Wash', providerName: 'Cooling Masters Inc.', rating: '4.9', reviewsCount: 142, price: 49, isWishlist: true, iconComponent: AcIcon },
  { id: 2, title: 'Complete Home Electrical Fault Fix', providerName: 'Apex Power Services', rating: '4.8', reviewsCount: 98, price: 55, isWishlist: false, iconComponent: ElecIcon },
  { id: 3, title: 'Bathroom Plumbing & Leakage Repair', providerName: 'Quick Flow Plumbing', rating: '4.9', reviewsCount: 210, price: 42, isWishlist: false, iconComponent: PlumbIcon }
]
</script>
