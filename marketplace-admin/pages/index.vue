<template>
  <div class="space-y-6">
    <!-- Valex Welcome Page Header -->
    <UiPageHeader
      title="Hi, welcome back!"
      description="Service Marketplace monitoring dashboard & performance analytics."
    >
      <template #actions>
        <button
          @click="loadDashboard"
          class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2"
        >
          <span>🔄</span> Refresh Data
        </button>
      </template>
    </UiPageHeader>

    <!-- Highlight Metrics Cards Grid (Valex Style Tinted Metric Boxes) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <!-- Metric 1: Total Users -->
      <div class="bg-gradient-to-br from-[#3858F9] to-[#4F46E5] text-white rounded-2xl p-5 shadow-lg shadow-[#3858F9]/20 relative overflow-hidden">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wider font-semibold opacity-90">Total Users</p>
          <span class="text-xs px-2 py-0.5 rounded-full bg-white/20 font-medium">+14%</span>
        </div>
        <p class="text-3xl font-heading font-bold mt-2">{{ stats.total_users || 0 }}</p>
        <div class="mt-3 flex items-center justify-between text-xs opacity-80">
          <span>Registered Customers</span>
          <span>👥</span>
        </div>
        <!-- Decorative SVG wave -->
        <svg class="absolute bottom-0 left-0 w-full h-8 opacity-20" viewBox="0 0 100 20" preserveAspectRatio="none">
          <path d="M0 10 Q 25 20, 50 10 T 100 10 L 100 20 L 0 20 Z" fill="#FFFFFF" />
        </svg>
      </div>

      <!-- Metric 2: Active Workers -->
      <div class="bg-gradient-to-br from-[#EC4899] to-[#F43F5E] text-white rounded-2xl p-5 shadow-lg shadow-[#EC4899]/20 relative overflow-hidden">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wider font-semibold opacity-90">Active Workers</p>
          <span class="text-xs px-2 py-0.5 rounded-full bg-white/20 font-medium">+8%</span>
        </div>
        <p class="text-3xl font-heading font-bold mt-2">{{ stats.total_workers || 0 }}</p>
        <div class="mt-3 flex items-center justify-between text-xs opacity-80">
          <span>Verified Providers</span>
          <span>🔧</span>
        </div>
        <svg class="absolute bottom-0 left-0 w-full h-8 opacity-20" viewBox="0 0 100 20" preserveAspectRatio="none">
          <path d="M0 15 Q 30 5, 60 15 T 100 10 L 100 20 L 0 20 Z" fill="#FFFFFF" />
        </svg>
      </div>

      <!-- Metric 3: Active Subscriptions -->
      <div class="bg-gradient-to-br from-[#10B981] to-[#059669] text-white rounded-2xl p-5 shadow-lg shadow-[#10B981]/20 relative overflow-hidden">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wider font-semibold opacity-90">Active Subscriptions</p>
          <span class="text-xs px-2 py-0.5 rounded-full bg-white/20 font-medium">+24%</span>
        </div>
        <p class="text-3xl font-heading font-bold mt-2">{{ stats.active_subscriptions || 0 }}</p>
        <div class="mt-3 flex items-center justify-between text-xs opacity-80">
          <span>Pro & Trial Members</span>
          <span>💳</span>
        </div>
        <svg class="absolute bottom-0 left-0 w-full h-8 opacity-20" viewBox="0 0 100 20" preserveAspectRatio="none">
          <path d="M0 10 Q 20 0, 50 15 T 100 5 L 100 20 L 0 20 Z" fill="#FFFFFF" />
        </svg>
      </div>

      <!-- Metric 4: Approved Services -->
      <div class="bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white rounded-2xl p-5 shadow-lg shadow-[#F59E0B]/20 relative overflow-hidden">
        <div class="flex items-center justify-between">
          <p class="text-xs uppercase tracking-wider font-semibold opacity-90">Approved Services</p>
          <span class="text-xs px-2 py-0.5 rounded-full bg-white/20 font-medium">98.2%</span>
        </div>
        <p class="text-3xl font-heading font-bold mt-2">{{ stats.approved_services || 0 }}</p>
        <div class="mt-3 flex items-center justify-between text-xs opacity-80">
          <span>Live Catalog Items</span>
          <span>✅</span>
        </div>
        <svg class="absolute bottom-0 left-0 w-full h-8 opacity-20" viewBox="0 0 100 20" preserveAspectRatio="none">
          <path d="M0 5 Q 40 20, 80 5 T 100 15 L 100 20 L 0 20 Z" fill="#FFFFFF" />
        </svg>
      </div>
    </div>

    <!-- Valex Charts Section: Monthly Analytics & Performance Trends -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Chart: Monthly Activity Bar / Line Chart (Valex Order Status Style) -->
      <div class="lg:col-span-2 bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100 dark:border-slate-800">
          <div>
            <h2 class="text-base font-heading font-bold text-slate-900 dark:text-white">Service & User Activity Trends</h2>
            <p class="text-xs text-slate-400">Monthly approval & registration volume across 2026</p>
          </div>
          <div class="flex items-center gap-3 text-xs">
            <span class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300 font-medium">
              <span class="w-2.5 h-2.5 rounded-full bg-[#3858F9]"></span> Approved
            </span>
            <span class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300 font-medium">
              <span class="w-2.5 h-2.5 rounded-full bg-[#EC4899]"></span> Registrations
            </span>
          </div>
        </div>

        <!-- Custom Visual Bar Chart SVG -->
        <div class="h-64 w-full flex items-end justify-between gap-2 pt-4 px-2">
          <div v-for="(bar, i) in chartData" :key="i" class="flex-1 flex flex-col items-center gap-2 group relative">
            <!-- Tooltip -->
            <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-[10px] py-1 px-2 rounded-md pointer-events-none whitespace-nowrap z-10">
              {{ bar.month }}: {{ bar.val1 }} Services, {{ bar.val2 }} Users
            </div>
            <!-- Bar Stack -->
            <div class="w-full flex items-end justify-center gap-1 h-48">
              <div
                class="w-3 rounded-t-md bg-[#3858F9] group-hover:brightness-110 transition-all duration-300"
                :style="{ height: bar.val1 + '%' }"
              />
              <div
                class="w-3 rounded-t-md bg-[#EC4899] group-hover:brightness-110 transition-all duration-300"
                :style="{ height: bar.val2 + '%' }"
              />
            </div>
            <span class="text-[10px] text-slate-400 font-medium">{{ bar.month }}</span>
          </div>
        </div>
      </div>

      <!-- Right Side Chart: Service Status Distribution Gauge / Donut -->
      <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm flex flex-col justify-between">
        <div>
          <h2 class="text-base font-heading font-bold text-slate-900 dark:text-white">Catalog Status</h2>
          <p class="text-xs text-slate-400">Moderation breakdown</p>
        </div>

        <!-- Donut Chart Visual -->
        <div class="relative w-44 h-44 mx-auto my-4 flex items-center justify-center">
          <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
            <path
              class="text-slate-100 dark:text-slate-800"
              stroke-width="3.5"
              stroke="currentColor"
              fill="none"
              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
            />
            <path
              class="text-[#10B981]"
              stroke-width="3.5"
              stroke-dasharray="75, 100"
              stroke-linecap="round"
              stroke="currentColor"
              fill="none"
              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
            />
            <path
              class="text-[#F59E0B]"
              stroke-width="3.5"
              stroke-dasharray="20, 100"
              stroke-dashoffset="-75"
              stroke-linecap="round"
              stroke="currentColor"
              fill="none"
              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
            />
          </svg>
          <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
            <span class="text-2xl font-bold font-heading text-slate-900 dark:text-white">100%</span>
            <span class="text-[10px] text-slate-400 font-medium">Catalog Operational</span>
          </div>
        </div>

        <div class="space-y-2 text-xs">
          <div class="flex items-center justify-between">
            <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
              <span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span> Approved Services
            </span>
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ stats.approved_services || 2 }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
              <span class="w-2.5 h-2.5 rounded-full bg-[#F59E0B]"></span> Pending Approvals
            </span>
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ stats.pending_services || 0 }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
              <span class="w-2.5 h-2.5 rounded-full bg-[#EF4444]"></span> Rejected Services
            </span>
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ stats.rejected_services || 0 }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity Widgets (Valex style) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Pending Services -->
      <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
          <h2 class="text-base font-heading font-bold text-slate-900 dark:text-white">Pending Approvals</h2>
          <NuxtLink to="/services?status=pending" class="text-xs font-semibold text-primary hover:underline">View all →</NuxtLink>
        </div>
        <div v-if="recentServices.length" class="space-y-2.5">
          <div
            v-for="service in recentServices"
            :key="service.id"
            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
          >
            <div>
              <p class="text-xs font-semibold text-slate-800 dark:text-slate-200">{{ service.title }}</p>
              <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ service.user }} · {{ service.category }}</p>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-400">{{ service.created }}</span>
              <UiStatusBadge status="pending" />
            </div>
          </div>
        </div>
        <p v-else class="text-slate-400 text-xs py-6 text-center">No pending services 🎉</p>
      </div>

      <!-- Recent Users -->
      <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
          <h2 class="text-base font-heading font-bold text-slate-900 dark:text-white">Recent Users</h2>
          <NuxtLink to="/users" class="text-xs font-semibold text-primary hover:underline">View all →</NuxtLink>
        </div>
        <div v-if="recentUsers.length" class="space-y-2.5">
          <div
            v-for="user in recentUsers"
            :key="user.id"
            class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
          >
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-bold shadow-xs">
                {{ user.name.charAt(0) }}
              </div>
              <div>
                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200">{{ user.name }}</p>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">{{ user.email }}</p>
              </div>
            </div>
            <div class="text-right">
              <UiStatusBadge :status="user.role" :label="user.role" :dot="false" />
              <p class="text-[10px] text-slate-400 mt-1">{{ user.created }}</p>
            </div>
          </div>
        </div>
        <p v-else class="text-slate-400 text-xs py-6 text-center">No users registered yet</p>
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

const chartData = [
  { month: 'Jan', val1: 45, val2: 30 },
  { month: 'Feb', val1: 65, val2: 40 },
  { month: 'Mar', val1: 50, val2: 55 },
  { month: 'Apr', val1: 85, val2: 70 },
  { month: 'May', val1: 70, val2: 60 },
  { month: 'Jun', val1: 95, val2: 80 },
  { month: 'Jul', val1: 80, val2: 75 },
];

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
