<template>
  <div class="sp-wrap">

    <!-- ── Page Header ──────────────────────────────────── -->
    <div class="sp-header">
      <h1 class="sp-title"><span class="sp-title-icon">🔍</span> {{ t('searchByLocation') }}</h1>
      <p class="sp-sub">{{ t('findNearYou') }}</p>
    </div>

    <!-- ── Info Banner ──────────────────────────────────── -->
    <div class="sp-info-banner">
      <span class="sp-info-icon">💡</span>
      <p>{{ t('locationInfoMsg') }}</p>
    </div>

    <!-- ── Search Card ──────────────────────────────────── -->
    <div class="sp-card">

      <!-- Area input row -->
      <div class="sp-section">
        <label class="sp-label">📍 {{ t('searchArea') }}</label>
        <div class="sp-area-row">
          <div class="sp-input-wrap">
            <input
              v-model="placeName"
              type="text"
              :placeholder="t('areaPlaceholder')"
              class="sp-input"
              @keydown.enter.prevent="geocodePlace"
              autocomplete="off"
            />
          </div>
          <button type="button" @click="geocodePlace" :disabled="geocoding" class="sp-find-btn">
            <span v-if="geocoding" class="sp-spinner" />
            <span v-else>{{ t('findArea') }}</span>
          </button>
          <button type="button" @click="useMyGPS" :disabled="locating" class="sp-gps-btn">
            <span v-if="locating" class="sp-spinner" />
            <span v-else>📍 GPS</span>
          </button>
        </div>

        <!-- Status feedback — NO coords shown -->
        <div v-if="locationResolved" class="sp-resolved">
          <span class="sp-resolved-dot" />
          {{ t('usingLocation') }}: <strong>{{ resolvedPlaceName }}</strong>
        </div>
        <p v-else-if="locationError" class="sp-error">⚠️ {{ locationError }}</p>
      </div>

      <!-- Divider -->
      <div class="sp-divider" />

      <!-- Filters -->
      <div class="sp-filters">
        <div class="sp-filter">
          <label class="sp-label">{{ t('category') }}</label>
          <select v-model="category" class="sp-select">
            <option value="">{{ t('allCategories') }}</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.slug">{{ cat.name }}</option>
          </select>
        </div>

        <div class="sp-filter">
          <label class="sp-label">{{ t('radius') }}: <span class="sp-radius-val">{{ radius }} km</span></label>
          <input v-model.number="radius" type="range" min="1" max="100" class="sp-slider" />
        </div>

        <div class="sp-filter">
          <label class="sp-label">{{ t('keyword') }}</label>
          <input v-model="keyword" type="text" :placeholder="t('keywordPlaceholder')" class="sp-input" />
        </div>
      </div>

      <!-- Action -->
      <div class="sp-action">
        <button
          type="button"
          @click="handleSearch"
          :disabled="searching || !locationResolved"
          class="sp-search-btn"
        >
          <span v-if="searching" class="sp-spinner" />
          <template v-else>🔍 {{ t('search') }}</template>
        </button>
        <p v-if="!locationResolved && !locationError" class="sp-hint">{{ t('enterAreaToSearch') }}</p>
      </div>

    </div>

    <!-- ── Results ──────────────────────────────────────── -->
    <template v-if="results.length">
      <div class="sp-results-meta">
        <span class="sp-results-count">
          <strong>{{ results.length }}</strong> {{ t('resultsNear') }} <strong>{{ resolvedPlaceName }}</strong>
        </span>
        <span class="sp-results-radius">within {{ radius }} km</span>
      </div>

      <div class="sp-grid">
        <div
          v-for="(r, i) in results"
          :key="i"
          class="sp-result-card"
          :style="{ animationDelay: `${i * 0.04}s` }"
        >
          <div class="sp-worker">
            <div class="sp-avatar">{{ r.worker_name?.charAt(0)?.toUpperCase() }}</div>
            <div class="sp-worker-info">
              <p class="sp-worker-name">{{ r.worker_name }}</p>
              <p class="sp-worker-area">📍 {{ r.address || t('areaHidden') }}</p>
            </div>
            <span class="sp-dist-badge">{{ r.distance }}</span>
          </div>

          <div class="sp-service">
            <p class="sp-service-title">{{ r.service_title }}</p>
            <span class="sp-service-cat">{{ r.category_name }}</span>
          </div>

          <div v-if="r.price_min || r.price_max" class="sp-price">
            ₹{{ r.price_min || 0 }} – ₹{{ r.price_max || 0 }}
          </div>

          <NuxtLink :to="`/services/${r.service_id}`" class="sp-view-btn">
            {{ t('viewDetails') }} →
          </NuxtLink>
        </div>
      </div>
    </template>

    <!-- No results -->
    <div v-else-if="searched && !searching" class="sp-empty">
      <div class="sp-empty-icon">📭</div>
      <p class="sp-empty-title">{{ t('noResults') }}</p>
      <p class="sp-empty-tip">{{ t('noResultsTip') }}</p>
    </div>

    <!-- Initial prompt -->
    <div v-else-if="!searched" class="sp-prompt">
      <div class="sp-prompt-icon">🗺️</div>
      <p class="sp-prompt-msg">{{ t('enterAreaToSearch') }}</p>
    </div>

  </div>
