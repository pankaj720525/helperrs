<template>
  <div class="h-full w-full flex flex-col justify-between p-6 relative overflow-y-auto transition-colors" :class="isDarkMode ? 'bg-slate-900 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Top Header -->
    <div class="pt-6 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-[#B20537] to-[#F43F5E] flex items-center justify-center font-bold text-white text-xs">
          DO
        </div>
        <span class="font-heading font-extrabold text-base tracking-tight" :class="isDarkMode ? 'text-white' : 'text-slate-900'">DO-PRO</span>
      </div>
      <button @click="$emit('nav', 'signup')" class="text-xs font-bold text-rose-600 hover:underline cursor-pointer">
        Register
      </button>
    </div>

    <!-- Main Content -->
    <div class="my-auto space-y-6 pt-4">
      <div>
        <h2 class="text-2xl font-heading font-bold mb-1" :class="isDarkMode ? 'text-white' : 'text-slate-900'">Welcome Back</h2>
        <p class="text-xs" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Sign in to manage your services, inquiries & profile</p>
      </div>

      <!-- Form -->
      <form @submit.prevent="$emit('login-success')" class="space-y-4">
        <!-- Email Input -->
        <div class="space-y-1">
          <label class="text-[11px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Email Address</label>
          <div class="relative">
            <input
              type="email"
              value="alex.pro@dopro.com"
              placeholder="name@domain.com"
              class="w-full pl-4 pr-10 py-3 rounded-xl border text-xs font-medium focus:outline-none focus:border-rose-600 transition-colors"
              :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'"
            />
            <svg class="w-4 h-4 absolute right-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
        </div>

        <!-- Password Input -->
        <div class="space-y-1">
          <div class="flex justify-between items-center">
            <label class="text-[11px] font-semibold uppercase tracking-wider" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Password</label>
            <button type="button" @click="$emit('nav', 'forgot')" class="text-[11px] font-semibold text-rose-600 hover:underline cursor-pointer">
              Forgot?
            </button>
          </div>
          <div class="relative">
            <input
              type="password"
              value="••••••••••••"
              placeholder="Enter password"
              class="w-full pl-4 pr-10 py-3 rounded-xl border text-xs font-medium focus:outline-none focus:border-rose-600 transition-colors"
              :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-white border-slate-300 text-slate-900 shadow-sm'"
            />
            <svg class="w-4 h-4 absolute right-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs pt-1" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" checked class="rounded border-slate-300 text-rose-600 focus:ring-0" />
            <span>Remember login</span>
          </label>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full py-3.5 rounded-xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-xs shadow-lg shadow-rose-900/20 hover:opacity-95 transition-all cursor-pointer"
        >
          Sign In to Account
        </button>
      </form>

      <!-- Divider -->
      <div class="relative flex items-center justify-center">
        <div class="border-t w-full" :class="isDarkMode ? 'border-slate-800' : 'border-slate-200'"></div>
        <span class="px-3 text-[10px] uppercase font-bold relative" :class="isDarkMode ? 'bg-slate-900 text-slate-500' : 'bg-slate-50 text-slate-400'">Or continue with</span>
      </div>

      <!-- Quick Social Auth -->
      <div class="grid grid-cols-2 gap-3">
        <button type="button" class="py-2.5 rounded-xl border flex items-center justify-center gap-2 text-xs font-semibold hover:border-rose-400 cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-200' : 'bg-white border-slate-300 text-slate-800 shadow-sm'">
          <svg class="w-4 h-4 text-slate-800" fill="currentColor" viewBox="0 0 24 24">
            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.34c.64-.78 1.08-1.85.96-2.94-1 .04-2.16.67-2.85 1.48-.61.71-1.12 1.83-.98 2.9 1.11.09 2.23-.58 2.87-1.44z"/>
          </svg>
          <span>Apple</span>
        </button>

        <button type="button" class="py-2.5 rounded-xl border flex items-center justify-center gap-2 text-xs font-semibold hover:border-rose-400 cursor-pointer" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-200' : 'bg-white border-slate-300 text-slate-800 shadow-sm'">
          <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.761H12.545z"/>
          </svg>
          <span>Google</span>
        </button>
      </div>
    </div>

    <!-- Bottom Footer -->
    <div class="pb-4 text-center text-[11px]" :class="isDarkMode ? 'text-slate-500' : 'text-slate-500'">
      Don't have an account?
      <button @click="$emit('nav', 'signup')" class="font-bold text-rose-600 hover:underline cursor-pointer">Create Account</button>
    </div>
  </div>
</template>

<script setup>
defineProps({ isDarkMode: Boolean })
defineEmits(['nav', 'login-success'])
</script>
