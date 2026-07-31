<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Header -->
    <div class="p-4 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <button @click="$emit('nav', 'home')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <h1 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">All Categories</h1>
      <button @click="$emit('nav', 'search')" class="p-1.5 rounded-lg text-slate-500 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </button>
    </div>

    <!-- Category Grid list -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 pb-20 scrollbar-none">
      <div v-for="cat in fullCategories" :key="cat.name" class="p-4 rounded-2xl border space-y-3 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" :class="isDarkMode ? 'bg-slate-800 text-rose-400' : 'bg-rose-50 text-rose-600'">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="cat.iconSvg" />
              </svg>
            </div>
            <div>
              <h3 class="text-sm font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">{{ cat.name }}</h3>
              <p class="text-[10px] font-medium" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">{{ cat.prosCount }} Verified Service Pros</p>
            </div>
          </div>
          <button @click="$emit('nav', 'services')" class="px-3 py-1 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-bold hover:bg-rose-100 cursor-pointer">
            Browse →
          </button>
        </div>

        <!-- Subcategories Chips -->
        <div class="flex flex-wrap gap-1.5 pt-1">
          <span
            v-for="sub in cat.subs"
            :key="sub"
            @click="$emit('nav', 'search')"
            class="px-2.5 py-1 rounded-lg text-[10px] font-medium border cursor-pointer transition-colors"
            :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300 hover:border-rose-500' : 'bg-slate-100 border-slate-200 text-slate-700 hover:border-rose-300 hover:bg-rose-50'"
          >
            {{ sub }}
          </span>
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

const fullCategories = [
  { name: 'AC & Cooling', prosCount: 84, iconSvg: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', subs: ['Chemical Wash', 'Gas Refill', 'Installation', 'PCB Repair'] },
  { name: 'Electrical & Wiring', prosCount: 112, iconSvg: 'M13 10V3L4 14h7v7l9-11h-7z', subs: ['Short Circuit Fix', 'MCB Upgrade', 'Chandelier', 'Fan Repair'] },
  { name: 'Plumbing & Leakage', prosCount: 95, iconSvg: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.12a2 2 0 00-1.022.547l-1.16 1.16a2 2 0 000 2.828l1.16 1.16a2 2 0 002.828 0l1.16-1.16a2 2 0 00.547-1.022l.477-2.387a6 6 0 01.517-3.86l.158-.318a6 6 0 00.517-3.86L9.12 5.6a2 2 0 00.547-1.022l1.16-1.16a2 2 0 012.828 0l1.16 1.16a2 2 0 010 2.828l-1.16 1.16z', subs: ['Tap Leakage', 'Drain Unblock', 'Water Tank', 'Geyser Installation'] },
  { name: 'Deep Cleaning & Pest', prosCount: 67, iconSvg: 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', subs: ['Sofa Cleaning', 'Bathroom Deep Clean', 'Cockroach Control', 'Full Home'] },
  { name: 'Wall Painting & Waterproof', prosCount: 45, iconSvg: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', subs: ['Interior Paint', 'Texture Wall', 'Waterproofing', 'Wood Polish'] }
]
</script>
