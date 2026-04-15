<template>
  <div class="min-h-screen gradient-dark flex">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main content -->
    <div
      class="flex-1 flex flex-col min-h-screen transition-all duration-300 ml-0"
      :class="uiStore.sidebarCollapsed ? 'lg:ml-[72px]' : 'lg:ml-[260px]'"
    >
      <!-- Header -->
      <AdminHeader />

      <!-- Page content -->
      <main class="flex-1 p-6 overflow-auto">
        <slot />
      </main>
    </div>

    <!-- Mobile sidebar overlay -->
    <div
      v-if="uiStore.sidebarMobileOpen"
      class="fixed inset-0 bg-black/50 z-30 lg:hidden"
      @click="uiStore.closeMobileSidebar()"
    />

    <!-- Toast container -->
    <ToastContainer />
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });
const uiStore = useUiStore();
</script>
