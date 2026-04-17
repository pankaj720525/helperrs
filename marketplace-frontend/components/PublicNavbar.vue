<template>
  <nav class="public-nav">
    <div class="nav-inner">
      <div class="nav-row">

        <!-- Logo -->
        <NuxtLink to="/" class="nav-logo group">
          <div class="logo-badge group-hover:scale-105">
            <span class="logo-text">SM</span>
          </div>
          <span class="logo-name hidden sm:block">
            Service<span class="text-gradient">Market</span>
          </span>
        </NuxtLink>

        <!-- Nav Links (Desktop) -->
        <div class="nav-links hidden md:flex">
          <NuxtLink to="/"         class="nav-link">{{ t('home') }}</NuxtLink>
          <NuxtLink to="/services" class="nav-link">{{ t('services') }}</NuxtLink>
          <NuxtLink to="/search"   class="nav-link">{{ t('search') }}</NuxtLink>
        </div>

        <!-- Right Side -->
        <div class="nav-right">

          <!-- ── Language Switcher ─────────────────────── -->
          <div class="lang-wrap" ref="langDropdownRef">
            <button
              type="button"
              @click="langOpen = !langOpen"
              class="lang-pill"
              :title="currentLangOption.label"
            >
              <span class="lang-flag">{{ currentLangOption.flag }}</span>
              <span class="lang-code">{{ currentLangOption.code.toUpperCase() }}</span>
              <svg class="lang-arrow" :class="{ 'rotated': langOpen }" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
              </svg>
            </button>

            <Transition name="lang-drop">
              <div v-if="langOpen" class="lang-menu">
                <button
                  v-for="lang in LANGUAGES"
                  :key="lang.code"
                  type="button"
                  @click="selectLang(lang.code)"
                  class="lang-item"
                  :class="{ active: currentLang === lang.code }"
                >
                  <span class="lang-item-flag">{{ lang.flag }}</span>
                  <span class="lang-item-native">{{ lang.nativeLabel }}</span>
                  <span v-if="currentLang === lang.code" class="lang-check">✓</span>
                </button>
              </div>
            </Transition>
          </div>
          <!-- ─────────────────────────────────────────── -->

          <!-- ── Theme Toggle ───────────────────────── -->
          <button
            type="button"
            @click="toggleTheme"
            :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
            class="theme-pill"
            :class="isDark ? 'pill-dark' : 'pill-light'"
          >
            <span class="pill-icon pill-left">🌙</span>
            <span class="pill-icon pill-right">☀️</span>
            <span class="pill-thumb" :class="isDark ? 'thumb-l' : 'thumb-r'" />
          </button>
          <!-- ─────────────────────────────────────────── -->

          <!-- Authenticated -->
          <template v-if="userStore.isAuthenticated">
            <NuxtLink to="/dashboard" class="nav-link hidden md:inline">{{ t('dashboard') }}</NuxtLink>
            <NuxtLink to="/chats"     class="nav-link hidden md:inline">💬 {{ t('chats') }}</NuxtLink>
            <div class="flex items-center gap-2">
              <div class="avatar-bubble">{{ userStore.user?.name?.charAt(0)?.toUpperCase() }}</div>
              <button @click="handleLogout" class="logout-btn">{{ t('logout') }}</button>
            </div>
          </template>

          <!-- Guest -->
          <template v-else>
            <NuxtLink to="/login"    class="nav-link">{{ t('login') }}</NuxtLink>
            <NuxtLink to="/register" class="register-btn">{{ t('register') }}</NuxtLink>
          </template>

          <!-- Mobile menu toggle -->
          <button @click="mobileOpen = !mobileOpen" class="mobile-toggle md:hidden">
            {{ mobileOpen ? '✕' : '☰' }}
          </button>
        </div>

      </div>

      <!-- Mobile Menu -->
      <Transition name="slide-down">
        <div v-if="mobileOpen" class="mobile-menu">
          <NuxtLink to="/"         class="mobile-link" @click="mobileOpen = false">{{ t('home') }}</NuxtLink>
          <NuxtLink to="/services" class="mobile-link" @click="mobileOpen = false">{{ t('services') }}</NuxtLink>
          <NuxtLink to="/search"   class="mobile-link" @click="mobileOpen = false">{{ t('search') }}</NuxtLink>

          <!-- Mobile language row -->
          <div class="mobile-lang-row">
            <button
              v-for="lang in LANGUAGES"
              :key="lang.code"
              type="button"
              @click="selectLang(lang.code); mobileOpen = false"
              class="mobile-lang-btn"
              :class="{ 'mlb-active': currentLang === lang.code }"
            >{{ lang.flag }} {{ lang.nativeLabel }}</button>
          </div>

          <template v-if="userStore.isAuthenticated">
            <NuxtLink to="/dashboard" class="mobile-link" @click="mobileOpen = false">{{ t('dashboard') }}</NuxtLink>
            <NuxtLink to="/chats"     class="mobile-link" @click="mobileOpen = false">{{ t('chats') }}</NuxtLink>
          </template>
        </div>
      </Transition>
    </div>
  </nav>
