<template>
  <div class="space-y-6">
    <!-- Page Header with Breadcrumbs -->
    <UiPageHeader
      title="Provider Subscriptions"
      description="Monitor worker membership plans, trial status, and manual plan extensions."
    />

    <!-- Filter Toolbar (All Filters Inline) -->
    <div class="bg-white dark:bg-[#121e2d] rounded-xl p-4 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs flex flex-wrap items-center gap-3">
      <input
        v-model="search"
        type="text"
        placeholder="Search by user name or email..."
        @keyup.enter="loadSubs"
        class="flex-1 min-w-[200px] px-4 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-100 placeholder:text-[#9da9bb] focus:outline-none focus:border-[#2c7be5] text-xs"
      />
      <select
        v-model="statusFilter"
        @change="loadSubs"
        class="px-3.5 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-200 focus:outline-none focus:border-[#2c7be5] text-xs font-medium"
      >
        <option value="">All Statuses</option>
        <option value="active">Active Only</option>
        <option value="expired">Expired Only</option>
      </select>
      <select
        v-model="planFilter"
        @change="loadSubs"
        class="px-3.5 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-200 focus:outline-none focus:border-[#2c7be5] text-xs font-medium"
      >
        <option value="">All Plans</option>
        <option value="trial">Trial Period</option>
        <option value="monthly">Monthly Plan</option>
        <option value="yearly">Yearly Plan</option>
      </select>
      <button
        @click="loadSubs"
        class="px-5 py-2 rounded-lg bg-[#2c7be5] text-white text-xs font-bold hover:bg-[#1665d8] transition-colors shadow-xs"
      >
        Search
      </button>
      <button
        v-if="search || statusFilter || planFilter"
        @click="clearFilters"
        class="px-3 py-2 rounded-lg text-xs font-semibold text-[#e63757] hover:bg-[#ffebe6] dark:hover:bg-[#e63757]/20 transition-colors"
      >
        Reset
      </button>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-[#EAEDF1] dark:border-[#334155] shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
          <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-[#EAEDF1] dark:border-[#334155]">
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">User</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Plan</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Period</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Days Left</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
              <th class="text-right px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr v-for="sub in subscriptions" :key="sub.id" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
              <td class="px-5 py-3.5 font-semibold text-slate-900 dark:text-slate-100">{{ sub.user?.name || '-' }}</td>
              <td class="px-5 py-3.5"><UiStatusBadge :status="sub.plan_type" :label="sub.plan_type" :dot="false" /></td>
              <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ sub.starts_at }} → {{ sub.expires_at }}</td>
              <td class="px-5 py-3.5 font-bold">
                <span :class="sub.days_remaining <= 7 ? 'text-rose-600' : sub.days_remaining <= 30 ? 'text-amber-600' : 'text-emerald-600'">
                  {{ sub.days_remaining }} days
                </span>
              </td>
              <td class="px-5 py-3.5"><UiStatusBadge :status="sub.is_expired ? 'expired' : 'active'" /></td>
              <td class="px-5 py-3.5 text-right">
                <button @click="openExtendModal(sub)" class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400 text-xs font-semibold hover:bg-blue-100 transition-colors">Extend</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="!subscriptions.length" class="p-10 text-center text-slate-400 text-xs">No subscriptions found.</div>
    </div>

    <!-- Extend Modal -->
    <UiModal v-model="extendModalOpen" title="Extend Subscription" confirm-label="Extend" @confirm="extendSub">
      <div class="space-y-4">
        <p class="text-xs text-slate-600 dark:text-slate-300">Extending subscription for <strong class="text-slate-900 dark:text-white">{{ selectedSub?.user?.name }}</strong></p>
        <div>
          <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Days to add</label>
          <input v-model.number="extendDays" type="number" min="1" max="365" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Admin Notes</label>
          <textarea v-model="extendNotes" rows="2" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs resize-none" placeholder="Optional notes..." />
        </div>
      </div>
    </UiModal>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });
const api = useApi();
const toast = useToast();

const showAdvanceFilters = ref(false);
const subscriptions = ref<any[]>([]);
const search = ref("");
const statusFilter = ref("");
const planFilter = ref("");
const extendModalOpen = ref(false);
const selectedSub = ref<any>(null);
const extendDays = ref(30);
const extendNotes = ref("");

const clearFilters = () => {
  search.value = "";
  statusFilter.value = "";
  planFilter.value = "";
  loadSubs();
};

const loadSubs = async () => {
  try {
    const params: any = { per_page: 20 };
    if (search.value) params.search = search.value;
    if (statusFilter.value) params.status = statusFilter.value;
    if (planFilter.value) params.plan_type = planFilter.value;
    const data = await api.get<any>("/admin/subscriptions", params);
    subscriptions.value = data.subscriptions || [];
  } catch { toast.error("Failed to load subscriptions."); }
};

const openExtendModal = (sub: any) => { selectedSub.value = sub; extendDays.value = 30; extendNotes.value = ""; extendModalOpen.value = true; };

const extendSub = async () => {
  if (!selectedSub.value) return;
  try {
    await api.put(`/admin/subscriptions/${selectedSub.value.id}/extend`, { days: extendDays.value, admin_notes: extendNotes.value });
    toast.success(`Extended by ${extendDays.value} days.`);
    extendModalOpen.value = false;
    loadSubs();
  } catch (e: any) { toast.error(e?.data?.message || "Failed."); }
};

onMounted(loadSubs);
</script>
