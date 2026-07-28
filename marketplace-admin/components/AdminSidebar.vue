<template>
  <aside
    class="fixed top-0 left-0 h-screen z-40 bg-white dark:bg-[#111827] border-r border-[#EAEDF1] dark:border-[#1F2937] flex flex-col transition-all duration-300 scrollbar-thin shadow-sm"
    :class="[
      uiStore.sidebarCollapsed ? 'w-[72px]' : 'w-[260px]',
      uiStore.sidebarMobileOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
  >
    <!-- Logo Header -->
    <div class="h-16 flex items-center px-4 border-b border-[#EAEDF1] dark:border-[#1F2937]">
      <div class="flex items-center gap-3 overflow-hidden">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#3858F9] to-[#4F46E5] flex items-center justify-center flex-shrink-0 shadow-sm shadow-[#3858F9]/30">
          <span class="text-white font-bold text-sm">SM</span>
        </div>
        <transition name="fade">
          <div v-if="!uiStore.sidebarCollapsed" class="whitespace-nowrap">
            <span class="font-heading font-bold text-[#1E293B] dark:text-white text-sm tracking-tight">
              Service Market
            </span>
          </div>
        </transition>
      </div>
    </div>

    <!-- Navigation List -->
    <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
      <!-- Section: MAIN -->
      <div class="px-3 pt-2 pb-1.5">
        <span v-if="!uiStore.sidebarCollapsed" class="text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
          Main
        </span>
        <div v-else class="border-t border-slate-200 dark:border-slate-800" />
      </div>

      <SidebarLink to="/" icon="📊" label="Dashboard" />
      <SidebarLink to="/services" icon="🔧" label="Services" />
      <SidebarLink to="/users" icon="👥" label="Users" />
      <SidebarLink to="/categories" icon="📁" label="Categories" />

      <!-- Section: MANAGEMENT -->
      <div class="pt-4 pb-1.5 px-3">
        <span v-if="!uiStore.sidebarCollapsed" class="text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
          Management
        </span>
        <div v-else class="border-t border-slate-200 dark:border-slate-800" />
      </div>

      <SidebarLink to="/reviews" icon="⭐" label="Reviews" />
      <SidebarLink to="/subscriptions" icon="💳" label="Subscriptions" />

      <!-- Section: SYSTEM -->
      <div class="pt-4 pb-1.5 px-3">
        <span v-if="!uiStore.sidebarCollapsed" class="text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
          System
        </span>
        <div v-else class="border-t border-slate-200 dark:border-slate-800" />
      </div>

      <SidebarLink to="/profile" icon="👤" label="My Profile" />
      <SidebarLink to="/settings" icon="⚙️" label="Settings" />
      <SidebarLink to="/audit-logs" icon="📋" label="Audit Logs" />
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
