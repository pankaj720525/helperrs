<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Header -->
    <div class="p-4 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <button @click="$emit('nav', 'provider-services')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-slate-100 border-slate-200 text-slate-700'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <h1 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Post New Service Listing</h1>
      <div class="w-8"></div>
    </div>

    <!-- Form -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 pb-20 scrollbar-none">
      <form @submit.prevent="$emit('nav', 'provider-services')" class="space-y-3.5">
        <!-- Title -->
        <div class="space-y-1">
          <label class="text-[10px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Service Title</label>
          <input
            type="text"
            placeholder="e.g. Emergency Electrical Circuit Repair"
            class="w-full px-3.5 py-2.5 rounded-xl border text-xs focus:outline-none focus:border-rose-600"
            :class="isDarkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'"
          />
        </div>

        <!-- Category Dropdown -->
        <div class="space-y-1">
          <label class="text-[10px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Category</label>
          <select class="w-full px-3.5 py-2.5 rounded-xl border text-xs focus:outline-none" :class="isDarkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'">
            <option>AC & Cooling</option>
            <option>Electrical & Wiring</option>
            <option>Plumbing & Leakage</option>
            <option>Deep Cleaning & Pest</option>
            <option>Painting</option>
          </select>
        </div>

        <!-- Hourly Price -->
        <div class="space-y-1">
          <label class="text-[10px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Price Rate (₹ INR / hour)</label>
          <input
            type="number"
            placeholder="55"
            class="w-full px-3.5 py-2.5 rounded-xl border text-xs focus:outline-none focus:border-rose-600"
            :class="isDarkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'"
          />
        </div>

        <!-- Description -->
        <div class="space-y-1">
          <label class="text-[10px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Detailed Description & Features</label>
          <textarea
            rows="3"
            placeholder="Describe what is included in your service rate..."
            class="w-full px-3.5 py-2.5 rounded-xl border text-xs focus:outline-none focus:border-rose-600"
            :class="isDarkMode ? 'bg-slate-900 border-slate-800 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'"
          ></textarea>
        </div>

        <!-- Photo Dropzone -->
        <div class="space-y-1">
          <label class="text-[10px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Service Banner Photo</label>
          <div class="p-6 rounded-2xl border-2 border-dashed text-center space-y-1 cursor-pointer hover:border-rose-500 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-300 shadow-xs'">
            <svg class="w-8 h-8 text-rose-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="text-xs font-bold block" :class="isDarkMode ? 'text-slate-300' : 'text-slate-700'">Tap to Upload Image</span>
            <span class="text-[9px] block" :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">PNG, JPG up to 5MB</span>
          </div>
        </div>

        <button
          type="submit"
          class="w-full py-3.5 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-xs shadow-lg shadow-rose-900/20 hover:opacity-95 transition-all mt-4 cursor-pointer"
        >
          Publish Service Listing →
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
defineProps({ isDarkMode: Boolean })
defineEmits(['nav'])
</script>
