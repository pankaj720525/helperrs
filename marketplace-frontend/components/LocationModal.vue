<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div v-if="isModalOpen" class="loc-overlay" @click.self="closeModal">
        <div class="loc-card">

          <!-- Modal Header -->
          <div class="loc-header">
            <div class="loc-title-group">
              <div class="loc-icon-bubble">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div>
                <h3 class="loc-title">Select Delivery Location</h3>
                <p class="loc-subtitle">Specify your city or pincode for accurate local service availability</p>
              </div>
            </div>
            <button type="button" @click="closeModal" class="loc-close-btn" title="Close">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Current Location Highlight -->
          <div class="loc-current-badge">
            <div class="loc-cb-dot" />
            <div class="loc-cb-text">
              Current Location: <strong>{{ currentLocation.formatted }}</strong>
            </div>
          </div>

          <!-- GPS & Pincode Row -->
          <div class="loc-inputs-row">
            <!-- GPS Button -->
            <button
              type="button"
              @click="handleGPS"
              :disabled="locating"
              class="loc-gps-btn"
            >
              <span v-if="locating" class="loc-spinner" />
              <template v-else>
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3A8.994 8.994 0 0013 3.06V1h-2v2.06A8.994 8.994 0 003.06 11H1v2h2.06A8.994 8.994 0 0011 20.94V23h2v-2.06A8.994 8.994 0 0020.94 13H23v-2h-2.06z"/>
                </svg>
                <span>Detect My GPS Location</span>
              </template>
            </button>

            <div class="loc-or">OR</div>

            <!-- Pincode / Area Search Form -->
            <form @submit.prevent="handlePincodeSubmit" class="loc-pincode-form">
              <div class="loc-input-wrap">
                <svg class="loc-search-ic" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                  v-model="inputQuery"
                  type="text"
                  placeholder="Enter Pincode (e.g. 380001) or Area name"
                  class="loc-input"
                />
              </div>
              <button type="submit" :disabled="!inputQuery.trim() || searching" class="loc-apply-btn">
                <span v-if="searching" class="loc-spinner" />
                <span v-else>Apply</span>
              </button>
            </form>
          </div>

          <p v-if="errorMsg" class="loc-error">⚠️ {{ errorMsg }}</p>

          <!-- Popular Cities -->
          <div class="loc-section">
            <h4 class="loc-section-title">Popular Cities</h4>
            <div class="loc-cities-grid">
              <button
                v-for="city in POPULAR_CITIES"
                :key="city.name"
                type="button"
                @click="handleSelectCity(city.name)"
                class="loc-city-pill"
                :class="{ active: currentLocation.city.toLowerCase() === city.name.toLowerCase() }"
              >
                <span class="loc-city-name">{{ city.name }}</span>
                <span class="loc-city-pin">{{ city.pincode }}</span>
              </button>
            </div>
          </div>

          <!-- Popular Neighborhoods for Active City -->
          <div class="loc-section">
            <h4 class="loc-section-title">
              Neighborhoods in {{ activeCityOption?.name || 'Ahmedabad' }}
            </h4>
            <div class="loc-areas-wrap">
              <button
                v-for="area in activeCityOption?.popularAreas || ahmedabadAreas"
                :key="area"
                type="button"
                @click="handleSelectArea(area)"
                class="loc-area-btn"
                :class="{ active: currentLocation.area.toLowerCase() === area.toLowerCase() }"
              >
                {{ area }}
              </button>
            </div>
          </div>

          <!-- Footer note -->
          <div class="loc-footer-note">
            Selecting location filters service providers and delivery estimates for your region.
          </div>

        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
const { currentLocation, isModalOpen, POPULAR_CITIES, setLocation, selectCity, closeModal } = useUserLocation();

const inputQuery = ref('');
const locating = ref(false);
const searching = ref(false);
const errorMsg = ref('');

const ahmedabadAreas = ['Navrangpura', 'Vastrapur', 'Satellite', 'Bodakdev', 'SG Highway', 'Maninagar', 'Bopal', 'Chandkheda', 'Paldi', 'CG Road'];

const activeCityOption = computed(() => {
  return POPULAR_CITIES.find(c => c.name.toLowerCase() === currentLocation.value.city.toLowerCase()) || POPULAR_CITIES[0];
});

const handleSelectCity = (cityName: string) => {
  errorMsg.value = '';
  selectCity(cityName);
  closeModal();
};

