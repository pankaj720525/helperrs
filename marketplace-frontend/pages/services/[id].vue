<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div v-if="service" class="space-y-6">

      <!-- Image + Service Header Card -->
      <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
        <div class="h-64 md:h-[450px] bg-slate-100 relative">
          <img :src="getServiceImage(service)" :alt="service.title" class="w-full h-full object-cover" />

          <!-- Category Badge & Wishlist Button overlay -->
          <div class="absolute top-4 left-4">
            <span class="px-3.5 py-1.5 rounded-full bg-slate-900/80 backdrop-blur text-cyan-300 text-xs font-bold uppercase tracking-wider">
              {{ service.category?.name || 'Service' }}
            </span>
          </div>

          <button
            type="button"
            @click="toggleWishlist(service.id, service.title)"
            class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/90 shadow-md backdrop-blur flex items-center justify-center transition-transform hover:scale-110"
            :title="isWishlisted(service.id) ? 'Remove from Wishlist' : 'Save to Wishlist'"
          >
            <svg class="w-5 h-5 transition-colors" :class="isWishlisted(service.id) ? 'text-rose-600 fill-rose-600' : 'text-slate-400 fill-none'" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </button>
        </div>

        <div class="p-6 md:p-8">
          <div class="flex items-start justify-between flex-wrap gap-4 mb-4">
            <div>
              <h1 class="text-2xl md:text-3xl font-heading font-extrabold text-slate-900 mb-1">{{ service.title }}</h1>
              <p class="text-sm text-slate-500 font-medium flex items-center gap-1.5">
                <svg class="w-4 h-4 text-rose-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Available in {{ currentLocation.formatted }}</span>
              </p>
            </div>

            <div class="text-right">
              <div v-if="service.price_min || service.price_max" class="text-2xl font-extrabold text-rose-600">
                ₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}
              </div>
              <div v-else class="text-2xl font-extrabold text-rose-600">₹499</div>
              <div class="flex items-center gap-1 mt-1 justify-end">
                <span class="text-amber-500">★</span>
                <span class="text-sm text-slate-900 font-bold">{{ service.reviews_avg || '4.8' }}</span>
                <span class="text-xs text-slate-500">({{ service.reviews_count || 1 }} review)</span>
              </div>
            </div>
          </div>

          <p class="text-slate-600 leading-relaxed whitespace-pre-wrap text-sm md:text-base">{{ service.description || 'Professional and verified service provider available in your area. Booking includes 30-day service guarantee.' }}</p>

          <!-- Worker Info & Red Start Chat Button -->
          <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white text-lg font-extrabold shadow-sm">
                {{ service.user?.name?.charAt(0)?.toUpperCase() || 'S' }}
              </div>
              <div>
                <p class="font-bold text-slate-900 text-base">{{ service.user?.name || 'Verified Provider' }}</p>
                <p class="text-xs text-slate-500 font-medium">Service Provider • Background Verified</p>
              </div>
            </div>

            <!-- Crisp Red Button with PURE WHITE text -->
            <!-- Crisp Red Button with PURE WHITE text (Only visible if authenticated and not the owner) -->
            <template v-if="userStore.isAuthenticated">
              <button
                v-if="service.user?.id !== userStore.user?.id"
                @click="startChat"
                :disabled="chatLoading"
                type="button"
                class="px-6 py-3 rounded-xl bg-gradient-to-r from-rose-600 via-rose-700 to-rose-800 text-white font-extrabold text-sm hover:shadow-lg transition-all disabled:opacity-50 flex items-center gap-2 cursor-pointer"
                style="color: #ffffff !important;"
              >
                <svg class="w-4 h-4 text-white fill-none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="text-white font-bold">{{ chatLoading ? 'Starting...' : 'Start Chat' }}</span>
              </button>
              <span v-else class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-500 font-bold text-xs border border-slate-200">
                Your Service Listing
              </span>
            </template>
            <NuxtLink
              v-else
              to="/login"
              class="px-6 py-3 rounded-xl bg-gradient-to-r from-rose-600 via-rose-700 to-rose-800 text-white font-extrabold text-sm hover:shadow-lg transition-all flex items-center gap-2"
              style="color: #ffffff !important;"
            >
              <span>Login to Chat</span>
            </NuxtLink>
          </div>
        </div>
      </div>

      <!-- Write a Review Section -->
      <div v-if="userStore.isAuthenticated && canReview" class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm">
        <h2 class="text-xl font-heading font-bold text-slate-900 mb-4">Write a Review</h2>
        <form @submit.prevent="submitReview" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Rating</label>
            <div class="flex gap-2">
              <button
                v-for="star in 5"
                :key="star"
                type="button"
                @click="reviewForm.rating = star"
                class="text-2xl transition-transform hover:scale-110 cursor-pointer"
                :class="star <= reviewForm.rating ? 'text-amber-500' : 'text-slate-300'"
              >★</button>
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Your Review Comment</label>
            <textarea
              v-model="reviewForm.comment"
              rows="3"
              placeholder="Share your experience with this service provider..."
              class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-rose-600 text-sm resize-none"
            />
          </div>
          <p v-if="reviewError" class="text-rose-600 text-sm font-semibold">{{ reviewError }}</p>
          <p v-if="reviewSuccess" class="text-emerald-600 text-sm font-semibold animate-fade-in">✅ Review submitted successfully!</p>
          <button
            type="submit"
            :disabled="!reviewForm.rating || reviewSubmitting"
            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white font-bold text-sm hover:shadow-md transition-all disabled:opacity-50 cursor-pointer"
            style="color: #ffffff !important;"
          >
            {{ reviewSubmitting ? 'Submitting...' : 'Submit Review' }}
          </button>
        </form>
      </div>

      <!-- Reviews List -->
      <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm">
        <h2 class="text-xl font-heading font-bold text-slate-900 mb-6">Customer Reviews</h2>
        <div v-if="reviews.length" class="space-y-4">
          <div v-for="review in reviews" :key="review.id" class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs">
                  {{ review.user?.name?.charAt(0)?.toUpperCase() }}
                </div>
                <span class="text-sm font-bold text-slate-900">{{ review.user?.name }}</span>
              </div>
              <span class="text-amber-500 text-sm font-bold">{{ '★'.repeat(review.rating) }}{{ '☆'.repeat(5 - review.rating) }}</span>
            </div>
            <p class="text-sm text-slate-600">{{ review.comment || 'No written comment provided.' }}</p>
          </div>
        </div>
        <p v-else class="text-slate-500 text-sm">No reviews yet. Be the first to leave a review!</p>
      </div>
    </div>

    <div v-else class="bg-white rounded-3xl p-16 text-center text-slate-400 border border-slate-200">
      Loading service details...
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const api = useApi();
const userStore = useUserStore();
const { currentLocation } = useUserLocation();
const { getServiceImage } = useDefaultImage();
const { isWishlisted, toggleWishlist } = useWishlist();

