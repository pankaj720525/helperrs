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
      return "bg-[#e6f9f0] text-[#00a661] dark:bg-[#00d27a]/20 dark:text-[#00d27a]";
    case "pending":
    case "trial":
      return "bg-[#fff3eb] text-[#d96b27] dark:bg-[#f5803e]/20 dark:text-[#f5803e]";
    case "rejected":
    case "inactive":
    case "expired":
    case "closed":
      return "bg-[#ffebe6] text-[#e63757] dark:bg-[#e63757]/20 dark:text-[#e63757]";
    case "pro":
    case "admin":
      return "bg-[#edf5ff] text-[#2c7be5] dark:bg-[#2c7be5]/20 dark:text-[#558bd7]";
    default:
      return "bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300";
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
