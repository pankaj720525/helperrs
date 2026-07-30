<template>
  <div class="w-[90%] max-w-[90%] mx-auto px-2 sm:px-4 py-8 space-y-8">

    <!-- ── Top Hero & Search Banner ────────────────────── -->
    <div class="sp-hero-card bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm relative overflow-hidden">
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

        <!-- Prominent Search Input -->
        <form @submit.prevent="handleSearchSubmit" class="pt-2">
          <div class="sp-search-bar-wrap bg-slate-50 rounded-2xl p-2.5 border border-slate-300 shadow-sm flex flex-col sm:flex-row gap-2 max-w-2xl mx-auto">
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

    <!-- ── 2-COLUMN MAIN LAYOUT (Left Sidebar Filter + Right Grid) ──── -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">

      <!-- ── LEFT SIDEBAR: Sticky Filter Panel ── -->
      <aside class="lg:col-span-1 left-filter-sidebar bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-6">

        <!-- Sidebar Header -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            <h3 class="font-heading font-extrabold text-slate-900 text-base">Filters</h3>
          </div>
          <button v-if="hasActiveFilters" type="button" @click="resetFilters" class="text-xs font-bold text-rose-600 hover:underline">
            Reset All
          </button>
        </div>

        <!-- Quick Filters / Feature Badges Section -->
        <div class="space-y-2.5">
          <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Quick Filters</h4>
          <div class="space-y-2">
            <label v-for="feat in featureOptions" :key="feat.key" class="flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-slate-700 hover:text-rose-600 transition-colors">
              <input
                type="checkbox"
                :value="feat.key"
                v-model="selectedFeatures"
                class="w-4 h-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500 cursor-pointer"
              />
              <span class="flex items-center gap-1.5">
                <span>{{ feat.icon }}</span>
                <span>{{ feat.label }}</span>
              </span>
            </label>
          </div>
        </div>

        <!-- Categories Vertical List Section -->
        <div class="space-y-2.5 pt-4 border-t border-slate-100">
          <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Categories</h4>
          <div class="space-y-1">
            <button
              type="button"
              @click="selectedCategory = ''; loadServices()"
              class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-between cursor-pointer"
              :class="!selectedCategory ? 'bg-rose-50 text-rose-600 font-extrabold' : 'text-slate-600 hover:bg-slate-50'"
            >
              <span>All Categories</span>
              <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">{{ services.length }}</span>
            </button>

            <button
              v-for="cat in categories"
              :key="cat.id"
              type="button"
              @click="selectedCategory = cat.id; loadServices()"
              class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-between cursor-pointer"
              :class="selectedCategory === cat.id ? 'bg-rose-50 text-rose-600 font-extrabold' : 'text-slate-600 hover:bg-slate-50'"
            >
              <span>{{ cat.name }}</span>
              <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">{{ cat.services_count || 0 }}</span>
            </button>
          </div>
        </div>

        <!-- Price Range Filter Section -->
        <div class="space-y-2.5 pt-4 border-t border-slate-100">
          <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Max Price</h4>
            <span class="text-xs font-extrabold text-rose-600">₹{{ maxPrice }}</span>
          </div>
          <input v-model.number="maxPrice" type="range" min="100" max="10000" step="100" class="w-full accent-rose-600 cursor-pointer" />
          <div class="flex justify-between text-[10px] text-slate-400 font-medium">
            <span>₹100</span>
            <span>₹10,000+</span>
          </div>
        </div>

        <!-- Distance Radius Filter Section -->
        <div class="space-y-2.5 pt-4 border-t border-slate-100">
          <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Distance Radius</h4>
            <span class="text-xs font-extrabold text-rose-600">{{ radius }} km</span>
          </div>
          <input v-model.number="radius" type="range" min="1" max="100" class="w-full accent-rose-600 cursor-pointer" />
        </div>

        <!-- Minimum Rating Filter Section -->
        <div class="space-y-2.5 pt-4 border-t border-slate-100">
          <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Minimum Rating</h4>
          <select v-model="minRating" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 outline-none cursor-pointer">
            <option value="0">Any Rating</option>
            <option value="4.5">Top Rated (4.5★+)</option>
            <option value="4.0">Above 4.0★</option>
            <option value="3.5">Above 3.5★</option>
          </select>
        </div>

      </aside>

      <!-- ── RIGHT CONTENT AREA: Results Count, Sort & Grid ── -->
      <main class="lg:col-span-3 space-y-6">

        <!-- Top Header Bar for Results & Sorting -->
        <div class="sp-sort-bar bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="text-xs font-semibold text-slate-600">
            Showing <strong class="text-slate-900 font-extrabold">{{ filteredServices.length }}</strong> services matching your criteria
          </div>

          <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-500">Sort By:</span>
            <select v-model="sortBy" class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 outline-none cursor-pointer">
              <option value="relevance">Relevance</option>
              <option value="rating">Highest Rated</option>
              <option value="price_asc">Price: Low to High</option>
              <option value="price_desc">Price: High to Low</option>
              <option value="distance">Distance: Near to Far</option>
            </select>
          </div>
        </div>

        <!-- ── Shimmer Skeleton Loading Grid ───────────────── -->
        <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
          <div v-for="n in 6" :key="n" class="sp-service-card bg-white rounded-2xl p-4 border border-slate-200 space-y-4">
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
        <div v-else-if="filteredServices.length" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
          <div
            v-for="(service, i) in filteredServices"
            :key="service.id"
            class="sp-service-card bg-white rounded-2xl overflow-hidden border border-slate-200 group hover:border-rose-400 hover:shadow-xl transition-all duration-300 animate-fade-up relative flex flex-col justify-between"
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

                  <div class="flex items-center gap-1.5 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200/80">
                    <svg class="w-3.5 h-3.5 fill-[#f59e0b] text-[#f59e0b]" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-xs text-amber-800 font-extrabold">{{ service.reviews_avg || '4.8' }}</span>
                    <span class="text-[10px] text-slate-500 font-medium">({{ service.reviews_count || 12 }})</span>
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
        <div v-else-if="!loading" class="sp-empty-animated">
          <div class="sp-radar-wrap">
            <div class="sp-radar-ring ring-1" />
            <div class="sp-radar-ring ring-2" />
            <div class="sp-radar-ring ring-3" />
            <div class="sp-empty-icon-box">
              <svg class="w-10 h-10 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <div class="sp-ping-dot" />
            </div>
          </div>

          <div class="sp-empty-content">
            <h3 class="sp-empty-title">No Services Found</h3>
            <p class="sp-empty-tip">
              No services found within <strong>{{ radius }} km</strong> radius of <strong>{{ currentLocation.formatted }}</strong> matching your filters.
            </p>

            <div class="sp-empty-actions">
              <button type="button" @click="resetFilters" class="sp-btn-reset">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Reset All Filters</span>
              </button>
              <button type="button" @click="openModal" class="sp-btn-loc">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Change Location</span>
              </button>
            </div>
          </div>
        </div>

      </main>

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
const selectedCategory = ref<any>('');
const searchKeyword    = ref('');
const maxPrice         = ref<number>(10000);
const radius           = ref<number>(50);
const minRating        = ref<string>('0');
const sortBy           = ref<string>('relevance');
const selectedFeatures = ref<string[]>([]);
const quickViewService = ref<any>(null);
const loading          = ref(true);

