<template>
  <header class="h-16 glass border-b border-white/5 flex items-center justify-between px-6 sticky top-0 z-20">
    <!-- Left: breadcrumb -->
    <div class="flex items-center gap-4">
      <button @click="uiStore.toggleMobileSidebar()" class="lg:hidden text-slate-400 hover:text-white transition-colors">☰</button>
      <h1 class="text-lg font-heading font-semibold text-white">{{ pageTitle }}</h1>
    </div>

    <!-- Right: Theme toggle + Admin info + Logout -->
    <div class="flex items-center gap-4">

      <!-- ─── Dark / Light Toggle ──────────────────────── -->
      <button
        type="button"
        @click="toggleTheme"
        :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
        class="theme-track"
        :class="isDark ? 'track-dark' : 'track-light'"
      >
        <span class="track-icon track-icon-left">🌙</span>
        <span class="track-icon track-icon-right">☀️</span>
        <span class="track-thumb" :class="isDark ? 'thumb-dark' : 'thumb-light'" />
      </button>
      <!-- ────────────────────────────────────────────────── -->

      <!-- Admin info -->
      <div class="flex items-center gap-3">
        <div class="text-right hidden sm:block">
          <p class="text-sm font-medium text-slate-200">{{ authStore.admin?.name }}</p>
          <p class="text-xs text-slate-400">Administrator</p>
        </div>
        <div class="w-9 h-9 rounded-full gradient-primary flex items-center justify-center text-white text-sm font-semibold">
          {{ initials }}
        </div>
      </div>

      <!-- Logout -->
      <button
        @click="handleLogout"
        class="text-slate-400 hover:text-danger transition-colors text-sm px-3 py-1.5 rounded-lg hover:bg-danger/10"
      >
        Logout
      </button>
    </div>
  </header>
</template>

<script setup lang="ts">
const authStore = useAuthStore();
const uiStore = useUiStore();
const route = useRoute();
const api = useApi();
const toast = useToast();
const { isDark, toggleTheme, initTheme } = useAdminTheme();

onMounted(() => initTheme());

const pageTitle = computed(() => {
  const path = route.path;
  if (path === "/") return "Dashboard";
  const segment = path.split("/")[1];
  return segment ? segment.charAt(0).toUpperCase() + segment.slice(1).replace(/-/g, " ") : "Dashboard";
});

const initials = computed(() => {
  const name = authStore.admin?.name || "A";
  return name.split(" ").map((n: string) => n[0]).join("").toUpperCase().slice(0, 2);
});

const handleLogout = async () => {
  try { await api.post("/admin/logout"); } catch {}
  authStore.logout();
  navigateTo("/login");
  toast.success("Logged out successfully.");
};
</script>

<style scoped>
/* ── Theme Toggle Track ───────────────────────────── */
.theme-track {
  position: relative;
  display: inline-flex;
  align-items: center;
  width: 3.5rem;     /* 56px */
  height: 1.75rem;   /* 28px */
  border-radius: 9999px;
  border: none;
  cursor: pointer;
  outline: none;
  flex-shrink: 0;
  transition: background-color 0.3s ease, box-shadow 0.25s ease;
}
.theme-track:focus-visible {
  box-shadow: 0 0 0 3px rgba(178, 5, 55, 0.45);
}
.track-dark  { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1); }
.track-light { background: #fef3c7; border: 1px solid #fde68a; }

/* ── Icons inside track ──────────────────────────── */
.track-icon {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.7rem;
  pointer-events: none;
  user-select: none;
  line-height: 1;
}
.track-icon-left  { left: 5px; }
.track-icon-right { right: 5px; }

/* ── Thumb ───────────────────────────────────────── */
.track-thumb {
  position: absolute;
  top: 3px;
  width: 1.375rem;   /* 22px */
  height: 1.375rem;
  border-radius: 9999px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.35);
  transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.3s ease;
}
.thumb-dark  { left: 3px; background: #475569; }
.thumb-light { left: calc(100% - 1.375rem - 3px); background: #ffffff; }
</style>
