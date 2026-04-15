<template>
  <div class="space-y-6">
    <!-- Back -->
    <NuxtLink to="/users" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-white transition-colors">
      ← Back to Users
    </NuxtLink>

    <div v-if="user" class="space-y-6">

      <!-- Profile Card -->
      <div class="glass rounded-2xl p-6 md:p-8">
        <div class="flex flex-col sm:flex-row items-start gap-6">
          <!-- Avatar -->
          <div class="w-20 h-20 rounded-2xl gradient-primary flex items-center justify-center text-white text-3xl font-bold flex-shrink-0">
            {{ user.name?.charAt(0)?.toUpperCase() }}
          </div>

          <!-- Info -->
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4 flex-wrap mb-2">
              <div>
                <h1 class="text-2xl font-heading font-bold text-white">{{ user.name }}</h1>
                <p class="text-slate-400 text-sm">{{ user.email }}</p>
                <p v-if="user.phone" class="text-slate-400 text-sm">📞 {{ user.phone }}</p>
              </div>
              <div class="flex items-center gap-2 flex-wrap">
                <UiStatusBadge :status="user.role" :label="user.role" :dot="false" />
                <UiStatusBadge :status="user.is_active ? 'active' : 'inactive'" />
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4 text-sm">
              <div class="bg-white/3 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-1">Joined</p>
                <p class="font-semibold text-white">{{ user.created_at?.split('T')[0] }}</p>
              </div>
              <div class="bg-white/3 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-1">Services</p>
                <p class="font-semibold text-white">{{ user.services_count ?? 0 }}</p>
              </div>
              <div class="bg-white/3 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-1">Subscription</p>
                <p class="font-semibold text-white">{{ user.active_subscription?.plan_type ?? 'None' }}</p>
              </div>
              <div class="bg-white/3 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-1">Email Notif.</p>
                <p class="font-semibold" :class="user.admin_email_override ? 'text-danger' : 'text-success'">
                  {{ user.admin_email_override ? 'Muted' : 'Active' }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex gap-3 flex-wrap">
          <button
            @click="confirmToggle"
            class="px-5 py-2.5 rounded-xl text-sm font-medium transition-all"
            :class="user.is_active ? 'bg-danger/15 text-danger hover:bg-danger/25' : 'bg-success/15 text-success hover:bg-success/25'"
          >
            {{ user.is_active ? '🚫 Deactivate Account' : '✅ Activate Account' }}
          </button>
        </div>
      </div>

      <!-- Worker Profile (if worker) -->
      <div v-if="user.worker_profile" class="glass rounded-2xl p-6">
        <h2 class="text-lg font-heading font-semibold text-white mb-4">Worker Profile</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
          <div>
            <p class="text-xs text-slate-500 mb-1">Bio</p>
            <p class="text-slate-300">{{ user.worker_profile.bio || 'No bio provided.' }}</p>
          </div>
          <div>
            <p class="text-xs text-slate-500 mb-1">Experience</p>
            <p class="text-slate-300">{{ user.worker_profile.experience_years ? `${user.worker_profile.experience_years} years` : 'Not specified' }}</p>
          </div>
          <div>
            <p class="text-xs text-slate-500 mb-1">Location</p>
            <p class="text-slate-300">{{ user.worker_profile.city || 'Not specified' }}</p>
          </div>
          <div>
            <p class="text-xs text-slate-500 mb-1">Avg Rating</p>
            <p class="text-slate-300">⭐ {{ user.worker_profile.avg_rating ? Number(user.worker_profile.avg_rating).toFixed(1) : '—' }}</p>
          </div>
        </div>
      </div>

      <!-- Services -->
      <div v-if="user.services?.length" class="glass rounded-2xl p-6">
        <h2 class="text-lg font-heading font-semibold text-white mb-4">Services ({{ user.services.length }})</h2>
        <div class="space-y-3">
          <div v-for="s in user.services" :key="s.id"
            class="flex items-center justify-between p-3 rounded-xl bg-white/3 border border-white/5">
            <div>
              <p class="text-sm font-medium text-slate-200">{{ s.title }}</p>
              <p class="text-xs text-slate-500">{{ s.category?.name }} · {{ s.created_at?.split('T')[0] }}</p>
            </div>
            <div class="flex items-center gap-2">
              <UiStatusBadge :status="s.status" />
              <NuxtLink :to="`/services/${s.id}`" class="text-xs text-primary-light hover:underline">View</NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-else class="glass rounded-2xl p-16 text-center">
      <div class="animate-pulse space-y-3">
        <div class="h-5 w-48 bg-white/10 rounded mx-auto" />
        <div class="h-3 w-64 bg-white/5 rounded mx-auto" />
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
      <p class="text-sm text-slate-300">
        Are you sure you want to
        <strong class="text-white">{{ user?.is_active ? 'deactivate' : 'activate' }}</strong>
        the account for <strong class="text-white">{{ user?.name }}</strong>?
        <span v-if="user?.is_active" class="block mt-2 text-danger">
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
    await loadUser();
  } catch (e: any) {
    toast.error(e?.data?.message || "Failed to update status.");
  }
};

onMounted(loadUser);
</script>
