<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <NuxtLink to="/dashboard" class="text-sm text-slate-400 hover:text-primary-light mb-4 inline-block">← Back to Dashboard</NuxtLink>
    <h1 class="text-2xl font-heading font-bold text-white mb-6">👤 Edit Profile</h1>

    <div class="space-y-6">
      <!-- Basic Info -->
      <div class="glass rounded-2xl p-6">
        <h2 class="text-lg font-heading font-semibold text-white mb-4">Basic Information</h2>
        <form @submit.prevent="updateProfile" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Name</label>
            <input v-model="profileForm.name" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary/50 text-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Phone</label>
            <input v-model="profileForm.phone" type="tel" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary/50 text-sm" />
          </div>
          <button type="submit" :disabled="profileSaving" class="px-5 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all disabled:opacity-50">
            {{ profileSaving ? 'Saving...' : 'Update Profile' }}
          </button>
        </form>
      </div>

      <!-- Worker Profile -->
      <div v-if="userStore.isWorker || userStore.user?.role === 'both'" class="glass rounded-2xl p-6">
        <h2 class="text-lg font-heading font-semibold text-white mb-4">🔧 Worker Profile</h2>
        <form @submit.prevent="updateWorkerProfile" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Address</label>
            <input v-model="workerForm.address" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary/50 text-sm" placeholder="Your work area address" />
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Bio</label>
            <textarea v-model="workerForm.bio" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary/50 text-sm resize-none" placeholder="Tell customers about yourself..." />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-1.5">Public Phone</label>
              <input v-model="workerForm.phone_public" type="tel" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary/50 text-sm" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-1.5">Public Email</label>
              <input v-model="workerForm.email_public" type="email" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary/50 text-sm" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-1.5">Latitude</label>
              <input v-model.number="workerForm.latitude" type="number" step="any" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary/50 text-sm" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-1.5">Longitude</label>
              <input v-model.number="workerForm.longitude" type="number" step="any" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary/50 text-sm" />
            </div>
          </div>
          <button type="button" @click="detectLocation" class="text-xs text-primary-light hover:underline">📍 Detect my location</button>
          <p v-if="locationMsg" class="text-xs text-slate-400">{{ locationMsg }}</p>

          <div class="flex items-center gap-3">
            <label class="text-sm text-slate-300">Available for work</label>
            <button type="button" @click="workerForm.is_available = !workerForm.is_available"
              class="relative w-12 h-6 rounded-full transition-all"
              :class="workerForm.is_available ? 'bg-success' : 'bg-surface-lighter'">
              <span class="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow-sm transition-transform"
                :class="workerForm.is_available ? 'translate-x-6' : 'translate-x-0.5'" />
            </button>
          </div>

          <button type="submit" :disabled="workerSaving" class="px-5 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all disabled:opacity-50">
            {{ workerSaving ? 'Saving...' : 'Update Worker Profile' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const api = useApi();
const userStore = useUserStore();

const profileForm = reactive({ name: "", phone: "" });
const workerForm = reactive({ address: "", bio: "", phone_public: "", email_public: "", latitude: 0, longitude: 0, is_available: true });
const profileSaving = ref(false);
const workerSaving = ref(false);
const locationMsg = ref("");

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo("/login");

  try {
    const data = await api.get<any>("/profile");
    const u = data.user;
    profileForm.name = u.name;
    profileForm.phone = u.phone || "";
    if (u.worker_profile) {
      Object.assign(workerForm, {
        address: u.worker_profile.address || "",
        bio: u.worker_profile.bio || "",
        phone_public: u.worker_profile.phone_public || "",
        email_public: u.worker_profile.email_public || "",
        is_available: u.worker_profile.is_available ?? true,
      });
    }
  } catch { }
});

const updateProfile = async () => {
  profileSaving.value = true;
  try {
    const data = await api.put<any>("/profile", profileForm);
    userStore.updateUser(data.user);
    alert("Profile updated!");
  } catch { }
  finally { profileSaving.value = false; }
};

const updateWorkerProfile = async () => {
  workerSaving.value = true;
  try {
    await api.post("/worker-profile", workerForm);
    alert("Worker profile updated!");
  } catch (e: any) {
    alert(e?.data?.message || "Failed to update.");
  } finally { workerSaving.value = false; }
};

const detectLocation = () => {
  if (!navigator.geolocation) { locationMsg.value = "Not supported"; return; }
  locationMsg.value = "Detecting...";
  navigator.geolocation.getCurrentPosition(
    (pos) => {
      workerForm.latitude = pos.coords.latitude;
      workerForm.longitude = pos.coords.longitude;
      locationMsg.value = `📍 ${workerForm.latitude.toFixed(4)}, ${workerForm.longitude.toFixed(4)}`;
    },
    () => { locationMsg.value = "Failed to detect location."; }
  );
};
</script>
