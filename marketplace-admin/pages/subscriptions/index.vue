<template>
  <div class="space-y-6">
    <div class="glass rounded-2xl p-5 flex flex-wrap items-center gap-4">
      <input v-model="search" type="text" placeholder="Search by user name or email..."
        class="flex-1 min-w-[200px] px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" />
      <select v-model="statusFilter"
        class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm">
        <option value="">All</option>
        <option value="active">Active</option>
        <option value="expired">Expired</option>
      </select>
      <select v-model="planFilter"
        class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm">
        <option value="">All Plans</option>
        <option value="trial">Trial</option>
        <option value="monthly">Monthly</option>
        <option value="yearly">Yearly</option>
      </select>
      <button @click="loadSubs" class="px-4 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all">Search</button>
    </div>

    <div class="glass rounded-2xl overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-white/5">
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">User</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Plan</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Period</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Days Left</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Status</th>
            <th class="text-right px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sub in subscriptions" :key="sub.id" class="border-b border-white/3 hover:bg-white/3 transition-colors">
            <td class="px-5 py-4 text-slate-200">{{ sub.user?.name || '-' }}</td>
            <td class="px-5 py-4"><UiStatusBadge :status="sub.plan_type" :label="sub.plan_type" :dot="false" /></td>
            <td class="px-5 py-4 text-slate-400 text-xs">{{ sub.starts_at }} → {{ sub.expires_at }}</td>
            <td class="px-5 py-4">
              <span :class="sub.days_remaining <= 7 ? 'text-danger font-semibold' : sub.days_remaining <= 30 ? 'text-warning' : 'text-success'">
                {{ sub.days_remaining }} days
              </span>
            </td>
            <td class="px-5 py-4"><UiStatusBadge :status="sub.is_expired ? 'expired' : 'active'" /></td>
            <td class="px-5 py-4 text-right">
              <button @click="openExtendModal(sub)" class="px-3 py-1.5 rounded-lg bg-info/15 text-info text-xs font-medium hover:bg-info/25 transition-colors">Extend</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!subscriptions.length" class="p-10 text-center text-slate-500">No subscriptions found.</div>
    </div>

    <!-- Extend Modal -->
    <UiModal v-model="extendModalOpen" title="Extend Subscription" confirm-label="Extend" @confirm="extendSub">
      <div class="space-y-4">
        <p class="text-sm text-slate-300">Extending subscription for <strong class="text-white">{{ selectedSub?.user?.name }}</strong></p>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-1.5">Days to add</label>
          <input v-model.number="extendDays" type="number" min="1" max="365" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-1.5">Admin Notes</label>
          <textarea v-model="extendNotes" rows="2" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm resize-none" placeholder="Optional notes..." />
        </div>
      </div>
    </UiModal>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });
const api = useApi();
const toast = useToast();

const subscriptions = ref<any[]>([]);
const search = ref(""); const statusFilter = ref(""); const planFilter = ref("");
const extendModalOpen = ref(false);
const selectedSub = ref<any>(null);
const extendDays = ref(30);
const extendNotes = ref("");

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
