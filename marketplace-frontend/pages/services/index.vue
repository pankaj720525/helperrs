<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 md:py-12 space-y-8">

    <!-- ── Top Hero & Search Banner ────────────────────── -->
    <div class="bg-white rounded-3xl p-6 md:p-10 border border-slate-200 shadow-sm relative overflow-hidden">
      <div class="absolute top-0 left-0 right-0 h-2 gradient-primary" />

      <div class="max-w-3xl mx-auto text-center space-y-4">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold">
          <svg class="w-3.5 h-3.5 text-rose-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          <span>Servicing <strong>{{ currentLocation.formatted }}</strong></span>
          <button type="button" @click="openModal" class="underline text-rose-800 font-extrabold hover:text-rose-900 cursor-pointer">Change</button>
        </div>

        <h1 class="text-3xl sm:text-5xl font-heading font-extrabold text-slate-900 tracking-tight">
          Find Verified Local Service Experts
        </h1>
        <p class="text-slate-600 text-sm sm:text-base max-w-xl mx-auto">
          Book background-checked technicians for home repairs, AC servicing, plumbing, and deep cleaning.
        </p>

        <!-- Prominent Search Bar -->
        <form @submit.prevent="handleSearchSubmit" class="pt-2">
          <div class="bg-slate-50 rounded-2xl p-2.5 border border-slate-300 shadow-sm flex flex-col sm:flex-row gap-2 max-w-2xl mx-auto">
            <div class="flex-1 flex items-center px-3 bg-white rounded-xl border border-slate-200">
              <svg class="w-4 h-4 text-slate-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <input
                v-model="searchKeyword"
                type="text"
                placeholder="Search AC repair, plumber, electrician..."
                class="w-full py-2.5 bg-transparent border-none text-slate-900 text-sm outline-none font-medium placeholder-slate-400"
              />
            </div>

            <select v-model="selectedCategory" class="px-3 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-800 text-sm font-medium outline-none cursor-pointer">
              <option value="">All Categories</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>

            <button
              type="submit"
              class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 via-rose-700 to-rose-800 text-white font-extrabold text-sm hover:shadow-md transition-all whitespace-nowrap cursor-pointer flex items-center justify-center gap-1.5"
              style="color: #ffffff !important;"
            >
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <span class="text-white font-bold">Search</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ── Category Filter Pills Row ───────────────────── -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
      <button
        type="button"
        @click="selectedCategory = ''; loadServices()"
        class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all border cursor-pointer"
        :class="!selectedCategory ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'"
        :style="!selectedCategory ? 'color: #ffffff !important;' : ''"
      >
        All Services
      </button>

      <button
        v-for="cat in categories"
        :key="cat.id"
        type="button"
        @click="selectedCategory = cat.id; loadServices()"
        class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all border cursor-pointer"
        :class="selectedCategory === cat.id ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'"
        :style="selectedCategory === cat.id ? 'color: #ffffff !important;' : ''"
      >
        {{ cat.name }}
      </button>
    </div>

    <!-- ── Shimmer Skeleton Loading Grid ───────────────── -->
    <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="n in 6" :key="n" class="bg-white rounded-2xl p-4 border border-slate-200 space-y-4">
        <div class="h-48 shimmer-skeleton rounded-xl w-full" />
        <div class="h-5 shimmer-skeleton rounded w-3/4" />
        <div class="h-4 shimmer-skeleton rounded w-full" />
        <div class="flex justify-between items-center pt-2 border-t border-slate-100">
          <div class="h-4 shimmer-skeleton rounded w-1/3" />
          <div class="h-8 shimmer-skeleton rounded-lg w-1/2" />
        </div>
      </div>
    </div>

    <!-- ── Services Cards Grid ─────────────────────────── -->
    <div v-else-if="filteredServices.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="(service, i) in filteredServices"
        :key="service.id"
        class="bg-white rounded-2xl overflow-hidden border border-slate-200 group hover:border-rose-400 hover:shadow-xl transition-all duration-300 animate-fade-up relative flex flex-col justify-between"
        :style="{ animationDelay: `${i * 0.04}s` }"
      >
        <div>
          <!-- Service Banner Image with fallback -->
          <div class="h-48 bg-slate-100 relative overflow-hidden">
            <img :src="getServiceImage(service)" :alt="service.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />

            <!-- Labels / Category Badge -->
            <div class="absolute top-3 left-3 flex flex-col gap-1 items-start">
              <span class="px-2.5 py-1 rounded-full bg-slate-900/80 backdrop-blur text-xs text-cyan-300 font-bold">
                {{ service.category?.name || 'Service' }}
              </span>
              <span v-if="i % 2 === 0" class="px-2.5 py-0.5 rounded-full bg-rose-600 text-[10px] text-white font-bold uppercase tracking-wider">
                Trending
              </span>
            </div>

            <!-- Wishlist Heart SVG Button -->
            <button
              type="button"
              @click="toggleWishlist(service.id, service.title)"
              class="absolute top-3 right-3 w-9 h-9 rounded-full bg-white/90 shadow-md backdrop-blur flex items-center justify-center transition-transform hover:scale-110 cursor-pointer"
              :title="isWishlisted(service.id) ? 'Remove from Saved Services' : 'Save Service'"
            >
              <svg class="w-4 h-4 transition-colors" :class="isWishlisted(service.id) ? 'text-rose-600 fill-rose-600' : 'text-slate-400 fill-none'" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
            </button>
          </div>

          <!-- Content Details -->
          <div class="p-5">
            <h3 class="font-extrabold text-slate-900 text-lg mb-1.5 group-hover:text-rose-600 transition-colors line-clamp-1">
              {{ service.title }}
            </h3>
            <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">{{ service.description || 'Professional and verified service provider available in your area. Booking includes 30-day warranty.' }}</p>

            <!-- Price & Rating Row -->
            <div class="flex items-center justify-between mb-4">
              <div v-if="service.price_min || service.price_max" class="text-base font-extrabold text-rose-600">
                ₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}
              </div>
              <div v-else class="text-base font-extrabold text-rose-600">₹499</div>

              <div class="flex items-center gap-1 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">
                <span class="text-amber-500 text-xs">★</span>
                <span class="text-xs text-slate-900 font-bold">{{ service.reviews_avg || '4.8' }}</span>
                <span class="text-[10px] text-slate-500">({{ service.reviews_count || 12 }})</span>
              </div>
            </div>

            <!-- Provider Info -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
              <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full gradient-primary flex items-center justify-center text-white text-[10px] font-bold">
                  {{ service.user?.name?.charAt(0)?.toUpperCase() || 'S' }}
                </div>
                <span class="text-xs text-slate-700 font-bold">{{ service.user?.name || 'Service Pro' }}</span>
              </div>
              <span class="text-[11px] text-slate-500 font-medium">📍 {{ currentLocation.city }}</span>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="p-4 pt-0 flex gap-2">
          <button
            type="button"
            @click="quickViewService = service"
            class="px-3.5 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-200 transition-all cursor-pointer flex items-center gap-1"
          >
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span>Quick View</span>
          </button>

          <NuxtLink
            :to="`/services/${service.id}`"
            class="flex-1 text-center py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-extrabold hover:shadow-md transition-all flex items-center justify-center gap-1"
            style="color: #ffffff !important;"
          >
            <span>View Details →</span>
          </NuxtLink>
        </div>

      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="!loading" class="bg-white rounded-3xl p-16 text-center text-slate-500 border border-slate-200 space-y-3">
      <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
      <h3 class="font-bold text-slate-900 text-base">No services match your search</h3>
      <p class="text-xs text-slate-500">Try clearing filters or searching for another term.</p>
      <button type="button" @click="searchKeyword = ''; selectedCategory = ''; loadServices()" class="px-4 py-2 rounded-xl bg-rose-600 text-white text-xs font-bold" style="color: #ffffff !important;">
        Reset Search Filters
      </button>
    </div>

    <!-- Quick View Modal -->
    <QuickViewModal
      v-if="quickViewService"
      :service="quickViewService"
      @close="quickViewService = null"
    />

  </div>
