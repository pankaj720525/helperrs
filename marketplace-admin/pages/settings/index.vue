<template>
  <div class="space-y-6">
    <h2 class="text-xl font-heading font-bold text-white">Global Settings</h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Notification Controls -->
      <div class="glass rounded-2xl p-6 animate-fade-up">
        <h3 class="text-lg font-heading font-semibold text-white mb-4">🔔 Notification Master Switches</h3>
        <p class="text-xs text-slate-400 mb-5">These switches control ALL outgoing notifications system-wide.</p>

        <div class="space-y-4">
          <SettingToggle
            v-for="key in ['global_email_enabled', 'global_push_enabled', 'global_audio_enabled']"
            :key="key"
            :label="settingLabel(key)"
            :description="settingDesc(key)"
            :value="settings[key] === '1'"
            @toggle="toggleSetting(key)"
          />
        </div>
      </div>

      <!-- Platform Settings -->
      <div class="glass rounded-2xl p-6 animate-fade-up" style="animation-delay: 0.1s">
        <h3 class="text-lg font-heading font-semibold text-white mb-4">⚙️ Platform Configuration</h3>
        <div class="space-y-4">
          <div v-for="key in ['platform_name', 'platform_email', 'default_search_radius', 'max_search_radius']" :key="key">
            <label class="block text-sm font-medium text-slate-300 mb-1.5">{{ settingLabel(key) }}</label>
            <input
              v-model="settings[key]"
              type="text"
              class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm"
            />
          </div>
          <button @click="savePlatformSettings" class="px-4 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all">
            Save Settings
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });
const api = useApi();
const toast = useToast();
const settings = ref<Record<string, string>>({});

const loadSettings = async () => {
  try {
    const data = await api.get<any>("/admin/settings");
    const allSettings: Record<string, string> = {};
    for (const group of Object.values(data.settings) as any[]) {
      for (const s of group) { allSettings[s.key] = s.value; }
    }
    settings.value = allSettings;
  } catch { toast.error("Failed to load settings."); }
};

const toggleSetting = async (key: string) => {
  const newVal = settings.value[key] === '1' ? '0' : '1';
  settings.value[key] = newVal;
  try {
    await api.put("/admin/settings", { settings: [{ key, value: newVal }] });
    toast.success(`${settingLabel(key)} ${newVal === '1' ? 'enabled' : 'disabled'}.`);
  } catch { toast.error("Failed to update."); }
};

const savePlatformSettings = async () => {
  const keys = ['platform_name', 'platform_email', 'default_search_radius', 'max_search_radius'];
  try {
    await api.put("/admin/settings", { settings: keys.map(key => ({ key, value: settings.value[key] })) });
    toast.success("Settings saved.");
  } catch { toast.error("Failed to save."); }
};

const settingLabel = (key: string) => key.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
const settingDesc = (key: string) => {
  const descs: Record<string, string> = {
    global_email_enabled: "Controls all outgoing transactional and notification emails",
    global_push_enabled: "Controls all real-time push notifications via WebSocket",
    global_audio_enabled: "Controls all audio alert triggers on client side",
  };
  return descs[key] || "";
};

onMounted(loadSettings);
</script>
