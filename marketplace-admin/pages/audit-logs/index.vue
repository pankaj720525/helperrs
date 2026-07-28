<template>
  <div class="space-y-6">
    <!-- Page Header with Breadcrumbs -->
    <UiPageHeader
      title="System Audit Logs"
      description="Track administrative actions, moderation events, and security access logs."
    />

    <!-- Filter Toolbar -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-4 border border-[#EAEDF1] dark:border-[#334155] shadow-sm flex flex-wrap items-center gap-3">
      <select v-model="actionFilter" @change="loadLogs"
        class="flex-1 min-w-[200px] px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 focus:outline-none focus:border-primary text-xs">
        <option value="">All Administrative Actions</option>
        <option value="service_approved">Service Approved</option>
        <option value="service_rejected">Service Rejected</option>
        <option value="user_activated">User Activated</option>
        <option value="user_deactivated">User Deactivated</option>
        <option value="category_created">Category Created</option>
        <option value="setting_changed">Setting Changed</option>
      </select>
      <button @click="loadLogs" class="px-5 py-2 rounded-xl bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition-all shadow-xs">Filter Logs</button>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-[#EAEDF1] dark:border-[#334155] shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
          <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-[#EAEDF1] dark:border-[#334155]">
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Admin</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Action</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Target</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Changes</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">IP</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr v-for="log in logs" :key="log.id" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
              <td class="px-5 py-3.5 font-semibold text-slate-900 dark:text-slate-100">{{ log.admin?.name || '-' }}</td>
              <td class="px-5 py-3.5">
                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[11px] font-mono text-slate-700 dark:text-slate-300 font-semibold">{{ log.action }}</span>
              </td>
              <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ log.target_type }} #{{ log.target_id }}</td>
              <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400 max-w-xs truncate">
                <span v-if="log.old_values">{{ JSON.stringify(log.old_values) }} → {{ JSON.stringify(log.new_values) }}</span>
                <span v-else>-</span>
              </td>
              <td class="px-5 py-3.5 text-slate-400 font-mono">{{ log.ip_address }}</td>
              <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400">{{ log.created_at?.split('T')[0] }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="!logs.length" class="p-10 text-center text-slate-400 text-xs">No audit logs recorded yet.</div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });
const api = useApi();
const toast = useToast();
const logs = ref<any[]>([]);
const actionFilter = ref("");

const loadLogs = async () => {
  try {
    const params: any = { per_page: 30 };
    if (actionFilter.value) params.action = actionFilter.value;
    const data = await api.get<any>("/admin/audit-logs", params);
    logs.value = data.audit_logs || [];
  } catch { toast.error("Failed to load logs."); }
};

onMounted(loadLogs);
</script>
