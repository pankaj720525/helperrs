<template>
  <div class="space-y-6">
    <div class="glass rounded-2xl p-5 flex flex-wrap items-center gap-4">
      <input v-model="search" type="text" placeholder="Search reviews..."
        class="flex-1 min-w-[200px] px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" />
      <select v-model="moderatedFilter"
        class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm">
        <option value="">All</option>
        <option value="no">Unmoderated</option>
        <option value="yes">Moderated</option>
      </select>
      <button @click="loadReviews" class="px-4 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all">Search</button>
    </div>

    <div class="glass rounded-2xl overflow-hidden">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-white/5">
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Reviewer</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Service</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Rating</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Comment</th>
            <th class="text-left px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Status</th>
            <th class="text-right px-5 py-4 text-xs font-semibold text-slate-400 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in reviews" :key="r.id" class="border-b border-white/3 hover:bg-white/3 transition-colors">
            <td class="px-5 py-4 text-slate-200">{{ r.user?.name || '-' }}</td>
            <td class="px-5 py-4 text-slate-300">{{ r.service?.title || '-' }}</td>
            <td class="px-5 py-4">
              <span class="text-warning">{{ '★'.repeat(r.rating) }}{{ '☆'.repeat(5 - r.rating) }}</span>
            </td>
            <td class="px-5 py-4 text-slate-400 max-w-xs truncate">{{ r.comment || '-' }}</td>
            <td class="px-5 py-4"><UiStatusBadge :status="r.is_moderated ? 'approved' : 'pending'" :label="r.is_moderated ? 'Moderated' : 'Pending'" /></td>
            <td class="px-5 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <button v-if="!r.is_moderated" @click="confirmModerate(r)" class="px-3 py-1.5 rounded-lg bg-success/15 text-success text-xs font-medium hover:bg-success/25 transition-colors">Approve</button>
                <button @click="confirmDelete(r)" class="px-3 py-1.5 rounded-lg bg-danger/15 text-danger text-xs font-medium hover:bg-danger/25 transition-colors">Delete</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!reviews.length" class="p-10 text-center text-slate-500">No reviews found.</div>
    </div>

    <!-- Moderate Confirm -->
    <UiModal v-model="moderateModal" title="Approve Review" confirm-label="Approve" @confirm="moderate">
      <p class="text-sm text-slate-300">Approve the review from <strong class="text-white">{{ selectedReview?.user?.name }}</strong>?</p>
    </UiModal>

    <!-- Delete Confirm -->
    <UiModal v-model="deleteModal" title="Delete Review" confirm-label="Delete" :confirm-danger="true" @confirm="deleteReview">
      <p class="text-sm text-slate-300">Permanently delete this review from <strong class="text-white">{{ selectedReview?.user?.name }}</strong>? This cannot be undone.</p>
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
    loadReviews();
  } catch { toast.error("Failed."); }
};

const deleteReview = async () => {
  if (!selectedReview.value) return;
  try {
    await api.delete(`/admin/reviews/${selectedReview.value.id}`);
    toast.success("Review deleted.");
    loadReviews();
  } catch { toast.error("Failed."); }
};

onMounted(loadReviews);
</script>
