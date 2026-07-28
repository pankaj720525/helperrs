<template>
  <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 animate-fade-down">
    <div>
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500 font-medium mb-1">
        <NuxtLink to="/" class="hover:text-primary transition-colors">Admin</NuxtLink>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <span class="text-primary font-semibold">{{ title }}</span>
        <template v-if="subtitle">
          <span class="text-slate-300 dark:text-slate-600">/</span>
          <span class="text-slate-600 dark:text-slate-400">{{ category || 'Overview' }}</span>
        </template>
      </nav>

      <!-- Title & Subtitle -->
      <h1 class="text-2xl font-heading font-bold text-slate-900 dark:text-white tracking-tight">
        {{ title }}
      </h1>
      <p v-if="description" class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        {{ description }}
      </p>
    </div>

    <!-- Right Side Action Slot -->
    <div v-if="$slots.actions" class="flex items-center gap-3">
      <slot name="actions" />
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string;
  description?: string;
  category?: string;
  subtitle?: boolean;
}>();
</script>
