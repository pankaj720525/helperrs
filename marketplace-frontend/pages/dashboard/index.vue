<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="text-3xl font-heading font-bold text-white mb-6">Dashboard</h1>

    <div v-if="user" class="space-y-6">
      <!-- Profile Card -->
      <div class="glass rounded-2xl p-6 flex flex-wrap items-center gap-6">
        <div class="w-16 h-16 rounded-full gradient-primary flex items-center justify-center text-white text-2xl font-bold">
          {{ user.name?.charAt(0) }}
        </div>
        <div class="flex-1">
          <h2 class="text-xl font-heading font-semibold text-white">{{ user.name }}</h2>
          <p class="text-sm text-slate-400">{{ user.email }} · {{ user.role }}</p>
        </div>
        <div v-if="subscription" class="text-right">
          <span class="px-3 py-1 rounded-full text-xs font-medium"
            :class="subscription.is_expired ? 'bg-danger/15 text-danger' : 'bg-success/15 text-success'">
            {{ subscription.is_trial ? 'Trial' : subscription.plan_type }} · {{ subscription.days_remaining }} days left
          </span>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <NuxtLink to="/chats" class="glass rounded-2xl p-5 hover:border-primary/20 transition-all text-center group">
          <span class="text-2xl block mb-2">💬</span>
          <span class="text-sm font-medium text-slate-200 group-hover:text-primary-light">My Chats</span>
        </NuxtLink>
        <NuxtLink v-if="userStore.isWorker" to="/dashboard/services" class="glass rounded-2xl p-5 hover:border-primary/20 transition-all text-center group">
          <span class="text-2xl block mb-2">🔧</span>
          <span class="text-sm font-medium text-slate-200 group-hover:text-primary-light">My Services</span>
        </NuxtLink>
        <NuxtLink to="/services" class="glass rounded-2xl p-5 hover:border-primary/20 transition-all text-center group">
          <span class="text-2xl block mb-2">🔍</span>
          <span class="text-sm font-medium text-slate-200 group-hover:text-primary-light">Browse Services</span>
        </NuxtLink>
        <NuxtLink to="/search" class="glass rounded-2xl p-5 hover:border-primary/20 transition-all text-center group">
          <span class="text-2xl block mb-2">📍</span>
          <span class="text-sm font-medium text-slate-200 group-hover:text-primary-light">Nearby Search</span>
        </NuxtLink>
        <NuxtLink to="/dashboard/profile" class="glass rounded-2xl p-5 hover:border-primary/20 transition-all text-center group">
          <span class="text-2xl block mb-2">👤</span>
          <span class="text-sm font-medium text-slate-200 group-hover:text-primary-light">Edit Profile</span>
        </NuxtLink>
        <NuxtLink to="/dashboard/notifications" class="glass rounded-2xl p-5 hover:border-primary/20 transition-all text-center group">
          <span class="text-2xl block mb-2">🔔</span>
          <span class="text-sm font-medium text-slate-200 group-hover:text-primary-light">Notification Prefs</span>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const userStore = useUserStore();
const api = useApi();
const user = ref<any>(null);
const subscription = ref<any>(null);

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo("/login");

  try {
    const data = await api.get<any>("/auth/user");
    user.value = data.user;
    userStore.updateUser(data.user);
  } catch { }

  if (userStore.isWorker) {
    try {
      const data = await api.get<any>("/subscription");
      subscription.value = data.subscription;
    } catch { }
  }
});
</script>
