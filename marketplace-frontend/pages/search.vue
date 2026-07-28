<template>
  <div class="sp-wrap">

    <!-- ── Header & Location Bar ────────────────────────── -->
    <div class="sp-header">
      <div class="sp-header-title-row">
        <h1 class="sp-title"><span class="sp-title-icon">🔍</span> {{ t('searchTitle') }}</h1>
        <button type="button" @click="openModal" class="sp-loc-badge">
          📍 {{ t('locationLabel') }} <strong>{{ currentLocation.formatted }}</strong> {{ t('clickToChange') }}
        </button>
      </div>
      <p class="sp-sub">{{ t('searchSub') }}</p>
    </div>

    <!-- ── Search & Advanced Filters Panel ──────────────── -->
    <div class="sp-card">

      <!-- Main Search Row -->
      <div class="sp-main-search-row">
        <div class="sp-search-input-wrap">
          <span class="sp-si-icon">🔍</span>
          <input
            v-model="keyword"
            type="text"
            :placeholder="t('searchPlaceholderInput')"
            class="sp-main-input"
            @keydown.enter="handleSearch"
          />
          <button v-if="keyword" type="button" @click="keyword = ''" class="sp-clear-btn">✕</button>
        </div>

        <button
          type="button"
          @click="handleSearch"
          :disabled="searching"
          class="sp-search-btn"
        >
          <span v-if="searching" class="sp-spinner" />
          <template v-else>{{ t('searchBtnLabel') }}</template>
        </button>
      </div>

      <!-- Quick Trending Query Chips -->
      <div class="sp-chips-bar">
        <span class="sp-chips-label">{{ t('popularSearches') }}</span>
        <button
          v-for="chip in trendingChips"
          :key="chip"
          type="button"
          @click="selectChip(chip)"
          class="sp-chip"
          :class="{ active: keyword.toLowerCase() === chip.toLowerCase() }"
        >
          {{ chip }}
        </button>
      </div>

      <div class="sp-divider" />

      <!-- Advanced Filter Controls Grid -->
      <div class="sp-filters-grid">

        <!-- Category Dropdown -->
        <div class="sp-filter-group">
          <label class="sp-label">{{ t('categoryFilter') }}</label>
          <select v-model="category" class="sp-select">
            <option value="">{{ t('allCategoriesOpt') }}</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.slug">{{ cat.name }}</option>
          </select>
        </div>

        <!-- Price Range Slider -->
        <div class="sp-filter-group">
          <div class="sp-label-row">
            <label class="sp-label">{{ t('priceFilter') }}</label>
            <span class="sp-val-highlight">₹{{ maxPrice }}</span>
          </div>
          <input v-model.number="maxPrice" type="range" min="100" max="10000" step="100" class="sp-slider" />
        </div>

        <!-- Radius Range Slider -->
        <div class="sp-filter-group">
          <div class="sp-label-row">
            <label class="sp-label">{{ t('radiusFilter') }}</label>
            <span class="sp-val-highlight">{{ radius }} km</span>
          </div>
          <input v-model.number="radius" type="range" min="1" max="100" class="sp-slider" />
        </div>

        <!-- Minimum Rating Filter -->
        <div class="sp-filter-group">
          <label class="sp-label">{{ t('ratingFilter') }}</label>
          <select v-model="minRating" class="sp-select">
            <option value="0">{{ t('anyRating') }}</option>
            <option value="4.5">{{ t('topRated') }}</option>
            <option value="4.0">{{ t('aboveFour') }}</option>
            <option value="3.5">{{ t('aboveThree') }}</option>
          </select>
        </div>

        <!-- Sort By -->
        <div class="sp-filter-group">
          <label class="sp-label">🔃 {{ t('sortBy') }}</label>
          <select v-model="sortBy" class="sp-select">
            <option value="relevance">{{ t('relevance') }}</option>
            <option value="rating">Highest Rated</option>
            <option value="price_asc">{{ t('priceLowHigh') }}</option>
            <option value="price_desc">{{ t('priceHighLow') }}</option>
            <option value="distance">{{ t('distanceNearFar') }}</option>
          </select>
        </div>

      </div>

      <!-- Labels & Feature Toggles -->
      <div class="sp-labels-section">
        <label class="sp-label mb-2 block">🏷️ Filter by Requirement & Badges</label>
        <div class="sp-toggles-row">
          <button
            type="button"
            @click="toggleFeature('express')"
            class="sp-toggle-pill"
            :class="{ active: selectedFeatures.includes('express') }"
          >
            ⚡ Express 2-Hour Delivery
          </button>
          <button
            type="button"
            @click="toggleFeature('verified')"
            class="sp-toggle-pill"
            :class="{ active: selectedFeatures.includes('verified') }"
          >
            ✔️ Verified Professionals
          </button>
          <button
            type="button"
            @click="toggleFeature('trending')"
            class="sp-toggle-pill"
            :class="{ active: selectedFeatures.includes('trending') }"
          >
            🔥 Trending & Hot Deals
          </button>
          <button
            type="button"
            @click="toggleFeature('warranty')"
            class="sp-toggle-pill"
            :class="{ active: selectedFeatures.includes('warranty') }"
          >
            🛡️ 30-Day Warranty
          </button>
        </div>
      </div>

      <!-- Reset & Active Filters Row -->
      <div class="sp-active-bar" v-if="hasActiveFilters">
        <span class="sp-af-text">Filters Active</span>
        <button type="button" @click="resetFilters" class="sp-reset-btn">Reset All Filters 🔄</button>
      </div>

    </div>

    <!-- ── Results Section ──────────────────────────────── -->
    <div class="sp-results-header">
      <h2 class="sp-rh-title">
        Available Services in {{ currentLocation.city }}
        <span class="sp-rh-count">({{ filteredResults.length }} results)</span>
      </h2>
      <div class="sp-rh-tag">within {{ radius }} km radius</div>
    </div>

    <!-- Shimmer Skeleton Loading State -->
    <div v-if="searching" class="sp-grid">
      <div v-for="n in 6" :key="n" class="bg-white rounded-2xl p-4 border border-slate-200 space-y-3">
        <div class="h-44 shimmer-skeleton rounded-xl w-full" />
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 shimmer-skeleton rounded-full flex-shrink-0" />
          <div class="space-y-1.5 flex-1">
            <div class="h-3.5 shimmer-skeleton rounded w-3/4" />
            <div class="h-3 shimmer-skeleton rounded w-1/2" />
          </div>
        </div>
        <div class="h-4 shimmer-skeleton rounded w-5/6" />
        <div class="flex justify-between items-center pt-2">
          <div class="h-5 shimmer-skeleton rounded w-1/3" />
          <div class="h-8 shimmer-skeleton rounded-lg w-1/2" />
        </div>
      </div>
    </div>

    <!-- Service Cards Grid -->
    <div v-else-if="filteredResults.length" class="sp-grid">
      <div
        v-for="(r, i) in filteredResults"
        :key="r.id || i"
        class="sp-result-card group"
        :style="{ animationDelay: `${i * 0.04}s` }"
      >
        <!-- Card Image & Top Badges -->
        <div class="sp-card-media">
          <img :src="getServiceImage(r)" :alt="r.service_title || r.title" class="sp-card-img" />

          <!-- Badges / Labels -->
          <div class="sp-card-badges">
            <span v-if="r.badge || i % 2 === 0" class="sp-badge badge-trending">🔥 Trending</span>
            <span class="sp-badge badge-cat">{{ r.category_name || r.category?.name || 'Service' }}</span>
            <span v-if="r.express || i % 3 === 0" class="sp-badge badge-express">⚡ Express 2hr</span>
          </div>

          <!-- Wishlist Toggle SVG -->
          <button
            type="button"
            @click="toggleWishlist(r.id || r.service_id, r.service_title || r.title)"
            class="sp-card-wish"
            :class="{ active: isWishlisted(r.id || r.service_id) }"
            :title="isWishlisted(r.id || r.service_id) ? 'Remove from Wishlist' : 'Save to Wishlist'"
          >
            <svg class="w-4 h-4 transition-colors" :class="isWishlisted(r.id || r.service_id) ? 'text-rose-600 fill-rose-600' : 'text-slate-400 fill-none'" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </button>
        </div>

        <!-- Card Body -->
        <div class="sp-card-body">
          <div class="sp-worker-row">
            <div class="sp-avatar">{{ (r.worker_name || r.user?.name || 'S')?.charAt(0)?.toUpperCase() }}</div>
            <div class="sp-worker-info">
              <div class="sp-worker-name">{{ r.worker_name || r.user?.name || 'Service Provider' }}</div>
              <div class="sp-worker-area">📍 {{ r.address || currentLocation.area }}</div>
            </div>
            <span class="sp-dist-badge">{{ r.distance || '2.4 km' }}</span>
          </div>

          <h3 class="sp-service-title">{{ r.service_title || r.title }}</h3>

          <!-- Rating & Price Row -->
          <div class="sp-meta-row">
            <div class="sp-rating">
              <span class="sp-star">★</span>
              <span class="sp-rating-num">{{ r.reviews_avg || r.rating || '4.8' }}</span>
              <span class="sp-rating-cnt">({{ r.reviews_count || r.reviews || 18 }})</span>
            </div>

            <div class="sp-price-box">
              <template v-if="r.price_min || r.price_max">
                ₹{{ r.price_min || 0 }} – ₹{{ r.price_max || 0 }}
              </template>
              <template v-else>₹{{ r.price || 499 }}</template>
            </div>
          </div>

          <!-- Buttons -->
          <div class="sp-card-actions">
            <button
              type="button"
              @click="quickViewService = r"
              class="sp-qv-btn flex items-center gap-1 cursor-pointer"
            >
              <svg class="w-3.5 h-3.5 text-slate-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              <span>Quick View</span>
            </button>
            <NuxtLink :to="`/services/${r.id || r.service_id}`" class="sp-book-btn" style="color: #ffffff !important;">
              <span class="text-white font-bold">Book Service →</span>
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="sp-empty">
      <div class="sp-empty-icon">📭</div>
      <h3 class="sp-empty-title">No services found for selected filters</h3>
      <p class="sp-empty-tip">Try adjusting your price range, radius, or reset active filters to see all available services in {{ currentLocation.city }}.</p>
      <button type="button" @click="resetFilters" class="sp-reset-btn-large">Reset All Filters 🔄</button>
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

