<template>
  <header class="public-header sticky top-0 z-50">


    <!-- ── Main Navbar ─────────────────────────────────── -->
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

          <!-- Location Selector Pill -->
          <button
            type="button"
            @click="openModal"
            class="nav-loc-pill"
            :title="`Location: ${currentLocation.formatted}. Click to change.`"
          >
            <svg class="nav-loc-icon text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <div class="nav-loc-text">
              <span class="nav-loc-label">Deliver to</span>
              <span class="nav-loc-value">{{ currentLocation.area }}, {{ currentLocation.pincode }}</span>
            </div>
            <svg class="nav-loc-arrow" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
          </button>

          <!-- Header Search Bar (Visible on Laptop & Desktop screens >= 1024px) -->
          <div class="header-search hidden lg:flex">
            <select v-model="headerCategory" class="hs-cat-select">
              <option value="">All Categories</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.slug">{{ cat.name }}</option>
            </select>
            <input
              v-model="headerQuery"
              type="text"
              :placeholder="`Search services in ${currentLocation.city}...`"
              class="hs-input"
              @keydown.enter="execHeaderSearch"
            />
            <button type="button" @click="execHeaderSearch" class="hs-btn" title="Search">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </button>
          </div>

          <!-- Right Side Actions -->
          <div class="nav-right">

            <!-- Language Switcher (Visible on desktop screens >= 1024px) -->
            <div class="lang-wrap hidden lg:block" ref="langDropdownRef">
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

            <!-- Authenticated User -->
            <template v-if="userStore.isAuthenticated">
              <NuxtLink to="/chats" class="nav-link hidden lg:inline-flex items-center gap-1">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>{{ t('chats') }}</span>
              </NuxtLink>

              <!-- Hover User Dropdown Menu -->
              <div class="user-menu-wrap hidden lg:block">
                <button type="button" class="user-menu-trigger">
                  <div class="avatar-bubble">{{ userStore.user?.name?.charAt(0)?.toUpperCase() }}</div>
                  <span class="user-menu-name hidden lg:inline">{{ userStore.user?.name }}</span>
                  <svg class="user-menu-arrow" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                  </svg>
                </button>
                <div class="user-dropdown-menu">
                  <div class="user-dropdown-header">
                    <div class="user-display-name">{{ userStore.user?.name }}</div>
                    <div class="user-display-email text-slate-400">{{ userStore.user?.email }}</div>
                  </div>
                  <div class="user-dropdown-divider"></div>
                  
                  <NuxtLink to="/dashboard" class="user-dropdown-item">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>{{ t('dashboard') }}</span>
                  </NuxtLink>
                  <NuxtLink to="/dashboard/subscription" class="user-dropdown-item">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Upgrade Plan & Subscription</span>
                  </NuxtLink>
                  <NuxtLink to="/chats" class="user-dropdown-item">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>{{ t('chats') }}</span>
                  </NuxtLink>
                  
                  <div class="user-dropdown-item" @click="toggleTheme">
                    <svg v-if="isDark" class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <svg v-else class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <span>{{ isDark ? 'Light Mode' : 'Dark Mode' }}</span>
                  </div>
                  
                  <div class="user-dropdown-divider"></div>
                  
                  <button @click="handleLogout" class="user-dropdown-item logout-item">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>{{ t('logout') }}</span>
                  </button>
                </div>
              </div>
            </template>

            <!-- Guest -->
            <template v-else>
              <NuxtLink to="/login" class="header-login-btn hidden sm:inline-flex">{{ t('login') }}</NuxtLink>
              <NuxtLink to="/register" class="register-btn" style="color: #ffffff !important;">{{ t('register') }}</NuxtLink>
            </template>

            <!-- Mobile Menu Toggle Button (Visible ONLY on responsive screens < 1024px) -->
            <button
              type="button"
              @click="mobileOpen = !mobileOpen"
              class="mobile-toggle lg:hidden p-2 rounded-lg text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
              :aria-label="mobileOpen ? 'Close Menu' : 'Open Menu'"
            >
              <svg v-if="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>

          </div>

        </div>
      </div>

      <!-- ── Secondary Category / Sub-Nav Bar ── -->
      <div class="sub-nav hidden md:block">
        <div class="sub-nav-inner">
          <NuxtLink to="/services" class="sub-nav-item item-hot flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-rose-500 fill-current" viewBox="0 0 24 24">
              <path d="M13.5 0.67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8c0-5.52-4.5-9.33-6.5-13.33z"/>
            </svg>
            <span>Trending Deals</span>
          </NuxtLink>
          <NuxtLink to="/services?category=ac-repair" class="sub-nav-item">AC Service</NuxtLink>
          <NuxtLink to="/services?category=electrician" class="sub-nav-item">Electrician</NuxtLink>
          <NuxtLink to="/services?category=plumbing" class="sub-nav-item">Plumbing</NuxtLink>
          <NuxtLink to="/services?category=cleaning" class="sub-nav-item">Deep Cleaning</NuxtLink>
          <NuxtLink to="/services?category=painting" class="sub-nav-item">Painting</NuxtLink>
          <NuxtLink to="/services?express=true" class="sub-nav-item item-express flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span>2-Hour Express</span>
          </NuxtLink>
          <NuxtLink to="/search" class="sub-nav-item item-find flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span>Search Near Me</span>
          </NuxtLink>
        </div>
      </div>

      <!-- Mobile Search & Menu -->
      <Transition name="slide-down">
        <div v-if="mobileOpen" class="mobile-menu">
          <!-- Mobile Search Bar -->
          <div class="mobile-search-row">
            <input
              v-model="headerQuery"
              type="text"
              placeholder="Search services..."
              class="mobile-search-input"
              @keydown.enter="execHeaderSearch; mobileOpen = false"
            />
            <button type="button" @click="execHeaderSearch(); mobileOpen = false" class="mobile-search-btn" style="color: #ffffff !important;">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </button>
          </div>

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
            
            <!-- Mobile Theme Toggle -->
            <button type="button" @click="toggleTheme" class="mobile-link w-full text-left flex items-center justify-between">
              <span>{{ isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode' }}</span>
              <span>{{ isDark ? '🌙' : '☀️' }}</span>
            </button>
            
            <!-- Mobile Logout -->
            <button type="button" @click="handleLogout(); mobileOpen = false" class="mobile-link w-full text-left text-red-500 font-bold flex items-center justify-between">
              <span>{{ t('logout') }}</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
            </button>
          </template>
          <template v-else>
            <div class="pt-2 flex flex-col gap-2">
              <NuxtLink to="/login" class="mobile-link text-center border border-slate-300 rounded-xl py-2.5" @click="mobileOpen = false">{{ t('login') }}</NuxtLink>
              <NuxtLink to="/register" class="mobile-link text-center bg-[#B20537] text-white font-bold rounded-xl py-2.5" style="color: #ffffff !important;" @click="mobileOpen = false">{{ t('register') }}</NuxtLink>
            </div>
          </template>
        </div>
      </Transition>
    </nav>

    <!-- Location Modal Teleport -->
    <LocationModal />
  </header>
</template>

<script setup lang="ts">
const userStore = useUserStore();
const api       = useApi();
const { isDark, toggleTheme, initTheme } = useTheme();
const { currentLang, currentLangOption, setLang, initLang, t, LANGUAGES } = useLanguage();
const { currentLocation, initLocation, openModal } = useUserLocation();
const { wishlistCount, initWishlist } = useWishlist();

const mobileOpen      = ref(false);
const langOpen        = ref(false);
const langDropdownRef = ref<HTMLElement | null>(null);
const headerQuery     = ref('');
const headerCategory  = ref('');
const categories      = ref<any[]>([]);

onMounted(async () => {
  userStore.loadFromStorage();
  initTheme();
  initLang();
  initLocation();
  initWishlist();
  document.addEventListener('click', onOutsideClick);

  try {
    const data = await api.get<any>('/categories');
    categories.value = data.categories || [];
  } catch { }
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

const execHeaderSearch = () => {
  const query: any = {};
  if (headerQuery.value.trim()) query.keyword = headerQuery.value.trim();
  if (headerCategory.value) query.category = headerCategory.value;
  navigateTo({ path: '/search', query });
};
</script>

<style scoped>
/* ── Top Bar ─────────────────────────────────────────── */
.top-bar {
  background: #f8fafc; color: #475569; font-size: 0.75rem;
  border-bottom: 1px solid #e2e8f0;
}
:global(html.dark) .top-bar { background: #090d16; color: #94a3b8; border-bottom-color: rgba(255,255,255,0.06); }

.top-bar-inner {
  max-width: 80rem; margin: 0 auto; padding: 0.4rem 1rem;
  display: flex; align-items: center; justify-content: space-between; gap: 1rem;
}
@media (min-width: 640px) {
  .top-bar-inner { padding-left: 1.5rem; padding-right: 1.5rem; }
}
.top-bar-left { display: flex; align-items: center; gap: 0.75rem; }
.tb-badge {
  background: #fef2f5; border: 1px solid #fbc0d0;
  color: #b20537; font-weight: 700; padding: 2px 9px; border-radius: 9999px; display: inline-flex; align-items: center;
}
:global(html.dark) .tb-badge { background: rgba(178,5,55,0.2); border-color: rgba(178,5,55,0.4); color: #f43f5e; }
.tb-msg strong { color: #0f172a; font-weight: 700; }
:global(html.dark) .tb-msg strong { color: #fff; }
.top-bar-right { display: flex; align-items: center; gap: 0.75rem; }
.tb-phone { font-weight: 600; color: #334155; display: inline-flex; align-items: center; }
:global(html.dark) .tb-phone { color: #e2e8f0; }

/* ── Main Nav Shell ──────────────────────────────────── */
.public-nav {
  background: #ffffff;
  border-bottom: 1px solid #e2e8f0;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
}
:global(html.dark) .public-nav {
  background: rgba(15, 23, 42, 0.92);
  backdrop-filter: blur(20px) saturate(180%);
  border-bottom-color: rgba(255,255,255,0.08);
  box-shadow: none;
}
.nav-inner {
  width: 90% !important;
  max-width: 90% !important;
  margin: 0 auto;
  padding: 0 1rem;
}
@media (min-width: 640px) {
  .nav-inner { padding-left: 1.5rem; padding-right: 1.5rem; }
}
.nav-row   { display: flex; align-items: center; justify-content: space-between; height: 4.25rem; gap: 1.25rem; }

/* ── Logo ────────────────────────────────────────────── */
.nav-logo  { display: flex; align-items: center; gap: 0.625rem; text-decoration: none; flex-shrink: 0; }
.logo-badge {
  width: 2.375rem; height: 2.375rem; border-radius: 0.625rem;
  background: linear-gradient(135deg, #B20537, #D4064A, #F43F5E);
  display: flex; align-items: center; justify-content: center;
  transition: transform 0.2s;
  box-shadow: 0 2px 10px rgba(178,5,55,0.3);
}
.logo-text { color: #fff; font-weight: 800; font-size: 0.875rem; }
.logo-name { font-family: 'Outfit', sans-serif; font-weight: 700; color: #0f172a; font-size: 1.125rem; }
:global(html.dark) .logo-name { color: #fff; }

/* ── Location Pill ───────────────────────────────────── */
.nav-loc-pill {
  display: flex; align-items: center; gap: 0.5rem;
  padding: 0.375rem 0.875rem; border-radius: 0.75rem;
  background: #f8fafc; border: 1px solid #e2e8f0;
  color: #0f172a; cursor: pointer; text-align: left; transition: all 0.2s;
  flex-shrink: 0;
}
@media (max-width: 480px) {
  .nav-loc-pill { padding: 0.35rem 0.5rem; }
  .nav-loc-label { display: none; }
}
:global(html.dark) .nav-loc-pill {
  background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.12); color: #f8fafc;
}
.nav-loc-pill:hover {
  background: #fef2f5; border-color: #fbc0d0;
}
:global(html.dark) .nav-loc-pill:hover { background: rgba(178,5,55,0.15); border-color: rgba(178,5,55,0.4); }

.nav-loc-icon { width: 1.125rem; height: 1.125rem; flex-shrink: 0; }
.nav-loc-text { display: flex; flex-direction: column; line-height: 1.15; }
.nav-loc-label { font-size: 0.6875rem; color: #64748b; font-weight: 500; }
.nav-loc-value { font-size: 0.8125rem; font-weight: 700; max-width: 9rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: #0f172a; }
:global(html.dark) .nav-loc-value { color: #f8fafc; }
.nav-loc-arrow { width: 0.875rem; height: 0.875rem; color: #64748b; flex-shrink: 0; }

/* ── Search Bar & Responsive Mobile Toggle Rules ── */
.header-search {
  display: none !important;
  flex: 1; max-width: 36rem; height: 2.625rem; border-radius: 0.75rem;
  background: #f8fafc; border: 1.5px solid #cbd5e1;
  align-items: center; overflow: hidden; transition: border-color 0.2s;
}
.mobile-toggle {
  display: flex !important;
}

@media (min-width: 1024px) {
  .header-search {
    display: flex !important;
  }
  .mobile-toggle {
    display: none !important;
  }
}
:global(html.dark) .header-search {
  background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.12);
}
.header-search:focus-within { border-color: #B20537; background: #ffffff; }

.hs-cat-select {
  height: 100%; padding: 0 0.75rem; background: transparent !important; border: none !important;
  border-right: 1px solid #cbd5e1 !important; color: #334155;
  font-size: 0.8125rem; font-weight: 500; outline: none; cursor: pointer; max-width: 8.5rem; flex-shrink: 0;
}
:global(html.dark) .hs-cat-select { border-right-color: rgba(255,255,255,0.1) !important; color: #cbd5e1 !important; }
.hs-cat-select option { background: #ffffff; color: #0f172a; }
:global(html.dark) .hs-cat-select option { background: #1e293b !important; color: #fff !important; }

.hs-input {
  flex: 1; min-width: 0; height: 100%; padding: 0 0.875rem; background: transparent !important; border: none !important;
  color: #0f172a; font-size: 0.875rem; outline: none; font-weight: 500;
}
:global(html.dark) .hs-input { color: #fff !important; }
.hs-input::placeholder { color: #94a3b8; }

.hs-btn {
  width: 2.625rem; height: 100%; background: #B20537; border: none;
  color: #fff; cursor: pointer; transition: background 0.15s;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.hs-btn:hover { background: #8A042B; }
.hs-btn svg,
.mobile-search-btn svg {
  color: #ffffff !important;
}

/* ── Right Cluster ───────────────────────────────────── */
.nav-right { display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0; }

.wish-pill {
  position: relative; display: flex; align-items: center; justify-content: center;
  width: 2.25rem; height: 2.25rem; border-radius: 50%;
  background: #f1f5f9; border: 1px solid #e2e8f0;
  text-decoration: none; cursor: pointer; transition: all 0.2s;
}
:global(html.dark) .wish-pill { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.1); }
.wish-pill:hover { background: #fef2f5; border-color: #fbc0d0; }
.wish-badge {
  position: absolute; top: -3px; right: -3px;
  background: #ef4444; color: #fff; font-size: 0.65rem; font-weight: 800;
  width: 1.125rem; height: 1.125rem; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
}

/* ── Sub Nav / Categories Bar ────────────────────────── */
.sub-nav {
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
}
:global(html.dark) .sub-nav {
  background: rgba(10, 16, 30, 0.95); border-top-color: rgba(255,255,255,0.05);
}
.sub-nav-inner {
  width: 90% !important;
  max-width: 90% !important;
  margin: 0 auto;
  padding: 0.375rem 1rem;
  display: flex; align-items: center; gap: 0.75rem; overflow-x: auto;
  scrollbar-width: none;
}
@media (min-width: 640px) {
  .sub-nav-inner { padding-left: 1.5rem; padding-right: 1.5rem; }
}
.sub-nav-inner::-webkit-scrollbar { display: none; }

.sub-nav-item {
  font-size: 0.8125rem; font-weight: 500; color: #475569; text-decoration: none;
  white-space: nowrap; padding: 4px 12px; border-radius: 9999px;
  transition: all 0.15s;
}
:global(html.dark) .sub-nav-item { color: #94a3b8; }
.sub-nav-item:hover { color: #0f172a; background: #e2e8f0; }
:global(html.dark) .sub-nav-item:hover { color: #fff; background: rgba(255,255,255,0.06); }
.item-hot { color: #ef4444; font-weight: 700; background: rgba(239,68,68,0.1); }
.item-express { color: #d97706; font-weight: 700; background: rgba(245,158,11,0.1); }
.item-find { color: #0284c7; font-weight: 700; background: rgba(56,189,248,0.1); }

/* ── Language pill button ────────────────────────────── */
.lang-wrap { position: relative; }
.lang-pill {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 11px 6px 9px; border-radius: 9999px;
  border: 1.5px solid rgba(178, 5, 55, 0.4); background: #fef2f5;
  color: #b20537; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.04em;
  cursor: pointer; outline: none; transition: all 0.2s; white-space: nowrap;
}
:global(html.dark) .lang-pill { background: rgba(178, 5, 55, 0.12); color: #f9a8bd; border-color: rgba(178, 5, 55, 0.5); }
.lang-pill:hover { background: #b20537; color: #fff; border-color: #b20537; }
.lang-flag { font-size: 1rem; line-height: 1; }
.lang-code { font-size: 0.72rem; font-weight: 700; }
.lang-arrow { width: 0.75rem; height: 0.75rem; flex-shrink: 0; transition: transform 0.2s; }
.lang-arrow.rotated { transform: rotate(180deg); }

.lang-menu {
  position: absolute; top: calc(100% + 8px); right: 0; min-width: 10rem;
  background: #ffffff; border: 1px solid #e2e8f0;
  border-radius: 0.875rem; padding: 5px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); z-index: 200;
}
:global(html.dark) .lang-menu { background: rgba(15, 23, 42, 0.98); border-color: rgba(178, 5, 55, 0.25); }
.lang-item {
  display: flex; align-items: center; gap: 9px; width: 100%; padding: 9px 12px;
  border: none; border-radius: 0.5rem; background: transparent; color: #475569;
  cursor: pointer; font-size: 0.875rem; transition: all 0.15s;
}
:global(html.dark) .lang-item { color: #94a3b8; }
.lang-item:hover { background: #f1f5f9; color: #0f172a; }
:global(html.dark) .lang-item:hover { background: rgba(255,255,255,0.06); color: #fff; }
.lang-item.active { background: #fef2f5; color: #b20537; font-weight: 600; }

.lang-drop-enter-active { transition: opacity 0.15s ease, transform 0.15s ease; }
.lang-drop-leave-active { transition: opacity 0.1s ease, transform 0.1s ease; }
.lang-drop-enter-from, .lang-drop-leave-to { opacity: 0; transform: translateY(-6px) scale(0.97); }

/* ── Theme Toggle pill ───────────────────────────────── */
.theme-pill {
  position: relative; display: inline-flex; align-items: center;
  width: 3.5rem; height: 1.75rem; border-radius: 9999px; border: none;
  cursor: pointer; outline: none; flex-shrink: 0; transition: background 0.3s;
}
.pill-dark  { background: rgba(30,41,59,0.9); border: 1px solid rgba(255,255,255,0.12); }
.pill-light { background: #fef3c7; border: 1px solid #fde68a; }
.pill-icon { position: absolute; top: 50%; transform: translateY(-50%); font-size: 0.65rem; pointer-events: none; }
.pill-left  { left: 5px; }
.pill-right { right: 5px; }
.pill-thumb {
  position: absolute; top: 3px; width: 1.375rem; height: 1.375rem;
  border-radius: 9999px; box-shadow: 0 1px 4px rgba(0,0,0,0.35);
  transition: left 0.3s cubic-bezier(0.4,0,0.2,1), background 0.3s;
}
.thumb-l { left: 3px; background: #475569; }
.thumb-r { left: calc(100% - 1.375rem - 3px); background: #fff; }

/* ── Auth buttons ────────────────────────────────────── */
.nav-link { font-size: 0.875rem; color: #475569; text-decoration: none; transition: color 0.15s; font-weight: 600; }
:global(html.dark) .nav-link { color: #94a3b8; }
.nav-link:hover { color: #b20537; }
.header-login-btn {
  font-size: 0.875rem;
  font-weight: 700;
  color: #334155;
  text-decoration: none;
  padding: 0.45rem 1.125rem;
  border-radius: 0.75rem;
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  transition: all 0.2s ease;
}
:global(html.dark) .header-login-btn {
  color: #e2e8f0;
  background: rgba(255, 255, 255, 0.06);
  border-color: rgba(255, 255, 255, 0.12);
}
.header-login-btn:hover {
  color: #b20537;
  background: #fef2f5;
  border-color: rgba(178, 5, 55, 0.3);
  box-shadow: 0 4px 12px rgba(178, 5, 55, 0.08);
}
:global(html.dark) .header-login-btn:hover {
  color: #f9a8bd;
  background: rgba(178, 5, 55, 0.18);
  border-color: rgba(178, 5, 55, 0.5);
}
.avatar-bubble {
  width: 2.125rem; height: 2.125rem; border-radius: 9999px;
  background: linear-gradient(135deg,#B20537,#F43F5E);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 0.8125rem; font-weight: 700;
}
.logout-btn { font-size: 0.875rem; color: #64748b; background: none; border: none; cursor: pointer; font-weight: 600; }
.logout-btn:hover { color: #ef4444; }
.register-btn {
  padding: 0.45rem 1.25rem; border-radius: 0.75rem;
  background: linear-gradient(135deg,#B20537,#D4064A,#F43F5E);
  color: #ffffff !important; font-size: 0.875rem; font-weight: 700; text-decoration: none;
  box-shadow: 0 4px 14px rgba(178,5,55,0.35);
  transition: all 0.2s ease;
}
.register-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 18px rgba(178,5,55,0.45);
}
.mobile-toggle { color: #475569; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; }

/* ── Mobile menu ─────────────────────────────────────── */
.mobile-menu {
  display: flex; flex-direction: column; gap: 0.5rem;
  padding: 1rem; border-top: 1px solid #e2e8f0; background: #ffffff;
}
:global(html.dark) .mobile-menu { background: #0f172a; border-top-color: rgba(255,255,255,0.06); }

.mobile-search-row { display: flex; gap: 0.5rem; margin-bottom: 0.5rem; }
.mobile-search-input {
  flex: 1; padding: 0.625rem 0.875rem; border-radius: 0.625rem;
  background: #f8fafc; border: 1px solid #cbd5e1;
  color: #0f172a; font-size: 0.875rem; outline: none;
}
.mobile-search-btn { padding: 0 1rem; border-radius: 0.625rem; border: none; background: #B20537; color: #fff; }
.mobile-link { display: block; padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #475569; text-decoration: none; border-radius: 0.5rem; font-weight: 500; }
.mobile-link:hover { background: #f1f5f9; color: #0f172a; }
.mobile-lang-row { display: flex; gap: 6px; padding: 0.5rem 0.375rem; flex-wrap: wrap; }
.mobile-lang-btn {
  padding: 4px 10px; border-radius: 9999px; border: 1.5px solid #cbd5e1;
  background: #f1f5f9; color: #475569; font-size: 0.78rem; font-weight: 600; cursor: pointer;
}
.mobile-lang-btn.mlb-active { background: #fef2f5; border-color: #fbc0d0; color: #b20537; }

.slide-down-enter-active, .slide-down-leave-active { transition: all 0.2s ease; overflow: hidden; }
.slide-down-enter-from, .slide-down-leave-to { opacity: 0; max-height: 0; }
.slide-down-enter-to, .slide-down-leave-from { max-height: 600px; }

/* ── User Dropdown Menu ──────────────────────────────── */
.user-menu-wrap {
  position: relative;
  display: inline-block;
}
.user-menu-trigger {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.25rem 0.75rem 0.25rem 0.25rem;
  border-radius: 9999px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  cursor: pointer;
  outline: none;
  transition: all 0.2s ease;
}
.dark .user-menu-trigger {
  background: rgba(255, 255, 255, 0.05) !important;
  border-color: rgba(255, 255, 255, 0.12) !important;
}
.user-menu-trigger:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
}
.dark .user-menu-trigger:hover {
  background: rgba(255, 255, 255, 0.08) !important;
  border-color: rgba(255, 255, 255, 0.2) !important;
}
.user-menu-name {
  font-size: 0.8125rem;
  font-weight: 600;
  color: #334155;
}
.dark .user-menu-name {
  color: #e2e8f0 !important;
}
.user-menu-arrow {
  width: 0.875rem;
  height: 0.875rem;
  color: #64748b;
  transition: transform 0.2s ease;
}
.user-menu-wrap:hover .user-menu-arrow {
  transform: rotate(180deg);
}

.user-dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 0.5rem;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 0.875rem;
  min-width: 14rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  opacity: 0;
  visibility: hidden;
  transform: translateY(-8px);
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  z-index: 100;
  overflow: hidden;
  padding: 0.5rem 0;
}
.dark .user-dropdown-menu {
  background: #1e293b !important;
  border-color: rgba(255, 255, 255, 0.08) !important;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3) !important;
}

/* Bridging gap for smooth hover transition */
.user-dropdown-menu::before {
  content: '';
  position: absolute;
  top: -12px;
  left: 0;
  right: 0;
  height: 12px;
  background: transparent;
}

.user-menu-wrap:hover .user-dropdown-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.user-dropdown-header {
  padding: 0.75rem 1rem;
}
.user-display-name {
  font-size: 0.875rem;
  font-weight: 700;
  color: #0f172a;
}
.dark .user-display-name {
  color: #f8fafc !important;
}
.user-display-email {
  font-size: 0.75rem;
  color: #64748b;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.dark .user-display-email {
  color: #94a3b8 !important;
}

.user-dropdown-divider {
  height: 1px;
  background-color: #f1f5f9;
  margin: 0.375rem 0;
}
.dark .user-dropdown-divider {
  background-color: rgba(255, 255, 255, 0.06) !important;
}

.user-dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.625rem 1rem;
  color: #475569;
  font-size: 0.875rem;
  font-weight: 500;
  text-decoration: none;
  background: none;
  border: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
  transition: all 0.15s ease;
}
.dark .user-dropdown-item {
  color: #cbd5e1 !important;
}
.user-dropdown-item:hover {
  background-color: #f1f5f9;
  color: #0f172a;
}
.dark .user-dropdown-item:hover {
  background-color: rgba(255, 255, 255, 0.05) !important;
  color: #ffffff !important;
}

.logout-item {
  color: #ef4444;
}
.dark .logout-item {
  color: #f87171 !important;
}
.logout-item:hover {
  background-color: #fef2f2;
  color: #dc2626;
}
.dark .logout-item:hover {
  background-color: rgba(239, 68, 68, 0.1) !important;
  color: #f87171 !important;
}
</style>
