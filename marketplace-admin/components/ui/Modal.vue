<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs" @click="close" />

        <!-- Modal Dialog -->
        <div class="relative bg-white dark:bg-[#1E293B] rounded-2xl w-full max-w-md animate-scale-in border border-[#EAEDF1] dark:border-[#334155] shadow-2xl overflow-hidden">
          <!-- Header -->
          <div class="flex items-center justify-between p-5 border-b border-[#EAEDF1] dark:border-[#334155]">
            <h3 class="text-base font-heading font-bold text-slate-900 dark:text-white">{{ title }}</h3>
            <button @click="close" class="text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors text-xl leading-none">&times;</button>
          </div>

          <!-- Body -->
          <div class="p-5">
            <slot />
          </div>

          <!-- Footer -->
          <div v-if="$slots.footer" class="flex items-center justify-end gap-3 p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-[#EAEDF1] dark:border-[#334155]">
            <slot name="footer" />
          </div>
          <div v-else class="flex items-center justify-end gap-3 p-4 bg-slate-50 dark:bg-slate-900/50 border-t border-[#EAEDF1] dark:border-[#334155]">
            <button
              @click="close"
              class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
            >
              Cancel
            </button>
            <button
              v-if="confirmLabel"
              @click="$emit('confirm')"
              :disabled="confirmDisabled"
              class="px-4 py-2 text-xs font-semibold text-white rounded-lg transition-all duration-200 disabled:opacity-50 shadow-sm"
              :class="confirmDanger ? 'bg-danger hover:bg-rose-600' : 'bg-gradient-to-r from-[#3858F9] to-[#4F46E5] hover:shadow-md'"
            >
              {{ confirmLabel }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
defineProps<{
  modelValue: boolean;
  title: string;
  confirmLabel?: string;
  confirmDanger?: boolean;
  confirmDisabled?: boolean;
}>();

const emit = defineEmits(["update:modelValue", "confirm"]);

const close = () => emit("update:modelValue", false);
</script>

<style scoped>
.modal-enter-active { transition: opacity 0.2s ease; }
.modal-leave-active { transition: opacity 0.15s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
