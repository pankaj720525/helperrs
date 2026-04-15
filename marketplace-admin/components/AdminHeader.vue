<template>
  <header class="h-16 glass border-b border-white/5 flex items-center justify-between px-6 sticky top-0 z-20">
    <!-- Left: Mobile menu + breadcrumb -->
    <div class="flex items-center gap-4">
      <button
        @click="uiStore.toggleMobileSidebar()"
        class="lg:hidden text-slate-400 hover:text-white transition-colors"
      >
        ☰
      </button>
      <div>
        <h1 class="text-lg font-heading font-semibold text-white">{{ pageTitle }}</h1>
      </div>
    </div>

    <!-- Right: Admin info + actions -->
    <div class="flex items-center gap-4">
      <!-- Admin avatar and name -->
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
  try {
    await api.post("/admin/logout");
  } catch {}
  authStore.logout();
  navigateTo("/login");
  toast.success("Logged out successfully.");
};
</script>
