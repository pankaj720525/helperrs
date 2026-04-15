<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close" />

        <!-- Modal -->
        <div class="relative glass rounded-2xl w-full max-w-md animate-scale-in border border-white/10">
          <!-- Header -->
          <div class="flex items-center justify-between p-5 border-b border-white/5">
            <h3 class="text-lg font-heading font-semibold text-white">{{ title }}</h3>
            <button @click="close" class="text-slate-400 hover:text-white transition-colors text-xl">&times;</button>
          </div>

          <!-- Body -->
          <div class="p-5">
            <slot />
          </div>

          <!-- Footer -->
          <div v-if="$slots.footer" class="flex items-center justify-end gap-3 p-5 border-t border-white/5">
            <slot name="footer" />
          </div>
          <div v-else class="flex items-center justify-end gap-3 p-5 border-t border-white/5">
            <button
              @click="close"
              class="px-4 py-2 text-sm font-medium text-slate-300 bg-white/5 rounded-xl hover:bg-white/10 transition-colors"
            >
              Cancel
            </button>
            <button
              v-if="confirmLabel"
              @click="$emit('confirm')"
              :disabled="confirmDisabled"
              class="px-4 py-2 text-sm font-medium text-white rounded-xl transition-all duration-200 disabled:opacity-50"
              :class="confirmDanger ? 'bg-danger hover:bg-danger/80' : 'gradient-primary hover:shadow-glow'"
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
