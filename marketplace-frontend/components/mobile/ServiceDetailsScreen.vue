<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Scrollable Body -->
    <div class="flex-1 overflow-y-auto pb-20 scrollbar-none">
      
      <!-- Top Image Banner Header -->
      <div class="h-56 bg-gradient-to-br from-rose-600/20 via-rose-500/10 to-slate-900 relative flex items-center justify-center">
        <!-- Banner SVG Icon -->
        <svg class="w-16 h-16 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>

        <button @click="$emit('nav', 'services')" class="absolute top-6 left-4 w-8 h-8 rounded-full border flex items-center justify-center text-xs z-10 cursor-pointer" :class="isDarkMode ? 'bg-slate-900/80 border-slate-700 text-white' : 'bg-white/90 border-slate-200 text-slate-900 shadow-sm'">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click="isSaved = !isSaved" class="absolute top-6 right-4 w-8 h-8 rounded-full border flex items-center justify-center text-sm z-10 cursor-pointer" :class="isDarkMode ? 'bg-slate-900/80 border-slate-700' : 'bg-white/90 border-slate-200 shadow-sm'">
          <svg class="w-4 h-4" :class="isSaved ? 'text-rose-600 fill-rose-600' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
        </button>
        
        <div class="absolute bottom-3 left-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full backdrop-blur">
          ✓ Verified DO-PRO Provider
        </div>
      </div>

      <!-- Main Service Details -->
      <div class="p-4 space-y-4">
        <div>
          <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider">AC & Cooling Category</span>
          <h1 class="text-lg font-heading font-extrabold leading-tight mt-0.5" :class="isDarkMode ? 'text-white' : 'text-slate-900'">
            Deep AC Chemical Cleaning & Jet Wash Fix
          </h1>
          <div class="flex items-center gap-3 mt-2 text-xs" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">
            <span class="text-amber-500 font-bold">★ 4.9 (142 reviews)</span>
            <span>•</span>
            <span>📍 Servicing: Downtown Zone</span>
          </div>
        </div>

        <!-- Provider Card -->
        <div class="p-3 rounded-2xl border flex items-center justify-between transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-600 to-amber-500 flex items-center justify-center font-bold text-white text-sm">
              CM
            </div>
            <div>
              <h4 class="text-xs font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Cooling Masters Inc.</h4>
              <p class="text-[10px]" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Member since 2024 • Pro Vendor Tier</p>
            </div>
          </div>
          <button @click="$emit('nav', 'chat-detail')" class="px-2.5 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 font-bold text-[10px] flex items-center gap-1 cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Message
          </button>
        </div>

        <!-- Price & Features Box -->
        <div class="p-4 rounded-2xl border space-y-3 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
          <div class="flex justify-between items-center border-b pb-3" :class="isDarkMode ? 'border-slate-800' : 'border-slate-100'">
            <div>
              <span class="text-[10px] block font-medium" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Standard Service Rate</span>
              <span class="text-xl font-extrabold text-rose-600">₹49 <span class="text-xs font-normal" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">/ hour</span></span>
            </div>
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">
              30-Day Warranty
            </span>
          </div>

          <div class="space-y-1.5 text-xs" :class="isDarkMode ? 'text-slate-300' : 'text-slate-700'">
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
              <span>High-pressure jet coil chemical cleaning</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
              <span>Freon refrigerant pressure check & top-up</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
              <span>Filter disinfection & water tray unblock</span>
            </div>
          </div>
        </div>

        <!-- Customer Reviews Snippet -->
        <div class="space-y-2">
          <h3 class="text-xs font-bold uppercase tracking-wider" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Client Reviews</h3>
          <div class="p-3 rounded-2xl border space-y-1 text-xs transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
            <div class="flex justify-between items-center">
              <span class="font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">David K.</span>
              <span class="text-amber-500 text-[10px] font-bold">★★★★★</span>
            </div>
            <p class="text-[11px]" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">"Tech arrived in 25 mins. The AC is blowing ice-cold air again. Super polite!"</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Sticky Bottom Bar -->
    <div class="p-3.5 border-t flex items-center justify-between gap-3 absolute bottom-0 inset-x-0 z-30 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-lg'">
      <div>
        <span class="text-[9px] block" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Total Hourly Rate</span>
        <span class="text-base font-extrabold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">₹49.00</span>
      </div>

      <button @click="$emit('nav', 'chat-detail')" class="flex-1 py-3 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-xs shadow-lg shadow-rose-900/20 hover:opacity-95 transition-all text-center cursor-pointer">
        Start Direct Chat Inquiry →
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({ isDarkMode: Boolean })
defineEmits(['nav'])
const isSaved = ref(false)
</script>
