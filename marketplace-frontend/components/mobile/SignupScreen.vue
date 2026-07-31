<template>
  <div class="h-full w-full flex flex-col justify-between p-6 relative overflow-y-auto transition-colors" :class="isDarkMode ? 'bg-slate-900 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Top Header -->
    <div class="pt-6 flex items-center justify-between">
      <button @click="$emit('nav', 'login')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-white border-slate-300 text-slate-700 shadow-sm'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <span class="text-xs font-bold" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Join DO-PRO</span>
      <div class="w-8"></div>
    </div>

    <!-- Main Form Content -->
    <div class="my-auto space-y-5 py-4">
      <div>
        <h2 class="text-2xl font-heading font-bold mb-1" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Create Account</h2>
        <p class="text-xs" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Select your role and start servicing or hiring</p>
      </div>

      <!-- Account Role Switcher -->
      <div class="grid grid-cols-2 p-1 rounded-xl border" :class="isDarkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-300 shadow-sm'">
        <button
          type="button"
          @click="role = 'customer'"
          class="py-2 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center justify-center gap-1.5"
          :class="role === 'customer' ? 'bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white shadow-md' : (isDarkMode ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900')"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <span>Customer</span>
        </button>
        <button
          type="button"
          @click="role = 'worker'"
          class="py-2 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center justify-center gap-1.5"
          :class="role === 'worker' ? 'bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white shadow-md' : (isDarkMode ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900')"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
          <span>Service Worker</span>
        </button>
      </div>

      <!-- Registration Form -->
      <form @submit.prevent="$emit('signup-success')" class="space-y-3">
        <!-- Full Name -->
        <div class="space-y-1">
          <label class="text-[10px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Full Name</label>
          <input
            type="text"
            placeholder="John Doe"
            class="w-full px-3.5 py-2.5 rounded-xl border text-xs font-medium focus:outline-none focus:border-rose-600"
            :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'"
          />
        </div>

        <!-- Email -->
        <div class="space-y-1">
          <label class="text-[10px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Email Address</label>
          <input
            type="email"
            placeholder="john@example.com"
            class="w-full px-3.5 py-2.5 rounded-xl border text-xs font-medium focus:outline-none focus:border-rose-600"
            :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'"
          />
        </div>

        <!-- Phone -->
        <div class="space-y-1">
          <label class="text-[10px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Phone Number</label>
          <input
            type="tel"
            placeholder="+1 (555) 000-0000"
            class="w-full px-3.5 py-2.5 rounded-xl border text-xs font-medium focus:outline-none focus:border-rose-600"
            :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'"
          />
        </div>

        <!-- Business Name if Worker -->
        <div v-if="role === 'worker'" class="space-y-1">
          <label class="text-[10px] font-semibold uppercase tracking-wider text-rose-600">Business / Service Trade Name</label>
          <input
            type="text"
            placeholder="e.g. Apex Electrical Solutions"
            class="w-full px-3.5 py-2.5 rounded-xl border text-xs font-medium focus:outline-none focus:border-rose-600"
            :class="isDarkMode ? 'bg-slate-800 border-rose-500/40 text-white' : 'bg-rose-50/50 border-rose-300 text-slate-900'"
          />
        </div>

        <!-- Password -->
        <div class="space-y-1">
          <label class="text-[10px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Password</label>
          <input
            type="password"
            placeholder="Create password"
            class="w-full px-3.5 py-2.5 rounded-xl border text-xs font-medium focus:outline-none focus:border-rose-600"
            :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'"
          />
        </div>

        <!-- Terms checkbox -->
        <div class="flex items-center gap-2 pt-1 text-[11px]" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">
          <input type="checkbox" checked class="rounded border-slate-300 text-rose-600 focus:ring-0" />
          <span>I agree to DO-PRO Terms & Privacy Policy</span>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full py-3 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-xs shadow-lg shadow-rose-900/20 hover:opacity-95 transition-all mt-2 cursor-pointer"
        >
          Complete Registration
        </button>
      </form>
    </div>

    <!-- Bottom link -->
    <div class="pb-4 text-center text-[11px]" :class="isDarkMode ? 'text-slate-500' : 'text-slate-500'">
      Already registered?
      <button @click="$emit('nav', 'login')" class="font-bold text-rose-600 hover:underline cursor-pointer">Sign In</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({ isDarkMode: Boolean })
defineEmits(['nav', 'signup-success'])
const role = ref('customer')
</script>
