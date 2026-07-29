<template>
  <div>
    <!-- ── Premium Admin Preloader Splash Screen ──────── -->
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
          <div class="preloader-sub">Admin Dashboard</div>
          <!-- Animated Bar -->
          <div class="preloader-bar-track">
            <div class="preloader-bar-fill"></div>
          </div>
        </div>
      </div>
    </Transition>

    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
  </div>
</template>

<script setup>
const loading = ref(true);

onMounted(() => {
  setTimeout(() => {
    loading.value = false;
  }, 800);
});
</script>

<style>
/* ── Admin Preloader Screen ────────────────────────────── */
.preloader-screen {
  position: fixed;
  inset: 0;
  z-index: 99999;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 50%, #e0e7ff 100%);
}
html.dark .preloader-screen {
  background: linear-gradient(135deg, #050a14 0%, #0b1120 50%, #0f172a 100%);
}

.preloader-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
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
  border-top-color: #5c52e9;
  border-right-color: #7c74f0;
  animation: preloader-spin 1s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}
html.dark .preloader-ring::before {
  border-color: rgba(255,255,255,0.06);
  border-top-color: #818cf8;
  border-right-color: #a5b4fc;
}

.preloader-logo {
  width: 56px;
  height: 56px;
  border-radius: 1rem;
  background: linear-gradient(135deg, #4338ca 0%, #5c52e9 50%, #7c74f0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 32px rgba(92, 82, 233, 0.35);
  animation: preloader-pulse 1.5s ease-in-out infinite;
}
.preloader-logo-text {
  color: #ffffff;
  font-family: 'Poppins', 'Inter', sans-serif;
  font-weight: 700;
  font-size: 1.375rem;
  letter-spacing: -0.02em;
}

/* ── Brand Name ──────────────────────────────────────── */
.preloader-brand {
  font-family: 'Poppins', 'Inter', sans-serif;
  font-weight: 700;
  font-size: 1.25rem;
  color: #0f172a;
  letter-spacing: -0.01em;
}
html.dark .preloader-brand {
  color: #f8fafc;
}
.preloader-brand-accent {
  background: linear-gradient(135deg, #4338ca, #7c74f0);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.preloader-sub {
  font-family: 'Inter', sans-serif;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.15em;
  color: #64748b;
  margin-top: -0.25rem;
}
html.dark .preloader-sub {
  color: #94a3b8;
}

/* ── Progress Bar ────────────────────────────────────── */
.preloader-bar-track {
  width: 140px;
  height: 3px;
  border-radius: 999px;
  background: #e2e8f0;
  overflow: hidden;
  margin-top: 0.25rem;
}
html.dark .preloader-bar-track {
  background: rgba(255,255,255,0.08);
}
.preloader-bar-fill {
  width: 40%;
  height: 100%;
  border-radius: 999px;
  background: linear-gradient(90deg, #5c52e9, #818cf8);
  animation: preloader-progress 0.8s ease-in-out infinite alternate;
}

/* ── Animations ──────────────────────────────────────── */
@keyframes preloader-spin {
  to { transform: rotate(360deg); }
}
@keyframes preloader-pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}
@keyframes preloader-progress {
  from { transform: translateX(-20%); width: 30%; }
  to { transform: translateX(200%); width: 50%; }
}

/* ── Fade Out Transition ─────────────────────────────── */
.preloader-fade-leave-active {
  transition: opacity 0.4s ease, transform 0.4s ease;
}
.preloader-fade-leave-to {
  opacity: 0;
  transform: scale(1.05);
}
</style>
