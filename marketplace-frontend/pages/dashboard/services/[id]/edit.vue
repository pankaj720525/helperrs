<template>
  <div class="space-y-6 max-w-4xl mx-auto">
    <!-- Back link -->
    <div class="flex items-center gap-3">
      <NuxtLink to="/dashboard/services" class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1.5">
        ← {{ t('backToServices') }}
      </NuxtLink>
    </div>

    <!-- Page Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-heading font-extrabold text-slate-900 mb-1">🔧 {{ t('editServiceTitle') }}</h1>
        <p class="text-sm text-slate-500 font-medium">{{ t('editServiceSub') }}</p>
      </div>
      <span
        v-if="service"
        class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
        :class="{
          'bg-emerald-100 text-emerald-800 border border-emerald-200': service.status === 'approved',
          'bg-amber-100 text-amber-800 border border-amber-200': service.status === 'pending',
          'bg-rose-100 text-rose-800 border border-rose-200': service.status === 'rejected'
        }"
      >
        Live Status: {{ t('tab' + service.status.charAt(0).toUpperCase() + service.status.slice(1)) }}
      </span>
    </div>

    <!-- Alert for draft/moderation updates -->
    <div
      v-if="hasDraft"
      class="rounded-2xl p-5 border flex items-start gap-4"
      :class="isDraftRejected ? 'bg-rose-50 border-rose-200' : 'bg-amber-50 border-amber-200'"
    >
      <span class="text-2xl">{{ isDraftRejected ? '❌' : '⏳' }}</span>
      <div class="flex-1">
        <h3 class="font-bold text-slate-900 text-sm mb-0.5">
          {{ isDraftRejected ? t('proposedChangesRejected') : t('proposedChangesLabel') }}
        </h3>
        <p class="text-xs text-slate-600">
          {{ isDraftRejected
            ? `Your previous update request was rejected by the admin. Reason: "${service.draft_update.rejection_reason}". Edit and resubmit below.`
            : 'You currently have proposed changes under review by our admin team. Updating the form below will modify your existing request.'
          }}
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" v-if="service">
      <!-- Edit Form (Left / Main) -->
      <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-5">
        <h2 class="text-base font-heading font-extrabold text-slate-900 pb-3 border-b border-slate-100">
          {{ hasDraft ? t('editBtn') + ' ' + t('proposedChangesLabel') : t('proposedChangesLabel') }}
        </h2>

        <form @submit.prevent="handleSubmit" class="space-y-5">
          <!-- Category selection -->
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ t('category') }}</label>
            <select
              v-model="form.category_id"
              required
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium"
            >
              <option value="" disabled>{{ t('selectCategory') }}</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>

          <!-- Title -->
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ t('title') }}</label>
            <input
              v-model="form.title"
              type="text"
              required
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium"
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ t('description') }}</label>
            <textarea
              v-model="form.description"
              rows="5"
              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm resize-none font-medium"
            />
          </div>

          <!-- Price Range -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ t('minPrice') }}</label>
              <input
                v-model.number="form.price_min"
                type="number"
                min="0"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ t('maxPriceLabel') }}</label>
              <input
                v-model.number="form.price_max"
                type="number"
                min="0"
                required
                class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium"
              />
            </div>
          </div>

          <!-- Image Upload -->
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ t('serviceImageLabel') }}</label>
            <div class="flex items-center gap-4 flex-wrap">
              <div class="w-24 h-24 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center flex-shrink-0">
                <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover" />
                <span v-else class="text-3xl text-slate-300">📸</span>
              </div>
              <div class="flex-1">
                <input
                  type="file"
                  ref="fileInput"
                  accept="image/*"
                  @change="handleImageChange"
                  class="hidden"
                />
                <button
                  type="button"
                  @click="triggerFileInput"
                  class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 font-bold text-xs cursor-pointer transition-colors"
                >
                  {{ t('uploadNewImage') }}
                </button>
                <p class="text-[10px] text-slate-400 mt-1.5">Uploading a new image will replace the current image in the draft proposal.</p>
              </div>
            </div>
          </div>

          <!-- Validation / Error messages -->
          <p v-if="errorMsg" class="text-rose-600 text-sm font-semibold">❌ {{ errorMsg }}</p>

          <!-- Form Actions -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <NuxtLink
              to="/dashboard/services"
              class="px-5 py-2.5 text-xs text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors font-bold"
            >
              {{ t('cancelBtn') }}
            </NuxtLink>
            <button
              type="submit"
              :disabled="saving"
              class="px-6 py-2.5 text-xs text-white bg-gradient-to-r from-rose-600 to-rose-700 rounded-xl hover:shadow-md transition-all disabled:opacity-50 font-extrabold cursor-pointer"
              style="color: #ffffff !important;"
            >
              {{ saving ? t('savingChangesBtn') : (service.status === 'approved' ? t('proposedChangesLabel') : t('saveChangesBtn')) }}
            </button>
          </div>
        </form>
      </div>

      <!-- Live / Active Approved Reference (Right) -->
      <div class="bg-slate-50 dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 self-start">
        <h2 class="text-sm font-heading font-extrabold text-slate-900 dark:text-white uppercase tracking-wider pb-2 border-b border-slate-200 dark:border-slate-800 flex items-center gap-1.5">
          🟢 {{ t('liveVersionLabel') }}
        </h2>
        <p class="text-xs text-slate-500">{{ t('liveVersionDesc') }}</p>

        <div class="space-y-4 pt-2">
          <!-- Thumbnail -->
          <div class="w-full h-36 rounded-2xl bg-white border border-slate-200 overflow-hidden shadow-xs">
            <img :src="serviceImage" :alt="service.title" class="w-full h-full object-cover" />
          </div>

          <div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-200 text-slate-700 font-extrabold">
              {{ service.category?.name }}
            </span>
            <h3 class="font-bold text-slate-900 dark:text-white mt-1.5 text-base">{{ service.title }}</h3>
            <p class="text-xs text-rose-600 font-extrabold mt-1">₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}</p>
          </div>

          <div class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
            <h4 class="font-bold text-slate-500 uppercase text-[10px] mb-1">{{ t('description') }}</h4>
            <p class="whitespace-pre-line">{{ service.description || 'No description provided.' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard' });

const route = useRoute();
const api = useApi();
const { getServiceImage } = useDefaultImage();
const { t, initLang } = useLanguage();

const categories = ref<any[]>([]);
const service = ref<any>(null);
const saving = ref(false);
const errorMsg = ref("");

const form = reactive({
  category_id: "",
  title: "",
  description: "",
  price_min: 0,
  price_max: 0
});

const fileInput = ref<HTMLInputElement | null>(null);
const imageFile = ref<File | null>(null);
const previewUrl = ref("");

onMounted(async () => {
  initLang();
  await loadCategories();
  await loadService();
});

const loadCategories = async () => {
  try {
    const data = await api.get<any>("/categories");
    categories.value = data.categories || [];
  } catch { }
};

const loadService = async () => {
  try {
    const data = await api.get<any>(`/services/${route.params.id}`);
    service.value = data.service;
    
    // Determine source values (populate with draft details if exists)
    const source = service.value.draft_update || service.value;
    
    form.category_id = source.category_id || (source.category?.id) || "";
    form.title = source.title;
    form.description = source.description || "";
    form.price_min = Number(source.price_min) || 0;
    form.price_max = Number(source.price_max) || 0;
    
    // Set preview URL to the existing image
    previewUrl.value = source.image || "";
  } catch {
    errorMsg.value = "Failed to load service details.";
  }
};

const hasDraft = computed(() => !!service.value?.draft_update);
const isDraftRejected = computed(() => service.value?.draft_update?.status === 'rejected');

const serviceImage = computed(() => {
  if (!service.value) return "";
  return getServiceImage(service.value);
});

const triggerFileInput = () => {
  fileInput.value?.click();
};

const handleImageChange = (e: Event) => {
  const files = (e.target as HTMLInputElement).files;
  if (!files || !files.length) return;

  const file = files[0];
  if (file.size > 2 * 1024 * 1024) {
    errorMsg.value = "Image size must not exceed 2MB.";
    return;
  }

  imageFile.value = file;
  previewUrl.value = URL.createObjectURL(file);
  errorMsg.value = "";
};

const triggerFileInputRef = () => {
  fileInput.value?.click();
};

const handleSubmit = async () => {
  saving.value = true;
  errorMsg.value = "";

  try {
    const formData = new FormData();
    formData.append("_method", "PUT"); // Method spoofing for Laravel PUT requests with file uploads
    formData.append("category_id", form.category_id);
    formData.append("title", form.title);
    if (form.description) formData.append("description", form.description);
    if (form.price_min !== null) formData.append("price_min", String(form.price_min));
    if (form.price_max !== null) formData.append("price_max", String(form.price_max));
    if (imageFile.value) {
      formData.append("image", imageFile.value);
    }

    // Call update API
    await api.post(`/my-services/${service.value.id}`, formData);
    navigateTo("/dashboard/services");
  } catch (e: any) {
    const errors = e?.data?.errors;
    errorMsg.value = errors ? Object.values(errors).flat().join(" ") : (e?.data?.message || "Failed to update service.");
  } finally {
    saving.value = false;
  }
};
</script>
