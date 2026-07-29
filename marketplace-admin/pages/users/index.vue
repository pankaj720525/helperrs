<template>
  <div class="space-y-6">
    <!-- Page Header with Breadcrumbs -->
    <UiPageHeader
      title="Users & Provider Management"
      description="Manage marketplace customers, worker accounts, roles, and status."
    />

    <!-- Filter Toolbar (All Filters Inline) -->
    <div class="bg-white dark:bg-[#121e2d] rounded-xl p-4 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs flex flex-wrap items-center gap-3">
      <input
        v-model="search"
        type="text"
        placeholder="Search by name, email, phone..."
        @keyup.enter="loadUsers"
        class="flex-1 min-w-[200px] px-4 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-100 placeholder:text-[#9da9bb] focus:outline-none focus:border-[#2c7be5] text-xs"
      />
      <select
        v-model="roleFilter"
        @change="loadUsers"
        class="px-3.5 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-200 focus:outline-none focus:border-[#2c7be5] text-xs font-medium"
      >
        <option value="">All Roles</option>
        <option value="user">Customer (User)</option>
        <option value="worker">Service Provider (Worker)</option>
        <option value="both">Both (Dual Account)</option>
      </select>
      <select
        v-model="statusFilterVal"
        @change="loadUsers"
        class="px-3.5 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-200 focus:outline-none focus:border-[#2c7be5] text-xs font-medium"
      >
        <option value="">All Status</option>
        <option value="active">Active Only</option>
        <option value="inactive">Inactive / Suspended</option>
      </select>
      <button
        @click="loadUsers"
        class="px-5 py-2 rounded-lg bg-[#2c7be5] text-white text-xs font-bold hover:bg-[#1665d8] transition-colors shadow-xs"
      >
        Search
      </button>
      <button
        v-if="search || roleFilter || statusFilterVal"
        @click="clearFilters"
        class="px-3 py-2 rounded-lg text-xs font-semibold text-[#e63757] hover:bg-[#ffebe6] dark:hover:bg-[#e63757]/20 transition-colors"
      >
        Reset
      </button>
    </div>

    <!-- Users Data Table -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-[#EAEDF1] dark:border-[#334155] shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
          <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-[#EAEDF1] dark:border-[#334155]">
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">User</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Role</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Notifications</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Joined</th>
              <th class="text-right px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr
              v-for="user in users"
              :key="user.id"
              class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors"
            >
              <td class="px-5 py-3.5">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-bold flex-shrink-0 shadow-xs">
                    {{ user.name?.charAt(0)?.toUpperCase() }}
                  </div>
                  <div>
                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ user.name }}</p>
                    <p class="text-[11px] text-slate-400">{{ user.email }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-3.5">
                <UiStatusBadge :status="user.role" :label="user.role" :dot="false" />
              </td>
              <td class="px-5 py-3.5">
                <UiStatusBadge :status="user.is_active ? 'active' : 'inactive'" />
              </td>
              <td class="px-5 py-3.5">
                <div class="flex gap-2">
                  <span class="text-[11px] font-medium" :class="user.admin_email_override ? 'text-rose-600' : 'text-emerald-600'">
                    📧 {{ user.admin_email_override ? 'Muted' : 'OK' }}
                  </span>
                  <span class="text-[11px] font-medium" :class="user.admin_push_override ? 'text-rose-600' : 'text-emerald-600'">
                    🔔 {{ user.admin_push_override ? 'Muted' : 'OK' }}
                  </span>
                </div>
              </td>
              <td class="px-5 py-3.5 text-slate-500 dark:text-slate-400 font-medium">{{ formatDate(user.created_at) }}</td>
              <td class="px-5 py-3.5 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button
                    @click="toggleStatus(user)"
                    class="px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors"
                    :class="user.is_active
                      ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 hover:bg-rose-100'
                      : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 hover:bg-emerald-100'"
                  >
                    {{ user.is_active ? 'Deactivate' : 'Activate' }}
                  </button>
                  <NuxtLink
                    :to="`/users/${user.id}`"
                    class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold hover:bg-slate-200 transition-colors"
                  >
                    View
                  </NuxtLink>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="!users.length" class="p-10 text-center text-slate-400 text-xs">No users found.</div>

      <!-- Pagination -->
      <div v-if="meta.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30">
        <p class="text-xs text-slate-500">Page {{ meta.current_page }} of {{ meta.last_page }}</p>
        <div class="flex gap-2">
          <button @click="page--; loadUsers()" :disabled="page <= 1" class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium disabled:opacity-40 hover:bg-slate-100">← Prev</button>
          <button @click="page++; loadUsers()" :disabled="page >= meta.last_page" class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium disabled:opacity-40 hover:bg-slate-100">Next →</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const api = useApi();
const toast = useToast();

const showAdvanceFilters = ref(false);
const users = ref<any[]>([]);
const meta = ref<any>({});
const search = ref("");
const roleFilter = ref("");
const statusFilterVal = ref("");
const page = ref(1);

const clearFilters = () => {
  search.value = "";
  roleFilter.value = "";
  statusFilterVal.value = "";
  loadUsers();
};

const loadUsers = async () => {
  try {
    const params: any = { page: page.value, per_page: 15 };
    if (search.value) params.search = search.value;
    if (roleFilter.value) params.role = roleFilter.value;
    if (statusFilterVal.value) params.status = statusFilterVal.value;

    const data = await api.get<any>("/admin/users", params);
    users.value = data.users || [];
    meta.value = data.meta || {};
  } catch {
    toast.error("Failed to load users.");
  }
};

const toggleStatus = async (user: any) => {
  try {
    await api.put(`/admin/users/${user.id}/toggle-status`);
    toast.success(`${user.name} ${user.is_active ? 'deactivated' : 'activated'}.`);
    loadUsers();
  } catch {
    toast.error("Failed to update status.");
  }
};

onMounted(loadUsers);
</script>
