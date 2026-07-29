<template>
  <div class="space-y-4">

    <!-- Search & GPS Row -->
    <div class="flex flex-col sm:flex-row gap-3">
      <!-- Search Input -->
      <div class="relative flex-1">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search city, area, pincode or address..."
          class="w-full pl-9 pr-20 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium"
          @keydown.enter.prevent="searchLocation"
        />
        <button
          type="button"
          @click="searchLocation"
          :disabled="searching || !searchQuery.trim()"
          class="absolute right-1.5 top-1.5 px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all disabled:opacity-50 cursor-pointer"
          style="color: #ffffff !important;"
        >
          {{ searching ? 'Searching...' : 'Search Map' }}
        </button>
      </div>

      <!-- GPS Button -->
      <button
        type="button"
        @click="detectGPS"
        :disabled="locating"
        class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap"
        style="color: #ffffff !important;"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3A8.994 8.994 0 0013 3.06V1h-2v2.06A8.994 8.994 0 003.06 11H1v2h2.06A8.994 8.994 0 0011 20.94V23h2v-2.06A8.994 8.994 0 0020.94 13H23v-2h-2.06z"/>
        </svg>
        <span>{{ locating ? 'Detecting...' : 'Detect My Location' }}</span>
      </button>
    </div>

    <!-- Interactive Map Container -->
    <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-inner bg-slate-100 h-80 w-full">
      <div ref="mapContainer" class="w-full h-full z-0" />
      <div v-if="loadingMap" class="absolute inset-0 bg-white/80 backdrop-blur flex items-center justify-center text-sm font-semibold text-slate-600">
        📍 Loading Interactive Map...
      </div>
      <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 shadow-sm pointer-events-none z-10 flex items-center gap-1.5">
        <span class="w-2 h-2 rounded-full bg-rose-600 animate-ping" />
        <span>💡 Drag & drop pin on map to adjust exact position</span>
      </div>
    </div>

    <!-- Error notice -->
    <p v-if="errorMsg" class="text-rose-600 text-xs font-semibold">⚠️ {{ errorMsg }}</p>

    <!-- Location Form Inputs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
      <div class="sm:col-span-2">
        <label class="block text-xs font-bold text-slate-700 mb-1">Full Address <span class="text-rose-600">*</span></label>
        <input
          v-model="address"
          type="text"
          required
          placeholder="e.g. 102, Sunrise Towers, Vastrapur Main Road"
          class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm font-medium focus:outline-none focus:border-rose-600"
          @input="emitLocation"
        />
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 mb-1">City / Region <span class="text-rose-600">*</span></label>
        <input
          v-model="city"
          type="text"
          required
          placeholder="e.g. Ahmedabad"
          class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-sm font-medium focus:outline-none focus:border-rose-600"
          @input="emitLocation"
        />
      </div>

      <div class="grid grid-cols-2 gap-2">
        <div>
          <label class="block text-xs font-bold text-slate-500 mb-1">Latitude</label>
          <input
            v-model.number="lat"
            type="number"
            step="any"
            readonly
            class="w-full px-3 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-600 text-xs font-mono font-semibold outline-none"
          />
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-500 mb-1">Longitude</label>
          <input
            v-model.number="lng"
            type="number"
            step="any"
            readonly
            class="w-full px-3 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-600 text-xs font-mono font-semibold outline-none"
          />
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  initialLat?: number | null;
  initialLng?: number | null;
  initialAddress?: string | null;
  initialCity?: string | null;
}>();

const emit = defineEmits<{
  (e: 'update:location', data: { lat: number; lng: number; address: string; city: string }): void;
}>();

const mapContainer = ref<HTMLElement | null>(null);
const loadingMap = ref(true);
const searchQuery = ref('');
const searching = ref(false);
const locating = ref(false);
const errorMsg = ref('');

const lat = ref<number>(props.initialLat ?? 23.0225);
const lng = ref<number>(props.initialLng ?? 72.5714);
const address = ref<string>(props.initialAddress ?? '');
const city = ref<string>(props.initialCity ?? 'Ahmedabad');

let mapInstance: any = null;
let markerInstance: any = null;

onMounted(async () => {
  if (import.meta.server) return;
  await loadLeaflet();
  initMap();
  emitLocation();
});

