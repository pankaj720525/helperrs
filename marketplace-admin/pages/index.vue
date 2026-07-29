<template>
  <div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-xl font-heading font-extrabold text-[#12263f] dark:text-white tracking-tight">
          Service Market Dashboard
        </h1>
        <p class="text-xs text-[#5e6e82] dark:text-slate-400 mt-0.5">
          Real-time service catalog analytics & platform monitoring
        </p>
      </div>

      <button
        @click="loadDashboard"
        :disabled="loading"
        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-white dark:bg-[#121e2d] border border-[#edf2f9] dark:border-[#1e2c40] text-xs font-bold text-[#2c7be5] hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-xs"
      >
        <svg class="w-3.5 h-3.5" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Refresh Data
      </button>
    </div>

    <!-- 1. Real Data Hero Banner (Falcon Aesthetic) -->
    <div class="bg-gradient-to-r from-[#1265d8] to-[#2c7be5] text-white rounded-xl shadow-xs p-6 relative overflow-hidden">
      <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
          <span class="text-xs font-bold text-white/80 uppercase tracking-wider">Service Market Metrics</span>
          <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-white mt-1">
            {{ stats.total_services || 0 }} Total Services
          </h2>
          <p class="text-xs text-white/80 mt-1 max-w-md">
            {{ stats.approved_services || 0 }} live approved items in catalog, {{ stats.pending_services || 0 }} awaiting admin moderation.
          </p>
        </div>

        <!-- Real Stats Highlights Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-center">
          <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/15">
            <p class="text-[11px] text-white/70 font-semibold uppercase">Total Users</p>
            <p class="text-xl font-heading font-extrabold text-white mt-0.5">{{ stats.total_users || 0 }}</p>
          </div>
          <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/15">
            <p class="text-[11px] text-white/70 font-semibold uppercase">Workers</p>
            <p class="text-xl font-heading font-extrabold text-white mt-0.5">{{ stats.total_workers || 0 }}</p>
          </div>
          <div class="bg-white/10 backdrop-blur-md rounded-lg p-3 border border-white/15 col-span-2 sm:col-span-1">
            <p class="text-[11px] text-white/70 font-semibold uppercase">Active Pro</p>
            <p class="text-xl font-heading font-extrabold text-white mt-0.5">{{ stats.active_subscriptions || 0 }}</p>
          </div>
        </div>
      </div>

      <!-- Decorative SVG curve -->
      <svg class="absolute bottom-0 right-0 w-full h-24 opacity-15 pointer-events-none" viewBox="0 0 1000 150" preserveAspectRatio="none">
        <path d="M 0,100 Q 250,20 500,80 T 1000,40 L 1000,150 L 0,150 Z" fill="#ffffff" />
      </svg>
    </div>

    <!-- 2. Real Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <!-- Total Registered Users -->
      <div class="bg-white dark:bg-[#121e2d] rounded-xl p-5 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs flex items-start justify-between">
        <div>
          <p class="text-xs font-semibold text-[#5e6e82] dark:text-slate-400 uppercase tracking-wider">Total Users</p>
          <p class="text-3xl font-heading font-extrabold text-[#12263f] dark:text-white mt-1">
            {{ stats.total_users || 0 }}
          </p>
          <p class="text-[11px] text-[#00a661] font-bold mt-2 flex items-center gap-1">
            <span>●</span> {{ stats.active_users || 0 }} Active Users
          </p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-[#edf5ff] text-[#2c7be5] flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
      </div>

      <!-- Service Providers / Workers -->
      <div class="bg-white dark:bg-[#121e2d] rounded-xl p-5 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs flex items-start justify-between">
        <div>
          <p class="text-xs font-semibold text-[#5e6e82] dark:text-slate-400 uppercase tracking-wider">Active Workers</p>
          <p class="text-3xl font-heading font-extrabold text-[#12263f] dark:text-white mt-1">
            {{ stats.total_workers || 0 }}
          </p>
          <p class="text-[11px] text-[#5e6e82] dark:text-slate-400 font-medium mt-2">
            Verified Service Providers
          </p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-[#fff3eb] text-[#d96b27] flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
        </div>
      </div>

      <!-- Active Subscriptions -->
      <div class="bg-white dark:bg-[#121e2d] rounded-xl p-5 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs flex items-start justify-between">
        <div>
          <p class="text-xs font-semibold text-[#5e6e82] dark:text-slate-400 uppercase tracking-wider">Active Subscriptions</p>
          <p class="text-3xl font-heading font-extrabold text-[#2c7be5] mt-1">
            {{ stats.active_subscriptions || 0 }}
          </p>
          <p class="text-[11px] text-[#d96b27] font-medium mt-2">
            {{ stats.expiring_soon || 0 }} Expiring in 30 Days
          </p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-[#e6f9f0] text-[#00a661] flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
        </div>
      </div>

      <!-- Moderated Reviews -->
      <div class="bg-white dark:bg-[#121e2d] rounded-xl p-5 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs flex items-start justify-between">
        <div>
          <p class="text-xs font-semibold text-[#5e6e82] dark:text-slate-400 uppercase tracking-wider">Total Reviews</p>
          <p class="text-3xl font-heading font-extrabold text-[#12263f] dark:text-white mt-1">
            {{ stats.total_reviews || 0 }}
          </p>
          <p class="text-[11px] text-[#e63757] font-medium mt-2">
            {{ stats.unmoderated_reviews || 0 }} Pending Review
          </p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-[#ffebe6] text-[#e63757] flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- 3. Catalog Status Breakdown (Real Data) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <!-- Approved Services Card -->
      <div class="bg-white dark:bg-[#121e2d] rounded-xl p-5 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-[#5e6e82] dark:text-slate-400 uppercase">Approved Services</span>
          <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-[#e6f9f0] text-[#00a661]">
            Live
          </span>
        </div>
        <p class="text-3xl font-heading font-extrabold text-[#00a661] mt-2">
          {{ stats.approved_services || 0 }}
        </p>
        <p class="text-xs text-[#5e6e82] dark:text-slate-400 mt-2">
          Active in marketplace catalog
        </p>
      </div>

      <!-- Pending Services Card -->
      <div class="bg-white dark:bg-[#121e2d] rounded-xl p-5 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-[#5e6e82] dark:text-slate-400 uppercase">Pending Approvals</span>
          <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-[#fff3eb] text-[#d96b27]">
            Action Req.
          </span>
        </div>
        <p class="text-3xl font-heading font-extrabold text-[#d96b27] mt-2">
          {{ stats.pending_services || 0 }}
        </p>
        <NuxtLink to="/services?status=pending" class="text-xs font-bold text-[#2c7be5] hover:underline mt-2 inline-block">
          Review pending →
        </NuxtLink>
      </div>

      <!-- Categories Card -->
      <div class="bg-white dark:bg-[#121e2d] rounded-xl p-5 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-[#5e6e82] dark:text-slate-400 uppercase">Service Categories</span>
          <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-[#edf5ff] text-[#2c7be5]">
            Active
          </span>
        </div>
        <p class="text-3xl font-heading font-extrabold text-[#2c7be5] mt-2">
          {{ stats.total_categories || 0 }}
        </p>
        <NuxtLink to="/categories" class="text-xs font-bold text-[#2c7be5] hover:underline mt-2 inline-block">
          Manage categories →
        </NuxtLink>
      </div>
    </div>

    <!-- 4. Real Recent Activity Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
      <!-- Real Pending Services List -->
      <div class="bg-white dark:bg-[#121e2d] rounded-xl p-5 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#edf2f9] dark:border-slate-800">
          <h3 class="text-sm font-heading font-bold text-[#12263f] dark:text-white">Pending Service Approvals</h3>
          <NuxtLink to="/services?status=pending" class="text-xs font-bold text-[#2c7be5] hover:underline">View all →</NuxtLink>
        </div>
        <div v-if="recentServices.length" class="space-y-2">
          <div
            v-for="service in recentServices"
            :key="service.id"
            class="flex items-center justify-between p-3 rounded-lg bg-slate-50/60 dark:bg-slate-800/50 hover:bg-slate-100/60 transition-colors"
          >
            <div>
              <p class="text-xs font-semibold text-[#12263f] dark:text-slate-200">{{ service.title }}</p>
              <p class="text-[11px] text-[#748194] dark:text-slate-400 mt-0.5">{{ service.user }} · {{ service.category }}</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[10px] text-[#9da9bb]">{{ service.created }}</span>
              <UiStatusBadge status="pending" />
            </div>
          </div>
        </div>
        <p v-else class="text-[#748194] text-xs py-8 text-center">No pending services requiring review 🎉</p>
      </div>

      <!-- Real Recent Users List -->
      <div class="bg-white dark:bg-[#121e2d] rounded-xl p-5 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#edf2f9] dark:border-slate-800">
          <h3 class="text-sm font-heading font-bold text-[#12263f] dark:text-white">Recent Registrations</h3>
          <NuxtLink to="/users" class="text-xs font-bold text-[#2c7be5] hover:underline">View all →</NuxtLink>
        </div>
        <div v-if="recentUsers.length" class="space-y-2">
          <div
            v-for="user in recentUsers"
            :key="user.id"
            class="flex items-center justify-between p-3 rounded-lg bg-slate-50/60 dark:bg-slate-800/50 hover:bg-slate-100/60 transition-colors"
          >
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-[#2c7be5] flex items-center justify-center text-white text-xs font-bold shadow-2xs">
                {{ user.name.charAt(0) }}
              </div>
              <div>
                <p class="text-xs font-semibold text-[#12263f] dark:text-slate-200">{{ user.name }}</p>
                <p class="text-[11px] text-[#748194] dark:text-slate-400">{{ user.email }}</p>
              </div>
            </div>
            <div class="text-right">
              <UiStatusBadge :status="user.role" :label="user.role" :dot="false" />
              <p class="text-[10px] text-[#9da9bb] mt-0.5">{{ user.created }}</p>
            </div>
          </div>
        </div>
        <p v-else class="text-[#748194] text-xs py-8 text-center">No recent user registrations</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const api = useApi();

const loading = ref(false);
const stats = ref<any>({});
const recentServices = ref<any[]>([]);
const recentUsers = ref<any[]>([]);

const loadDashboard = async () => {
  loading.value = true;
  try {
    const data = await api.get<any>("/admin/dashboard");
    stats.value = data.stats || {};
    recentServices.value = data.recent_services || [];
    recentUsers.value = data.recent_users || [];
  } catch (error) {
    console.error("Failed to load dashboard:", error);
  } finally {
    loading.value = false;
  }
};

onMounted(loadDashboard);
</script>
