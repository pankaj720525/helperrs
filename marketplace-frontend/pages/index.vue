<template>
  <div>

    <!-- ── Hero Section ────────────────────────────────── -->
    <section class="relative overflow-hidden pt-8 pb-20 md:pb-28">
      <!-- Background spheres -->
      <div class="absolute inset-0 gradient-hero" />
      <div class="absolute inset-0 opacity-20 pointer-events-none">
        <div class="absolute top-10 left-10 w-96 h-96 bg-primary/30 rounded-full blur-3xl animate-float" />
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-rose-500/20 rounded-full blur-3xl animate-float" style="animation-delay: -3s" />
      </div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6">

        <!-- Active Location Announcement Pill -->
        <div class="flex justify-center mb-6 animate-fade-down">
          <button
            type="button"
            @click="openModal"
            class="location-announcement-pill inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 border border-slate-200 backdrop-blur text-xs text-slate-700 hover:border-primary/40 hover:text-primary-dark transition-all shadow-sm"
          >
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping" />
            <span>{{ t('servicingLabel') }} <strong>{{ currentLocation.formatted }}</strong> & 50+ Cities</span>
            <span class="text-primary-light font-bold flex items-center gap-1">
              {{ t('changeLabel') }}
              <svg class="w-3.5 h-3.5 inline text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </span>
          </button>
        </div>

        <div class="max-w-4xl mx-auto text-center">
          <h1 class="text-4xl md:text-6xl font-heading font-extrabold text-slate-900 mb-6 tracking-tight animate-fade-up">
            {{ t('heroTitlePre') }}<br />
            <span class="text-gradient">{{ t('heroTitlePost') }} {{ currentLocation.city }}</span>
          </h1>
          <p class="text-lg text-slate-600 mb-8 max-w-2xl mx-auto animate-fade-up" style="animation-delay: 0.1s">
            {{ t('heroSubtitleNew') }}
          </p>

          <!-- Main Hero Search Bar -->
          <div class="glass rounded-2xl p-3 max-w-3xl mx-auto shadow-xl animate-fade-up" style="animation-delay: 0.2s">
            <form @submit.prevent="handleHeroSearch" class="flex flex-col sm:flex-row gap-3">
              <!-- Location Indicator inside Search -->
              <button type="button" @click="openModal" class="hero-search-loc-btn flex-shrink-0 px-4 py-3 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-800 hover:bg-slate-50 flex items-center justify-between sm:justify-start gap-2">
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  {{ currentLocation.city }}
                </span>
                <span class="text-slate-400">▼</span>
              </button>

              <!-- Category Select -->
              <select v-model="searchCategory" class="hero-search-select flex-shrink-0 px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-800 focus:outline-none focus:border-primary/50 text-sm font-medium">
                <option value="">{{ t('allCategoriesOpt') }}</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.slug">{{ cat.name }}</option>
              </select>

              <!-- Keyword input -->
              <input
                v-model="searchKeyword"
                type="text"
                :placeholder="t('searchPlaceholder')"
                class="hero-search-input flex-1 min-w-0 px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 placeholder-slate-400 text-sm font-medium focus:outline-none focus:border-primary/50"
              />

              <!-- Submit -->
              <button type="submit" class="flex-shrink-0 px-8 py-3 rounded-xl gradient-primary text-white font-bold text-sm hover:shadow-glow transition-all whitespace-nowrap flex items-center justify-center gap-2 cursor-pointer" style="color: #ffffff !important;">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="text-white font-bold">{{ t('findServices') }}</span>
              </button>
            </form>

            <!-- Quick Chips -->
            <div class="flex items-center justify-center gap-2 flex-wrap mt-3 pt-3 border-t border-slate-200/80 text-xs text-slate-600">
              <span class="font-bold text-slate-500">{{ t('trendingLabel') }}:</span>
              <button
                v-for="chip in ['AC Service', 'Plumber', 'Electrician', 'Deep Cleaning', 'Painting']"
                :key="chip"
                type="button"
                @click="searchKeyword = chip; handleHeroSearch()"
                class="hover:text-primary-light transition-colors cursor-pointer font-medium"
              >
                #{{ chip }}
              </button>
            </div>

          </div>

        </div>

      </div>
    </section>

    <!-- ── Promo Feature Cards Banner ──────────────────── -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 -mt-8 mb-16 relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="glass rounded-2xl p-6 border border-slate-200 hover:border-primary/40 transition-all flex items-center gap-4 group">
          <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform flex-shrink-0">
            <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-rose-600 bg-rose-50 px-2 py-0.5 rounded">Emergency Fix</span>
            <h4 class="font-heading font-bold text-slate-900 text-base mt-1">{{ t('expressServiceTitle') }}</h4>
            <p class="text-xs text-slate-500">{{ t('expressServiceDesc') }}</p>
          </div>
        </div>

        <div class="glass rounded-2xl p-6 border border-slate-200 hover:border-primary/40 transition-all flex items-center gap-4 group">
          <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform flex-shrink-0">
            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-700 bg-amber-50 px-2 py-0.5 rounded">100% Protection</span>
            <h4 class="font-heading font-bold text-slate-900 text-base mt-1">{{ t('warrantyTitle') }}</h4>
            <p class="text-xs text-slate-500">{{ t('warrantyDesc') }}</p>
          </div>
        </div>

        <div class="glass rounded-2xl p-6 border border-slate-200 hover:border-primary/40 transition-all flex items-center gap-4 group">
          <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform flex-shrink-0">
            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">Fixed Rates</span>
            <h4 class="font-heading font-bold text-slate-900 text-base mt-1">{{ t('pricingTitle') }}</h4>
            <p class="text-xs text-slate-500">{{ t('pricingDesc') }}</p>
          </div>
        </div>

      </div>
    </section>

    <!-- ── Categories Grid Section ─────────────────────── -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
      <div class="flex items-center justify-between mb-8">
        <div>
          <h2 class="text-3xl font-heading font-bold text-slate-900 mb-2">{{ t('browseCategory') }}</h2>
          <p class="text-slate-600 text-sm">{{ t('browseCategorySub') }}</p>
        </div>
        <NuxtLink to="/services" class="text-primary-light font-semibold text-sm hover:underline">
          {{ t('viewAllCategories') }}
        </NuxtLink>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        <NuxtLink
          v-for="(cat, i) in categories"
          :key="cat.id"
          :to="`/services?category=${cat.slug}`"
          class="glass rounded-2xl p-6 text-center group hover:border-primary/40 transition-all duration-300 animate-fade-up relative overflow-hidden"
          :style="{ animationDelay: `${i * 0.05}s` }"
        >
          <div class="text-4xl mb-4 group-hover:scale-110 transition-transform duration-300">
            {{ iconEmoji(cat.icon) }}
          </div>
          <h3 class="font-bold text-slate-900 text-base group-hover:text-primary-light transition-colors mb-1">{{ cat.name }}</h3>
          <p class="text-xs text-slate-500 font-medium">{{ cat.services_count || 12 }} {{ t('verifiedPros') }}</p>

          <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-primary-light font-bold">
            {{ t('exploreServices') }}
          </div>
        </NuxtLink>
      </div>
    </section>

    <!-- ── Platform Metrics Bar ────────────────────────── -->
    <section class="border-y border-slate-200 py-14 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">

          <div>
            <div class="text-4xl font-heading font-extrabold text-slate-900 mb-1">25,000+</div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ t('verifiedTechsCount') }}</div>
          </div>

          <div>
            <div class="text-4xl font-heading font-extrabold text-rose-600 mb-1">4.9 ★</div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ t('avgServiceRating') }}</div>
          </div>

          <div>
            <div class="text-4xl font-heading font-extrabold text-slate-900 mb-1">50+</div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ t('citiesActive') }}</div>
          </div>

          <div>
            <div class="text-4xl font-heading font-extrabold text-emerald-600 mb-1">30 Mins</div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ t('avgResponseTime') }}</div>
          </div>

        </div>
      </div>
    </section>

    <!-- ── How It Works Section ────────────────────────── -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-20">
      <div class="text-center mb-16">
        <h2 class="text-3xl font-heading font-bold text-slate-900 mb-3">{{ t('howItWorks') }}</h2>
        <p class="text-slate-600 max-w-lg mx-auto">{{ t('howItWorksSteps') }}</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="(step, i) in steps" :key="i" class="glass rounded-2xl p-8 text-center relative animate-fade-up" :style="{ animationDelay: `${i * 0.15}s` }">
          <div class="w-16 h-16 rounded-2xl gradient-primary flex items-center justify-center mx-auto mb-6 text-white text-2xl shadow-glow">
            <svg v-if="i === 0" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <svg v-else-if="i === 1" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <svg v-else class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
          </div>
          <span class="text-[10px] font-extrabold text-primary-light bg-primary/10 px-3 py-1 rounded-full uppercase tracking-wider">Step 0{{ i + 1 }}</span>
          <h3 class="text-xl font-heading font-bold text-slate-900 mt-3 mb-2">{{ t(step.titleKey) }}</h3>
          <p class="text-sm text-slate-600 leading-relaxed">{{ t(step.descKey) }}</p>
        </div>
      </div>
    </section>

    <!-- ── Partner CTA Section ─────────────────────────── -->
    <section class="py-16">
      <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="glass rounded-3xl p-10 md:p-14 relative overflow-hidden border border-slate-200">
          <div class="absolute inset-0 gradient-primary opacity-10" />
          <div class="relative flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
            <div>
              <span class="px-3 py-1 rounded-full bg-rose-500/20 text-rose-600 text-xs font-bold uppercase tracking-wider">{{ t('workerPartnershipProgram') }}</span>
              <h2 class="text-3xl md:text-4xl font-heading font-bold text-slate-900 mt-3 mb-3">{{ t('ctaTitle') }}</h2>
              <p class="text-slate-600 max-w-xl text-sm leading-relaxed">{{ t('ctaSubtitle') }}</p>
            </div>
            <NuxtLink
              to="/register"
              class="px-8 py-4 rounded-xl gradient-primary text-white font-bold hover:shadow-glow transition-all whitespace-nowrap"
            >
              {{ t('registerAsWorker') }}
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup lang="ts">
const api = useApi();
const { iconEmoji } = useIconEmoji();
const { t, initLang } = useLanguage();
const { currentLocation, openModal } = useUserLocation();

