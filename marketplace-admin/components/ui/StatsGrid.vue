<template>
  <div class="grid gap-4" :class="gridClass">
    <slot />
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(
  defineProps<{ cols?: number }>(),
  { cols: 4 }
);

const gridClass = computed(() => {
  switch (props.cols) {
    case 2: return "grid-cols-1 sm:grid-cols-2";
    case 3: return "grid-cols-1 sm:grid-cols-2 lg:grid-cols-3";
    case 4: return "grid-cols-1 sm:grid-cols-2 lg:grid-cols-4";
    default: return "grid-cols-1 sm:grid-cols-2 lg:grid-cols-4";
  }
});
</script>
