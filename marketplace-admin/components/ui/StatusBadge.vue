<template>
  <span
    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold"
    :class="badgeClass"
  >
    <span v-if="dot" class="w-1.5 h-1.5 rounded-full mr-1.5" :class="dotClass" />
    {{ formattedLabel }}
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

const formattedLabel = computed(() => props.label || (props.status ? props.status.charAt(0).toUpperCase() + props.status.slice(1) : ''));

const badgeClass = computed(() => {
  switch (props.status?.toLowerCase()) {
    case "approved":
    case "active":
    case "open":
      return "bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800";
    case "pending":
    case "trial":
      return "bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800";
    case "rejected":
    case "inactive":
    case "expired":
    case "closed":
      return "bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-200 dark:border-rose-800";
    default:
      return "bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700";
  }
});

const dotClass = computed(() => {
  switch (props.status?.toLowerCase()) {
    case "approved":
    case "active":
    case "open":
      return "bg-emerald-500";
    case "pending":
    case "trial":
      return "bg-amber-500";
    case "rejected":
    case "inactive":
    case "expired":
    case "closed":
      return "bg-rose-500";
    default:
      return "bg-slate-400";
  }
});
</script>
