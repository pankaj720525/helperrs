<template>
  <div
    class="h-full w-full flex flex-col justify-between p-6 relative overflow-hidden transition-colors"
    :class="isDarkMode ? 'bg-gradient-to-br from-[#0F172A] via-[#1E293B] to-[#8A042B] text-white' : 'bg-gradient-to-br from-rose-50 via-white to-rose-100 text-slate-900'"
  >
    <!-- Ambient glow circles -->
    <div class="absolute -top-20 -left-20 w-64 h-64 bg-rose-500/20 rounded-full blur-3xl" />
    <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-amber-500/20 rounded-full blur-3xl" />

    <!-- Top status bar spacing -->
    <div class="pt-8 text-right z-10">
      <span class="text-[10px] uppercase font-bold tracking-widest px-2.5 py-1 rounded-full border" :class="isDarkMode ? 'text-slate-300 bg-white/10 border-white/10' : 'text-slate-600 bg-white/80 border-slate-200 shadow-sm'">
        v2.4.0
      </span>
    </div>

    <!-- Center branding -->
    <div class="flex flex-col items-center justify-center text-center my-auto z-10">
      <div class="relative mb-6">
        <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-[#B20537] to-[#F43F5E] p-0.5 shadow-2xl flex items-center justify-center animate-pulse">
          <div class="w-full h-full rounded-[22px] flex items-center justify-center" :class="isDarkMode ? 'bg-[#0F172A]' : 'bg-white'">
            <span class="text-4xl font-extrabold tracking-tighter bg-gradient-to-r from-rose-600 to-amber-500 bg-clip-text text-transparent font-heading">
              DO
            </span>
          </div>
        </div>
        <div class="absolute -bottom-2 -right-2 bg-amber-400 text-slate-950 font-black text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-md shadow-md">
          PRO
        </div>
      </div>

      <h1 class="text-3xl font-heading font-extrabold tracking-tight mb-2" :class="isDarkMode ? 'text-white' : 'text-slate-900'">
        DO-PRO <span class="text-rose-600">Services</span>
      </h1>
      <p class="text-xs max-w-[220px] font-medium leading-relaxed" :class="isDarkMode ? 'text-slate-300' : 'text-slate-600'">
        Connecting verified service experts with your home & business
      </p>
    </div>

    <!-- Bottom progress indicator -->
    <div class="pb-6 z-10 space-y-4 text-center">
      <div class="w-48 h-1.5 rounded-full mx-auto overflow-hidden" :class="isDarkMode ? 'bg-white/10' : 'bg-slate-200'">
        <div class="h-full bg-gradient-to-r from-rose-500 via-amber-400 to-emerald-500 rounded-full w-3/4 animate-pulse"></div>
      </div>
      <p class="text-[11px] font-medium" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Securing verified professionals near you...</p>
    </div>
  </div>
</template>

<script setup>
defineProps({ isDarkMode: Boolean })
</script>
