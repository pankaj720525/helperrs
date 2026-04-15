<template>
  <aside
    class="fixed top-0 left-0 h-screen z-40 gradient-sidebar border-r border-white/5 flex flex-col transition-all duration-300 scrollbar-thin"
    :class="[
      uiStore.sidebarCollapsed ? 'w-[72px]' : 'w-[260px]',
      uiStore.sidebarMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
  >
    <!-- Logo -->
    <div class="h-16 flex items-center px-4 border-b border-white/5">
      <div class="flex items-center gap-3 overflow-hidden">
        <div class="w-9 h-9 rounded-xl gradient-primary flex items-center justify-center flex-shrink-0">
          <span class="text-white font-bold text-sm">SM</span>
        </div>
        <transition name="fade">
          <span v-if="!uiStore.sidebarCollapsed" class="font-heading font-semibold text-white text-sm whitespace-nowrap">
            Service Marketplace
          </span>
        </transition>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
      <SidebarLink to="/" icon="📊" label="Dashboard" />
      <SidebarLink to="/services" icon="🔧" label="Services" />
      <SidebarLink to="/users" icon="👥" label="Users" />
      <SidebarLink to="/categories" icon="📁" label="Categories" />
      <SidebarLink to="/reviews" icon="⭐" label="Reviews" />
      <SidebarLink to="/subscriptions" icon="💳" label="Subscriptions" />

      <div class="pt-4 pb-2 px-3">
        <div v-if="!uiStore.sidebarCollapsed" class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold">
          System
        </div>
        <div v-else class="border-t border-white/5" />
      </div>

      <SidebarLink to="/settings" icon="⚙️" label="Settings" />
      <SidebarLink to="/audit-logs" icon="📋" label="Audit Logs" />
    </nav>

    <!-- Collapse button -->
    <div class="p-3 border-t border-white/5 hidden lg:block">
      <button
        @click="uiStore.toggleSidebar()"
        class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-200 text-sm"
      >
        <span class="transition-transform duration-300" :class="uiStore.sidebarCollapsed ? 'rotate-180' : ''">
          ◀
        </span>
        <span v-if="!uiStore.sidebarCollapsed">Collapse</span>
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
const uiStore = useUiStore();
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
