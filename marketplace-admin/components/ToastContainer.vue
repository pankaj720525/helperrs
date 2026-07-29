<template>
  <Teleport to="body">
    <div class="fixed top-5 right-5 z-[9999] flex flex-col gap-2.5 max-w-sm w-full pointer-events-none">
      <TransitionGroup name="toast">
        <div
          v-for="t in toasts"
          :key="t.id"
          class="pointer-events-auto bg-white dark:bg-[#121e2d] border border-slate-200 dark:border-slate-700 shadow-2xl rounded-2xl p-3.5 flex items-center gap-3 animate-slide-in cursor-pointer transition-all hover:scale-[1.01]"
          :class="borderClass(t.type)"
          @click="remove(t.id)"
        >
          <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm flex-shrink-0" :class="iconBg(t.type)">
            {{ icon(t.type) }}
          </div>
          <p class="text-xs font-bold text-slate-800 dark:text-slate-100 flex-1 leading-snug">{{ t.message }}</p>
          <button class="w-6 h-6 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 text-xs font-bold flex items-center justify-center flex-shrink-0 transition-colors">
            ✕
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
const { toasts, remove } = useToast();

const icon = (type?: string) => {
  switch (type) {
    case "success": return "✓";
    case "error": return "✕";
    case "warning": return "⚠️";
    default: return "ℹ️";
  }
};

const iconBg = (type?: string) => {
  switch (type) {
    case "success": return "bg-emerald-100 text-emerald-600 font-extrabold";
    case "error": return "bg-rose-100 text-rose-600 font-extrabold";
    case "warning": return "bg-amber-100 text-amber-600 font-extrabold";
    default: return "bg-blue-100 text-blue-600 font-extrabold";
  }
};

const borderClass = (type?: string) => {
  switch (type) {
    case "success": return "border-l-4 border-l-emerald-500";
    case "error": return "border-l-4 border-l-rose-500";
    case "warning": return "border-l-4 border-l-amber-500";
    default: return "border-l-4 border-l-blue-500";
  }
};
</script>

<style scoped>
.toast-enter-active { animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
.toast-leave-active { animation: fadeOut 0.2s ease-in forwards; }
@keyframes slideIn { from { opacity: 0; transform: translateY(-10px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
@keyframes fadeOut { to { opacity: 0; transform: translateX(100%); } }
</style>