</template>

<script setup lang="ts">
const api = useApi();
const { t, initLang } = useLanguage();

const categories        = ref<any[]>([]);
const results           = ref<any[]>([]);
const category          = ref('');
const radius            = ref(25);
const keyword           = ref('');
const lat               = ref(0);
const lng               = ref(0);
const searching         = ref(false);
const geocoding         = ref(false);
const locating          = ref(false);
const searched          = ref(false);
const locationResolved  = ref(false);
const resolvedPlaceName = ref('');
const placeName         = ref('');
const locationError     = ref('');

const geocodePlace = async () => {
  const q = placeName.value.trim();
  if (!q) return;
  geocoding.value = true;
  locationError.value = '';
  locationResolved.value = false;
  try {
    const url  = `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(q)}`;
    const res  = await fetch(url, { headers: { 'Accept-Language': 'en' } });
    const data = await res.json();
    if (data.length > 0) {
      lat.value = parseFloat(data[0].lat);
      lng.value = parseFloat(data[0].lon);
      resolvedPlaceName.value = data[0].display_name.split(',').slice(0, 3).join(', ');
      locationResolved.value = true;
    } else {
      locationError.value = 'Area not found. Try a city or neighbourhood name.';
    }
  } catch {
    locationError.value = 'Could not reach geocoding service. Check your connection.';
  } finally {
    geocoding.value = false;
  }
};

