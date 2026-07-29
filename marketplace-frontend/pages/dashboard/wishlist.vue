<template>
  <div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-heading font-extrabold text-slate-900 mb-1">❤️ Saved Services</h1>
        <p class="text-sm text-slate-500 font-medium">Your bookmarked and favorite service professionals</p>
      </div>

      <NuxtLink to="/services" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-all border border-slate-200">
        Browse More Services →
      </NuxtLink>
    </div>

    <!-- Wishlist Cards Grid -->
    <div v-if="wishlistServices.length" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
      <div
        v-for="service in wishlistServices"
        :key="service.id"
        class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm flex flex-col justify-between group hover:border-rose-400 transition-all"
      >
        <div>
          <div class="h-44 bg-slate-100 relative overflow-hidden">
            <img :src="getServiceImage(service)" :alt="service.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <button
              type="button"
              @click="toggleWishlist(service.id, service.title); loadWishlistServices()"
              class="absolute top-3 right-3 w-9 h-9 rounded-full bg-white/90 shadow-md backdrop-blur flex items-center justify-center cursor-pointer"
              title="Remove from Saved"
            >
              <svg class="w-4 h-4 text-rose-600 fill-rose-600" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
              </svg>
            </button>
          </div>

          <div class="p-5">
            <h3 class="font-bold text-slate-900 text-base mb-1 group-hover:text-rose-600 transition-colors line-clamp-1">{{ service.title }}</h3>
            <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ service.description || 'Verified local service provider in your area.' }}</p>
            <div class="text-base font-extrabold text-rose-600">₹{{ service.price_min || service.price || 499 }}</div>
          </div>
        </div>

        <div class="p-4 pt-0">
          <NuxtLink
            :to="`/services/${service.id}`"
            class="w-full block text-center py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-extrabold hover:shadow-md transition-all"
            style="color: #ffffff !important;"
          >
            View Details & Book →
          </NuxtLink>
        </div>
      </div>
    </div>

    <!-- Empty Wishlist -->
    <div v-else class="bg-white rounded-3xl p-16 text-center text-slate-400 border border-slate-200 space-y-3">
      <svg class="w-12 h-12 text-rose-400 fill-rose-100 mx-auto" viewBox="0 0 24 24">
        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
      </svg>
      <h3 class="font-bold text-slate-900 text-base">No Saved Services Yet</h3>
      <p class="text-xs text-slate-500 max-w-sm mx-auto">Click the heart icon on any service card to bookmark it here for quick access later.</p>
      <NuxtLink to="/services" class="inline-block px-5 py-2.5 rounded-xl bg-rose-600 text-white text-xs font-bold shadow-sm" style="color: #ffffff !important;">
        Explore Services
      </NuxtLink>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard' });

const api = useApi();
const { wishlistedIds, initWishlist, toggleWishlist } = useWishlist();
const { getServiceImage } = useDefaultImage();

const wishlistServices = ref<any[]>([]);
const loading = ref(false);

const loadWishlistServices = async () => {
  initWishlist();
  const ids = wishlistedIds.value.map(id => String(id));
  if (!ids.length) {
    wishlistServices.value = [];
    return;
  }

  loading.value = true;
  try {
    const data = await api.get<any>('/services', { per_page: 100 });
    const all = data.services || [];
    wishlistServices.value = all.filter((s: any) => ids.includes(String(s.id)));
  } catch (e) {
    console.error('Failed to load wishlist services:', e);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  const userStore = useUserStore();
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) {
    return navigateTo('/login');
  }
  loadWishlistServices();
});
</script>
