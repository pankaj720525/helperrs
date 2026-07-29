<template>
  <div class="space-y-6">
    <!-- Back button -->
    <NuxtLink to="/users" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
      ← Back to Users
    </NuxtLink>

    <div v-if="user" class="space-y-6">
      <!-- Profile Card -->
      <div class="bg-white dark:bg-[#1E293B] rounded-xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
        <div class="flex flex-col sm:flex-row items-start gap-6">
          <!-- Avatar -->
          <div class="w-20 h-20 rounded-2xl gradient-primary flex items-center justify-center text-white text-3xl font-bold flex-shrink-0 shadow-sm">
            {{ user.name?.charAt(0)?.toUpperCase() }}
          </div>

          <!-- Info -->
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4 flex-wrap mb-2">
              <div>
                <h1 class="text-xl font-heading font-bold text-slate-900 dark:text-white">{{ user.name }}</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ user.email }}</p>
                <p v-if="user.phone" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">📞 {{ user.phone }}</p>
              </div>
              <div class="flex items-center gap-2 flex-wrap">
                <UiStatusBadge :status="user.role" :label="user.role" :dot="false" />
                <UiStatusBadge :status="user.is_active ? 'active' : 'inactive'" />
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4 text-xs">
              <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] text-slate-400 mb-0.5">Joined Date & Time</p>
                <p class="font-bold text-slate-900 dark:text-white text-[11px]">{{ formatDate(user.created_at) }}</p>
              </div>
              <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] text-slate-400 mb-0.5">Services</p>
                <p class="font-bold text-slate-900 dark:text-white">{{ user.services_count ?? 0 }}</p>
              </div>
              <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] text-slate-400 mb-0.5">Subscription</p>
                <p class="font-bold text-slate-900 dark:text-white">{{ user.active_subscription?.plan_type ?? 'None' }}</p>
              </div>
              <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] text-slate-400 mb-0.5">Email Notif.</p>
                <p class="font-bold" :class="user.admin_email_override ? 'text-rose-600' : 'text-emerald-600'">
                  {{ user.admin_email_override ? 'Muted' : 'Active' }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex gap-3 flex-wrap pt-4 border-t border-slate-100 dark:border-slate-800">
          <button
            @click="confirmToggle"
            class="px-4 py-2 rounded-lg text-xs font-semibold transition-all"
            :class="user.is_active ? 'bg-rose-600 text-white hover:bg-rose-700' : 'bg-emerald-600 text-white hover:bg-emerald-700'"
          >
            {{ user.is_active ? '🚫 Deactivate Account' : '✅ Activate Account' }}
          </button>
        </div>
      </div>

      <!-- Worker Profile (if worker) -->
      <div v-if="user.worker_profile" class="bg-white dark:bg-[#1E293B] rounded-xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
        <h2 class="text-base font-heading font-bold text-slate-900 dark:text-white mb-4">Worker Profile</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div>
            <p class="text-[11px] text-slate-400 mb-1">Bio</p>
            <p class="text-slate-700 dark:text-slate-300 font-medium">{{ user.worker_profile.bio || 'No bio provided.' }}</p>
          </div>
          <div>
            <p class="text-[11px] text-slate-400 mb-1">Experience</p>
            <p class="text-slate-700 dark:text-slate-300 font-medium">{{ user.worker_profile.experience_years ? `${user.worker_profile.experience_years} years` : 'Not specified' }}</p>
          </div>
          <div>
            <p class="text-[11px] text-slate-400 mb-1">Location</p>
            <p class="text-slate-700 dark:text-slate-300 font-medium">{{ user.worker_profile.city || 'Not specified' }}</p>
          </div>
          <div>
            <p class="text-[11px] text-slate-400 mb-1">Avg Rating</p>
            <p class="text-slate-700 dark:text-slate-300 font-medium">⭐ {{ user.worker_profile.avg_rating ? Number(user.worker_profile.avg_rating).toFixed(1) : '—' }}</p>
          </div>
        </div>
      </div>

      <!-- Services -->
      <div v-if="user.services?.length" class="bg-white dark:bg-[#1E293B] rounded-xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
        <h2 class="text-base font-heading font-bold text-slate-900 dark:text-white mb-4">Services ({{ user.services.length }})</h2>
        <div class="space-y-2.5">
          <div v-for="s in user.services" :key="s.id"
            class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
            <div>
              <p class="text-xs font-semibold text-slate-900 dark:text-slate-100">{{ s.title }}</p>
              <p class="text-[11px] text-slate-400">{{ s.category?.name }} · {{ formatDate(s.created_at) }}</p>
            </div>
            <div class="flex items-center gap-2">
              <UiStatusBadge :status="s.status" />
              <NuxtLink :to="`/services/${s.id}`" class="text-xs font-semibold text-primary hover:underline">View</NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-else class="bg-white dark:bg-[#1E293B] rounded-xl p-16 text-center border border-[#EAEDF1] dark:border-[#334155]">
      <div class="animate-pulse space-y-3">
        <div class="h-5 w-48 bg-slate-100 dark:bg-slate-800 rounded mx-auto" />
        <div class="h-3 w-64 bg-slate-100 dark:bg-slate-800 rounded mx-auto" />
      </div>
    </div>

    <!-- Confirm Modal -->
    <UiModal
      v-model="confirmModal"
      :title="user?.is_active ? 'Deactivate Account' : 'Activate Account'"
      :confirm-label="user?.is_active ? 'Deactivate' : 'Activate'"
      :confirm-danger="user?.is_active"
      @confirm="doToggleStatus"
    >
      <p class="text-xs text-slate-600 dark:text-slate-300">
        Are you sure you want to
        <strong class="text-slate-900 dark:text-white">{{ user?.is_active ? 'deactivate' : 'activate' }}</strong>
        the account for <strong class="text-slate-900 dark:text-white">{{ user?.name }}</strong>?
        <span v-if="user?.is_active" class="block mt-2 text-rose-600 font-semibold">
          This will prevent the user from logging in.
        </span>
      </p>
    </UiModal>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const route = useRoute();
const api = useApi();
const toast = useToast();

const user = ref<any>(null);
const confirmModal = ref(false);

const loadUser = async () => {
  try {
    const data = await api.get<any>(`/admin/users/${route.params.id}`);
    user.value = data.user;
  } catch {
    toast.error("Failed to load user.");
    navigateTo("/users");
  }
};

const confirmToggle = () => { confirmModal.value = true; };

const doToggleStatus = async () => {
  try {
    await api.put(`/admin/users/${route.params.id}/toggle-status`);
    toast.success(`Account ${user.value?.is_active ? 'deactivated' : 'activated'} successfully.`);
    confirmModal.value = false;
    await loadUser();
  } catch (e: any) {
    toast.error(e?.data?.message || "Failed to update status.");
  }
};

onMounted(loadUser);
</script>
