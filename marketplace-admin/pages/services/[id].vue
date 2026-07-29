<template>
  <div class="space-y-6">
    <!-- Back link -->
    <div class="flex items-center gap-3">
      <NuxtLink to="/services" class="text-xs font-semibold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
        ← Back to Services
      </NuxtLink>
    </div>

    <div v-if="service" class="space-y-6">
      <!-- Comparison Card (If service has a parent, i.e., it's a pending change request) -->
      <div v-if="service.parent" class="bg-white dark:bg-[#1E293B] rounded-xl p-6 border border-amber-200 dark:border-amber-900 shadow-sm bg-gradient-to-r from-amber-50/50 to-white dark:from-amber-950/20 dark:to-[#1E293B]">
        <h2 class="text-sm font-heading font-extrabold text-amber-800 dark:text-amber-400 mb-4 uppercase tracking-wider flex items-center gap-1.5">
          ⚠️ Proposed Updates for Existing Approved Service
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Current Approved Version (Left) -->
          <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30 space-y-4">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Current Approved Version</h3>
            
            <div class="flex items-start gap-4">
              <div class="w-20 h-20 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0 border border-slate-200 dark:border-slate-700">
                <img v-if="service.parent.image" :src="service.parent.image" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-3xl">🔧</div>
              </div>
              <div class="space-y-1 min-w-0">
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300 font-bold">
                  {{ service.parent.category?.name || 'Category' }}
                </span>
                <h4 class="font-bold text-slate-900 dark:text-white text-sm mt-1 truncate">{{ service.parent.title }}</h4>
                <p class="font-extrabold text-slate-700 dark:text-slate-300 text-xs">
                  ₹{{ service.parent.price_min || 0 }} – ₹{{ service.parent.price_max || 0 }}
                </p>
              </div>
            </div>
            
            <div>
              <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Description</p>
              <p class="text-slate-600 dark:text-slate-300 text-xs leading-relaxed whitespace-pre-line">
                {{ service.parent.description || 'No description.' }}
              </p>
            </div>
          </div>

          <!-- Proposed New Version (Right) -->
          <div class="p-4 rounded-xl border border-amber-300 dark:border-amber-800 bg-amber-50/20 dark:bg-amber-950/10 space-y-4">
            <h3 class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Proposed Changes</h3>
            
            <div class="flex items-start gap-4">
              <div class="w-20 h-20 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0 border border-slate-200 dark:border-slate-700" :class="{'border-amber-500': service.parent.image !== service.image}">
                <img v-if="service.image" :src="service.image" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-3xl">🔧</div>
              </div>
              <div class="space-y-1 min-w-0">
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-400 font-bold" :class="{'bg-amber-100 text-amber-800 border border-amber-200': service.parent.category?.id !== service.category?.id}">
                  {{ service.category?.name }}
                </span>
                <h4 class="font-bold text-slate-900 dark:text-white text-sm mt-1 truncate" :class="{'text-amber-600 dark:text-amber-400 font-extrabold': service.parent.title !== service.title}">
                  {{ service.title }}
                </h4>
                <p class="font-extrabold text-slate-700 dark:text-slate-300 text-xs" :class="{'text-rose-600 dark:text-rose-400 font-black scale-105': service.parent.price_min !== service.price_min || service.parent.price_max !== service.price_max}">
                  ₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}
                </p>
              </div>
            </div>
            
            <div>
              <p class="text-[10px] font-bold text-amber-500 uppercase mb-1">Description</p>
              <p class="text-slate-600 dark:text-slate-300 text-xs leading-relaxed whitespace-pre-line" :class="{'text-slate-900 dark:text-slate-100 font-medium bg-amber-50/50 p-2 rounded-lg border border-amber-100': service.parent.description !== service.description}">
                {{ service.description || 'No description.' }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Header Card -->
      <div class="bg-white dark:bg-[#1E293B] rounded-xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
        <div class="flex flex-col md:flex-row md:items-start gap-6">
          <!-- Image -->
          <div class="w-full md:w-56 h-40 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0 border border-slate-200 dark:border-slate-700">
            <img v-if="service.image" :src="service.image" :alt="service.title" class="w-full h-full object-cover" />
            <div v-else class="w-full h-full flex items-center justify-center text-5xl opacity-20">🔧</div>
          </div>

          <!-- Info -->
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4 flex-wrap mb-3">
              <div>
                <span class="text-[11px] px-2.5 py-0.5 rounded-full bg-[#EEF2FF] text-[#3858F9] dark:bg-indigo-950/60 dark:text-indigo-400 font-bold">
                  {{ service.category?.name }}
                </span>
                <h1 class="text-xl font-heading font-bold text-slate-900 dark:text-white mt-2">{{ service.title }}</h1>
              </div>
              <UiStatusBadge :status="service.status" class="text-xs" />
            </div>

            <p class="text-slate-600 dark:text-slate-300 text-xs mb-4 leading-relaxed">{{ service.description || 'No description.' }}</p>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs">
              <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] text-slate-400 mb-0.5">Price Range</p>
                <p class="font-bold text-slate-900 dark:text-white">
                  {{ service.price_min || service.price_max ? `₹${service.price_min || 0} – ₹${service.price_max || 0}` : 'Not set' }}
                </p>
              </div>
              <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] text-slate-400 mb-0.5">City / Location</p>
                <p class="font-bold text-[#2c7be5] truncate">
                  📍 {{ service.city || service.user?.worker_profile?.address || 'Mumbai' }}
                </p>
              </div>
              <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] text-slate-400 mb-0.5">Rating</p>
                <p class="font-bold text-[#f5803e]">
                  ⭐ {{ service.reviews_avg ? Number(service.reviews_avg).toFixed(1) : '0.0' }}
                </p>
              </div>
              <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] text-slate-400 mb-0.5">Reviews</p>
                <p class="font-bold text-slate-900 dark:text-white">{{ service.reviews_count || service.reviews?.length || 0 }}</p>
              </div>
              <div class="bg-slate-50 dark:bg-slate-800/60 rounded-lg p-3 border border-slate-100 dark:border-slate-800">
                <p class="text-[11px] text-slate-400 mb-0.5">Created Date & Time</p>
                <p class="font-bold text-slate-900 dark:text-white text-[11px]">🕒 {{ formatDate(service.created_at) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Rejection reason banner -->
        <div v-if="service.rejection_reason" class="mt-5 p-4 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800">
          <p class="text-xs text-rose-700 dark:text-rose-400 font-bold mb-1">Rejection Reason</p>
          <p class="text-xs text-slate-700 dark:text-slate-300">{{ service.rejection_reason }}</p>
        </div>

        <!-- Action buttons -->
        <div v-if="service.status === 'pending'" class="mt-6 flex gap-3 flex-wrap pt-4 border-t border-slate-100 dark:border-slate-800">
          <button
            @click="approveService"
            :disabled="actionLoading"
            class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition-all disabled:opacity-50 shadow-xs"
          >
            ✅ Approve Service
          </button>
          <button
            @click="rejectModalOpen = true"
            :disabled="actionLoading"
            class="px-4 py-2 rounded-lg bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 transition-all disabled:opacity-50 shadow-xs"
          >
            ❌ Reject Service
          </button>
        </div>
      </div>

      <!-- Worker Info Card (Full Details) -->
      <div class="bg-white dark:bg-[#1E293B] rounded-xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
        <h2 class="text-base font-heading font-bold text-slate-900 dark:text-white mb-4">Worker Profile Details</h2>
        <div class="flex items-start gap-4 flex-wrap sm:flex-nowrap">
          <div class="w-14 h-14 rounded-full bg-[#2c7be5] flex items-center justify-center text-white font-bold text-xl flex-shrink-0 shadow-xs">
            {{ service.user?.name?.charAt(0) }}
          </div>
          <div class="space-y-2 flex-1">
            <div class="flex items-center justify-between gap-2 flex-wrap">
              <p class="font-bold text-slate-900 dark:text-white text-sm">{{ service.user?.name }}</p>
              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" :class="service.user?.worker_profile?.is_available !== false ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'">
                {{ service.user?.worker_profile?.is_available !== false ? '● Available for Hiring' : '○ Currently Unavailable' }}
              </span>
            </div>
            <div class="flex flex-wrap gap-4 text-xs text-slate-500 dark:text-slate-400">
              <span v-if="service.user?.email">📧 {{ service.user?.email }}</span>
              <span v-if="service.user?.phone || service.user?.worker_profile?.phone_public">📞 {{ service.user?.phone || service.user?.worker_profile?.phone_public }}</span>
              <span>📍 <strong class="text-slate-800 dark:text-slate-200">{{ service.user?.worker_profile?.city || service.city }}</strong> <span v-if="service.user?.worker_profile?.address">({{ service.user?.worker_profile?.address }})</span></span>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 leading-relaxed">
              {{ service.user?.worker_profile?.bio || 'No bio available for this provider profile.' }}
            </p>
          </div>
        </div>
      </div>

      <!-- Reviews Card -->
      <div class="bg-white dark:bg-[#1E293B] rounded-xl p-6 border border-[#EAEDF1] dark:border-[#334155] shadow-sm">
        <h2 class="text-base font-heading font-bold text-slate-900 dark:text-white mb-4">
          Reviews
          <span class="text-xs font-normal text-slate-400 ml-2">({{ service.reviews?.length || 0 }})</span>
        </h2>

        <div v-if="service.reviews?.length" class="space-y-3">
          <div
            v-for="review in service.reviews"
            :key="review.id"
            class="p-4 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800"
          >
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs text-slate-800 dark:text-slate-200 font-bold">
                  {{ review.user?.name?.charAt(0) }}
                </div>
                <span class="text-xs text-slate-900 dark:text-slate-100 font-bold">{{ review.user?.name }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-amber-500 text-xs font-bold">{{ '★'.repeat(review.rating) }}{{ '☆'.repeat(5 - review.rating) }}</span>
                <span class="text-[11px] text-slate-400">🕒 {{ formatDate(review.created_at) }}</span>
              </div>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ review.comment || 'No comment.' }}</p>
          </div>
        </div>

        <p v-else class="text-slate-400 text-xs">No reviews yet.</p>
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
      <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">
        Provide a reason for rejecting "<strong>{{ service?.title }}</strong>":
      </p>
      <textarea
        v-model="rejectionReason"
        rows="4"
        placeholder="Enter rejection reason (required)..."
        class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs resize-none"
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
