<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <NuxtLink to="/dashboard" class="text-sm text-slate-400 hover:text-primary-light mb-4 inline-block">← Back to Dashboard</NuxtLink>
    <h1 class="text-2xl font-heading font-bold text-white mb-6">🔔 Notification Preferences</h1>

    <div class="space-y-6">
      <!-- Email Notifications -->
      <div class="glass rounded-2xl p-6">
        <h2 class="text-lg font-heading font-semibold text-white mb-1">Email Notifications</h2>
        <p class="text-sm text-slate-400 mb-4">Control which emails you receive from us</p>
        <div class="space-y-1">
          <PreferenceToggle
            label="Email Notifications"
            description="Receive email notifications for new chats, service updates"
            :value="prefs.email_enabled"
            @toggle="() => { prefs.email_enabled = !prefs.email_enabled; save(); }"
          />
        </div>
      </div>

      <!-- Push Notifications -->
      <div class="glass rounded-2xl p-6">
        <h2 class="text-lg font-heading font-semibold text-white mb-1">Push Notifications</h2>
        <p class="text-sm text-slate-400 mb-4">In-app real-time push notifications</p>
        <div class="space-y-1">
          <PreferenceToggle
            label="Push Notifications"
            description="Receive real-time push notifications in the browser"
            :value="prefs.push_enabled"
            @toggle="() => { prefs.push_enabled = !prefs.push_enabled; save(); }"
          />
        </div>
      </div>

      <!-- Audio Alerts -->
      <div class="glass rounded-2xl p-6">
        <h2 class="text-lg font-heading font-semibold text-white mb-1">Audio Alerts</h2>
        <p class="text-sm text-slate-400 mb-4">Play a sound when you receive new messages</p>
        <div class="space-y-1">
          <PreferenceToggle
            label="Audio Alerts"
            description="Play notification sound for new chat messages"
            :value="prefs.audio_enabled"
            @toggle="() => { prefs.audio_enabled = !prefs.audio_enabled; save(); }"
          />
        </div>
        <button @click="testSound" class="mt-4 px-4 py-2 rounded-xl bg-white/5 text-slate-300 text-sm hover:bg-white/10 transition-colors">
          🔊 Test Sound
        </button>
      </div>

      <p v-if="saved" class="text-success text-sm text-center animate-fade-in">✅ Preferences saved</p>
    </div>
  </div>
</template>

<script setup lang="ts">
const api = useApi();
const userStore = useUserStore();
const { playNotificationSound } = useAudio();

const prefs = reactive({ email_enabled: true, push_enabled: true, audio_enabled: true });
const saved = ref(false);

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo('/login');

  try {
    const data = await api.get<any>('/preferences');
    Object.assign(prefs, data);
  } catch { }
});

const save = async () => {
  try {
    await api.put('/preferences', prefs);
    saved.value = true;
    setTimeout(() => { saved.value = false; }, 2000);
  } catch { }
};

const testSound = () => {
  playNotificationSound();
};
</script>
