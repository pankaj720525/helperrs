<template>
  <div class="h-full w-full flex flex-col justify-between p-6 relative transition-colors" :class="isDarkMode ? 'bg-slate-900 text-white' : 'bg-white text-slate-900'">
    <!-- Top bar -->
    <div class="pt-6 flex justify-between items-center z-10">
      <span class="text-xs font-bold uppercase tracking-wider text-rose-600">Step {{ currentStep + 1 }}/3</span>
      <button @click="$emit('skip')" class="text-xs font-semibold hover:text-rose-600 transition-colors" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">
        Skip Tour
      </button>
    </div>

    <!-- Active Step Content -->
    <div class="my-auto text-center space-y-6 px-2">
      <!-- Graphic Card -->
      <div class="w-56 h-56 mx-auto rounded-3xl p-6 flex flex-col items-center justify-center shadow-xl relative border" :class="isDarkMode ? 'bg-slate-800/80 border-slate-700' : 'bg-slate-50 border-slate-200'">
        <div class="w-20 h-20 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-600 mb-4">
          <!-- Step 0 SVG: Search / Magnifying Glass -->
          <svg v-if="currentStep === 0" class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <!-- Step 1 SVG: Chat Bubble -->
          <svg v-else-if="currentStep === 1" class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          <!-- Step 2 SVG: Shield Check / Pro -->
          <svg v-else class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </div>
        <div class="text-xs font-bold px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700">
          {{ steps[currentStep].badge }}
        </div>
      </div>

      <!-- Text -->
      <div class="space-y-2">
        <h2 class="text-2xl font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">
          {{ steps[currentStep].title }}
        </h2>
        <p class="text-xs leading-relaxed max-w-[260px] mx-auto" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">
          {{ steps[currentStep].desc }}
        </p>
      </div>
    </div>

    <!-- Bottom Controls -->
    <div class="pb-6 space-y-6 z-10">
      <!-- Indicator Dots -->
      <div class="flex justify-center items-center gap-2">
        <button
          v-for="(step, idx) in steps"
          :key="idx"
          @click="currentStep = idx"
          class="h-2 rounded-full transition-all duration-300 cursor-pointer"
          :class="currentStep === idx ? 'w-8 bg-rose-600' : (isDarkMode ? 'w-2 bg-slate-700' : 'w-2 bg-slate-200')"
        />
      </div>

      <!-- Action Button -->
      <button
        @click="nextStep"
        class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white font-bold text-sm shadow-lg shadow-rose-900/20 hover:opacity-95 transition-all flex items-center justify-center gap-2 cursor-pointer"
      >
        <span>{{ currentStep === steps.length - 1 ? 'Get Started Now' : 'Continue' }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({ isDarkMode: Boolean })
const emit = defineEmits(['complete', 'skip'])
const currentStep = ref(0)

const steps = [
  {
    badge: 'Verified Experts',
    title: 'Find Trusted Local Pros',
    desc: 'Browse top-rated electricians, plumbers, AC technicians, and cleaners servicing your zone.'
  },
  {
    badge: 'Direct Messaging',
    title: 'Instant Chat & Inquiry',
    desc: 'Communicate directly with service specialists, request quotes, and clarify details in real-time.'
  },
  {
    badge: 'Pro Subscription',
    title: 'Grow Your Business',
    desc: 'Service providers can upgrade to Pro tiers, list services, and connect with thousands of local clients.'
  }
]

function nextStep() {
  if (currentStep.value < steps.length - 1) {
    currentStep.value++
  } else {
    emit('complete')
  }
}
</script>
