<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Chat Header -->
    <div class="p-3.5 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <div class="flex items-center gap-3">
        <button @click="$emit('nav', 'chats')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-600 to-amber-500 flex items-center justify-center font-bold text-white text-xs shadow-xs">
          CM
        </div>
        <div>
          <h3 class="text-xs font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Cooling Masters Inc.</h3>
          <p class="text-[9px] text-emerald-600 font-semibold">● Online • AC Technician</p>
        </div>
      </div>

      <button class="p-2 rounded-xl border cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
      </button>
    </div>

    <!-- Messages Container -->
    <div class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-none">
      <div class="text-center my-2">
        <span class="text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full border transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800 text-slate-400' : 'bg-slate-200/60 border-slate-300 text-slate-600'">
          Today 10:30 AM
        </span>
      </div>

      <!-- Incoming Message -->
      <div class="flex flex-col items-start max-w-[80%] space-y-1">
        <div class="p-3 rounded-2xl rounded-tl-sm border text-xs transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800 text-slate-200' : 'bg-white border-slate-200 text-slate-800 shadow-xs'">
          Hello Alex! We received your inquiry regarding the Deep AC Chemical Wash.
        </div>
        <span class="text-[9px] px-1" :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">10:31 AM</span>
      </div>

      <!-- Outgoing Message -->
      <div class="flex flex-col items-end max-w-[80%] ml-auto space-y-1">
        <div class="p-3 rounded-2xl rounded-tr-sm bg-gradient-to-r from-[#B20537] to-[#D4064A] text-xs text-white shadow-md">
          Hi! Yes, my living room AC unit is not cooling properly and leaking water.
        </div>
        <span class="text-[9px] px-1" :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">10:35 AM • Read ✓✓</span>
      </div>

      <!-- Incoming Message -->
      <div class="flex flex-col items-start max-w-[80%] space-y-1">
        <div class="p-3 rounded-2xl rounded-tl-sm border text-xs transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800 text-slate-200' : 'bg-white border-slate-200 text-slate-800 shadow-xs'">
          Understood! I can dispatch our senior technician to your Downtown zone today at 3:00 PM. Rate is $49/hr with 30-day warranty.
        </div>
        <span class="text-[9px] px-1" :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">10:42 AM</span>
      </div>
    </div>

    <!-- Input Bar -->
    <div class="p-3 border-t flex items-center gap-2 relative z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-lg'">
      <button class="p-2 text-slate-400 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
      </button>
      <input
        type="text"
        placeholder="Type a message..."
        class="flex-1 px-3.5 py-2.5 rounded-xl border text-xs focus:outline-none focus:border-rose-600 transition-colors"
        :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-100 border-slate-200 text-slate-900'"
      />
      <button class="p-2.5 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white text-xs font-bold shadow-md cursor-pointer">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({ isDarkMode: Boolean })
defineEmits(['nav'])
</script>
