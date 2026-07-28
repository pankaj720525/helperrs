<template>
  <div class="space-y-6 max-w-5xl mx-auto">
    <!-- Page Header with Breadcrumbs -->
    <UiPageHeader
      title="My Account Profile"
      description="Manage your administrator account details, security credentials, and preferences."
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left Column: User Summary Card -->
      <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm flex flex-col items-center text-center">
        <div class="w-24 h-24 rounded-full gradient-primary flex items-center justify-center text-white text-3xl font-bold shadow-md shadow-[#3858F9]/20 mb-4">
          {{ initials }}
        </div>
        <h2 class="text-lg font-heading font-bold text-slate-900 dark:text-white">
          {{ authStore.admin?.name || 'Super Admin' }}
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
          {{ authStore.admin?.email || 'admin@servicemarketplace.com' }}
        </p>

        <div class="flex items-center gap-2 mt-3">
          <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-primary/10 text-primary">
            Super Administrator
          </span>
          <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
            ● Active
          </span>
        </div>

        <div class="w-full border-t border-slate-100 dark:border-slate-800 my-5" />

        <div class="w-full space-y-3 text-xs text-left">
          <div class="flex justify-between">
            <span class="text-slate-400">Account ID</span>
            <span class="font-mono text-slate-700 dark:text-slate-300 font-semibold">{{ authStore.admin?.id || 'ADM-001' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Role Level</span>
            <span class="text-slate-700 dark:text-slate-300 font-semibold">Full Access</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Last Login</span>
            <span class="text-slate-700 dark:text-slate-300 font-semibold">Today, Just now</span>
          </div>
        </div>
      </div>

      <!-- Right Column: Profile Edit & Security Forms -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Edit Profile Form -->
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
          <h3 class="text-base font-heading font-bold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
            Personal Information
          </h3>

          <form @submit.prevent="handleUpdateProfile" class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Full Name</label>
              <input
                v-model="profileForm.name"
                type="text"
                required
                class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-primary"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
              <input
                v-model="profileForm.email"
                type="email"
                required
                class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-primary"
              />
            </div>

            <div class="pt-2 flex justify-end">
              <button
                type="submit"
                :disabled="savingProfile"
                class="px-5 py-2.5 rounded-xl bg-primary text-white font-semibold text-xs hover:bg-primary-dark transition-all disabled:opacity-50"
              >
                {{ savingProfile ? 'Saving...' : 'Save Profile Changes' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Security / Password Update Form -->
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
          <h3 class="text-base font-heading font-bold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
            Security & Password
          </h3>

          <form @submit.prevent="handleUpdatePassword" class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Current Password</label>
              <input
                v-model="passwordForm.current"
                type="password"
                required
                placeholder="••••••••"
                class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-primary"
              />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">New Password</label>
                <input
                  v-model="passwordForm.newPass"
                  type="password"
                  required
                  placeholder="••••••••"
                  class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-primary"
                />
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Confirm New Password</label>
                <input
                  v-model="passwordForm.confirmPass"
                  type="password"
                  required
                  placeholder="••••••••"
                  class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-primary"
                />
              </div>
            </div>

            <div class="pt-2 flex justify-end">
              <button
                type="submit"
                :disabled="savingPassword"
                class="px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 font-semibold text-xs hover:opacity-90 transition-all disabled:opacity-50"
              >
                {{ savingPassword ? 'Updating Password...' : 'Update Password' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const authStore = useAuthStore();
const toast = useToast();

const profileForm = reactive({
  name: authStore.admin?.name || "Super Admin",
  email: authStore.admin?.email || "admin@servicemarketplace.com",
});

const passwordForm = reactive({
  current: "",
  newPass: "",
  confirmPass: "",
});

const savingProfile = ref(false);
const savingPassword = ref(false);

const initials = computed(() => {
  const name = profileForm.name || "Super Admin";
  return name.split(" ").map((n: string) => n[0]).join("").toUpperCase().slice(0, 2);
});

const handleUpdateProfile = async () => {
  savingProfile.value = true;
  setTimeout(() => {
    savingProfile.value = false;
    authStore.admin = { ...authStore.admin, name: profileForm.name, email: profileForm.email };
    toast.success("Profile details updated successfully!");
  }, 500);
};

const handleUpdatePassword = async () => {
  if (passwordForm.newPass !== passwordForm.confirmPass) {
    toast.error("New passwords do not match!");
    return;
  }
  savingPassword.value = true;
  setTimeout(() => {
    savingPassword.value = false;
    passwordForm.current = "";
    passwordForm.newPass = "";
    passwordForm.confirmPass = "";
    toast.success("Password updated successfully!");
  }, 600);
};
</script>