</template>

<script setup lang="ts">
const userStore = useUserStore();
const api       = useApi();
const { isDark, toggleTheme, initTheme }      = useTheme();
const { currentLang, currentLangOption, setLang, initLang, t, LANGUAGES } = useLanguage();

const mobileOpen      = ref(false);
const langOpen        = ref(false);
const langDropdownRef = ref<HTMLElement | null>(null);

onMounted(() => {
  initTheme();
  initLang();
  document.addEventListener('click', onOutsideClick);
});
onUnmounted(() => document.removeEventListener('click', onOutsideClick));

const onOutsideClick = (e: MouseEvent) => {
  if (langDropdownRef.value && !langDropdownRef.value.contains(e.target as Node)) {
    langOpen.value = false;
  }
};
const selectLang = (code: 'en' | 'hi' | 'gu') => { setLang(code); langOpen.value = false; };
const handleLogout = async () => {
  try { await api.post('/auth/logout'); } catch {}
  userStore.logout();
  navigateTo('/');
};
</script>

<style scoped>
/* ── Nav shell ───────────────────────────────────────── */
.public-nav {
  position: sticky; top: 0; z-index: 50;
  background: rgba(15, 23, 42, 0.85);
  backdrop-filter: blur(16px) saturate(180%);
  border-bottom: 1px solid rgba(255,255,255,0.07);
}
.nav-inner { max-width: 80rem; margin: 0 auto; padding: 0 1.25rem; }
.nav-row   { display: flex; align-items: center; justify-content: space-between; height: 4rem; }

