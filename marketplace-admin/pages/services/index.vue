<template>
  <div class="space-y-6">
    <!-- Filters -->
    <div class="glass rounded-2xl p-5 flex flex-wrap items-center gap-4">
      <input
        v-model="search"
        type="text"
        placeholder="Search services..."
        class="flex-1 min-w-[200px] px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm"
      />
      <select
        v-model="statusFilter"
        class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm"
      >
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
      </select>
      <button @click="loadServices" class="px-4 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all">
        Search
      </button>
    </div>

    <!-- Services Table -->
    <div class="glass rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-white/5">
              <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Service</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Worker</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Category</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Price Range</th>
              <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
              <th class="text-right px-5 py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="service in services"
              :key="service.id"
              class="border-b border-white/3 hover:bg-white/3 transition-colors"
            >
              <td class="px-5 py-4">
                <p class="font-medium text-slate-200">{{ service.title }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ service.created_at?.split('T')[0] }}</p>
              </td>
              <td class="px-5 py-4 text-slate-300">{{ service.user?.name || '-' }}</td>
              <td class="px-5 py-4 text-slate-300">{{ service.category?.name || '-' }}</td>
              <td class="px-5 py-4 text-slate-300">
                <span v-if="service.price_min || service.price_max">
                  ₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}
                </span>
                <span v-else class="text-slate-500">Not set</span>
              </td>
              <td class="px-5 py-4">
                <UiStatusBadge :status="service.status" />
              </td>
              <td class="px-5 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button
                    v-if="service.status === 'pending'"
                    @click="approveService(service)"
                    class="px-3 py-1.5 rounded-lg bg-success/15 text-success text-xs font-medium hover:bg-success/25 transition-colors"
                  >
                    Approve
                  </button>
                  <button
                    v-if="service.status === 'pending'"
                    @click="openRejectModal(service)"
                    class="px-3 py-1.5 rounded-lg bg-danger/15 text-danger text-xs font-medium hover:bg-danger/25 transition-colors"
                  >
                    Reject
                  </button>
                  <NuxtLink
                    :to="`/services/${service.id}`"
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

      <div v-if="!services.length" class="p-10 text-center text-slate-500">
        No services found.
      </div>

      <!-- Pagination -->
      <div v-if="meta.last_page > 1" class="flex items-center justify-between px-5 py-4 border-t border-white/5">
        <p class="text-xs text-slate-400">Page {{ meta.current_page }} of {{ meta.last_page }} ({{ meta.total }} total)</p>
        <div class="flex gap-2">
          <button
            @click="page--; loadServices()"
            :disabled="page <= 1"
            class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs disabled:opacity-30 hover:bg-white/10 transition-colors"
          >
            ← Prev
          </button>
          <button
            @click="page++; loadServices()"
            :disabled="page >= meta.last_page"
            class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs disabled:opacity-30 hover:bg-white/10 transition-colors"
          >
            Next →
          </button>
        </div>
      </div>
    </div>

    <!-- Reject Modal -->
    <UiModal
      v-model="rejectModalOpen"
      title="Reject Service"
      confirm-label="Reject"
      :confirm-danger="true"
      :confirm-disabled="!rejectionReason.trim()"
      @confirm="rejectService"
    >
      <p class="text-sm text-slate-300 mb-3">
        Please provide a reason for rejecting "<strong>{{ selectedService?.title }}</strong>":
      </p>
      <textarea
        v-model="rejectionReason"
        rows="4"
        placeholder="Enter rejection reason (required)..."
        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-danger/50 text-sm resize-none"
      />
    </UiModal>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const api = useApi();
const toast = useToast();

const services = ref<any[]>([]);
const meta = ref<any>({});
const search = ref("");
const statusFilter = ref("");
const page = ref(1);

const rejectModalOpen = ref(false);
const selectedService = ref<any>(null);
const rejectionReason = ref("");

const loadServices = async () => {
  try {
    const params: any = { page: page.value, per_page: 15 };
    if (search.value) params.search = search.value;
    if (statusFilter.value) params.status = statusFilter.value;

    const data = await api.get<any>("/admin/services", params);
    services.value = data.services || [];
    meta.value = data.meta || {};
  } catch (error) {
    toast.error("Failed to load services.");
  }
};

const approveService = async (service: any) => {
  try {
    await api.put(`/admin/services/${service.id}/approve`);
    toast.success(`"${service.title}" approved successfully.`);
    loadServices();
  } catch (error: any) {
    toast.error(error?.data?.message || "Failed to approve.");
  }
};

const openRejectModal = (service: any) => {
  selectedService.value = service;
  rejectionReason.value = "";
  rejectModalOpen.value = true;
};

const rejectService = async () => {
  if (!selectedService.value || !rejectionReason.value.trim()) return;

  try {
    await api.put(`/admin/services/${selectedService.value.id}/reject`, {
      rejection_reason: rejectionReason.value,
    });
    toast.success(`"${selectedService.value.title}" rejected.`);
    rejectModalOpen.value = false;
    loadServices();
  } catch (error: any) {
    toast.error(error?.data?.message || "Failed to reject.");
  }
};

onMounted(loadServices);
</script>
