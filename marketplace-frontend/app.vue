<template>
  <div>
    <!-- ── Premium Preloader Splash Screen ─────────────── -->
    <Transition name="preloader-fade">
      <div v-if="loading" class="preloader-screen" aria-label="Loading">
        <div class="preloader-content">
          <!-- Animated Logo Ring -->
          <div class="preloader-ring">
            <div class="preloader-logo">
              <span class="preloader-logo-text">SM</span>
            </div>
          </div>
          <!-- Brand Text -->
          <div class="preloader-brand">
            Service<span class="preloader-brand-accent">Market</span>
          </div>
          <!-- Animated Dots -->
          <div class="preloader-dots">
            <span class="preloader-dot" style="animation-delay: 0s"></span>
            <span class="preloader-dot" style="animation-delay: 0.15s"></span>
            <span class="preloader-dot" style="animation-delay: 0.3s"></span>
          </div>
        </div>
      </div>
    </Transition>

    <!-- ── Top Progress Loading Bar (Zepto Style) ──── -->
    <NuxtLoadingIndicator
      :color="'linear-gradient(to right, #B20537, #D4064A, #F43F5E, #F472B6)'"
      :height="4"
      :duration="2000"
      :throttle="0"
    />

    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
  </div>
</template>

<script setup>
const loading = ref(true);

onMounted(() => {
  // Small delay to ensure smooth transition after hydration
  setTimeout(() => {
    loading.value = false;
  }, 800);
});
</script>

<style>
/* ── Preloader Screen ──────────────────────────────────── */
.preloader-screen {
  position: fixed;
  inset: 0;
  z-index: 99999;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #f1f5f9 100%);
}
html.dark .preloader-screen {
  background: linear-gradient(135deg, #0a0f1a 0%, #0f172a 50%, #111827 100%);
}

.preloader-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.25rem;
}

/* ── Animated Ring ───────────────────────────────────── */
.preloader-ring {
  position: relative;
  width: 80px;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.preloader-ring::before {
  content: '';
  position: absolute;
  inset: 0;
  border-radius: 50%;
  border: 3px solid #e2e8f0;
  border-top-color: #B20537;
  border-right-color: #D4064A;
  animation: preloader-spin 1s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}
html.dark .preloader-ring::before {
  border-color: rgba(255,255,255,0.08);
  border-top-color: #f43f5e;
  border-right-color: #fb7185;
}

.preloader-logo {
  width: 56px;
  height: 56px;
  border-radius: 1rem;
  background: linear-gradient(135deg, #B20537 0%, #D4064A 50%, #F43F5E 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 32px rgba(178, 5, 55, 0.3);
  animation: preloader-pulse 1.5s ease-in-out infinite;
}
.preloader-logo-text {
  color: #ffffff;
  font-family: 'Outfit', 'Inter', sans-serif;
  font-weight: 800;
  font-size: 1.375rem;
  letter-spacing: -0.02em;
}

/* ── Brand Name ──────────────────────────────────────── */
.preloader-brand {
  font-family: 'Outfit', 'Inter', sans-serif;
  font-weight: 700;
  font-size: 1.25rem;
  color: #0f172a;
  letter-spacing: -0.01em;
}
html.dark .preloader-brand {
  color: #f8fafc;
}
.preloader-brand-accent {
  background: linear-gradient(135deg, #B20537, #F43F5E);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* ── Loading Dots ────────────────────────────────────── */
.preloader-dots {
  display: flex;
  gap: 6px;
}
.preloader-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #B20537;
  animation: preloader-bounce 0.6s ease-in-out infinite alternate;
}
html.dark .preloader-dot {
  background: #f43f5e;
}

/* ── Animations ──────────────────────────────────────── */
@keyframes preloader-spin {
  to { transform: rotate(360deg); }
}
@keyframes preloader-pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}
@keyframes preloader-bounce {
  from { transform: translateY(0); opacity: 0.4; }
  to { transform: translateY(-6px); opacity: 1; }
}

/* ── Fade Out Transition ─────────────────────────────── */
.preloader-fade-leave-active {
  transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.preloader-fade-leave-to {
  opacity: 0;
}

/* ── Top Loading Indicator Bar Glow ───────────────────── */
.nuxt-loading-indicator {
  box-shadow: 0 0 10px rgba(178, 5, 55, 0.7), 0 0 5px rgba(244, 63, 94, 0.5) !important;
  z-index: 999999 !important;
}
</style>
