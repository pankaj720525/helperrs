<template>
  <div class="space-y-6">
    <!-- Page Header with Breadcrumbs -->
    <UiPageHeader
      title="Customer Reviews Moderation"
      description="Inspect ratings, approve feedback, and moderate reported reviews."
    />

    <!-- Filter Toolbar -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl p-4 border border-[#EAEDF1] dark:border-[#334155] shadow-sm flex flex-wrap items-center gap-3">
      <input
        v-model="search"
        type="text"
        placeholder="Search reviews by user or comment..."
        @keyup.enter="loadReviews"
        class="flex-1 min-w-[200px] px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-primary text-xs"
      />
      <select
        v-model="moderatedFilter"
        @change="loadReviews"
        class="px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 focus:outline-none focus:border-primary text-xs"
      >
        <option value="">All Moderation Status</option>
        <option value="no">Unmoderated Only</option>
        <option value="yes">Moderated Only</option>
      </select>
      <button @click="loadReviews" class="px-5 py-2 rounded-xl bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition-all shadow-xs">
        Search
      </button>
    </div>

    <!-- Reviews Data Table -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-[#EAEDF1] dark:border-[#334155] shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
          <thead>
            <tr class="bg-slate-50 dark:bg-slate-900/60 border-b border-[#EAEDF1] dark:border-[#334155]">
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Reviewer</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Service</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Rating</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Comment</th>
              <th class="px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
              <th class="text-right px-5 py-3.5 font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <tr v-for="r in reviews" :key="r.id" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
              <td class="px-5 py-3.5 font-semibold text-slate-900 dark:text-slate-100">{{ r.user?.name || '-' }}</td>
              <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300 font-medium">{{ r.service?.title || '-' }}</td>
              <td class="px-5 py-3.5">
                <span class="text-amber-500 font-bold">{{ '★'.repeat(r.rating) }}{{ '☆'.repeat(5 - r.rating) }}</span>
              </td>
              <td class="px-5 py-3.5 text-slate-600 dark:text-slate-400 max-w-xs truncate">{{ r.comment || '-' }}</td>
              <td class="px-5 py-3.5"><UiStatusBadge :status="r.is_moderated ? 'approved' : 'pending'" :label="r.is_moderated ? 'Moderated' : 'Pending'" /></td>
              <td class="px-5 py-3.5 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button v-if="!r.is_moderated" @click="confirmModerate(r)" class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 text-xs font-semibold hover:bg-emerald-100 transition-colors">Approve</button>
                  <button @click="confirmDelete(r)" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 text-xs font-semibold hover:bg-rose-100 transition-colors">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="!reviews.length" class="p-10 text-center text-slate-400 text-xs">No reviews found.</div>
    </div>

    <!-- Moderate Confirm -->
    <UiModal v-model="moderateModal" title="Approve Review" confirm-label="Approve" @confirm="moderate">
      <p class="text-xs text-slate-600 dark:text-slate-300">Approve the review from <strong class="text-slate-900 dark:text-white">{{ selectedReview?.user?.name }}</strong>?</p>
    </UiModal>

    <!-- Delete Confirm -->
    <UiModal v-model="deleteModal" title="Delete Review" confirm-label="Delete" :confirm-danger="true" @confirm="deleteReview">
      <p class="text-xs text-slate-600 dark:text-slate-300">Permanently delete this review from <strong class="text-slate-900 dark:text-white">{{ selectedReview?.user?.name }}</strong>? This cannot be undone.</p>
    </UiModal>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });
const api = useApi();
const toast = useToast();

const reviews = ref<any[]>([]);
const search = ref("");
const moderatedFilter = ref("");
const selectedReview = ref<any>(null);
const moderateModal = ref(false);
const deleteModal = ref(false);

const loadReviews = async () => {
  try {
    const params: any = { per_page: 20 };
    if (search.value) params.search = search.value;
    if (moderatedFilter.value) params.moderated = moderatedFilter.value;
    const data = await api.get<any>("/admin/reviews", params);
    reviews.value = data.reviews || [];
  } catch { toast.error("Failed to load reviews."); }
};

const confirmModerate = (r: any) => { selectedReview.value = r; moderateModal.value = true; };
const confirmDelete = (r: any) => { selectedReview.value = r; deleteModal.value = true; };

const moderate = async () => {
  if (!selectedReview.value) return;
  try {
    await api.put(`/admin/reviews/${selectedReview.value.id}/moderate`);
    toast.success("Review approved.");
    moderateModal.value = false;
    loadReviews();
  } catch { toast.error("Failed."); }
};

const deleteReview = async () => {
  if (!selectedReview.value) return;
  try {
    await api.delete(`/admin/reviews/${selectedReview.value.id}`);
    toast.success("Review deleted.");
    deleteModal.value = false;
    loadReviews();
  } catch { toast.error("Failed."); }
};

onMounted(loadReviews);
</script>