const service = ref<any>(null);
const reviews = ref<any[]>([]);
const chatLoading = ref(false);
const reviewForm = reactive({ rating: 0, comment: "" });
const reviewSubmitting = ref(false);
const reviewError = ref("");
const reviewSuccess = ref(false);

const canReview = computed(() => {
  if (!service.value || !userStore.user) return false;
  return service.value.user?.id !== userStore.user.id;
});

onMounted(async () => {
  userStore.loadFromStorage();
  try {
    const data = await api.get<any>(`/services/${route.params.id}`);
    service.value = data.service;
  } catch { }

  try {
    const data = await api.get<any>("/reviews", { service_id: route.params.id });
    reviews.value = data.reviews || [];
  } catch { }
});

const startChat = async () => {
  if (service.value?.user?.id === userStore.user?.id) {
    return;
  }
  chatLoading.value = true;
  try {
    const data = await api.post<any>("/chats", {
      service_id: service.value.id,
      message: `Hi, I'm interested in your service "${service.value.title}".`,
    });
    navigateTo(`/chats/${data.chat.id}`);
  } catch (error: any) {
    alert(error?.data?.message || "Failed to start chat.");
  } finally {
    chatLoading.value = false;
  }
};

const submitReview = async () => {
  reviewError.value = "";
  reviewSuccess.value = false;
  reviewSubmitting.value = true;
  try {
    await api.post("/reviews", {
      service_id: service.value.id,
      rating: reviewForm.rating,
      comment: reviewForm.comment,
    });
    reviewSuccess.value = true;
    reviewForm.rating = 0;
    reviewForm.comment = "";
    const data = await api.get<any>("/reviews", { service_id: route.params.id });
    reviews.value = data.reviews || [];
  } catch (e: any) {
    const errors = e?.data?.errors;
    reviewError.value = errors
      ? Object.values(errors).flat().join(" ")
      : (e?.data?.message || "Failed to submit review.");
  } finally {
    reviewSubmitting.value = false;
  }
};
</script>
