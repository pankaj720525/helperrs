<template>
  <div class="flex items-center justify-between py-3 px-1 border-b border-white/5 last:border-0">
    <div>
      <p class="text-sm font-medium text-slate-200">{{ label }}</p>
      <p v-if="description" class="text-xs text-slate-400 mt-0.5">{{ description }}</p>
    </div>
    <button
      @click="$emit('toggle')"
      class="relative w-12 h-6 rounded-full transition-all duration-300 focus:outline-none flex-shrink-0 ml-4"
      :class="value ? 'bg-success' : 'bg-surface-lighter'"
    >
      <span
        class="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow-sm transition-transform duration-300"
        :class="value ? 'translate-x-6' : 'translate-x-0.5'"
      />
    </button>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  label: string;
  description?: string;
  value: boolean;
}>();
defineEmits(['toggle']);
</script>