const categories = ref<any[]>([]);
const results = ref<any[]>([]);

const keyword = ref('');
const category = ref('');
const radius = ref(25);
const maxPrice = ref(5000);
const minRating = ref('0');
const sortBy = ref('relevance');
const selectedFeatures = ref<string[]>([]);
const searching = ref(false);
const quickViewService = ref<any>(null);

const trendingChips = [
  'AC Repair Ahmedabad',
  'Plumber Near Me',
  'Electrician 380001',
  'Full House Cleaning',
  'RO Purifier Service',
  'Washing Machine Fix',
  'Interior Painter'
];

const selectChip = (chipText: string) => {
  keyword.value = chipText;
  handleSearch();
};

const toggleFeature = (featKey: string) => {
  const idx = selectedFeatures.value.indexOf(featKey);
  if (idx >= 0) selectedFeatures.value.splice(idx, 1);
  else selectedFeatures.value.push(featKey);
};

const hasActiveFilters = computed(() => {
  return !!category.value || maxPrice.value < 5000 || minRating.value !== '0' || selectedFeatures.value.length > 0 || !!keyword.value;
});

const resetFilters = () => {
  keyword.value = '';
  category.value = '';
  radius.value = 25;
  maxPrice.value = 5000;
  minRating.value = '0';
  sortBy.value = 'relevance';
  selectedFeatures.value = [];
  handleSearch();
};