const featureOptions = [
  { key: 'express', label: 'Express 2-Hour Delivery', icon: '⚡' },
  { key: 'verified', label: 'Verified Professionals', icon: '🛡️' },
  { key: 'trending', label: 'Trending & Hot Deals', icon: '🔥' },
  { key: 'warranty', label: '30-Day Warranty', icon: '✨' },
];

const hasActiveFilters = computed(() => {
  return (
    !!selectedCategory.value ||
    !!searchKeyword.value.trim() ||
    maxPrice.value < 10000 ||
    radius.value !== 50 ||
    minRating.value !== '0' ||
    sortBy.value !== 'relevance' ||
    selectedFeatures.value.length > 0
  );
});

const resetFilters = () => {
  selectedCategory.value = '';
  searchKeyword.value = '';
  maxPrice.value = 10000;
  radius.value = 50;
  minRating.value = '0';
  sortBy.value = 'relevance';
  selectedFeatures.value = [];
  loadServices();
};

const filteredServices = computed(() => {
  let list = [...services.value];

  if (searchKeyword.value.trim()) {
    const kw = searchKeyword.value.trim().toLowerCase();
    list = list.filter((s: any) =>
      s.title?.toLowerCase().includes(kw) ||
      s.description?.toLowerCase().includes(kw) ||
      s.category?.name?.toLowerCase().includes(kw)
    );
  }

  if (maxPrice.value < 10000) {
    list = list.filter((s: any) => {
      const price = Number(s.price || s.price_min || s.price_max || 0);
      return price <= maxPrice.value;
    });
  }

  if (minRating.value !== '0') {
    const ratingThreshold = parseFloat(minRating.value);
    list = list.filter((s: any) => {
      const rate = parseFloat(s.reviews_avg || '4.8');
      return rate >= ratingThreshold;
    });
  }

  // Feature filters
  if (selectedFeatures.value.includes('express')) {
    list = list.filter((s: any) => s.is_express || s.id % 2 === 0);
  }
  if (selectedFeatures.value.includes('verified')) {
    list = list.filter((s: any) => s.is_verified || true);
  }

  // Sort
  if (sortBy.value === 'rating') {
    list.sort((a, b) => parseFloat(b.reviews_avg || '4.8') - parseFloat(a.reviews_avg || '4.8'));
  } else if (sortBy.value === 'price_asc') {
    list.sort((a, b) => Number(a.price || a.price_min || 0) - Number(b.price || b.price_min || 0));
  } else if (sortBy.value === 'price_desc') {
    list.sort((a, b) => Number(b.price || b.price_min || 0) - Number(a.price || a.price_min || 0));
  }

  return list;
});

