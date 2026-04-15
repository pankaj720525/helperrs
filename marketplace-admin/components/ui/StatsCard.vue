<template>
  <div class="glass rounded-2xl p-5 animate-fade-up group hover:border-primary/20 transition-all duration-300">
    <!-- Skeleton when loading -->
    <div v-if="value === undefined" class="animate-pulse space-y-2">
      <div class="h-3 w-24 bg-white/10 rounded" />
      <div class="h-8 w-16 bg-white/10 rounded" />
    </div>
    <div v-else>
      <div class="flex items-start justify-between">
        <div>
          <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">{{ label }}</p>
          <p class="text-2xl font-heading font-bold text-white mt-1">
            {{ formatted }}
          </p>
          <p v-if="subtitle" class="text-xs text-slate-500 mt-1">{{ subtitle }}</p>
        </div>
        <div
          class="w-11 h-11 rounded-xl flex items-center justify-center text-xl transition-transform duration-300 group-hover:scale-110"
          :class="iconBg"
        >
          {{ icon }}
        </div>
      </div>
      <div v-if="change !== undefined" class="mt-3 flex items-center gap-1.5">
        <span
          class="text-xs font-semibold px-1.5 py-0.5 rounded"
          :class="change >= 0 ? 'text-success bg-success/10' : 'text-danger bg-danger/10'"
        >
          {{ change >= 0 ? '↑' : '↓' }} {{ Math.abs(change) }}%
        </span>
        <span class="text-xs text-slate-500">vs last month</span>
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
    case "primary": return "bg-primary/15 text-primary-light";
    case "success": return "bg-success/15";
    case "warning": return "bg-warning/15";
    case "danger": return "bg-danger/15";
    case "info": return "bg-info/15";
    default: return "bg-primary/15 text-primary-light";
  }
});
</script>
