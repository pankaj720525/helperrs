<template>
  <div class="space-y-6">
    <!-- Stats Cards -->
    <UiStatsGrid :cols="4">
      <UiStatsCard label="Total Users" :value="stats.total_users" icon="👥" color="info" />
      <UiStatsCard label="Active Workers" :value="stats.total_workers" icon="🔧" color="primary" />
      <UiStatsCard label="Pending Services" :value="stats.pending_services" icon="⏳" color="warning" />
      <UiStatsCard label="Active Subscriptions" :value="stats.active_subscriptions" icon="💳" color="success" />
    </UiStatsGrid>

    <UiStatsGrid :cols="4">
      <UiStatsCard label="Approved Services" :value="stats.approved_services" icon="✅" color="success" />
      <UiStatsCard label="Rejected Services" :value="stats.rejected_services" icon="❌" color="danger" />
      <UiStatsCard label="Unmoderated Reviews" :value="stats.unmoderated_reviews" icon="⭐" color="warning" />
      <UiStatsCard label="Expiring Soon" :value="stats.expiring_soon" icon="⚠️" color="danger" :subtitle="'Within 30 days'" />
    </UiStatsGrid>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Pending Services -->
      <div class="glass rounded-2xl p-6 animate-fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-heading font-semibold text-white">Pending Approvals</h2>
          <NuxtLink to="/services?status=pending" class="text-xs text-primary-light hover:underline">View all →</NuxtLink>
        </div>
        <div v-if="recentServices.length" class="space-y-3">
          <div
            v-for="service in recentServices"
            :key="service.id"
            class="flex items-center justify-between p-3 rounded-xl bg-white/3 hover:bg-white/5 transition-colors"
          >
            <div>
              <p class="text-sm font-medium text-slate-200">{{ service.title }}</p>
              <p class="text-xs text-slate-400">{{ service.user }} · {{ service.category }}</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-xs text-slate-500">{{ service.created }}</span>
              <UiStatusBadge status="pending" />
            </div>
          </div>
        </div>
        <p v-else class="text-slate-500 text-sm">No pending services 🎉</p>
      </div>

      <!-- Recent Users -->
      <div class="glass rounded-2xl p-6 animate-fade-up" style="animation-delay: 0.3s">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-heading font-semibold text-white">Recent Users</h2>
          <NuxtLink to="/users" class="text-xs text-primary-light hover:underline">View all →</NuxtLink>
        </div>
        <div v-if="recentUsers.length" class="space-y-3">
          <div
            v-for="user in recentUsers"
            :key="user.id"
            class="flex items-center justify-between p-3 rounded-xl bg-white/3 hover:bg-white/5 transition-colors"
          >
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-semibold">
                {{ user.name.charAt(0) }}
              </div>
              <div>
                <p class="text-sm font-medium text-slate-200">{{ user.name }}</p>
                <p class="text-xs text-slate-400">{{ user.email }}</p>
              </div>
            </div>
            <div class="text-right">
              <UiStatusBadge :status="user.role" :label="user.role" :dot="false" />
              <p class="text-xs text-slate-500 mt-1">{{ user.created }}</p>
            </div>
          </div>
        </div>
        <p v-else class="text-slate-500 text-sm">No users yet</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const api = useApi();

const stats = ref<any>({});
const recentServices = ref<any[]>([]);
const recentUsers = ref<any[]>([]);

const loadDashboard = async () => {
  try {
    const data = await api.get<any>("/admin/dashboard");
    stats.value = data.stats || {};
    recentServices.value = data.recent_services || [];
    recentUsers.value = data.recent_users || [];
  } catch (error) {
    console.error("Failed to load dashboard:", error);
  }
};

onMounted(loadDashboard);
</script>