const handleSelectArea = (areaName: string) => {
  errorMsg.value = '';
  setLocation({
    area: areaName,
    formatted: `${areaName}, ${currentLocation.value.city} ${currentLocation.value.pincode}`
  });
  closeModal();
};

const handlePincodeSubmit = async () => {
  const q = inputQuery.value.trim();
  if (!q) return;
  searching.value = true;
  errorMsg.value = '';

  if (/^\d{6}$/.test(q)) {
    if (q.startsWith('380')) {
      setLocation({
        city: 'Ahmedabad',
        pincode: q,
        area: 'Ahmedabad Zone',
        state: 'Gujarat'
      });
      inputQuery.value = '';
      searching.value = false;
      closeModal();
      return;
    }
  }

  try {
    const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(q + ', India')}`;
    const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
    const data = await res.json();
    if (data && data.length > 0) {
      const place = data[0];
      const displayName = place.display_name.split(', ');
      const city = displayName[0] || 'Ahmedabad';
      const area = displayName[1] || city;

      setLocation({
        city: city,
        area: area,
        pincode: q.match(/\d{6}/)?.[0] || currentLocation.value.pincode,
        lat: parseFloat(place.lat),
        lng: parseFloat(place.lon)
      });
      inputQuery.value = '';
      closeModal();
    } else {
      selectCity(q);
      closeModal();
    }
  } catch {
    selectCity(q);
    closeModal();
  } finally {
    searching.value = false;
  }
};

const handleGPS = () => {
  if (!navigator.geolocation) {
    errorMsg.value = 'Geolocation is not supported by your browser.';
    return;
  }
  locating.value = true;
  errorMsg.value = '';

  navigator.geolocation.getCurrentPosition(
    async (pos) => {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      try {
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
        const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
        const data = await res.json();
        const a = data.address || {};
        const city = a.city || a.town || a.county || 'Ahmedabad';
        const area = a.suburb || a.neighbourhood || a.city_district || city;
        const pincode = a.postcode || '380001';

        setLocation({ city, area, pincode, state: a.state || 'Gujarat', lat, lng });
        closeModal();
      } catch {
        setLocation({ city: 'Ahmedabad', area: 'Detected Location', lat, lng });
        closeModal();
      } finally {
        locating.value = false;
      }
    },
    (err) => {
      errorMsg.value = 'Could not access GPS. Please choose a city below.';
      locating.value = false;
    }
  );
};
</script>

<style scoped>
.loc-overlay {
  position: fixed; inset: 0; z-index: 999;
  background: rgba(15, 23, 42, 0.6);
  backdrop-filter: blur(8px);
  display: flex; align-items: center; justify-content: center;
  padding: 1rem;
}
.loc-card {
  width: 100%; max-width: 36rem;
  background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.9);
  border-radius: 1.25rem; padding: 1.75rem;
  box-shadow: 0 20px 45px rgba(15, 23, 42, 0.18);
  color: #0f172a;
}
:global(html.dark) .loc-card {
  background: #1e293b; border-color: rgba(255, 255, 255, 0.12); color: #f8fafc;
}

.loc-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; }
.loc-title-group { display: flex; align-items: center; gap: 0.75rem; }
.loc-icon-bubble {
  width: 2.75rem; height: 2.75rem; border-radius: 0.75rem;
  background: #FEF2F5; border: 1px solid #FBC0D0;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
:global(html.dark) .loc-icon-bubble { background: rgba(178,5,55,0.2); border-color: rgba(178,5,55,0.4); }

.loc-title { font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; margin: 0 0 2px; }
:global(html.dark) .loc-title { color: #f8fafc; }
.loc-subtitle { font-size: 0.8125rem; color: #64748b; margin: 0; }
:global(html.dark) .loc-subtitle { color: #94a3b8; }

.loc-close-btn {
  background: #f1f5f9; border: 1px solid #e2e8f0; color: #64748b;
  width: 2rem; height: 2rem; border-radius: 50%;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: all 0.15s;
}
:global(html.dark) .loc-close-btn { background: rgba(255,255,255,0.06); border-color: transparent; color: #94a3b8; }
.loc-close-btn:hover { background: #fee2e2; color: #ef4444; border-color: #fca5a5; }

.loc-current-badge {
  display: flex; align-items: center; gap: 0.5rem;
  padding: 0.625rem 0.875rem; border-radius: 0.75rem;
  background: #fef2f5; border: 1px solid #fbc0d0;
  margin-bottom: 1.25rem; font-size: 0.8125rem; color: #b20537;
}
:global(html.dark) .loc-current-badge { background: rgba(178, 5, 55, 0.15); border-color: rgba(178, 5, 55, 0.4); color: #f43f5e; }
.loc-cb-dot { width: 8px; height: 8px; border-radius: 50%; background: #b20537; flex-shrink: 0; }
:global(html.dark) .loc-cb-dot { background: #f43f5e; }
.loc-cb-text { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

.loc-inputs-row { display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.25rem; }

.loc-gps-btn {
  width: 100%; padding: 0.75rem 1rem; border-radius: 0.75rem;
  background: #10b981; border: none;
  color: #fff; font-weight: 600; font-size: 0.875rem;
  cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
  transition: background 0.2s; shadow: 0 2px 8px rgba(16,185,129,0.25);
}
.loc-gps-btn:hover:not(:disabled) { background: #059669; }

.loc-or { font-size: 0.75rem; font-weight: 700; color: #94a3b8; letter-spacing: 0.05em; text-align: center; }

.loc-pincode-form { display: flex; gap: 0.5rem; }
.loc-input-wrap {
  position: relative; flex: 1; display: flex; align-items: center;
}
.loc-search-ic {
  position: absolute; left: 0.875rem; width: 1.125rem; height: 1.125rem; color: #94a3b8; pointer-events: none;
}
.loc-input {
  width: 100%; padding: 0.6875rem 0.875rem 0.6875rem 2.5rem; border-radius: 0.75rem;
  background: #f8fafc; border: 1.5px solid #cbd5e1;
  color: #0f172a; font-size: 0.875rem; font-weight: 500; outline: none; transition: border-color 0.2s;
}
:global(html.dark) .loc-input {
  background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.15); color: #ffffff;
}
.loc-input:focus { border-color: #B20537; background: #ffffff; }
:global(html.dark) .loc-input:focus { background: rgba(255,255,255,0.09); }

.loc-apply-btn {
  padding: 0 1.5rem; border-radius: 0.75rem; border: none;
  background: #B20537; color: #fff; font-weight: 700; font-size: 0.875rem;
  cursor: pointer; transition: background 0.2s;
}
.loc-apply-btn:hover:not(:disabled) { background: #8A042B; }
.loc-apply-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.loc-error { color: #ef4444; font-size: 0.8125rem; margin: 0 0 1rem; font-weight: 500; }

.loc-section { margin-bottom: 1.25rem; }
.loc-section-title { font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin: 0 0 0.625rem; }
:global(html.dark) .loc-section-title { color: #94a3b8; }

.loc-cities-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; }
@media (max-width: 500px) { .loc-cities-grid { grid-template-columns: repeat(2, 1fr); } }

.loc-city-pill {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 0.625rem 0.5rem; border-radius: 0.75rem;
  background: #f1f5f9; border: 1px solid #e2e8f0;
  color: #334155; cursor: pointer; transition: all 0.15s;
}
:global(html.dark) .loc-city-pill { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); color: #cbd5e1; }
.loc-city-pill:hover { background: #fef2f5; border-color: #fbc0d0; color: #b20537; }
.loc-city-pill.active { background: #B20537; border-color: #B20537; color: #fff; font-weight: 700; }

.loc-city-name { font-size: 0.8125rem; font-weight: 600; }
.loc-city-pin { font-size: 0.6875rem; opacity: 0.75; }

.loc-areas-wrap { display: flex; flex-wrap: wrap; gap: 0.375rem; }
.loc-area-btn {
  padding: 0.375rem 0.75rem; border-radius: 9999px;
  background: #f8fafc; border: 1px solid #e2e8f0;
  color: #475569; font-size: 0.75rem; font-weight: 500;
  cursor: pointer; transition: all 0.15s;
}
:global(html.dark) .loc-area-btn { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); color: #94a3b8; }
.loc-area-btn:hover { background: #fef2f5; color: #b20537; border-color: #fbc0d0; }
.loc-area-btn.active { background: #fef2f5; color: #b20537; border-color: #b20537; font-weight: 700; }

.loc-footer-note { font-size: 0.75rem; color: #64748b; text-align: center; border-top: 1px solid #f1f5f9; padding-top: 0.75rem; margin-top: 0.5rem; }
:global(html.dark) .loc-footer-note { border-top-color: rgba(255,255,255,0.06); color: #94a3b8; }

.loc-spinner {
  display: inline-block; width: 14px; height: 14px;
  border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff;
  border-radius: 50%; animation: spin 0.65s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.2s ease, transform 0.2s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; transform: scale(0.96); }
</style>
