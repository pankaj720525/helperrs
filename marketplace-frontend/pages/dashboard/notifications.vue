<template>
  <div class="space-y-6">
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-heading font-extrabold text-slate-900 mb-1">🔔 Notification Preferences</h1>
        <p class="text-sm text-slate-500 font-medium">Control email alerts and in-app message notifications</p>
      </div>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
      <div class="flex items-center justify-between py-4 border-b border-slate-100">
        <div>
          <h3 class="font-bold text-slate-900 text-base">Email Notifications</h3>
          <p class="text-xs text-slate-500 mt-0.5">Receive email notifications for new messages & service bookings</p>
        </div>
        <button
          type="button"
          @click="prefs.email_enabled = !prefs.email_enabled; save();"
          class="w-12 h-6 rounded-full transition-colors relative cursor-pointer"
          :class="prefs.email_enabled ? 'bg-rose-600' : 'bg-slate-200'"
        >
          <span class="w-5 h-5 rounded-full bg-white shadow-md absolute top-0.5 transition-all" :class="prefs.email_enabled ? 'right-0.5' : 'left-0.5'" />
        </button>
      </div>

      <div class="flex items-center justify-between py-4">
        <div>
          <h3 class="font-bold text-slate-900 text-base">In-App Push Alerts</h3>
          <p class="text-xs text-slate-500 mt-0.5">Receive instant browser popup notifications when online</p>
        </div>
        <button
          type="button"
          @click="prefs.push_enabled = !prefs.push_enabled; save();"
          class="w-12 h-6 rounded-full transition-colors relative cursor-pointer"
          :class="prefs.push_enabled ? 'bg-rose-600' : 'bg-slate-200'"
        >
          <span class="w-5 h-5 rounded-full bg-white shadow-md absolute top-0.5 transition-all" :class="prefs.push_enabled ? 'right-0.5' : 'left-0.5'" />
        </button>
      </div>

      <p v-if="saved" class="text-emerald-600 text-xs font-bold animate-fade-in">✅ Preferences saved!</p>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard' });

const api = useApi();

const prefs = reactive({ email_enabled: true, push_enabled: true });
const saved = ref(false);

const save = async () => {
  saved.value = false;
  try {
    await api.put('/auth/notifications', prefs);
    saved.value = true;
    setTimeout(() => { saved.value = false; }, 3000);
  } catch {}
};
</script>
