<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="text-3xl font-heading font-bold text-white mb-6">Browse Services</h1>

    <!-- Filters -->
    <div class="glass rounded-2xl p-5 mb-8 flex flex-wrap items-center gap-4">
      <select v-model="selectedCategory" @change="loadServices" class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm">
        <option value="">All Categories</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
      </select>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="(service, i) in services"
        :key="service.id"
        class="glass rounded-2xl overflow-hidden group hover:border-primary/20 transition-all duration-300 animate-fade-up"
        :style="{ animationDelay: `${i * 0.05}s` }"
      >
        <!-- Image -->
        <div class="h-48 bg-surface-light relative overflow-hidden">
          <img v-if="service.image" :src="service.image" :alt="service.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
          <div v-else class="w-full h-full flex items-center justify-center text-4xl opacity-30">🔧</div>
          <div class="absolute top-3 left-3">
            <span class="px-2.5 py-1 rounded-full bg-black/40 backdrop-blur text-xs text-white font-medium">
              {{ service.category?.name }}
            </span>
          </div>
        </div>

        <!-- Content -->
        <div class="p-5">
          <h3 class="font-semibold text-white text-lg mb-2 group-hover:text-primary-light transition-colors">
            {{ service.title }}
          </h3>
          <p class="text-sm text-slate-400 line-clamp-2 mb-3">{{ service.description || 'No description' }}</p>

          <!-- Price -->
          <div class="flex items-center justify-between mb-3">
            <div v-if="service.price_min || service.price_max" class="text-sm font-semibold text-primary-light">
              ₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}
            </div>
            <div v-else class="text-sm text-slate-500">Price on request</div>

            <!-- Rating -->
            <div class="flex items-center gap-1">
              <span class="text-warning text-sm">★</span>
              <span class="text-sm text-slate-300 font-medium">{{ service.reviews_avg || '0.0' }}</span>
              <span class="text-xs text-slate-500">({{ service.reviews_count || 0 }})</span>
            </div>
          </div>

          <!-- Worker -->
          <div class="flex items-center justify-between pt-3 border-t border-white/5">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 rounded-full gradient-primary flex items-center justify-center text-white text-[10px] font-semibold">
                {{ service.user?.name?.charAt(0) }}
              </div>
              <span class="text-xs text-slate-400">{{ service.user?.name }}</span>
            </div>
            <NuxtLink :to="`/services/${service.id}`" class="text-xs text-primary-light hover:underline font-medium">
              View Details →
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>

    <div v-if="!services.length" class="glass rounded-2xl p-16 text-center text-slate-500">
      <p class="text-4xl mb-4">🔍</p>
      <p>No services found. Check back soon!</p>
    </div>

    <!-- Pagination -->
    <div v-if="meta.last_page > 1" class="flex justify-center gap-3 mt-10">
      <button @click="page--; loadServices()" :disabled="page <= 1" class="px-4 py-2 rounded-xl glass text-sm text-slate-300 disabled:opacity-30 hover:border-primary/20 transition-all">← Previous</button>
      <span class="px-4 py-2 text-sm text-slate-400">{{ meta.current_page }} / {{ meta.last_page }}</span>
      <button @click="page++; loadServices()" :disabled="page >= meta.last_page" class="px-4 py-2 rounded-xl glass text-sm text-slate-300 disabled:opacity-30 hover:border-primary/20 transition-all">Next →</button>
    </div>
  </div>
</template>

<script setup lang="ts">
const api = useApi();
const route = useRoute();

const services = ref<any[]>([]);
const categories = ref<any[]>([]);
const meta = ref<any>({});
const page = ref(1);
const selectedCategory = ref("");

const loadServices = async () => {
  try {
    const params: any = { page: page.value, per_page: 12 };
    if (selectedCategory.value) params.category_id = selectedCategory.value;
    const data = await api.get<any>("/services", params);
    services.value = data.services || [];
    meta.value = data.meta || {};
  } catch { }
};

const loadCategories = async () => {
  try {
    const data = await api.get<any>("/categories");
    categories.value = data.categories || [];
  } catch { }
};

onMounted(async () => {
  await loadCategories();
  if (route.query.category) {
    const cat = categories.value.find((c: any) => c.slug === route.query.category);
    if (cat) selectedCategory.value = cat.id;
  }
  loadServices();
});
</script>
