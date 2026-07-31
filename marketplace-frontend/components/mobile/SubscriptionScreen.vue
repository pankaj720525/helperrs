<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Header -->
    <div class="p-4 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <button @click="$emit('nav', 'profile')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <h1 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Membership Plans</h1>
      <div class="w-8"></div>
    </div>

    <!-- Active Plan Banner & Options -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 pb-20 scrollbar-none">
      <!-- Active Status Card -->
      <div class="p-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-700 text-white shadow-md space-y-2">
        <div class="flex justify-between items-center">
          <span class="text-[10px] uppercase font-bold tracking-wider bg-white/20 px-2 py-0.5 rounded border border-white/20">
            Active Free Trial
          </span>
          <span class="text-xs font-bold">24 Days Left</span>
        </div>
        <h3 class="text-base font-heading font-bold">Service Provider Trial</h3>
        <p class="text-[11px] opacity-90">Enjoy unlimited client messages and 5 service listings.</p>
      </div>

      <!-- Plan Options -->
      <div class="space-y-3">
        <h3 class="text-xs font-bold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Available Subscription Tiers</h3>

        <!-- Pro Tier -->
        <div class="p-4 rounded-2xl border relative overflow-hidden space-y-3 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-rose-500/50' : 'bg-white border-rose-300 shadow-md'">
          <span class="absolute top-0 right-0 bg-rose-600 text-white font-extrabold text-[8px] uppercase px-3 py-1 rounded-bl-xl">
            Popular Choice
          </span>

          <div class="space-y-1">
            <h4 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Pro Vendor Plan</h4>
            <div class="text-xl font-extrabold text-rose-600">₹299 <span class="text-xs font-normal" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">/ month</span></div>
          </div>

          <div class="space-y-1.5 text-xs" :class="isDarkMode ? 'text-slate-300' : 'text-slate-700'">
            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> List up to 15 services</div>
            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Priority search ranking in your city</div>
            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Verified Vendor Badge</div>
          </div>

          <button class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-xs shadow-md cursor-pointer">
            Upgrade to Pro →
          </button>
        </div>

        <!-- Enterprise Tier -->
        <div class="p-4 rounded-2xl border space-y-3 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
          <div class="space-y-1">
            <h4 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Enterprise Business Tier</h4>
            <div class="text-xl font-extrabold text-amber-500">₹799 <span class="text-xs font-normal" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">/ month</span></div>
          </div>

          <div class="space-y-1.5 text-xs" :class="isDarkMode ? 'text-slate-300' : 'text-slate-700'">
            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Unlimited Service Listings</div>
            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Featured Homepage Hero Slot</div>
            <div class="flex items-center gap-2"><svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Dedicated Account Manager</div>
          </div>

          <button class="w-full py-2.5 rounded-xl border text-slate-800 font-bold text-xs hover:bg-slate-100 cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-200' : 'bg-slate-100 border-slate-200 text-slate-800'">
            Select Enterprise
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({ isDarkMode: Boolean })
defineEmits(['nav'])
</script>
