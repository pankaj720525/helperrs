<template>
  <div class="space-y-6">

    <!-- Back button -->
    <div class="flex items-center gap-3">
      <NuxtLink to="/services" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-1.5">
        ← Back to Services
      </NuxtLink>
    </div>

    <div v-if="service" class="space-y-6">

      <!-- Header Card -->
      <div class="glass rounded-2xl p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-start gap-6">

          <!-- Image -->
          <div class="w-full md:w-56 h-40 rounded-xl overflow-hidden bg-surface-light flex-shrink-0">
            <img v-if="service.image" :src="service.image" :alt="service.title" class="w-full h-full object-cover" />
            <div v-else class="w-full h-full flex items-center justify-center text-5xl opacity-20">🔧</div>
          </div>

          <!-- Info -->
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4 flex-wrap mb-3">
              <div>
                <span class="text-xs px-2 py-1 rounded-full bg-primary/15 text-primary-light font-medium">
                  {{ service.category?.name }}
                </span>
                <h1 class="text-2xl font-heading font-bold text-white mt-2">{{ service.title }}</h1>
              </div>
              <UiStatusBadge :status="service.status" class="text-sm" />
            </div>

            <p class="text-slate-400 text-sm mb-4 line-clamp-3">{{ service.description || 'No description.' }}</p>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
              <div class="bg-white/3 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-1">Price Range</p>
                <p class="font-semibold text-white">
                  {{ service.price_min || service.price_max ? `₹${service.price_min || 0} – ₹${service.price_max || 0}` : 'Not set' }}
                </p>
              </div>
              <div class="bg-white/3 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-1">Rating</p>
                <p class="font-semibold text-white">
                  ⭐ {{ service.reviews_avg ? Number(service.reviews_avg).toFixed(1) : '—' }}
                </p>
              </div>
              <div class="bg-white/3 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-1">Reviews</p>
                <p class="font-semibold text-white">{{ service.reviews_count || 0 }}</p>
              </div>
              <div class="bg-white/3 rounded-xl p-3">
                <p class="text-xs text-slate-500 mb-1">Created</p>
                <p class="font-semibold text-white">{{ service.created_at?.split('T')[0] }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Rejection reason banner -->
        <div v-if="service.rejection_reason" class="mt-5 p-4 rounded-xl bg-danger/10 border border-danger/20">
          <p class="text-xs text-danger font-semibold mb-1">Rejection Reason</p>
          <p class="text-sm text-slate-300">{{ service.rejection_reason }}</p>
        </div>

        <!-- Action buttons -->
        <div v-if="service.status === 'pending'" class="mt-6 flex gap-3 flex-wrap">
          <button
            @click="approveService"
            :disabled="actionLoading"
            class="px-5 py-2.5 rounded-xl bg-success/15 text-success text-sm font-medium hover:bg-success/25 transition-all disabled:opacity-50"
          >
            ✅ Approve Service
          </button>
          <button
            @click="rejectModalOpen = true"
            :disabled="actionLoading"
            class="px-5 py-2.5 rounded-xl bg-danger/15 text-danger text-sm font-medium hover:bg-danger/25 transition-all disabled:opacity-50"
          >
            ❌ Reject Service
          </button>
        </div>
      </div>

      <!-- Worker Info -->
      <div class="glass rounded-2xl p-6">
        <h2 class="text-lg font-heading font-semibold text-white mb-4">Worker Information</h2>
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white font-semibold text-lg">
            {{ service.user?.name?.charAt(0) }}
          </div>
          <div>
            <p class="font-medium text-white">{{ service.user?.name }}</p>
            <p class="text-sm text-slate-400">{{ service.user?.email }}</p>
            <p class="text-xs text-slate-500 mt-0.5">
              {{ service.user?.worker_profile?.bio || 'No bio available' }}
            </p>
          </div>
        </div>
      </div>

      <!-- Reviews -->
      <div class="glass rounded-2xl p-6">
        <h2 class="text-lg font-heading font-semibold text-white mb-4">
          Reviews
          <span class="text-sm font-normal text-slate-400 ml-2">({{ service.reviews?.length || 0 }})</span>
        </h2>

        <div v-if="service.reviews?.length" class="space-y-3">
          <div
            v-for="review in service.reviews"
            :key="review.id"
            class="p-4 rounded-xl bg-white/3 border border-white/5"
          >
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-surface-lighter flex items-center justify-center text-xs text-white font-medium">
                  {{ review.user?.name?.charAt(0) }}
                </div>
                <span class="text-sm text-slate-200 font-medium">{{ review.user?.name }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-warning text-sm">{{ '★'.repeat(review.rating) }}{{ '☆'.repeat(5 - review.rating) }}</span>
                <span class="text-xs text-slate-500">{{ review.created_at?.split('T')[0] }}</span>
              </div>
            </div>
            <p class="text-sm text-slate-400">{{ review.comment || 'No comment.' }}</p>
          </div>
        </div>

        <p v-else class="text-slate-500 text-sm">No reviews yet.</p>
      </div>
    </div>

    <!-- Loading skeleton -->
    <div v-else class="glass rounded-2xl p-16 text-center text-slate-500">
      <div class="animate-pulse space-y-3">
        <div class="h-5 w-48 bg-white/10 rounded mx-auto" />
        <div class="h-3 w-64 bg-white/5 rounded mx-auto" />
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
        Provide a reason for rejecting "<strong>{{ service?.title }}</strong>":
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

const route = useRoute();
const api = useApi();
const toast = useToast();

const service = ref<any>(null);
const actionLoading = ref(false);
const rejectModalOpen = ref(false);
const rejectionReason = ref("");

const loadService = async () => {
  try {
    const data = await api.get<any>(`/admin/services/${route.params.id}`);
    service.value = data.service;
  } catch {
    toast.error("Failed to load service.");
    navigateTo("/services");
  }
};

const approveService = async () => {
  actionLoading.value = true;
  try {
    await api.put(`/admin/services/${route.params.id}/approve`);
    toast.success("Service approved successfully.");
    await loadService();
  } catch (e: any) {
    toast.error(e?.data?.message || "Failed to approve.");
  } finally {
    actionLoading.value = false;
  }
};

const rejectService = async () => {
  if (!rejectionReason.value.trim()) return;
  actionLoading.value = true;
  try {
    await api.put(`/admin/services/${route.params.id}/reject`, {
      rejection_reason: rejectionReason.value,
    });
    toast.success("Service rejected.");
    rejectModalOpen.value = false;
    await loadService();
  } catch (e: any) {
    toast.error(e?.data?.message || "Failed to reject.");
  } finally {
    actionLoading.value = false;
  }
};

onMounted(loadService);
</script>