const handleSearch = async () => {
  searching.value = true;
  try {
    const params: any = {
      lat: currentLocation.value.lat,
      lng: currentLocation.value.lng,
      radius: radius.value
    };
    if (category.value) params.category = category.value;
    if (keyword.value) params.keyword = keyword.value;

    const data = await api.get<any>('/search', params);
    results.value = data.results || [];
  } catch {
    results.value = [];
  } finally {
    searching.value = false;
  }
};

const filteredResults = computed(() => {
  let list = [...results.value];

  // Price filter
  list = list.filter(item => {
    const price = item.price_min || item.price || 0;
    return price <= maxPrice.value;
  });

  // Rating filter
  if (minRating.value !== '0') {
    const minR = parseFloat(minRating.value);
    list = list.filter(item => (parseFloat(item.reviews_avg || item.rating || '4.5') >= minR));
  }

  // Sorting
  if (sortBy.value === 'price_asc') {
    list.sort((a, b) => (a.price_min || a.price || 0) - (b.price_min || b.price || 0));
  } else if (sortBy.value === 'price_desc') {
    list.sort((a, b) => (b.price_min || b.price || 0) - (a.price_min || a.price || 0));
  } else if (sortBy.value === 'rating') {
    list.sort((a, b) => (parseFloat(b.reviews_avg || '4.8')) - (parseFloat(a.reviews_avg || '4.8')));
  }

  return list;
});

