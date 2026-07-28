<template>
  <div class="space-y-6">

    <!-- Top Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-heading font-extrabold text-slate-900 mb-1">🔧 {{ t('offeredServicesTitle') }}</h1>
        <p class="text-sm text-slate-500 font-medium">{{ t('offeredServicesSub') }}</p>
      </div>

      <NuxtLink
        v-if="!atTrialLimit"
        to="/dashboard/services/create"
        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-extrabold hover:shadow-md transition-all flex items-center gap-1.5 cursor-pointer"
        style="color: #ffffff !important;"
      >
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        <span>{{ t('addNewService') }}</span>
      </NuxtLink>
    </div>

    <!-- Trial Limit Banner -->
    <div v-if="showTrialBanner" class="bg-amber-50 rounded-2xl p-5 border border-amber-200 flex flex-col sm:flex-row items-start sm:items-center gap-4">
      <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xl flex-shrink-0">🔒</div>
      <div class="flex-1">
        <p class="font-bold text-slate-900 mb-0.5 text-sm">{{ t('trialBannerTitle') }}</p>
        <p class="text-xs text-slate-600">{{ t('trialBannerDesc') }}</p>
      </div>
      <NuxtLink
        to="/dashboard/subscription"
        class="flex-shrink-0 px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 via-rose-700 to-rose-800 text-white text-xs font-extrabold hover:shadow-md transition-all whitespace-nowrap flex items-center gap-1.5 cursor-pointer"
        style="color: #ffffff !important;"
      >
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <span class="text-white font-bold">{{ t('upgradePlan') }}</span>
      </NuxtLink>
    </div>

    <!-- Status Tabs -->
    <div class="flex gap-2 flex-wrap">
      <button
        v-for="s in ['all', 'pending', 'approved', 'rejected']"
        :key="s"
        @click="statusFilter = s === 'all' ? '' : s; loadServices()"
        class="px-4 py-2 rounded-xl text-xs font-bold transition-all border cursor-pointer"
        :class="(s === 'all' ? !statusFilter : statusFilter === s) ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'"
        :style="(s === 'all' ? !statusFilter : statusFilter === s) ? 'color: #ffffff !important;' : ''"
      >
        {{ t('tab' + (s === 'all' ? 'All' : s.charAt(0).toUpperCase() + s.slice(1))) }}
      </button>
    </div>

    <!-- Services List -->
    <div class="space-y-4">
      <div
        v-for="service in services"
        :key="service.id"
        class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col gap-4 animate-fade-up"
      >
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div class="flex items-center gap-4">
            <!-- Service Image (With fallback category image) -->
            <div class="w-20 h-20 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200">
              <img :src="getServiceImage(service)" :alt="service.title" class="w-full h-full object-cover" />
            </div>

            <!-- Details -->
            <div class="min-w-0">
              <div class="flex items-center gap-2 mb-1 flex-wrap">
                <h3 class="font-bold text-slate-900 text-base truncate">{{ service.title }}</h3>
                <span
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                  :class="{
                    'bg-emerald-100 text-emerald-800 border border-emerald-200': service.status === 'approved',
                    'bg-amber-100 text-amber-800 border border-amber-200': service.status === 'pending',
                    'bg-rose-100 text-rose-800 border border-rose-200': service.status === 'rejected'
                  }"
                >
                  {{ t('tab' + service.status.charAt(0).toUpperCase() + service.status.slice(1)) }}
                </span>
                <span v-if="service.draft_update" class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-100 text-amber-800 border border-amber-200 uppercase tracking-wider">
                  {{ t('pendingApprovalLabel') }}
                </span>
              </div>
              <p class="text-xs text-slate-500 mb-1 font-medium">{{ service.category?.name || t('category') }} · Created {{ service.created_at?.split('T')[0] }}</p>
              <p v-if="service.price_min || service.price_max" class="text-sm text-rose-600 font-extrabold">
                ₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}
              </p>
              <p v-if="service.status === 'rejected' && service.rejection_reason" class="text-xs text-rose-600 mt-1 font-semibold">
                {{ t('tabRejected') }}: {{ service.rejection_reason }}
              </p>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-2 flex-shrink-0 self-end sm:self-center">
            <NuxtLink :to="`/dashboard/services/${service.id}/edit`" class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-colors border border-slate-200 cursor-pointer">{{ t('editBtn') }}</NuxtLink>
            <button @click="deleteService(service)" class="px-3.5 py-1.5 rounded-xl bg-rose-50 text-rose-600 text-xs font-bold hover:bg-rose-100 transition-colors border border-rose-200 cursor-pointer">{{ t('deleteBtn') }}</button>
          </div>
        </div>

        <!-- Proposed Changes Section -->
        <div v-if="service.draft_update" class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50 space-y-3">
          <div class="flex items-center justify-between flex-wrap gap-2">
            <span class="text-xs font-extrabold text-amber-800 uppercase tracking-wider flex items-center gap-1">
              📝 {{ t('proposedChangesLabel') }} (Status: {{ t('tab' + service.draft_update.status.charAt(0).toUpperCase() + service.draft_update.status.slice(1)) }})
            </span>
            <span v-if="service.draft_update.status === 'rejected'" class="text-[10px] font-bold text-rose-600">
              Reason: {{ service.draft_update.rejection_reason }}
            </span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs leading-relaxed">
            <div v-if="service.title !== service.draft_update.title">
              <span class="font-bold text-slate-400">Proposed Title:</span>
              <span class="font-semibold text-slate-800 ml-1">{{ service.draft_update.title }}</span>
            </div>
            <div v-if="service.category?.id !== service.draft_update.category?.id">
              <span class="font-bold text-slate-400">Proposed Category:</span>
              <span class="font-semibold text-slate-800 ml-1">{{ service.draft_update.category?.name }}</span>
            </div>
            <div v-if="service.description !== service.draft_update.description" class="col-span-1 sm:col-span-2">
              <span class="font-bold text-slate-400">Proposed Description:</span>
              <p class="text-slate-700 mt-0.5 whitespace-pre-line bg-white/50 p-2.5 rounded-xl border border-slate-100">
                {{ service.draft_update.description }}
              </p>
            </div>
            <div v-if="service.price_min !== service.draft_update.price_min || service.price_max !== service.draft_update.price_max">
              <span class="font-bold text-slate-400">Proposed Price:</span>
              <span class="font-semibold text-rose-600 ml-1">₹{{ service.draft_update.price_min }} – ₹{{ service.draft_update.price_max }}</span>
            </div>
          </div>

          <div class="flex justify-end pt-1">
            <NuxtLink :to="`/dashboard/services/${service.id}/edit`" class="px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-extrabold cursor-pointer" style="color: white !important;">
              {{ t('editBtn') }} {{ t('proposedChangesLabel') }}
            </NuxtLink>
          </div>
        </div>

      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!services.length" class="bg-white rounded-3xl p-16 text-center text-slate-400 border border-slate-200 space-y-3">
      <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
      </svg>
      <p class="font-bold text-slate-700 text-base">{{ t('noOfferedServices') }}</p>
      <p class="text-xs text-slate-500">{{ t('noOfferedServicesDesc') }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard' });

const api = useApi();
const userStore = useUserStore();
const { getServiceImage } = useDefaultImage();
const { t, initLang } = useLanguage();

const FREE_TRIAL_LIMIT = 2;

const services = ref<any[]>([]);
const statusFilter = ref("");

const isTrial = computed(() => userStore.user?.subscription?.plan_type === 'trial' || !userStore.user?.subscription);
const atTrialLimit = computed(() => isTrial.value && services.value.length >= FREE_TRIAL_LIMIT);
const showTrialBanner = computed(() => atTrialLimit.value);

onMounted(async () => {
  initLang();
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo("/login");
  if (!userStore.isWorker) return navigateTo("/dashboard");
  await loadServices();
});

const loadServices = async () => {
  try {
    const params: any = { per_page: 50 };
    if (statusFilter.value) params.status = statusFilter.value;
    const data = await api.get<any>("/my-services", params);
    services.value = data.services || [];
  } catch { }
};

const deleteService = async (s: any) => {
  if (!confirm(`Delete "${s.title}"?`)) return;
  try {
    await api.delete(`/my-services/${s.id}`);
    loadServices();
  } catch { }
};
</script>
