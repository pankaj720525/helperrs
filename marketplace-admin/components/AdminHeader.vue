<template>
  <header class="h-16 bg-white dark:bg-[#121e2d] border-b border-[#edf2f9] dark:border-[#1e2c40] flex items-center justify-between px-5 sticky top-0 z-20 transition-colors shadow-xs">
    <!-- Left: Hamburger Toggle + Service Market Logo + Search Input -->
    <div class="flex items-center gap-4">
      <!-- Sidebar Hamburger Toggle -->
      <button
        @click="handleToggleSidebar"
        title="Toggle Sidebar"
        class="text-[#5e6e82] hover:text-[#12263f] dark:text-slate-400 dark:hover:text-white p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- Brand Logo Icon & Site Name -->
      <NuxtLink to="/" class="flex items-center gap-2.5 mr-2 group">
        <div class="w-8 h-8 rounded-lg bg-[#2c7be5] flex items-center justify-center text-white font-extrabold text-xs shadow-xs group-hover:bg-[#1665d8] transition-colors">
          SM
        </div>
        <span class="font-heading font-extrabold text-[#12263f] dark:text-white text-base tracking-tight hidden sm:inline-block">
          Service Market
        </span>
      </NuxtLink>

      <!-- Search Input -->
      <div class="hidden md:flex items-center relative">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search catalog, users, services..."
          @keyup.enter="handleGlobalSearch"
          class="w-56 lg:w-72 pl-9 pr-4 py-1.5 rounded-full text-xs bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-100 placeholder:text-[#9da9bb] focus:outline-none focus:border-[#2c7be5] focus:bg-white dark:focus:bg-[#121e2d] transition-all shadow-2xs"
        />
        <svg class="w-4 h-4 absolute left-3 text-[#9da9bb]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
    </div>

    <!-- Right: Theme Toggle, Notifications, Admin Profile Pill & Logout -->
    <div class="flex items-center gap-2 sm:gap-3">
      <!-- Dark/Light Theme Toggle -->
      <button
        type="button"
        @click="toggleTheme"
        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
        class="w-8 h-8 rounded-full flex items-center justify-center text-[#5e6e82] dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
      >
        <span v-if="isDark" class="text-sm">☀️</span>
        <span v-else class="text-sm">🌙</span>
      </button>

      <!-- Notification Bell -->
      <button
        class="w-8 h-8 rounded-full flex items-center justify-center text-[#5e6e82] dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors relative"
        title="Notifications"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-[#2c7be5] ring-2 ring-white dark:ring-[#121e2d]"></span>
      </button>

      <!-- Admin User Avatar Profile Pill -->
      <NuxtLink
        to="/profile"
        class="flex items-center gap-2.5 pl-1.5 pr-3 py-1 rounded-full bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 border border-[#edf2f9] dark:border-slate-700/80 transition-all group"
      >
        <div class="w-7 h-7 rounded-full bg-[#2c7be5] flex items-center justify-center text-white text-xs font-bold ring-2 ring-white dark:ring-[#121e2d] shadow-xs">
          {{ initials }}
        </div>
        <span class="text-xs font-bold text-[#12263f] dark:text-slate-200 hidden sm:inline-block group-hover:text-[#2c7be5] transition-colors">
          {{ authStore.admin?.name || 'Super Admin' }}
        </span>
      </NuxtLink>

      <!-- Sign Out Button -->
      <button
        @click="handleLogout"
        title="Sign Out"
        class="text-xs font-semibold text-[#5e6e82] hover:text-[#e63757] dark:text-slate-400 dark:hover:text-[#e63757] px-2.5 py-1.5 rounded-md hover:bg-[#ffebe6] dark:hover:bg-[#e63757]/20 transition-colors"
      >
        Logout
      </button>
    </div>
  </header>
</template>

<script setup lang="ts">
const authStore = useAuthStore();
const uiStore = useUiStore();
const api = useApi();
const toast = useToast();
const { isDark, toggleTheme, initTheme } = useAdminTheme();

const searchQuery = ref("");

onMounted(() => initTheme());

const handleToggleSidebar = () => {
  if (window.innerWidth < 1024) {
    uiStore.toggleMobileSidebar();
  } else {
    uiStore.toggleSidebar();
  }
};

const handleGlobalSearch = () => {
  if (searchQuery.value.trim()) {
    navigateTo(`/services?search=${encodeURIComponent(searchQuery.value.trim())}`);
  }
};

const initials = computed(() => {
  const name = authStore.admin?.name || "Super Admin";
  return name.split(" ").map((n: string) => n[0]).join("").toUpperCase().slice(0, 2);
});

const handleLogout = async () => {
  try { await api.post("/admin/logout"); } catch {}
  authStore.logout();
  navigateTo("/login");
  toast.success("Logged out successfully.");
};
</script>
