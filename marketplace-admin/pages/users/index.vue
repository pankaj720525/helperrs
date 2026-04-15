<template>
  <div class="space-y-6">
    <!-- Filters -->
    <div class="glass rounded-2xl p-5 flex flex-wrap items-center gap-4">
      <input
        v-model="search"
        type="text"
        placeholder="Search by name, email, phone..."
        class="flex-1 min-w-[200px] px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm"
      />
      <select
        v-model="roleFilter"
        class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm"
      >
        <option value="">All Roles</option>
        <option value="user">User</option>
        <option value="worker">Worker</option>
        <option value="both">Both</option>
      </select>
      <select
        v-model="statusFilterVal"
        class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm"
      >
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>
      <button @click="loadUsers" class="px-4 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all">
        Search
      </button>
    </div>

    <!-- Users Table -->
    <div class="glass rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-white/5">
              <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">User</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Role</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Notifications</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Joined</th>
              <th class="text-right px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="user in users"
              :key="user.id"
              class="border-b border-white/3 hover:bg-white/3 transition-colors"
            >
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                    {{ user.name?.charAt(0)?.toUpperCase() }}
                  </div>
                  <div>
                    <p class="font-medium text-slate-200">{{ user.name }}</p>
                    <p class="text-xs text-slate-400">{{ user.email }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4">
                <UiStatusBadge :status="user.role" :label="user.role" :dot="false" />
              </td>
              <td class="px-5 py-4">
                <UiStatusBadge :status="user.is_active ? 'active' : 'inactive'" />
              </td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <span class="text-xs" :class="user.admin_email_override ? 'text-danger' : 'text-success'">
                    📧 {{ user.admin_email_override ? 'Muted' : 'OK' }}
                  </span>
                  <span class="text-xs" :class="user.admin_push_override ? 'text-danger' : 'text-success'">
                    🔔 {{ user.admin_push_override ? 'Muted' : 'OK' }}
                  </span>
                </div>
              </td>
              <td class="px-5 py-4 text-slate-400 text-xs">{{ user.created_at?.split('T')[0] }}</td>
              <td class="px-5 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button
                    @click="toggleStatus(user)"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    :class="user.is_active
                      ? 'bg-danger/15 text-danger hover:bg-danger/25'
                      : 'bg-success/15 text-success hover:bg-success/25'"
                  >
                    {{ user.is_active ? 'Deactivate' : 'Activate' }}
                  </button>
                  <NuxtLink
                    :to="`/users/${user.id}`"
                    class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs font-medium hover:bg-white/10 transition-colors"
                  >
                    View
                  </NuxtLink>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="!users.length" class="p-10 text-center text-slate-500">No users found.</div>

      <!-- Pagination -->
      <div v-if="meta.last_page > 1" class="flex items-center justify-between px-5 py-4 border-t border-white/5">
        <p class="text-xs text-slate-400">Page {{ meta.current_page }} of {{ meta.last_page }}</p>
        <div class="flex gap-2">
          <button @click="page--; loadUsers()" :disabled="page <= 1" class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs disabled:opacity-30 hover:bg-white/10">← Prev</button>
          <button @click="page++; loadUsers()" :disabled="page >= meta.last_page" class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs disabled:opacity-30 hover:bg-white/10">Next →</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const api = useApi();
const toast = useToast();

const users = ref<any[]>([]);
const meta = ref<any>({});
const search = ref("");
const roleFilter = ref("");
const statusFilterVal = ref("");
const page = ref(1);

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
