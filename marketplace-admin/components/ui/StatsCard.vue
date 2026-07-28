<template>
  <div class="bg-white dark:bg-[#1E293B] rounded-xl p-5 border border-[#EAEDF1] dark:border-[#334155] shadow-sm hover:shadow-md transition-all duration-300 group">
    <!-- Skeleton when loading -->
    <div v-if="value === undefined" class="animate-pulse space-y-2">
      <div class="h-3 w-24 bg-slate-100 dark:bg-slate-700 rounded" />
      <div class="h-8 w-16 bg-slate-100 dark:bg-slate-700 rounded" />
    </div>
    <div v-else>
      <div class="flex items-start justify-between">
        <div>
          <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ label }}</p>
          <p class="text-2xl font-heading font-bold text-slate-900 dark:text-white mt-1 tracking-tight">
            {{ formatted }}
          </p>
          <p v-if="subtitle" class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ subtitle }}</p>
        </div>
        <div
          class="w-11 h-11 rounded-xl flex items-center justify-center text-xl transition-transform duration-300 group-hover:scale-105 shadow-sm"
          :class="iconBg"
        >
          {{ icon }}
        </div>
      </div>
      <div v-if="change !== undefined" class="mt-3 flex items-center gap-1.5 border-t border-slate-100 dark:border-slate-800 pt-2.5">
        <span
          class="text-[11px] font-bold px-1.5 py-0.5 rounded"
          :class="change >= 0 ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/40' : 'text-rose-700 bg-rose-50 dark:text-rose-400 dark:bg-rose-950/40'"
        >
          {{ change >= 0 ? '↑' : '↓' }} {{ Math.abs(change) }}%
        </span>
        <span class="text-xs text-slate-400">vs last month</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  label: string;
  value: number | string;
  icon: string;
  subtitle?: string;
  change?: number;
  color?: "primary" | "success" | "warning" | "danger" | "info";
}>();

const formatted = computed(() => {
  const val = props.value ?? 0;
  if (typeof val === "number") {
    return val.toLocaleString();
  }
  return val;
});

const iconBg = computed(() => {
  switch (props.color) {
    case "primary": return "bg-[#EEF2FF] text-[#3858F9] dark:bg-indigo-950/60 dark:text-indigo-400";
    case "success": return "bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400";
    case "warning": return "bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400";
    case "danger": return "bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400";
    case "info": return "bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400";
    default: return "bg-[#EEF2FF] text-[#3858F9] dark:bg-indigo-950/60 dark:text-indigo-400";
  }
});
</script>
