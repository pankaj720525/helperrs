<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-heading font-bold text-white">🔧 My Services</h1>
      <button
        @click="handleAddClick"
        class="px-5 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all"
      >
        + Add Service
      </button>
    </div>

    <!-- Trial Limit Banner -->
    <div v-if="showTrialBanner" class="glass rounded-2xl p-5 border border-warning/20 flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-2">
      <div class="text-3xl">🔒</div>
      <div class="flex-1">
        <p class="font-semibold text-white mb-0.5">Free Trial Limit Reached</p>
        <p class="text-sm text-slate-400">You've used <strong class="text-warning">{{ services.length }}/2</strong> free trial services. Upgrade your plan to add unlimited services.</p>
      </div>
      <NuxtLink
        to="/dashboard/subscription"
        class="flex-shrink-0 px-5 py-2.5 rounded-xl gradient-primary text-white text-sm font-semibold hover:shadow-glow transition-all whitespace-nowrap"
      >
        ⬆️ Upgrade Plan
      </NuxtLink>
    </div>

    <!-- Status Tabs -->
    <div class="flex gap-2 mb-6">
      <button v-for="s in ['all', 'pending', 'approved', 'rejected']" :key="s"
        @click="statusFilter = s === 'all' ? '' : s; loadServices()"
        class="px-4 py-2 rounded-xl text-sm font-medium transition-all"
        :class="(s === 'all' ? !statusFilter : statusFilter === s) ? 'gradient-primary text-white' : 'glass text-slate-300 hover:text-white'"
      >
        {{ s.charAt(0).toUpperCase() + s.slice(1) }}
      </button>
    </div>

    <!-- Services List -->
    <div class="space-y-4">
      <div v-for="service in services" :key="service.id"
        class="glass rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4 animate-fade-up">
        <!-- Image -->
        <div class="w-20 h-20 rounded-xl bg-surface-light overflow-hidden flex-shrink-0">
          <img v-if="service.image" :src="service.image" :alt="service.title" class="w-full h-full object-cover" />
          <div v-else class="w-full h-full flex items-center justify-center text-2xl opacity-30">🔧</div>
        </div>

        <!-- Info -->
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-1">
            <h3 class="font-semibold text-white truncate">{{ service.title }}</h3>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
              :class="{
                'bg-success/15 text-success': service.status === 'approved',
                'bg-warning/15 text-warning': service.status === 'pending',
                'bg-danger/15 text-danger': service.status === 'rejected'
              }">
              {{ service.status }}
            </span>
          </div>
          <p class="text-xs text-slate-400 mb-1">{{ service.category?.name }} · Created {{ service.created_at?.split('T')[0] }}</p>
          <p v-if="service.price_min || service.price_max" class="text-sm text-primary-light font-medium">
            ₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}
          </p>
          <p v-if="service.status === 'rejected' && service.rejection_reason" class="text-xs text-danger mt-1">
            ❌ {{ service.rejection_reason }}
          </p>
        </div>

        <!-- Actions -->
        <div class="flex gap-2 flex-shrink-0">
          <button @click="editService(service)" class="px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 text-xs font-medium hover:bg-white/10 transition-colors">Edit</button>
          <button @click="deleteService(service)" class="px-3 py-1.5 rounded-lg bg-danger/15 text-danger text-xs font-medium hover:bg-danger/25 transition-colors">Delete</button>
        </div>
      </div>
    </div>

    <div v-if="!services.length" class="glass rounded-2xl p-16 text-center text-slate-500">
      <p class="text-4xl mb-3">🔧</p>
      <p>No services yet. Create your first one!</p>
    </div>

    <!-- Create/Edit Modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="modalOpen = false" />
          <div class="relative glass rounded-2xl w-full max-w-lg border border-white/10 animate-fade-up">
            <div class="flex items-center justify-between p-5 border-b border-white/5">
              <h3 class="text-lg font-heading font-semibold text-white">{{ editingId ? 'Edit Service' : 'Create Service' }}</h3>
              <button @click="modalOpen = false" class="text-slate-400 hover:text-white text-xl">&times;</button>
            </div>
            <form @submit.prevent="saveService" class="p-5 space-y-4">
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Category</label>
                <select v-model="form.category_id" required class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm">
                  <option value="" disabled>Select category</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Title</label>
                <input v-model="form.title" type="text" required class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" placeholder="e.g. AC Installation & Repair" />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Description</label>
                <textarea v-model="form.description" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm resize-none" placeholder="Describe your service..." />
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-1.5">Min Price (₹)</label>
                  <input v-model.number="form.price_min" type="number" min="0" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-1.5">Max Price (₹)</label>
                  <input v-model.number="form.price_max" type="number" min="0" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" />
                </div>
              </div>
              <p v-if="errorMsg" class="text-danger text-sm">{{ errorMsg }}</p>
              <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm text-slate-300 bg-white/5 rounded-xl hover:bg-white/10 transition-colors">Cancel</button>
                <button type="submit" :disabled="saving" class="px-5 py-2 text-sm text-white gradient-primary rounded-xl hover:shadow-glow transition-all disabled:opacity-50">
                  {{ saving ? 'Saving...' : (editingId ? 'Save Changes' : 'Create Service') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
const api = useApi();
const userStore = useUserStore();
const router = useRouter();

const FREE_TRIAL_LIMIT = 2;

const services = ref<any[]>([]);
const categories = ref<any[]>([]);
const statusFilter = ref("");
const modalOpen = ref(false);
const editingId = ref<string | null>(null);
const saving = ref(false);
const errorMsg = ref("");
const form = reactive({ category_id: "", title: "", description: "", price_min: 0, price_max: 0 });

// Trial limit: only applies when on trial plan and not editing
const isTrial = computed(() => userStore.user?.subscription?.plan_type === 'trial' || !userStore.user?.subscription);
const atTrialLimit = computed(() => isTrial.value && services.value.length >= FREE_TRIAL_LIMIT);
const showTrialBanner = computed(() => atTrialLimit.value);

const handleAddClick = () => {
  if (atTrialLimit.value) return; // Banner is shown instead
  openCreateModal();
};

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo("/login");
  if (!userStore.isWorker) return navigateTo("/dashboard");
  await loadCategories();
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

const loadCategories = async () => {
  try {
    const data = await api.get<any>("/categories");
    categories.value = data.categories || [];
  } catch { }
};

const openCreateModal = () => {
  editingId.value = null;
  Object.assign(form, { category_id: "", title: "", description: "", price_min: 0, price_max: 0 });
  errorMsg.value = "";
  modalOpen.value = true;
};

const editService = (s: any) => {
  editingId.value = s.id;
  Object.assign(form, {
    category_id: s.category?.id || "",
    title: s.title,
    description: s.description || "",
    price_min: s.price_min || 0,
    price_max: s.price_max || 0,
  });
  errorMsg.value = "";
  modalOpen.value = true;
};

const saveService = async () => {
  saving.value = true;
  errorMsg.value = "";
  try {
    if (editingId.value) {
      await api.put(`/my-services/${editingId.value}`, form);
    } else {
      await api.post("/services", form);
    }
    modalOpen.value = false;
    loadServices();
  } catch (e: any) {
    const errors = e?.data?.errors;
    errorMsg.value = errors ? Object.values(errors).flat().join(" ") : (e?.data?.message || "Failed to save.");
  } finally { saving.value = false; }
};

const deleteService = async (s: any) => {
  if (!confirm(`Delete "${s.title}"?`)) return;
  try {
    await api.delete(`/my-services/${s.id}`);
    loadServices();
  } catch { }
};
</script>

<style scoped>
.modal-enter-active { transition: opacity 0.2s ease; }
.modal-leave-active { transition: opacity 0.15s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