</template>

<script setup lang="ts">
const api = useApi();
const route = useRoute();
const { t, initLang } = useLanguage();
const { currentLocation, openModal } = useUserLocation();
const { isWishlisted, toggleWishlist } = useWishlist();
const { getServiceImage } = useDefaultImage();

const services         = ref<any[]>([]);
const categories       = ref<any[]>([]);
const selectedCategory = ref('');
const searchKeyword    = ref('');
const quickViewService = ref<any>(null);
const loading          = ref(true);

const filteredServices = computed(() => {
  let list = services.value;
  if (searchKeyword.value.trim()) {
    const kw = searchKeyword.value.trim().toLowerCase();
    list = list.filter((s: any) =>
      s.title?.toLowerCase().includes(kw) ||
      s.description?.toLowerCase().includes(kw) ||
      s.category?.name?.toLowerCase().includes(kw)
    );
  }
  return list;
});

const handleSearchSubmit = () => {
  loadServices();
};

const loadServices = async () => {
  loading.value = true;
  try {
    const params: any = { per_page: 30 };
    if (selectedCategory.value) params.category_id = selectedCategory.value;
    const data = await api.get<any>('/services', params);
    services.value = data.services || [];
  } catch { }
  finally {
    loading.value = false;
  }
};

const loadCategories = async () => {
  try {
    const data = await api.get<any>('/categories');
    categories.value = data.categories || [];
  } catch { }
};

onMounted(async () => {
  initLang();
  await loadCategories();
  if (route.query.category) {
    const cat = categories.value.find((c: any) => c.slug === route.query.category);
    if (cat) selectedCategory.value = cat.id;
  }
  if (route.query.keyword) {
    searchKeyword.value = String(route.query.keyword);
  }
  loadServices();
});
</script>
