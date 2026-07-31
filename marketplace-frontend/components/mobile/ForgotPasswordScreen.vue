<template>
  <div class="h-full w-full flex flex-col justify-between p-6 relative overflow-y-auto transition-colors" :class="isDarkMode ? 'bg-slate-900 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Header -->
    <div class="pt-6 flex items-center justify-between">
      <button @click="$emit('nav', 'login')" class="w-8 h-8 rounded-full border flex items-center justify-center cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-300' : 'bg-white border-slate-300 text-slate-700 shadow-sm'">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <span class="text-xs font-bold" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Password Recovery</span>
      <div class="w-8"></div>
    </div>

    <!-- Main Content -->
    <div class="my-auto space-y-6 py-4">
      <div class="w-16 h-16 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-600 mx-auto">
        <!-- Key SVG -->
        <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
        </svg>
      </div>

      <div class="text-center space-y-2">
        <h2 class="text-2xl font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Reset Password</h2>
        <p class="text-xs max-w-[240px] mx-auto" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">
          Enter your registered email address or phone number to receive a 4-digit verification code.
        </p>
      </div>

      <div v-if="!otpSent" class="space-y-4">
        <div class="space-y-1">
          <label class="text-[11px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Email or Mobile</label>
          <input
            type="text"
            value="user@dopro.com"
            placeholder="Enter email or phone"
            class="w-full px-4 py-3 rounded-xl border text-xs font-medium focus:outline-none focus:border-rose-600"
            :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'"
          />
        </div>

        <button
          @click="otpSent = true"
          class="w-full py-3.5 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-xs shadow-lg shadow-rose-900/20 hover:opacity-95 transition-all cursor-pointer"
        >
          Send Verification Code
        </button>
      </div>

      <!-- OTP Input step -->
      <div v-else class="space-y-5">
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 text-center text-xs text-rose-800 font-medium">
          Code sent to <strong>user@dopro.com</strong>
        </div>

        <div class="flex justify-center gap-3">
          <input
            v-for="i in 4"
            :key="i"
            type="text"
            maxlength="1"
            value="7"
            class="w-12 h-14 rounded-xl border border-rose-500 text-center font-bold text-lg focus:outline-none"
            :class="isDarkMode ? 'bg-slate-800 text-white' : 'bg-white text-slate-900 shadow-sm'"
          />
        </div>

        <div class="text-center text-[11px]" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">
          Resend code in <span class="font-bold text-rose-600">00:42</span>
        </div>

        <button
          @click="$emit('nav', 'login')"
          class="w-full py-3.5 rounded-xl bg-emerald-600 text-white font-bold text-xs shadow-lg hover:bg-emerald-500 transition-all cursor-pointer"
        >
          Verify & Reset Password
        </button>
      </div>
    </div>

    <!-- Bottom link -->
    <div class="pb-4 text-center text-[11px]" :class="isDarkMode ? 'text-slate-500' : 'text-slate-500'">
      Remembered your password?
      <button @click="$emit('nav', 'login')" class="font-bold text-rose-600 hover:underline cursor-pointer">Back to Login</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({ isDarkMode: Boolean })
defineEmits(['nav'])
const otpSent = ref(false)
</script>