/* ── Logo ────────────────────────────────────────────── */
.nav-logo  { display: flex; align-items: center; gap: 0.625rem; text-decoration: none; }
.logo-badge {
  width: 2.25rem; height: 2.25rem; border-radius: 0.625rem;
  background: linear-gradient(135deg, #B20537, #D4064A, #F43F5E);
  display: flex; align-items: center; justify-content: center;
  transition: transform 0.2s;
}
.logo-text { color: #fff; font-weight: 700; font-size: 0.8125rem; }
.logo-name { font-family: 'Outfit', sans-serif; font-weight: 600; color: #fff; font-size: 1.0625rem; }

/* ── Desktop nav links ───────────────────────────────── */
.nav-links { display: flex; align-items: center; gap: 1.25rem; }
.nav-link {
  font-size: 0.875rem; color: #94a3b8; text-decoration: none;
  transition: color 0.15s; font-weight: 500;
}
.nav-link:hover, .router-link-active.nav-link { color: #fff; }

/* ── Right side cluster ──────────────────────────────── */
.nav-right { display: flex; align-items: center; gap: 0.625rem; }

/* ── Language pill button ────────────────────────────── */
.lang-wrap { position: relative; }
.lang-pill {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 11px 6px 9px;
  border-radius: 9999px;
  border: 1.5px solid rgba(178, 5, 55, 0.5);
  background: rgba(178, 5, 55, 0.12);
  color: #f9a8bd;
  font-size: 0.78rem; font-weight: 700; letter-spacing: 0.04em;
  cursor: pointer; outline: none;
  transition: background 0.2s, border-color 0.2s, color 0.2s;
  white-space: nowrap;
}
.lang-pill:hover {
  background: rgba(178, 5, 55, 0.22);
  border-color: rgba(178, 5, 55, 0.8);
  color: #fff;
}
.lang-pill:focus-visible { box-shadow: 0 0 0 3px rgba(178,5,55,0.4); }
.lang-flag { font-size: 1rem; line-height: 1; }
.lang-code { font-size: 0.72rem; font-weight: 700; }
.lang-arrow { width: 0.75rem; height: 0.75rem; flex-shrink: 0; transition: transform 0.2s; }
.lang-arrow.rotated { transform: rotate(180deg); }

/* ── Dropdown menu ───────────────────────────────────── */
.lang-menu {
  position: absolute; top: calc(100% + 8px); right: 0;
  min-width: 10rem;
  background: rgba(15, 23, 42, 0.98);
  border: 1px solid rgba(178, 5, 55, 0.25);
  border-radius: 0.875rem;
  padding: 5px;
  backdrop-filter: blur(24px);
  box-shadow: 0 12px 40px rgba(0,0,0,0.45), 0 0 0 1px rgba(178,5,55,0.1);
  z-index: 200;
}
.lang-item {
  display: flex; align-items: center; gap: 9px;
  width: 100%; padding: 9px 12px;
  border: none; border-radius: 0.5rem;
  background: transparent; color: #94a3b8;
  cursor: pointer; font-size: 0.875rem;
  transition: background 0.15s, color 0.15s;
}
.lang-item:hover  { background: rgba(255,255,255,0.06); color: #fff; }
.lang-item.active { background: rgba(178,5,55,0.15); color: #f43f5e; font-weight: 600; }
.lang-item-flag   { font-size: 1.1rem; }
.lang-item-native { flex: 1; }
.lang-check { font-size: 0.75rem; color: #f43f5e; }

.lang-drop-enter-active { transition: opacity 0.15s ease, transform 0.15s ease; }
.lang-drop-leave-active { transition: opacity 0.1s ease, transform 0.1s ease; }
.lang-drop-enter-from, .lang-drop-leave-to { opacity: 0; transform: translateY(-6px) scale(0.97); }

/* ── Theme Toggle pill ───────────────────────────────── */
.theme-pill {
  position: relative; display: inline-flex; align-items: center;
  width: 3.5rem; height: 1.75rem;
  border-radius: 9999px; border: none;
  cursor: pointer; outline: none; flex-shrink: 0;
  transition: background 0.3s;
}
.theme-pill:focus-visible { box-shadow: 0 0 0 3px rgba(178,5,55,0.4); }
.pill-dark  { background: rgba(30,41,59,0.9); border: 1px solid rgba(255,255,255,0.12); }
.pill-light { background: #fef3c7; border: 1px solid #fde68a; }
.pill-icon {
  position: absolute; top: 50%; transform: translateY(-50%);
  font-size: 0.65rem; pointer-events: none; user-select: none;
}
.pill-left  { left: 5px; }
.pill-right { right: 5px; }
.pill-thumb {
  position: absolute; top: 3px;
  width: 1.375rem; height: 1.375rem;
  border-radius: 9999px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.35);
  transition: left 0.3s cubic-bezier(0.4,0,0.2,1), background 0.3s;
}
.thumb-l { left: 3px; background: #475569; }
.thumb-r { left: calc(100% - 1.375rem - 3px); background: #fff; }

/* ── Auth buttons ────────────────────────────────────── */
.avatar-bubble {
  width: 2rem; height: 2rem; border-radius: 9999px;
  background: linear-gradient(135deg,#B20537,#F43F5E);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 0.75rem; font-weight: 600; cursor: default;
}
.logout-btn { font-size: 0.875rem; color: #64748b; background: none; border: none; cursor: pointer; transition: color 0.15s; }
.logout-btn:hover { color: #ef4444; }
.register-btn {
  padding: 0.375rem 1rem; border-radius: 0.75rem;
  background: linear-gradient(135deg,#B20537,#D4064A,#F43F5E);
  color: #fff; font-size: 0.875rem; font-weight: 500; text-decoration: none;
  transition: box-shadow 0.2s;
}
.register-btn:hover { box-shadow: 0 0 18px rgba(178,5,55,0.4); }
.mobile-toggle { color: #94a3b8; background: none; border: none; font-size: 1.25rem; cursor: pointer; transition: color 0.15s; }
.mobile-toggle:hover { color: #fff; }

/* ── Mobile menu ─────────────────────────────────────── */
.mobile-menu {
  display: flex; flex-direction: column; gap: 2px;
  padding: 0.75rem 0 1rem;
  border-top: 1px solid rgba(255,255,255,0.06);
}
.mobile-link {
  display: block; padding: 0.5rem 0.75rem;
  font-size: 0.875rem; color: #94a3b8; text-decoration: none;
  border-radius: 0.5rem; transition: background 0.15s, color 0.15s;
}
.mobile-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
.mobile-lang-row { display: flex; gap: 6px; padding: 0.5rem 0.375rem; flex-wrap: wrap; }
.mobile-lang-btn {
  padding: 4px 10px; border-radius: 9999px;
  border: 1.5px solid rgba(255,255,255,0.1);
  background: rgba(255,255,255,0.04); color: #64748b;
  font-size: 0.78rem; font-weight: 600; cursor: pointer;
  transition: all 0.15s;
}
.mobile-lang-btn:hover { background: rgba(255,255,255,0.08); color: #fff; }
.mobile-lang-btn.mlb-active {
  background: rgba(178,5,55,0.15);
  border-color: rgba(178,5,55,0.5); color: #f43f5e;
}

/* ── Slide-down transition ───────────────────────────── */
.slide-down-enter-active, .slide-down-leave-active { transition: all 0.2s ease; overflow: hidden; }
.slide-down-enter-from, .slide-down-leave-to { opacity: 0; max-height: 0; }
.slide-down-enter-to, .slide-down-leave-from { max-height: 600px; }
</style>
