<template>
  <aside
    class="fixed top-0 left-0 h-screen z-40 bg-white dark:bg-[#121e2d] border-r border-[#edf2f9] dark:border-[#1e2c40] flex flex-col transition-all duration-300 scrollbar-thin shadow-xs"
    :class="[
      uiStore.sidebarCollapsed ? 'w-[72px]' : 'w-[250px]',
      uiStore.sidebarMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
  >
    <!-- Service Market Logo Header -->
    <div class="h-16 flex items-center px-4 border-b border-[#edf2f9] dark:border-[#1e2c40]">
      <div class="flex items-center gap-2.5 overflow-hidden">
        <div class="w-8 h-8 rounded-lg bg-[#2c7be5] flex items-center justify-center flex-shrink-0 text-white font-extrabold text-xs shadow-xs">
          SM
        </div>
        <transition name="fade">
          <div v-if="!uiStore.sidebarCollapsed" class="whitespace-nowrap">
            <span class="font-heading font-extrabold text-[#12263f] dark:text-white text-base tracking-tight">
              Service Market
            </span>
          </div>
        </transition>
      </div>
    </div>

    <!-- Navigation List (Real Active Pages Only) -->
    <nav class="flex-1 py-3 px-3 space-y-1 overflow-y-auto">
      <!-- Section: MAIN -->
      <div class="px-2 pt-2 pb-1">
        <span v-if="!uiStore.sidebarCollapsed" class="text-[10px] uppercase tracking-wider font-extrabold text-[#5e6e82] dark:text-slate-400">
          Main
        </span>
        <div v-else class="w-full border-t border-[#edf2f9] dark:border-slate-800" />
      </div>

      <SidebarLink to="/" icon="dashboard" label="Dashboard" />

      <!-- Section: CATALOG & USERS -->
      <div class="pt-3 pb-1 px-2">
        <span v-if="!uiStore.sidebarCollapsed" class="text-[10px] uppercase tracking-wider font-extrabold text-[#5e6e82] dark:text-slate-400">
          Management
        </span>
        <div v-else class="w-full border-t border-[#edf2f9] dark:border-slate-800" />
      </div>

      <SidebarLink to="/services" icon="services" label="Services" />
      <SidebarLink to="/users" icon="users" label="Users" />
      <SidebarLink to="/chats" icon="chats" label="Support Chats" />
      <SidebarLink to="/categories" icon="categories" label="Categories" />
      <SidebarLink to="/reviews" icon="reviews" label="Reviews" />
      <SidebarLink to="/subscriptions" icon="subscriptions" label="Subscriptions" />

      <!-- Section: SYSTEM -->
      <div class="pt-3 pb-1 px-2">
        <span v-if="!uiStore.sidebarCollapsed" class="text-[10px] uppercase tracking-wider font-extrabold text-[#5e6e82] dark:text-slate-400">
          System
        </span>
        <div v-else class="w-full border-t border-[#edf2f9] dark:border-slate-800" />
      </div>

      <SidebarLink to="/profile" icon="profile" label="My Profile" />
      <SidebarLink to="/settings" icon="settings" label="Settings" />
      <SidebarLink to="/audit-logs" icon="audit-logs" label="Audit Logs" />
    </nav>
  </aside>
</template>

<script setup lang="ts">
const uiStore = useUiStore();
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
