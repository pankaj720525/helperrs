<template>
  <div class="space-y-6">
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-heading font-extrabold text-slate-900 mb-1">👤 Account Profile</h1>
        <p class="text-sm text-slate-500 font-medium">Manage your personal information, contact phone & password</p>
      </div>
    </div>

    <!-- Basic Info -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
      <h2 class="text-lg font-heading font-extrabold text-slate-900 mb-4">Basic Information</h2>
      <form @submit.prevent="updateProfile" class="space-y-4 max-w-lg">
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name</label>
          <input v-model="profileForm.name" type="text" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Phone Number</label>
          <input v-model="profileForm.phone" type="tel" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium" />
        </div>
        <p v-if="profileSuccess" class="text-emerald-600 text-sm font-semibold">✅ Profile updated successfully!</p>
        <button
          type="submit"
          :disabled="profileSaving"
          class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-extrabold hover:shadow-md transition-all disabled:opacity-50 cursor-pointer"
          style="color: #ffffff !important;"
        >
          {{ profileSaving ? 'Saving...' : 'Update Profile' }}
        </button>
      </form>
    </div>

    <!-- Password Change -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
      <h2 class="text-lg font-heading font-extrabold text-slate-900 mb-4">Change Password</h2>
      <form @submit.prevent="updatePassword" class="space-y-4 max-w-lg">
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Current Password</label>
          <input v-model="passForm.current_password" type="password" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium" />
        </div>
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">New Password</label>
          <input v-model="passForm.new_password" type="password" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium" />
        </div>
        <p v-if="passError" class="text-rose-600 text-sm font-semibold">{{ passError }}</p>
        <p v-if="passSuccess" class="text-emerald-600 text-sm font-semibold">✅ Password changed successfully!</p>
        <button
          type="submit"
          :disabled="passSaving"
          class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-extrabold hover:shadow-md transition-all disabled:opacity-50 cursor-pointer"
          style="color: #ffffff !important;"
        >
          {{ passSaving ? 'Saving...' : 'Change Password' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard' });

const api = useApi();
const userStore = useUserStore();

const profileForm = reactive({ name: '', phone: '' });
const profileSaving = ref(false);
const profileSuccess = ref(false);

const passForm = reactive({ current_password: '', new_password: '' });
const passSaving = ref(false);
const passError = ref('');
const passSuccess = ref(false);

onMounted(() => {
  userStore.loadFromStorage();
  if (userStore.user) {
    profileForm.name = userStore.user.name || '';
    profileForm.phone = userStore.user.phone || '';
  }
});

const updateProfile = async () => {
  profileSaving.value = true;
  profileSuccess.value = false;
  try {
    const data = await api.put<any>('/auth/profile', profileForm);
    if (data.user) userStore.updateUser(data.user);
    profileSuccess.value = true;
  } catch {}
  finally { profileSaving.value = false; }
};

const updatePassword = async () => {
  passSaving.value = true;
  passError.value = '';
  passSuccess.value = false;
  try {
    await api.put('/auth/password', passForm);
    passSuccess.value = true;
    passForm.current_password = '';
    passForm.new_password = '';
  } catch (e: any) {
    passError.value = e?.data?.message || 'Failed to update password.';
  } finally { passSaving.value = false; }
};
</script>
