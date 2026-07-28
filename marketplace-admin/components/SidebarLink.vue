<template>
  <NuxtLink
    :to="to"
    class="relative flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-200 group"
    :class="isActive
      ? 'bg-[#EEF2FF] text-[#3858F9] dark:bg-[#1E293B] dark:text-[#818CF8]'
      : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-100 dark:hover:bg-slate-800/60'"
  >
    <!-- Left indicator accent line for active link -->
    <span
      v-if="isActive"
      class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-[#3858F9] dark:bg-[#818CF8] rounded-r"
    />

    <span class="text-sm flex-shrink-0 w-5 text-center">{{ icon }}</span>
    <transition name="fade">
      <span v-if="!uiStore.sidebarCollapsed" class="whitespace-nowrap">{{ label }}</span>
    </transition>
    <span
      v-if="badge && !uiStore.sidebarCollapsed"
      class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full"
      :class="isActive ? 'bg-[#3858F9] text-white' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300'"
    >
      {{ badge }}
    </span>
  </NuxtLink>
</template>

<script setup lang="ts">
const props = defineProps<{
  to: string;
  icon: string;
  label: string;
  badge?: string | number;
}>();

const route = useRoute();
const uiStore = useUiStore();

const isActive = computed(() => {
  if (props.to === "/") return route.path === "/";
  return route.path.startsWith(props.to);
});
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
