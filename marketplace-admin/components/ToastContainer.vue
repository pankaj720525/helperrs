<template>
  <Teleport to="body">
    <div class="fixed top-4 right-4 z-[100] flex flex-col gap-2 max-w-sm w-full pointer-events-none">
      <TransitionGroup name="toast">
        <div
          v-for="t in toasts"
          :key="t.id"
          class="pointer-events-auto glass rounded-xl px-4 py-3 flex items-start gap-3 animate-slide-in cursor-pointer"
          :class="borderClass(t.type)"
          @click="remove(t.id)"
        >
          <span class="text-lg flex-shrink-0">{{ icon(t.type) }}</span>
          <p class="text-sm text-slate-200 flex-1">{{ t.message }}</p>
          <button class="text-slate-500 hover:text-slate-300 text-xs flex-shrink-0">✕</button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
const { toasts, remove } = useToast();

const icon = (type?: string) => {
  switch (type) {
    case "success": return "✅";
    case "error": return "❌";
    case "warning": return "⚠️";
    default: return "ℹ️";
  }
};

const borderClass = (type?: string) => {
  switch (type) {
    case "success": return "border-l-2 border-l-success";
    case "error": return "border-l-2 border-l-danger";
    case "warning": return "border-l-2 border-l-warning";
    default: return "border-l-2 border-l-info";
  }
};
</script>

<style scoped>
.toast-enter-active { animation: slideIn 0.3s ease-out; }
.toast-leave-active { animation: fadeOut 0.2s ease-in forwards; }
@keyframes fadeOut { to { opacity: 0; transform: translateX(100%); } }
</style>
