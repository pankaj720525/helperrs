<template>
  <header class="h-16 bg-white dark:bg-[#111827] border-b border-[#EAEDF1] dark:border-[#1F2937] flex items-center justify-between px-6 sticky top-0 z-20 transition-colors">
    <!-- Left: Valex Sidebar Hamburger Toggle + Search Bar -->
    <div class="flex items-center gap-4">
      <!-- Sidebar Toggle Button (Valex Header Style) -->
      <button
        @click="handleToggleSidebar"
        title="Toggle Sidebar"
        class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"
      >
        <span class="text-base font-bold">☰</span>
      </button>

      <!-- Global Search Bar -->
      <div class="hidden md:flex items-center relative">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search catalog, users, services..."
          @keyup.enter="handleGlobalSearch"
          class="w-48 lg:w-72 pl-9 pr-3 py-1.5 rounded-full text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-primary focus:bg-white dark:focus:bg-slate-900 transition-all"
        />
        <span class="absolute left-3 text-slate-400 text-xs">🔍</span>
      </div>
    </div>

    <!-- Right: Theme toggle, Notifications, Admin User Profile Pill & Logout -->
    <div class="flex items-center gap-3">
      <!-- Theme Toggle Switch -->
      <button
        type="button"
        @click="toggleTheme"
        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
        class="w-9 h-9 rounded-lg flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"
      >
        <span v-if="isDark" class="text-sm">☀️</span>
        <span v-else class="text-sm">🌙</span>
      </button>

      <!-- Notification Bell -->
      <button
        class="w-9 h-9 rounded-lg flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors relative"
        title="Notifications"
      >
        <span class="text-sm">🔔</span>
        <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-danger ring-2 ring-white dark:ring-slate-900"></span>
      </button>

      <!-- Admin Profile Pill (Clickable -> /profile) -->
      <NuxtLink
        to="/profile"
        class="flex items-center gap-2.5 pl-2.5 pr-3 py-1 rounded-full bg-slate-50 dark:bg-slate-800/80 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 transition-all group"
      >
        <div class="w-7 h-7 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-bold shadow-xs">
          {{ initials }}
        </div>
        <div class="text-left hidden sm:block">
          <p class="text-xs font-semibold text-slate-900 dark:text-slate-100 leading-none group-hover:text-primary transition-colors">
            {{ authStore.admin?.name || 'Super Admin' }}
          </p>
          <p class="text-[10px] text-slate-400 mt-0.5 leading-none">Profile & Settings</p>
        </div>
      </NuxtLink>

      <!-- Logout Button -->
      <button
        @click="handleLogout"
        title="Sign Out"
        class="text-xs font-semibold text-slate-500 hover:text-danger dark:text-slate-400 dark:hover:text-danger px-3 py-1.5 rounded-lg hover:bg-danger/10 transition-colors"
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
