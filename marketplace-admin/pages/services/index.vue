<template>
  <div class="space-y-6">
    <!-- Page Header with Breadcrumbs -->
    <UiPageHeader
      title="Services Catalog"
      description="Manage, review, and approve worker service listings across all categories."
    />

    <!-- Filter Toolbar (Inline with Category & Price Filters) -->
    <div class="bg-white dark:bg-[#121e2d] rounded-xl p-4 border border-[#edf2f9] dark:border-[#1e2c40] shadow-xs flex flex-wrap items-center gap-3">
      <input
        v-model="search"
        type="text"
        placeholder="Search title or provider..."
        @keyup.enter="loadServices"
        class="flex-1 min-w-[180px] px-3.5 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-100 placeholder:text-[#9da9bb] focus:outline-none focus:border-[#2c7be5] text-xs"
      />

      <!-- Category Filter Dropdown -->
      <select
        v-model="categoryFilter"
        @change="loadServices"
        class="px-3 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-200 focus:outline-none focus:border-[#2c7be5] text-xs font-medium"
      >
        <option value="">All Categories</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
          {{ cat.name }}
        </option>
      </select>

      <!-- Status Filter Dropdown -->
      <select
        v-model="statusFilter"
        @change="loadServices"
        class="px-3 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-200 focus:outline-none focus:border-[#2c7be5] text-xs font-medium"
      >
        <option value="">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
      </select>

      <!-- Min Price Filter Input -->
      <input
        v-model="minPrice"
        type="number"
        placeholder="Min Price (₹)"
        @keyup.enter="loadServices"
        class="w-28 px-3 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-100 placeholder:text-[#9da9bb] focus:outline-none focus:border-[#2c7be5] text-xs"
      />

      <!-- Max Price Filter Input -->
      <input
        v-model="maxPrice"
        type="number"
        placeholder="Max Price (₹)"
        @keyup.enter="loadServices"
        class="w-28 px-3 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-100 placeholder:text-[#9da9bb] focus:outline-none focus:border-[#2c7be5] text-xs"
      />

      <!-- Sort By Dropdown -->
      <select
        v-model="sortBy"
        @change="loadServices"
        class="px-3 py-2 rounded-lg bg-slate-50/80 dark:bg-[#0b1727] border border-[#d8e2ef] dark:border-[#1e2c40] text-[#12263f] dark:text-slate-200 focus:outline-none focus:border-[#2c7be5] text-xs font-medium"
      >
        <option value="newest">Newest First</option>
        <option value="oldest">Oldest First</option>
        <option value="title">Title (A-Z)</option>
      </select>

      <button
        @click="loadServices"
        class="px-4 py-2 rounded-lg bg-[#2c7be5] text-white text-xs font-bold hover:bg-[#1665d8] transition-colors shadow-xs"
      >
        Search
      </button>

      <button
        v-if="search || categoryFilter || statusFilter || minPrice || maxPrice || sortBy !== 'newest'"
        @click="clearFilters"
        class="px-3 py-2 rounded-lg text-xs font-semibold text-[#e63757] hover:bg-[#ffebe6] dark:hover:bg-[#e63757]/20 transition-colors"
      >
        Reset
      </button>
    </div>

    <!-- Services Table -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-[#EAEDF1] dark:border-[#334155] shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
          <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-[#EAEDF1] dark:border-[#334155]">
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Service</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Worker</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Category</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">City</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Price Range</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Rating</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
              <th class="text-right px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr
              v-for="service in services"
              :key="service.id"
              class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors"
            >
              <td class="px-5 py-3.5">
                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ service.title }}</p>
                <div class="flex items-center gap-1.5 flex-wrap mt-1">
                  <p class="text-[11px] text-slate-400 font-medium">🕒 {{ formatDate(service.created_at) }}</p>
                  <span v-if="service.parent_id" class="px-1.5 py-0.5 rounded bg-amber-100 dark:bg-amber-950/40 text-amber-800 dark:text-amber-400 text-[9px] font-extrabold uppercase">
                    Update Request
                  </span>
                </div>
              </td>
              <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300 font-medium">{{ service.user?.name || '-' }}</td>
              <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300 font-medium">{{ service.category?.name || '-' }}</td>
              <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300 font-medium">
                <span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded text-[11px] text-slate-700 dark:text-slate-300">
                  📍 {{ service.city || service.user?.worker_profile?.address || 'Mumbai' }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300">
                <span v-if="service.price_min || service.price_max" class="font-medium">
                  ₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}
                </span>
                <span v-else class="text-slate-400">Not set</span>
              </td>
              <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300">
                <div class="flex items-center gap-1 text-xs font-bold text-[#f5803e]">
                  <span>⭐</span>
                  <span>{{ service.reviews_avg ? Number(service.reviews_avg).toFixed(1) : '0.0' }}</span>
                  <span class="text-[10px] text-slate-400 font-normal">({{ service.reviews_count || 0 }})</span>
                </div>
              </td>
              <td class="px-5 py-3.5">
                <UiStatusBadge :status="service.status" />
              </td>
              <td class="px-5 py-3.5 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button
                    v-if="service.status === 'pending'"
                    @click="confirmApprove(service)"
                    class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 text-xs font-semibold hover:bg-emerald-100 transition-colors"
                  >
                    Approve
                  </button>
                  <button
                    v-if="service.status === 'pending'"
                    @click="openRejectModal(service)"
                    class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 text-xs font-semibold hover:bg-rose-100 transition-colors"
                  >
                    Reject
                  </button>
                  <NuxtLink
                    :to="`/services/${service.id}`"
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

      <div v-if="!services.length" class="p-10 text-center text-slate-400 text-xs">
        No services found matching filters.
      </div>

      <!-- Pagination -->
      <div v-if="meta.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30">
        <p class="text-xs text-slate-500">Page {{ meta.current_page }} of {{ meta.last_page }} ({{ meta.total }} total)</p>
        <div class="flex gap-2">
          <button
            @click="page--; loadServices()"
            :disabled="page <= 1"
            class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium disabled:opacity-40 hover:bg-slate-100 transition-colors"
          >
            ← Prev
          </button>
          <button
            @click="page++; loadServices()"
            :disabled="page >= meta.last_page"
            class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-medium disabled:opacity-40 hover:bg-slate-100 transition-colors"
          >
            Next →
          </button>
        </div>
      </div>
    </div>

    <!-- Approve Confirm Modal -->
    <UiModal
      v-model="approveModalOpen"
      title="Approve Service Listing"
      confirm-label="Approve Listing"
      @confirm="approveService"
    >
      <p class="text-xs text-slate-600 dark:text-slate-300">
        Are you sure you want to approve
        <strong class="text-slate-900 dark:text-white">"{{ pendingApproveService?.title }}"</strong>?
        It will be published live on the customer marketplace.
      </p>
    </UiModal>

    <!-- Reject Confirm Modal -->
    <UiModal
      v-model="rejectModalOpen"
      title="Reject Service Listing"
      confirm-label="Reject Listing"
      :confirm-danger="true"
      :confirm-disabled="!rejectionReason.trim()"
      @confirm="rejectService"
    >
      <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">
        Please specify a rejection reason for "<strong>{{ selectedService?.title }}</strong>":
      </p>
      <textarea
        v-model="rejectionReason"
        rows="4"
        placeholder="Enter reason for rejection..."
        class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-danger text-xs resize-none"
      />
    </UiModal>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const route = useRoute();