watch(() => props.initialLat, (newVal) => {
  if (newVal && newVal !== lat.value) {
    lat.value = newVal;
    if (props.initialLng) lng.value = props.initialLng;
    if (props.initialAddress) address.value = props.initialAddress;
    if (props.initialCity) city.value = props.initialCity;
    updateMapMarker(lat.value, lng.value);
  }
});

const loadLeaflet = (): Promise<void> => {
  return new Promise((resolve) => {
    if ((window as any).L) {
      loadingMap.value = false;
      resolve();
      return;
    }

    // Insert Leaflet CSS
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(link);

    // Insert Leaflet JS
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    script.onload = () => {
      loadingMap.value = false;
      resolve();
    };
    document.head.appendChild(script);
  });
};

const initMap = () => {
  if (!mapContainer.value || !(window as any).L) return;
  const L = (window as any).L;

  const initialCoords = [lat.value, lng.value];

  mapInstance = L.map(mapContainer.value, {
    zoomControl: true,
  }).setView(initialCoords, 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 19,
  }).addTo(mapInstance);

  // Custom marker icon
  const customIcon = L.divIcon({
    className: 'custom-pin',
    html: `<div style="background-color:#b20537;width:28px;height:28px;border-radius:50%;border:3px solid #ffffff;box-shadow:0 4px 10px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:bold;">📍</div>`,
    iconSize: [28, 28],
    iconAnchor: [14, 28],
  });

  markerInstance = L.marker(initialCoords, {
    draggable: true,
    icon: customIcon,
  }).addTo(mapInstance);

  markerInstance.on('dragend', async () => {
    const pos = markerInstance.getLatLng();
    lat.value = parseFloat(pos.lat.toFixed(6));
    lng.value = parseFloat(pos.lng.toFixed(6));
    await reverseGeocode(lat.value, lng.value);
    emitLocation();
  });
};

const updateMapMarker = (newLat: number, newLng: number) => {
  if (!mapInstance || !markerInstance || !(window as any).L) return;
  const newCoords = [newLat, newLng];
  markerInstance.setLatLng(newCoords);
  mapInstance.setView(newCoords, 14);
};

const searchLocation = async () => {
  const q = searchQuery.value.trim();
  if (!q) return;

  searching.value = true;
  errorMsg.value = '';

  try {
    const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(q + ', India')}`;
    const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
    const data = await res.json();

    if (data && data.length > 0) {
      const place = data[0];
      lat.value = parseFloat(parseFloat(place.lat).toFixed(6));
      lng.value = parseFloat(parseFloat(place.lon).toFixed(6));

      const parts = place.display_name.split(', ');
      city.value = parts[0] || parts[1] || 'Ahmedabad';
      address.value = place.display_name;

      updateMapMarker(lat.value, lng.value);
      emitLocation();
    } else {
      errorMsg.value = 'Location not found. Please try searching another area or city name.';
    }
  } catch {
    errorMsg.value = 'Failed to search location map.';
  } finally {
    searching.value = false;
  }
};

const reverseGeocode = async (l1: number, l2: number) => {
  try {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${l1}&lon=${l2}`;
    const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
    const data = await res.json();

    if (data && data.address) {
      const a = data.address;
      city.value = a.city || a.town || a.county || a.state_district || 'Ahmedabad';
      address.value = data.display_name || `${a.suburb || a.road || ''}, ${city.value}`;
    }
  } catch { }
};

const detectGPS = () => {
  if (!navigator.geolocation) {
    errorMsg.value = 'Geolocation is not supported by your browser.';
    return;
  }

  locating.value = true;
  errorMsg.value = '';

  navigator.geolocation.getCurrentPosition(
    async (pos) => {
      lat.value = parseFloat(pos.coords.latitude.toFixed(6));
      lng.value = parseFloat(pos.coords.longitude.toFixed(6));
      await reverseGeocode(lat.value, lng.value);
      updateMapMarker(lat.value, lng.value);
      emitLocation();
      locating.value = false;
    },
    () => {
      errorMsg.value = 'Could not access GPS. Please search for your location above or drag the pin on map.';
      locating.value = false;
    }
  );
};

const emitLocation = () => {
  emit('update:location', {
    lat: lat.value,
    lng: lng.value,
    address: address.value,
    city: city.value,
  });
};
</script>

<style scoped>
:deep(.custom-pin) {
  background: transparent;
  border: none;
}
</style>
