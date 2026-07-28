<template>
  <div class="space-y-6 max-w-2xl mx-auto">
    <!-- Back to services -->
    <div class="flex items-center gap-3">
      <NuxtLink to="/dashboard/services" class="text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1.5">
        ← {{ t('backToServices') }}
      </NuxtLink>
    </div>

    <!-- Page Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
      <h1 class="text-2xl font-heading font-extrabold text-slate-900 mb-1">🔧 {{ t('createServiceTitle') }}</h1>
      <p class="text-sm text-slate-500 font-medium">{{ t('createServiceSub') }}</p>
    </div>

    <!-- Service Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
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
            placeholder="e.g. Professional AC Leak Repair & Installation"
            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-rose-600 text-sm font-medium"
          />
        </div>

        <!-- Description -->
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">{{ t('description') }}</label>
          <textarea
            v-model="form.description"
            rows="4"
            placeholder="Describe what is included, tools used, and special specifications of your service..."
            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-rose-600 text-sm resize-none font-medium"
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
                {{ t('uploadImage') }}
              </button>
              <p class="text-[10px] text-slate-400 mt-1.5">{{ t('imageSizeTip') }}</p>
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
            {{ saving ? t('creatingServiceBtn') : t('createServiceBtn') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard' });

const api = useApi();
const { t, initLang } = useLanguage();
const categories = ref<any[]>([]);
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
});

const loadCategories = async () => {
  try {
    const data = await api.get<any>("/categories");
    categories.value = data.categories || [];
  } catch { }
};

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

const handleSubmit = async () => {
  saving.value = true;
  errorMsg.value = "";

  try {
    const formData = new FormData();
    formData.append("category_id", form.category_id);
    formData.append("title", form.title);
    if (form.description) formData.append("description", form.description);
    if (form.price_min !== null) formData.append("price_min", String(form.price_min));
    if (form.price_max !== null) formData.append("price_max", String(form.price_max));
    if (imageFile.value) {
      formData.append("image", imageFile.value);
    }

    await api.post("/services", formData);
    navigateTo("/dashboard/services");
  } catch (e: any) {
    const errors = e?.data?.errors;
    errorMsg.value = errors ? Object.values(errors).flat().join(" ") : (e?.data?.message || "Failed to create service.");
  } finally {
    saving.value = false;
  }
};
</script>