const useMyGPS = () => {
  if (!navigator.geolocation) { locationError.value = 'Geolocation not supported.'; return; }
  locating.value = true;
  locationError.value = '';
  navigator.geolocation.getCurrentPosition(
    async (pos) => {
      lat.value = pos.coords.latitude;
      lng.value = pos.coords.longitude;
      try {
        const url  = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat.value}&lon=${lng.value}`;
        const res  = await fetch(url, { headers: { 'Accept-Language': 'en' } });
        const data = await res.json();
        const a    = data.address;
        resolvedPlaceName.value = a.suburb || a.neighbourhood || a.city_district || a.town || a.city || a.county || 'Your Location';
        placeName.value = resolvedPlaceName.value;
      } catch {
        resolvedPlaceName.value = 'Your Location';
        placeName.value = 'Your Location';
      }
      locationResolved.value = true;
      locating.value = false;
    },
    (err) => { locationError.value = 'Could not get GPS location. ' + err.message; locating.value = false; }
  );
};

const handleSearch = async () => {
  if (!locationResolved.value) { locationError.value = t('pleaseSetLocation'); return; }
  searching.value = true;
  searched.value  = true;
  try {
    const params: any = { lat: lat.value, lng: lng.value, radius: radius.value };
    if (category.value) params.category = category.value;
    if (keyword.value)  params.keyword  = keyword.value;
    const data = await api.get<any>('/search', params);
    results.value = data.results || [];
  } catch { results.value = []; }
  finally { searching.value = false; }
};

onMounted(async () => {
  initLang();
  try {
    const data = await api.get<any>('/categories');
    categories.value = data.categories || [];
  } catch { }
});
</script>

<style scoped>
/* ══════════════════════════════════════════════════════
   CSS CUSTOM PROPERTIES  (dark = default, light = override)
══════════════════════════════════════════════════════ */
.sp-wrap {
  --c-title:      #f1f5f9;
  --c-sub:        #94a3b8;
  --c-label:      #94a3b8;
  --c-card-bg:    rgba(30,41,59,0.8);
  --c-card-bdr:   rgba(255,255,255,0.08);
  --c-input-bg:   rgba(255,255,255,0.06);
  --c-input-bdr:  rgba(255,255,255,0.12);
  --c-input-text: #f1f5f9;
  --c-input-ph:   #475569;
  --c-gps-bg:     rgba(255,255,255,0.05);
  --c-gps-bdr:    rgba(255,255,255,0.1);
  --c-gps-text:   #94a3b8;
  --c-divider:    rgba(255,255,255,0.07);
  --c-hint:       #475569;
  --c-res-bg:     rgba(30,41,59,0.7);
  --c-res-bdr:    rgba(255,255,255,0.07);
  --c-w-name:     #f1f5f9;
  --c-w-area:     #64748b;
  --c-s-title:    #e2e8f0;
  --c-s-cat:      #64748b;
  --c-vbtn-bg:    rgba(255,255,255,0.04);
  --c-vbtn-bdr:   rgba(255,255,255,0.08);
  --c-vbtn-text:  #64748b;
  --c-meta:       #94a3b8;
  --c-meta-str:   #f1f5f9;
  --c-pill-bg:    rgba(255,255,255,0.04);
  --c-pill-bdr:   rgba(255,255,255,0.07);
  --c-pill-text:  #475569;
  --c-empty:      #475569;
  --c-tip:        #334155;
  --c-info:       #7dd3fc;
}

:global(html.light) .sp-wrap {
  --c-title:      #0f172a;
  --c-sub:        #475569;
  --c-label:      #374151;
  --c-card-bg:    #ffffff;
  --c-card-bdr:   rgba(0,0,0,0.09);
  --c-input-bg:   #f8fafc;
  --c-input-bdr:  rgba(0,0,0,0.13);
  --c-input-text: #0f172a;
  --c-input-ph:   #94a3b8;
  --c-gps-bg:     #f1f5f9;
  --c-gps-bdr:    rgba(0,0,0,0.1);
  --c-gps-text:   #374151;
  --c-divider:    rgba(0,0,0,0.07);
  --c-hint:       #64748b;
  --c-res-bg:     #ffffff;
  --c-res-bdr:    rgba(0,0,0,0.08);
  --c-w-name:     #0f172a;
  --c-w-area:     #64748b;
  --c-s-title:    #1e293b;
  --c-s-cat:      #64748b;
  --c-vbtn-bg:    #f1f5f9;
  --c-vbtn-bdr:   rgba(0,0,0,0.08);
  --c-vbtn-text:  #475569;
  --c-meta:       #64748b;
  --c-meta-str:   #0f172a;
  --c-pill-bg:    #f1f5f9;
  --c-pill-bdr:   rgba(0,0,0,0.08);
  --c-pill-text:  #475569;
  --c-empty:      #475569;
  --c-tip:        #64748b;
  --c-info:       #1e40af;
}

/* ── Wrapper ─────────────────────────────────────────── */
.sp-wrap { max-width: 62rem; margin: 0 auto; padding: 2rem 1.25rem 4rem; }

/* ── Header ──────────────────────────────────────────── */
.sp-header { margin-bottom: 1.25rem; }
.sp-title {
  font-family: 'Outfit', sans-serif; font-size: 1.875rem; font-weight: 700;
  color: var(--c-title); margin: 0 0 0.25rem;
  display: flex; align-items: center; gap: 0.5rem;
}
.sp-title-icon { font-size: 1.5rem; line-height: 1; }
.sp-sub { color: var(--c-sub); font-size: 0.9375rem; margin: 0; }

/* ── Info banner ─────────────────────────────────────── */
.sp-info-banner {
  display: flex; align-items: flex-start; gap: 10px;
  padding: 0.875rem 1.125rem; border-radius: 0.875rem;
  background: rgba(59,130,246,0.07); border: 1px solid rgba(59,130,246,0.2);
  color: var(--c-info); font-size: 0.8125rem; line-height: 1.55; margin-bottom: 1.5rem;
}
.sp-info-icon { flex-shrink: 0; font-size: 1rem; margin-top: 1px; }
.sp-info-banner p { margin: 0; }

/* ── Search card ─────────────────────────────────────── */
.sp-card {
  background: var(--c-card-bg); border: 1px solid var(--c-card-bdr);
  border-radius: 1.25rem; padding: 1.75rem; margin-bottom: 2rem;
  display: flex; flex-direction: column; gap: 1.5rem;
  box-shadow: 0 2px 20px rgba(0,0,0,0.08);
}

/* ── Labels ──────────────────────────────────────────── */
.sp-section { display: flex; flex-direction: column; gap: 0.625rem; }
.sp-label { font-size: 0.8125rem; font-weight: 600; color: var(--c-label); letter-spacing: 0.02em; }
.sp-radius-val { color: #B20537; font-weight: 700; }

/* ── Area row ────────────────────────────────────────── */
.sp-area-row { display: flex; gap: 0.625rem; }
.sp-input-wrap { flex: 1; }

.sp-input {
  width: 100%; padding: 0.75rem 1rem; border-radius: 0.75rem;
  background: var(--c-input-bg); border: 1.5px solid var(--c-input-bdr);
  color: var(--c-input-text); font-size: 0.9375rem;
  outline: none; transition: border-color 0.2s; box-sizing: border-box;
}
.sp-input:focus { border-color: rgba(178,5,55,0.5); }
.sp-input::placeholder { color: var(--c-input-ph); }

.sp-find-btn {
  padding: 0 1.25rem; height: 2.875rem; border-radius: 0.75rem; flex-shrink: 0;
  background: rgba(178,5,55,0.1); border: 1.5px solid rgba(178,5,55,0.4);
  color: #B20537; font-size: 0.9rem; font-weight: 700;
  cursor: pointer; white-space: nowrap; display: flex; align-items: center; gap: 6px;
  transition: all 0.2s;
}
.sp-find-btn:hover:not(:disabled) { background: rgba(178,5,55,0.2); color: #8B0000; }
.sp-find-btn:disabled { opacity: 0.45; cursor: not-allowed; }

.sp-gps-btn {
  padding: 0 1rem; height: 2.875rem; border-radius: 0.75rem; flex-shrink: 0;
  background: var(--c-gps-bg); border: 1.5px solid var(--c-gps-bdr);
  color: var(--c-gps-text); font-size: 0.875rem; font-weight: 500;
  cursor: pointer; white-space: nowrap; display: flex; align-items: center; gap: 5px;
  transition: all 0.2s;
}
.sp-gps-btn:hover:not(:disabled) { opacity: 0.8; }
.sp-gps-btn:disabled { opacity: 0.45; cursor: not-allowed; }

/* ── Status ──────────────────────────────────────────── */
.sp-resolved {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 6px 14px; border-radius: 9999px; width: fit-content;
  background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3);
  color: #059669; font-size: 0.8125rem; font-weight: 500;
}
.sp-resolved strong { color: #047857; font-weight: 700; }
.sp-resolved-dot { width: 8px; height: 8px; border-radius: 50%; background: #10b981; flex-shrink: 0; }
.sp-error { color: #dc2626; font-size: 0.8125rem; margin: 0; font-weight: 500; }

/* ── Divider ─────────────────────────────────────────── */
.sp-divider { height: 1px; background: var(--c-divider); }

/* ── Filters ─────────────────────────────────────────── */
.sp-filters { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
@media (max-width: 640px) { .sp-filters { grid-template-columns: 1fr; } }
.sp-filter { display: flex; flex-direction: column; gap: 0.5rem; }

.sp-select {
  padding: 0.6875rem 0.875rem; border-radius: 0.75rem;
  background: var(--c-input-bg); border: 1.5px solid var(--c-input-bdr);
  color: var(--c-input-text); font-size: 0.875rem; outline: none; cursor: pointer;
}
.sp-select:focus { border-color: rgba(178,5,55,0.5); }
.sp-slider { width: 100%; accent-color: #B20537; cursor: pointer; margin-top: 4px; }

/* ── Action ──────────────────────────────────────────── */
.sp-action { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.sp-search-btn {
  padding: 0.75rem 2rem; border-radius: 0.875rem; border: none;
  background: linear-gradient(135deg, #B20537, #D4064A, #F43F5E);
  color: #fff; font-size: 1rem; font-weight: 700;
  cursor: pointer; display: flex; align-items: center; gap: 8px;
  transition: box-shadow 0.2s, opacity 0.2s;
}
.sp-search-btn:hover:not(:disabled) { box-shadow: 0 0 24px rgba(178,5,55,0.4); }
.sp-search-btn:disabled { opacity: 0.4; cursor: not-allowed; }
.sp-hint { color: var(--c-hint); font-size: 0.8125rem; margin: 0; }

/* ── Spinner ─────────────────────────────────────────── */
.sp-spinner {
  display: inline-block; width: 15px; height: 15px;
  border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff;
  border-radius: 50%; animation: spin 0.65s linear infinite; flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Results meta ────────────────────────────────────── */
.sp-results-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
.sp-results-count { color: var(--c-meta); font-size: 0.9375rem; }
.sp-results-count strong { color: var(--c-meta-str); }
.sp-results-radius {
  font-size: 0.8125rem; color: var(--c-pill-text);
  background: var(--c-pill-bg); border: 1px solid var(--c-pill-bdr);
  padding: 3px 10px; border-radius: 9999px;
}

/* ── Grid ────────────────────────────────────────────── */
.sp-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(19rem,1fr)); gap: 1rem; }

@keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
.sp-result-card {
  display: flex; flex-direction: column; gap: 0.875rem; padding: 1.25rem;
  background: var(--c-res-bg); border: 1px solid var(--c-res-bdr); border-radius: 1rem;
  animation: fadeUp 0.35s ease both;
  box-shadow: 0 1px 8px rgba(0,0,0,0.07);
  transition: border-color 0.2s, box-shadow 0.2s;
}
.sp-result-card:hover { border-color: rgba(178,5,55,0.3); box-shadow: 0 4px 18px rgba(178,5,55,0.08); }

.sp-worker { display: flex; align-items: flex-start; gap: 0.75rem; }
.sp-avatar {
  width: 2.875rem; height: 2.875rem; flex-shrink: 0; border-radius: 50%;
  background: linear-gradient(135deg,#B20537,#F43F5E);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-weight: 700; font-size: 1.125rem;
}
.sp-worker-info { flex: 1; min-width: 0; }
.sp-worker-name { font-weight: 600; color: var(--c-w-name); font-size: 0.9375rem; margin: 0 0 3px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.sp-worker-area { color: var(--c-w-area); font-size: 0.8rem; margin: 0; }
.sp-dist-badge { flex-shrink: 0; font-size: 0.75rem; font-weight: 700; color: #B20537; background: rgba(178,5,55,0.08); border: 1px solid rgba(178,5,55,0.2); padding: 3px 9px; border-radius: 9999px; white-space: nowrap; }

.sp-service { display: flex; flex-direction: column; gap: 3px; }
.sp-service-title { font-size: 0.9375rem; font-weight: 500; color: var(--c-s-title); margin: 0; }
.sp-service-cat   { font-size: 0.75rem; color: var(--c-s-cat); }
.sp-price { font-size: 0.9375rem; font-weight: 700; color: #B20537; }

.sp-view-btn {
  display: block; text-align: center; padding: 0.5rem 0; border-radius: 0.5rem;
  background: var(--c-vbtn-bg); border: 1px solid var(--c-vbtn-bdr);
  color: var(--c-vbtn-text); font-size: 0.875rem; font-weight: 500; text-decoration: none;
  transition: all 0.15s;
}
.sp-view-btn:hover { opacity: 0.8; }

/* ── Empty / Prompt ──────────────────────────────────── */
.sp-empty, .sp-prompt { display: flex; flex-direction: column; align-items: center; gap: 0.75rem; padding: 5rem 1rem; text-align: center; }
.sp-empty-icon, .sp-prompt-icon { font-size: 3rem; line-height: 1; }
.sp-empty-title, .sp-prompt-msg { color: var(--c-empty); font-size: 1rem; margin: 0; }
.sp-empty-tip { color: var(--c-tip); font-size: 0.875rem; margin: 0; }
</style>
