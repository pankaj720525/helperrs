<template>
  <span
    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
    :class="badgeClass"
  >
    <span v-if="dot" class="w-1.5 h-1.5 rounded-full mr-1.5" :class="dotClass" />
    {{ label }}
  </span>
</template>

<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    status: string;
    label?: string;
    dot?: boolean;
  }>(),
  { dot: true }
);

const label = computed(() => props.label || props.status.charAt(0).toUpperCase() + props.status.slice(1));

const badgeClass = computed(() => {
  switch (props.status) {
    case "approved":
    case "active":
    case "open":
      return "bg-success/15 text-success border border-success/20";
    case "pending":
    case "trial":
      return "bg-warning/15 text-warning border border-warning/20";
    case "rejected":
    case "inactive":
    case "expired":
    case "closed":
      return "bg-danger/15 text-danger border border-danger/20";
    default:
      return "bg-slate-500/15 text-slate-400 border border-slate-500/20";
  }
});

const dotClass = computed(() => {
  switch (props.status) {
    case "approved":
    case "active":
    case "open":
      return "bg-success";
    case "pending":
    case "trial":
      return "bg-warning";
    case "rejected":
    case "inactive":
    case "expired":
    case "closed":
      return "bg-danger";
    default:
      return "bg-slate-400";
  }
});
</script>