const api = useApi();
const toast = useToast();

const services = ref<any[]>([]);
const categories = ref<any[]>([]);
const meta = ref<any>({});
const search = ref((route.query.search as string) || "");
const categoryFilter = ref("");
const statusFilter = ref((route.query.status as string) || "");
const minPrice = ref("");
const maxPrice = ref("");
const sortBy = ref("newest");
const page = ref(1);

const rejectModalOpen = ref(false);
const selectedService = ref<any>(null);
const rejectionReason = ref("");

const clearFilters = () => {
  search.value = "";
  categoryFilter.value = "";
  statusFilter.value = "";
  minPrice.value = "";
  maxPrice.value = "";
  sortBy.value = "newest";
  loadServices();
};

const loadCategories = async () => {
  try {
    const data = await api.get<any>("/admin/categories");
    categories.value = data.categories || data || [];
  } catch (e) {}
};

const loadServices = async () => {
  try {
    const params: any = { page: page.value, per_page: 15 };
    if (search.value) params.search = search.value;
    if (categoryFilter.value) params.category_id = categoryFilter.value;
    if (statusFilter.value) params.status = statusFilter.value;
    if (minPrice.value) params.min_price = minPrice.value;
    if (maxPrice.value) params.max_price = maxPrice.value;
    if (sortBy.value) params.sort = sortBy.value;

    const data = await api.get<any>("/admin/services", params);
    services.value = data.services || [];
    meta.value = data.meta || {};
  } catch (error) {
    toast.error("Failed to load services catalog.");
  }
};

const approveModalOpen = ref(false);
const pendingApproveService = ref<any>(null);

const confirmApprove = (service: any) => {
  pendingApproveService.value = service;
  approveModalOpen.value = true;
};

const approveService = async () => {
  const service = pendingApproveService.value;
  if (!service) return;
  try {
    await api.put(`/admin/services/${service.id}/approve`);
    toast.success(`"${service.title}" approved successfully.`);
    approveModalOpen.value = false;
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

onMounted(() => {
  loadCategories();
  loadServices();
});
</script>
