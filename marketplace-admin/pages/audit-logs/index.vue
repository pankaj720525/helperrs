<template>
  <div class="space-y-6">
    <div class="glass rounded-2xl p-5 flex flex-wrap items-center gap-4">
      <select v-model="actionFilter"
        class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm">
        <option value="">All Actions</option>
        <option value="service_approved">Service Approved</option>
        <option value="service_rejected">Service Rejected</option>
        <option value="user_activated">User Activated</option>
        <option value="user_deactivated">User Deactivated</option>
        <option value="category_created">Category Created</option>
        <option value="setting_changed">Setting Changed</option>
      </select>
      <button @click="loadLogs" class="px-4 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all">Filter</button>
    </div>

    <div class="glass rounded-2xl overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-white/5">
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Admin</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Action</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Target</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Changes</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">IP</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="log in logs" :key="log.id" class="border-b border-white/3 hover:bg-white/3 transition-colors">
            <td class="px-5 py-4 text-slate-200">{{ log.admin?.name || '-' }}</td>
            <td class="px-5 py-4">
              <span class="px-2 py-1 rounded-lg bg-white/5 text-xs font-mono text-slate-300">{{ log.action }}</span>
            </td>
            <td class="px-5 py-4 text-slate-400 text-xs">{{ log.target_type }} #{{ log.target_id }}</td>
            <td class="px-5 py-4 text-xs text-slate-400 max-w-xs truncate">
              <span v-if="log.old_values">{{ JSON.stringify(log.old_values) }} → {{ JSON.stringify(log.new_values) }}</span>
              <span v-else>-</span>
            </td>
            <td class="px-5 py-4 text-slate-500 text-xs font-mono">{{ log.ip_address }}</td>
            <td class="px-5 py-4 text-slate-500 text-xs">{{ log.created_at?.split('T')[0] }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="!logs.length" class="p-10 text-center text-slate-500">No audit logs found.</div>
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
