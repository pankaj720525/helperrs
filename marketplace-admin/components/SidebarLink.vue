<template>
  <NuxtLink
    :to="to"
    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group"
    :class="isActive
      ? 'bg-primary/15 text-primary-light border border-primary/20'
      : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent'"
  >
    <span class="text-base flex-shrink-0 w-5 text-center">{{ icon }}</span>
    <transition name="fade">
      <span v-if="!uiStore.sidebarCollapsed" class="whitespace-nowrap">{{ label }}</span>
    </transition>
    <span
      v-if="badge && !uiStore.sidebarCollapsed"
      class="ml-auto text-xs font-semibold px-2 py-0.5 rounded-full"
      :class="isActive ? 'bg-primary/20 text-primary-light' : 'bg-white/10 text-slate-300'"
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
