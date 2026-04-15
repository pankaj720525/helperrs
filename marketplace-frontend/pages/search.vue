<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="text-3xl font-heading font-bold text-white mb-2">🔍 Search by Location</h1>
    <p class="text-slate-400 mb-8">Find service professionals near you</p>

    <!-- Search Form -->
    <div class="glass rounded-2xl p-6 mb-8">
      <form @submit.prevent="handleSearch" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
          <label class="block text-xs text-slate-400 mb-1.5">Category</label>
          <select v-model="category" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm">
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.slug">{{ cat.name }}</option>
          </select>
        </div>
        <div class="w-32">
          <label class="block text-xs text-slate-400 mb-1.5">Radius (km)</label>
          <input v-model.number="radius" type="number" min="1" max="100" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary/50 text-sm" />
        </div>
        <div class="flex-1 min-w-[150px]">
          <label class="block text-xs text-slate-400 mb-1.5">Keyword</label>
          <input v-model="keyword" type="text" placeholder="e.g. AC repair" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" />
        </div>
        <div class="flex items-end">
          <button type="submit" :disabled="searching" class="px-6 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all disabled:opacity-50">
            {{ searching ? 'Searching...' : '🔍 Search' }}
          </button>
        </div>
        <div class="flex items-end">
          <button type="button" @click="getLocation" class="px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-sm hover:bg-white/10 transition-colors">
            📍 My Location
          </button>
        </div>
      </form>
      <p v-if="locationStatus" class="text-xs text-slate-400 mt-2">{{ locationStatus }}</p>
    </div>

    <!-- Results -->
    <div v-if="results.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="(r, i) in results" :key="i" class="glass rounded-2xl p-5 animate-fade-up hover:border-primary/20 transition-all"
        :style="{ animationDelay: `${i * 0.05}s` }">
        <div class="flex items-start gap-3 mb-3">
          <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white font-semibold flex-shrink-0">
            {{ r.worker_name?.charAt(0) }}
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-white truncate">{{ r.worker_name }}</h3>
            <p class="text-xs text-slate-400">{{ r.address || 'Location hidden' }}</p>
          </div>
          <span class="text-xs text-primary-light font-medium whitespace-nowrap">{{ r.distance }}</span>
        </div>

        <div class="mb-3">
          <p class="text-sm font-medium text-slate-200">{{ r.service_title }}</p>
          <span class="text-xs text-slate-500">{{ r.category_name }}</span>
        </div>

        <div v-if="r.price_min || r.price_max" class="text-sm font-semibold text-primary-light mb-3">
          ₹{{ r.price_min || 0 }} – ₹{{ r.price_max || 0 }}
        </div>

        <NuxtLink :to="`/services/${r.service_id}`" class="block text-center py-2 rounded-xl bg-white/5 text-sm text-slate-300 hover:bg-white/10 transition-colors">
          View Details
        </NuxtLink>
      </div>
    </div>

    <div v-else-if="searched && !searching" class="glass rounded-2xl p-16 text-center text-slate-500">
      <p class="text-4xl mb-3">📍</p>
      <p>No services found nearby. Try increasing the search radius.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
const api = useApi();
const categories = ref<any[]>([]);
const results = ref<any[]>([]);
const category = ref("");
const radius = ref(25);
const keyword = ref("");
const lat = ref(0);
const lng = ref(0);
const searching = ref(false);
const searched = ref(false);
const locationStatus = ref("");

const getLocation = () => {
  if (!navigator.geolocation) {
    locationStatus.value = "Geolocation not supported.";
    return;
  }
  locationStatus.value = "Getting your location...";
  navigator.geolocation.getCurrentPosition(
    (pos) => {
      lat.value = pos.coords.latitude;
      lng.value = pos.coords.longitude;
      locationStatus.value = `📍 Location found: ${lat.value.toFixed(4)}, ${lng.value.toFixed(4)}`;
    },
    () => { locationStatus.value = "Failed to get location. Using default."; }
  );
};

const handleSearch = async () => {
  if (!lat.value || !lng.value) {
    locationStatus.value = "Please click '📍 My Location' first.";
    return;
  }
  searching.value = true;
  searched.value = true;
  try {
    const params: any = { lat: lat.value, lng: lng.value, radius: radius.value };
    if (category.value) params.category = category.value;
    if (keyword.value) params.keyword = keyword.value;
    const data = await api.get<any>("/search", params);
    results.value = data.results || [];
  } catch { results.value = []; }
  finally { searching.value = false; }
};

onMounted(async () => {
  try {
    const data = await api.get<any>("/categories");
    categories.value = data.categories || [];
  } catch { }
  getLocation();
});
</script>