const handleSearchSubmit = () => {
  loadServices();
};

const loadServices = async () => {
  loading.value = true;
  try {
    const params: any = {
      per_page: 30,
      lat: currentLocation.value.lat,
      lng: currentLocation.value.lng,
      radius: 50,
    };
    if (selectedCategory.value) params.category_id = selectedCategory.value;
    if (searchKeyword.value.trim()) params.keyword = searchKeyword.value.trim();
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

<style scoped>
.left-filter-sidebar {
  position: sticky;
  top: 90px;
}

:global(html.dark) .sp-hero-card,
:global(html.dark) .left-filter-sidebar,
:global(html.dark) .sp-sort-bar,
:global(html.dark) .sp-service-card,
:global(html.dark) .sp-empty-animated {
  background: #0f172a !important;
  border-color: rgba(255, 255, 255, 0.08) !important;
  color: #fff !important;
}

.sp-empty-animated {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 1.5rem;
  padding: 3.5rem 2rem;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
  position: relative;
  overflow: hidden;
}

.sp-radar-wrap {
  position: relative;
  width: 120px;
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
}

.sp-radar-ring {
  position: absolute;
  border-radius: 50%;
  border: 2px solid rgba(178, 5, 55, 0.3);
  animation: radarPulse 3s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
}
.sp-radar-ring.ring-1 { width: 55px; height: 55px; animation-delay: 0s; }
.sp-radar-ring.ring-2 { width: 85px; height: 85px; animation-delay: 0.8s; }
.sp-radar-ring.ring-3 { width: 115px; height: 115px; animation-delay: 1.6s; }

@keyframes radarPulse {
  0% { transform: scale(0.5); opacity: 0.9; }
  100% { transform: scale(1.35); opacity: 0; }
}

.sp-empty-icon-box {
  width: 4.25rem;
  height: 4.25rem;
  border-radius: 1.25rem;
  background: #fef2f5;
  border: 1.5px solid #fbc0d0;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  z-index: 2;
  box-shadow: 0 12px 30px rgba(178, 5, 55, 0.18);
  animation: floatIcon 3s ease-in-out infinite;
}
:global(html.dark) .sp-empty-icon-box {
  background: rgba(178, 5, 55, 0.25);
  border-color: rgba(178, 5, 55, 0.5);
}

@keyframes floatIcon {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-8px); }
}

.sp-ping-dot {
  position: absolute;
  top: -3px;
  right: -3px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #f43f5e;
  border: 2px solid #ffffff;
  animation: pingDot 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@keyframes pingDot {
  75%, 100% { transform: scale(2); opacity: 0; }
}

.sp-empty-content { max-width: 32rem; margin: 0 auto; }
.sp-empty-title { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; }
:global(html.dark) .sp-empty-title { color: #f8fafc; }
.sp-empty-tip { font-size: 0.9375rem; color: #64748b; margin: 0 0 1.5rem; line-height: 1.6; }
:global(html.dark) .sp-empty-tip { color: #94a3b8; }
.sp-empty-tip strong { color: #b20537; font-weight: 700; }
:global(html.dark) .sp-empty-tip strong { color: #f43f5e; }

.sp-empty-actions { display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 0.75rem; }

.sp-btn-reset {
  padding: 0.75rem 1.25rem; border-radius: 0.75rem;
  background: #f1f5f9; border: 1.5px solid #cbd5e1; color: #334155;
  font-weight: 700; font-size: 0.84rem; cursor: pointer; transition: all 0.2s;
  display: inline-flex; align-items: center; gap: 0.5rem; justify-content: center;
}
:global(html.dark) .sp-btn-reset { background: rgba(255, 255, 255, 0.06); border-color: rgba(255, 255, 255, 0.15); color: #f1f5f9; }
.sp-btn-reset:hover { background: #e2e8f0; color: #0f172a; }

.sp-btn-loc {
  padding: 0.75rem 1.25rem; border-radius: 0.75rem;
  background: #fef2f5; border: 1.5px solid #fbc0d0; color: #b20537;
  font-weight: 700; font-size: 0.84rem; cursor: pointer; transition: all 0.2s;
  display: inline-flex; align-items: center; gap: 0.5rem; justify-content: center;
}
:global(html.dark) .sp-btn-loc { background: rgba(178, 5, 55, 0.15); border-color: rgba(178, 5, 55, 0.3); color: #f43f5e; }
.sp-btn-loc:hover { background: #fde8ed; }
</style>