const categories = ref<any[]>([]);
const searchCategory = ref('');
const searchKeyword = ref('');

const steps = [
  { titleKey: 'stepSearchTitle',  descKey: 'stepSearchDesc'  },
  { titleKey: 'stepConnectTitle', descKey: 'stepConnectDesc' },
  { titleKey: 'stepReviewTitle',  descKey: 'stepReviewDesc'  },
];

const handleHeroSearch = () => {
  const query: any = {};
  if (searchCategory.value) query.category = searchCategory.value;
  if (searchKeyword.value.trim()) query.keyword = searchKeyword.value.trim();
  navigateTo({ path: '/search', query });
};

const loadCategories = async () => {
  try {
    const data = await api.get<any>('/categories');
    categories.value = data.categories || [];
  } catch { }
};

onMounted(() => {
  initLang();
  loadCategories();
});
</script>

<style scoped>
.hero-search-loc-btn,
.hero-search-select,
.hero-search-input {
  background-color: #ffffff !important;
  border-color: #e2e8f0 !important;
  color: #0f172a !important;
  height: 2.875rem;
}
.hero-search-loc-btn:hover {
  background-color: #f8fafc !important;
}
.dark .hero-search-loc-btn,
.dark .hero-search-select,
.dark .hero-search-input {
  background-color: #1e293b !important;
  border-color: #334155 !important;
  color: #f8fafc !important;
}
.dark .hero-search-loc-btn:hover {
  background-color: #334155 !important;
}
.dark .hero-search-input::placeholder {
  color: #64748b !important;
}
.location-announcement-pill {
  background-color: rgba(255, 255, 255, 0.8) !important;
  border-color: #e2e8f0 !important;
  color: #334155 !important;
}
.location-announcement-pill strong {
  color: #0f172a !important;
}
.dark .location-announcement-pill {
  background-color: rgba(30, 41, 59, 0.8) !important;
  border-color: rgba(255, 255, 255, 0.1) !important;
  color: #94a3b8 !important;
}
.dark .location-announcement-pill strong {
  color: #ffffff !important;
}
</style>
