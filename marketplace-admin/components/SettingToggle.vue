<template>
  <div class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-800 last:border-b-0">
    <div class="flex-1 min-w-0 pr-4">
      <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ label }}</p>
      <p v-if="description" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ description }}</p>
    </div>
    <button
      type="button"
      role="switch"
      :aria-checked="value"
      @click="$emit('toggle')"
      class="relative flex-shrink-0 w-12 h-6 rounded-full border-none cursor-pointer transition-colors duration-200 focus:outline-none"
      :class="value ? 'bg-[#10B981]' : 'bg-slate-300 dark:bg-slate-700'"
    >
      <span
        class="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow-md transition-all duration-200"
        :class="value ? 'left-6' : 'left-0.5'"
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
