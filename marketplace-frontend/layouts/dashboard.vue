<template>
  <div class="min-h-screen flex flex-col bg-slate-50">
    <!-- Navbar Header -->
    <PublicNavbar />

    <!-- Dashboard Sidebar + Slot Container -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 py-4 sm:py-8">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">

        <!-- ── Mobile Menu Toggle Bar (Mobile Only) ──────────── -->
        <div class="lg:hidden bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
          <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center text-white text-base font-extrabold shadow-sm flex-shrink-0">
              {{ userStore.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <div class="min-w-0">
              <h2 class="font-bold text-slate-900 text-sm leading-tight truncate">{{ userStore.user?.name || 'Dashboard' }}</h2>
              <p class="text-xs text-slate-500 font-medium truncate max-w-[180px]">{{ userStore.user?.email }}</p>
            </div>
          </div>
          <button
            @click="mobileNavOpen = !mobileNavOpen"
            class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold flex items-center gap-1.5 hover:bg-slate-200 transition-colors flex-shrink-0"
          >
            <span>Navigation</span>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': mobileNavOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
        </div>

        <!-- ── Persistent Left Sidebar Navigation ──────────── -->
        <aside
          class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm lg:sticky lg:top-24 transition-all"
          :class="[ mobileNavOpen ? 'block' : 'hidden lg:block' ]"
        >
          <!-- User Profile Brief -->
          <div class="text-center pb-6 border-b border-slate-100">
            <div class="w-16 h-16 rounded-2xl gradient-primary flex items-center justify-center text-white text-2xl font-extrabold mx-auto shadow-md mb-3">
              {{ userStore.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <h2 class="font-heading font-extrabold text-slate-900 text-lg leading-tight truncate">{{ userStore.user?.name || 'Dashboard' }}</h2>
            <p class="text-xs text-slate-500 font-medium truncate mt-0.5 max-w-[220px] mx-auto">{{ userStore.user?.email }}</p>
            <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200">
              {{ userStore.isWorker ? t('roleWorker') : t('roleCustomer') }}
            </span>
          </div>

          <!-- Navigation Links -->
          <nav class="mt-6 space-y-1.5">
            <!-- 1. Overview -->
            <NuxtLink
              to="/dashboard"
              @click="mobileNavOpen = false"
              class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs transition-all"
              :class="route.path === '/dashboard' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100'"
              :style="route.path === '/dashboard' ? 'color: #ffffff !important;' : ''"
            >
              <svg class="w-4 h-4" :class="route.path === '/dashboard' ? 'text-white' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
              </svg>
              <span>{{ t('sidebarOverview') }}</span>
            </NuxtLink>

            <!-- 2. Messages (Chats) -->
            <NuxtLink
              to="/chats"
              @click="mobileNavOpen = false"
              class="w-full flex items-center justify-between px-4 py-3 rounded-xl font-bold text-xs transition-all"
              :class="route.path.startsWith('/chats') ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100'"
              :style="route.path.startsWith('/chats') ? 'color: #ffffff !important;' : ''"
            >
              <div class="flex items-center gap-3">
                <svg class="w-4 h-4" :class="route.path.startsWith('/chats') ? 'text-white' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>{{ t('sidebarMessages') }}</span>
              </div>
              <span class="w-2 h-2 rounded-full" :class="route.path.startsWith('/chats') ? 'bg-white' : 'bg-rose-500 animate-pulse'" />
            </NuxtLink>

            <!-- 3. My Offered Services (Worker Only) -->
            <NuxtLink
              v-if="userStore.isWorker"
              to="/dashboard/services"
              @click="mobileNavOpen = false"
              class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs transition-all"
              :class="route.path.startsWith('/dashboard/services') ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100'"
              :style="route.path.startsWith('/dashboard/services') ? 'color: #ffffff !important;' : ''"
            >
              <svg class="w-4 h-4" :class="route.path.startsWith('/dashboard/services') ? 'text-white' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <span>{{ t('sidebarServices') }}</span>
            </NuxtLink>

            <!-- 4. Saved Services (Wishlist) -->
            <NuxtLink
              to="/dashboard/wishlist"
              @click="mobileNavOpen = false"
              class="w-full flex items-center justify-between px-4 py-3 rounded-xl font-bold text-xs transition-all"
              :class="route.path === '/dashboard/wishlist' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100'"
              :style="route.path === '/dashboard/wishlist' ? 'color: #ffffff !important;' : ''"
            >
              <div class="flex items-center gap-3">
                <svg class="w-4 h-4" :class="route.path === '/dashboard/wishlist' ? 'text-white fill-white' : 'text-rose-500 fill-rose-500'" viewBox="0 0 24 24">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span>{{ t('sidebarSaved') }}</span>
              </div>
              <span v-if="wishlistCount > 0" class="px-2 py-0.5 rounded-full text-[10px] font-extrabold" :class="route.path === '/dashboard/wishlist' ? 'bg-white text-rose-700' : 'bg-rose-100 text-rose-700'">
                {{ wishlistCount }}
              </span>
            </NuxtLink>

            <!-- 5. Profile -->
            <NuxtLink
              to="/dashboard/profile"
              @click="mobileNavOpen = false"
              class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs transition-all"
              :class="route.path === '/dashboard/profile' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100'"
              :style="route.path === '/dashboard/profile' ? 'color: #ffffff !important;' : ''"
            >
              <svg class="w-4 h-4" :class="route.path === '/dashboard/profile' ? 'text-white' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <span>{{ t('sidebarProfile') }}</span>
            </NuxtLink>

            <!-- 6. Notifications -->
            <NuxtLink
              to="/dashboard/notifications"
              @click="mobileNavOpen = false"
              class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-xs transition-all"
              :class="route.path === '/dashboard/notifications' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100'"
              :style="route.path === '/dashboard/notifications' ? 'color: #ffffff !important;' : ''"
            >
              <svg class="w-4 h-4" :class="route.path === '/dashboard/notifications' ? 'text-white' : 'text-slate-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
              </svg>
              <span>{{ t('sidebarNotifications') }}</span>
            </NuxtLink>

            <!-- 7. Logout -->
            <button
              type="button"
              @click="handleLogout"
              class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-xs text-rose-600 hover:bg-rose-50 transition-all cursor-pointer text-left"
            >
              <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
              <span>{{ t('sidebarSignOut') }}</span>
            </button>
          </nav>
        </aside>

        <!-- ── Main Dashboard Content Slot ────────────────── -->
        <main class="lg:col-span-3 min-w-0">
          <slot />
        </main>

      </div>
    </main>

    <!-- Footer -->
    <PublicFooter />
  </div>
</template>

<script setup lang="ts">
const userStore = useUserStore();
const route = useRoute();
const api = useApi();
const { wishlistCount, initWishlist } = useWishlist();
const { t, initLang } = useLanguage();

const mobileNavOpen = ref(false);

onMounted(() => {
  initLang();
  userStore.loadFromStorage();
  initWishlist();
});

const handleLogout = async () => {
  try { await api.post('/auth/logout'); } catch {}
  userStore.logout();
  navigateTo('/');
};
</script>
