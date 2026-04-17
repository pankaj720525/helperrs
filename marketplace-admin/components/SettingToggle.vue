<template>
  <div class="setting-toggle-row">
    <div class="toggle-label-group">
      <p class="toggle-label">{{ label }}</p>
      <p v-if="description" class="toggle-desc">{{ description }}</p>
    </div>
    <button
      type="button"
      role="switch"
      :aria-checked="value"
      @click="$emit('toggle')"
      class="toggle-btn"
      :class="value ? 'toggle-on' : 'toggle-off'"
    >
      <span class="toggle-thumb" :class="value ? 'thumb-on' : 'thumb-off'" />
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

<style scoped>
.setting-toggle-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem 0.25rem;
  border-bottom: 1px solid rgba(255,255,255,0.06);
}
.setting-toggle-row:last-child { border-bottom: none; }

.toggle-label-group { flex: 1; min-width: 0; }
.toggle-label  { font-size: 0.875rem; font-weight: 500; color: #e2e8f0; margin: 0; }
.toggle-desc   { font-size: 0.75rem;  color: #94a3b8; margin: 0.125rem 0 0; }

/* ── Track ─────────────────────────────── */
.toggle-btn {
  position: relative;
  flex-shrink: 0;
  margin-left: 1rem;
  width: 3rem;       /* 48 px */
  height: 1.625rem;  /* 26 px */
  border-radius: 9999px;
  border: none;
  cursor: pointer;
  outline: none;
  transition: background-color 0.25s ease, box-shadow 0.25s ease;
}
.toggle-btn:focus-visible {
  box-shadow: 0 0 0 3px rgba(178, 5, 55, 0.45);
}

.toggle-on  { background-color: #10B981; }
.toggle-off { background-color: #334155; }

/* ── Thumb ─────────────────────────────── */
.toggle-thumb {
  position: absolute;
  top: 3px;
  width: 1.25rem;
  height: 1.25rem;
  border-radius: 9999px;
  background: #ffffff;
  box-shadow: 0 1px 4px rgba(0,0,0,0.3);
  transition: left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.thumb-off { left: 3px; }
.thumb-on  { left: calc(100% - 1.25rem - 3px); }
</style>