onMounted(async () => {
  initLang();
  if (route.query.keyword) keyword.value = String(route.query.keyword);
  if (route.query.category) category.value = String(route.query.category);

  try {
    const data = await api.get<any>('/categories');
    categories.value = data.categories || [];
  } catch { }

  handleSearch();
});

watch(() => currentLocation.value.city, () => {
  handleSearch();
});
</script>

<style scoped>
.sp-wrap { max-width: 80rem; margin: 0 auto; padding: 2rem 1.25rem 5rem; }

.sp-header { margin-bottom: 1.5rem; }
.sp-header-title-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 0.5rem; }
.sp-title { font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800; color: #fff; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
.light .sp-title { color: #0f172a; }
.sp-title-icon { font-size: 1.5rem; }

.sp-loc-badge {
  background: #fef2f5; border: 1.5px solid #fbc0d0;
  color: #b20537; font-size: 0.8125rem; padding: 6px 14px; border-radius: 9999px;
  cursor: pointer; transition: all 0.2s; font-weight: 600;
}
.dark .sp-loc-badge { background: rgba(178,5,55,0.12); border-color: rgba(178,5,55,0.4); color: #f43f5e; }
.sp-loc-badge:hover { background: #b20537; color: #fff; border-color: #b20537; }
.sp-sub { color: #64748b; font-size: 0.9375rem; margin: 0; }
.dark .sp-sub { color: #94a3b8; }

.sp-card {
  background: #ffffff; border: 1px solid #e2e8f0;
  border-radius: 1.25rem; padding: 1.5rem; margin-bottom: 2rem;
  display: flex; flex-direction: column; gap: 1.25rem; box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}
.dark .sp-card { background: rgba(30, 41, 59, 0.7); border-color: rgba(255,255,255,0.08); shadow: none; }

.sp-main-search-row { display: flex; gap: 0.75rem; }
@media (max-width: 640px) { .sp-main-search-row { flex-direction: column; } }

.sp-search-input-wrap {
  flex: 1; position: relative; display: flex; align-items: center;
  background: #f8fafc; border: 1.5px solid #cbd5e1;
  border-radius: 0.75rem; padding: 0 1rem;
}
.dark .sp-search-input-wrap { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.12); }
.sp-si-icon { font-size: 1rem; color: #64748b; margin-right: 0.5rem; }
.sp-main-input { flex: 1; height: 3rem; background: transparent; border: none; color: #0f172a; font-size: 0.9375rem; outline: none; font-weight: 500; }
.dark .sp-main-input { color: #fff; }
.sp-clear-btn { background: none; border: none; color: #64748b; font-size: 1rem; cursor: pointer; }

.sp-search-btn {
  padding: 0 1.75rem; height: 3rem; border-radius: 0.75rem; border: none;
  background: linear-gradient(135deg, #B20537, #D4064A, #F43F5E);
  color: #fff; font-weight: 700; font-size: 0.9375rem; cursor: pointer;
  transition: box-shadow 0.2s; white-space: nowrap;
}
.sp-search-btn:hover:not(:disabled) { box-shadow: 0 0 20px rgba(178,5,55,0.4); }

.sp-chips-bar { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.sp-chips-label { font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
.dark .sp-chips-label { color: #94a3b8; }
.sp-chip {
  padding: 4px 12px; border-radius: 9999px;
  background: #f1f5f9; border: 1px solid #e2e8f0;
  color: #334155; font-size: 0.78rem; cursor: pointer; transition: all 0.15s; font-weight: 500;
}
.dark .sp-chip { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); color: #cbd5e1; }
.sp-chip:hover, .sp-chip.active { background: #fef2f5; border-color: #b20537; color: #b20537; font-weight: 700; }

.sp-divider { height: 1px; background: #e2e8f0; }
.dark .sp-divider { background: rgba(255,255,255,0.06); }

.sp-filters-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; }
@media (max-width: 1024px) { .sp-filters-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px) { .sp-filters-grid { grid-template-columns: 1fr; } }

.sp-filter-group { display: flex; flex-direction: column; gap: 0.375rem; }
.sp-label { font-size: 0.8125rem; font-weight: 600; color: #475569; }
.dark .sp-label { color: #94a3b8; }
.sp-label-row { display: flex; justify-content: space-between; align-items: center; }
.sp-val-highlight { font-size: 0.8125rem; font-weight: 700; color: #b20537; }
.dark .sp-val-highlight { color: #f43f5e; }

.sp-select {
  padding: 0.625rem 0.875rem; border-radius: 0.625rem;
  background: #f8fafc; border: 1.5px solid #cbd5e1;
  color: #0f172a; font-size: 0.8125rem; outline: none; cursor: pointer; font-weight: 500;
}
.dark .sp-select { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.12); color: #fff; }
.sp-select option { background: #ffffff; color: #0f172a; }
.dark .sp-select option { background: #1e293b; color: #fff; }

.sp-slider { accent-color: #B20537; cursor: pointer; margin-top: 4px; }

.sp-toggles-row { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.sp-toggle-pill {
  padding: 6px 14px; border-radius: 9999px;
  background: #f1f5f9; border: 1px solid #e2e8f0;
  color: #475569; font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: all 0.15s;
}
.dark .sp-toggle-pill { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); color: #94a3b8; }
.sp-toggle-pill:hover { background: #e2e8f0; color: #0f172a; }
.sp-toggle-pill.active { background: #fef2f5; border-color: #b20537; color: #b20537; }

.sp-active-bar { display: flex; align-items: center; justify-content: space-between; padding-top: 0.5rem; border-top: 1px dashed #cbd5e1; }
.sp-af-text { font-size: 0.8125rem; font-weight: 700; color: #b20537; }
.sp-reset-btn { background: none; border: none; color: #64748b; font-size: 0.8125rem; cursor: pointer; text-decoration: underline; font-weight: 600; }
.sp-reset-btn:hover { color: #b20537; }

.sp-results-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
.sp-rh-title { font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0; }
.dark .sp-rh-title { color: #fff; }
.sp-rh-count { color: #b20537; }
.sp-rh-tag { font-size: 0.8125rem; color: #94a3b8; background: rgba(255,255,255,0.05); padding: 4px 12px; border-radius: 9999px; }

/* Grid & Cards */
.sp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(20rem, 1fr)); gap: 1.25rem; }

.sp-result-card {
  background: rgba(30, 41, 59, 0.75); border: 1px solid rgba(255,255,255,0.08);
  border-radius: 1.25rem; overflow: hidden; display: flex; flex-direction: column;
  transition: all 0.3s cubic-bezier(0.4,0,0.2,1); box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}
.light .sp-result-card { background: #ffffff; border-color: rgba(0,0,0,0.08); }
.sp-result-card:hover { transform: translateY(-4px); border-color: rgba(178,5,55,0.4); box-shadow: 0 12px 30px rgba(178,5,55,0.15); }

.sp-card-media { position: relative; height: 12rem; background: #0f172a; overflow: hidden; }
.sp-card-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.sp-result-card:hover .sp-card-img { transform: scale(1.05); }
.sp-card-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3rem; opacity: 0.3; }

.sp-card-badges { position: absolute; top: 0.75rem; left: 0.75rem; display: flex; flex-direction: column; gap: 4px; align-items: flex-start; }
.sp-badge { padding: 3px 8px; border-radius: 9999px; font-size: 0.6875rem; font-weight: 700; }
.badge-trending { background: #ef4444; color: #fff; }
.badge-cat { background: rgba(15,23,42,0.85); color: #38bdf8; backdrop-filter: blur(4px); }
.badge-express { background: #f59e0b; color: #000; }

.sp-card-wish {
  position: absolute; top: 0.75rem; right: 0.75rem;
  width: 2rem; height: 2rem; border-radius: 50%;
  background: rgba(15,23,42,0.7); backdrop-filter: blur(4px); border: none;
  cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.9375rem;
}
.sp-card-wish.active { background: rgba(239,68,68,0.2); }

.sp-card-body { padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; flex: 1; }

.sp-worker-row { display: flex; align-items: center; gap: 0.625rem; }
.sp-avatar {
  width: 2.25rem; height: 2.25rem; border-radius: 50%;
  background: linear-gradient(135deg, #B20537, #F43F5E);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-weight: 700; font-size: 0.875rem; flex-shrink: 0;
}
.sp-worker-info { flex: 1; min-width: 0; }
.sp-worker-name { font-size: 0.875rem; font-weight: 600; color: #fff; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.light .sp-worker-name { color: #0f172a; }
.sp-worker-area { font-size: 0.75rem; color: #94a3b8; }
.sp-dist-badge { font-size: 0.72rem; font-weight: 700; color: #f43f5e; background: rgba(178,5,55,0.1); padding: 2px 8px; border-radius: 9999px; }

.sp-service-title { font-family: 'Outfit', sans-serif; font-size: 1.0625rem; font-weight: 700; color: #fff; margin: 0; line-height: 1.3; }
.light .sp-service-title { color: #0f172a; }

.sp-meta-row { display: flex; align-items: center; justify-content: space-between; }
.sp-rating { display: flex; align-items: center; gap: 3px; font-size: 0.8125rem; }
.sp-star { color: #f59e0b; }
.sp-rating-num { font-weight: 700; color: #fff; }
.light .sp-rating-num { color: #0f172a; }
.sp-rating-cnt { color: #64748b; font-size: 0.75rem; }

.sp-price-box { font-size: 1.0625rem; font-weight: 800; color: #f43f5e; }

.sp-card-actions { display: flex; gap: 0.5rem; margin-top: 0.25rem; }
.sp-qv-btn {
  padding: 0.5rem 0.875rem; border-radius: 0.625rem;
  background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
  color: #cbd5e1; font-size: 0.8125rem; font-weight: 600; cursor: pointer; transition: all 0.15s;
}
.light .sp-qv-btn { background: #f1f5f9; border-color: rgba(0,0,0,0.1); color: #334155; }
.sp-qv-btn:hover { background: rgba(178,5,55,0.15); color: #f43f5e; border-color: rgba(178,5,55,0.3); }

.sp-book-btn {
  flex: 1; text-align: center; padding: 0.5rem 0.875rem; border-radius: 0.625rem;
  background: linear-gradient(135deg, #B20537, #D4064A, #F43F5E);
  color: #fff; font-size: 0.8125rem; font-weight: 700; text-decoration: none;
  transition: opacity 0.15s;
}
.sp-book-btn:hover { opacity: 0.9; }

.sp-empty { text-align: center; padding: 4rem 1rem; display: flex; flex-direction: column; align-items: center; gap: 1rem; }
.sp-empty-icon { font-size: 4rem; }
.sp-empty-title { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0; }
.light .sp-empty-title { color: #0f172a; }
.sp-empty-tip { color: #94a3b8; max-width: 28rem; text-align: center; margin: 0; }
.sp-reset-btn-large {
  padding: 0.75rem 1.5rem; border-radius: 0.75rem; border: none;
  background: #B20537; color: #fff; font-weight: 700; cursor: pointer;
}

.sp-spinner {
  display: inline-block; width: 16px; height: 16px;
  border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff;
  border-radius: 50%; animation: spin 0.65s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
