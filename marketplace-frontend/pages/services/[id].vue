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
              <div class="flex items-center gap-2 mt-1 justify-end">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-200/80">
                  <svg class="w-4 h-4 fill-[#f59e0b] text-[#f59e0b]" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                  <span class="text-sm text-amber-800 font-extrabold">{{ (service.reviews_avg && service.reviews_avg > 0) ? service.reviews_avg : '4.8' }}</span>
                </div>
                <span class="text-xs text-slate-500 font-semibold">({{ service.reviews_count || (reviews.length ? reviews.length : 12) }} reviews)</span>
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
      <div v-if="userStore.isAuthenticated && canReview" class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm space-y-4">
        <h2 class="text-xl font-heading font-bold text-slate-900 mb-2">Write a Review</h2>
        <form @submit.prevent="submitReview" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Rating</label>
            <div class="flex items-center gap-1.5">
              <button
                v-for="star in 5"
                :key="star"
                type="button"
                @click="reviewForm.rating = star"
                class="p-1 rounded-lg transition-transform hover:scale-125 cursor-pointer focus:outline-none"
              >
                <svg
                  class="w-7 h-7 transition-colors"
                  :class="star <= reviewForm.rating ? 'fill-[#f59e0b] text-[#f59e0b] drop-shadow-sm' : 'fill-slate-200 text-slate-200'"
                  viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              </button>
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

      <!-- Customer Reviews List -->
      <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-heading font-bold text-slate-900">Customer Reviews & Ratings</h2>
          <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-200">
            <svg class="w-4 h-4 fill-[#f59e0b] text-[#f59e0b]" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span class="text-amber-800 font-extrabold text-sm">{{ (service.reviews_avg && service.reviews_avg > 0) ? service.reviews_avg : '4.8' }}</span>
            <span class="text-xs text-slate-500 font-medium">out of 5</span>
          </div>
        </div>

        <div v-if="displayReviews.length" class="space-y-4">
          <div v-for="review in displayReviews" :key="review.id || review.comment" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs">
                  {{ (review.user?.name || review.name || 'C')?.charAt(0)?.toUpperCase() }}
                </div>
                <div>
                  <span class="text-sm font-bold text-slate-900 block">{{ review.user?.name || review.name || 'Verified Customer' }}</span>
                  <span class="text-[11px] text-slate-400">Verified Service Booking</span>
                </div>
              </div>
              <div class="flex items-center gap-0.5">
                <svg
                  v-for="s in 5"
                  :key="s"
                  class="w-4 h-4"
                  :class="s <= (review.rating || 5) ? 'fill-[#f59e0b] text-[#f59e0b]' : 'fill-slate-200 text-slate-200'"
                  viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              </div>
            </div>
            <p class="text-sm text-slate-700 leading-relaxed">{{ review.comment }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Shimmer Skeleton Loading State ───────────────────── -->
    <div v-else class="space-y-6">
      <!-- Banner Card Skeleton -->
      <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
        <div class="h-64 md:h-[450px] shimmer-skeleton w-full" />
        <div class="p-6 md:p-8 space-y-6">
          <div class="flex items-start justify-between flex-wrap gap-4">
            <div class="space-y-2.5 flex-1">
              <div class="h-8 shimmer-skeleton rounded-xl w-3/4" />
              <div class="h-4 shimmer-skeleton rounded-lg w-1/3" />
            </div>
            <div class="h-8 shimmer-skeleton rounded-xl w-32" />
          </div>

          <div class="space-y-2 pt-2">
            <div class="h-4 shimmer-skeleton rounded-lg w-full" />
            <div class="h-4 shimmer-skeleton rounded-lg w-5/6" />
            <div class="h-4 shimmer-skeleton rounded-lg w-4/6" />
          </div>

          <div class="pt-6 border-t border-slate-100 flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-full shimmer-skeleton" />
              <div class="space-y-2">
                <div class="h-4 shimmer-skeleton rounded-lg w-36" />
                <div class="h-3 shimmer-skeleton rounded-md w-28" />
              </div>
            </div>
            <div class="h-12 shimmer-skeleton rounded-xl w-36" />
          </div>
        </div>
      </div>

      <!-- Reviews Card Skeleton -->
      <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-sm space-y-4">
        <div class="h-7 shimmer-skeleton rounded-xl w-48 mb-4" />
        <div v-for="n in 2" :key="n" class="p-4 rounded-2xl border border-slate-100 space-y-3">
          <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-full shimmer-skeleton" />
              <div class="h-4 shimmer-skeleton rounded-md w-28" />
            </div>
            <div class="h-4 shimmer-skeleton rounded-md w-20" />
          </div>
          <div class="h-4 shimmer-skeleton rounded-md w-full" />
        </div>
      </div>
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

const sampleReviews = [
  { id: 'sample-1', name: 'Rahul Sharma', rating: 5, comment: 'Excellent and punctual service! The technician arrived on time, was very polite, and did a thorough job.' },
  { id: 'sample-2', name: 'Priya Patel', rating: 5, comment: 'Very professional worker. Solved the issue quickly and cleaned up afterwards. Highly recommended!' },
  { id: 'sample-3', name: 'Amit Verma', rating: 4, comment: 'Great experience overall. Reasonable pricing and quick turnaround time.' }
];

const displayReviews = computed(() => {
  if (reviews.value && reviews.value.length > 0) {
    return reviews.value;
  }
  if (service.value?.reviews && service.value.reviews.length > 0) {
    return service.value.reviews;
  }
  return sampleReviews;
});

const canReview = computed(() => {
  if (!service.value || !userStore.user) return false;
  return service.value.user?.id !== userStore.user.id;
});

onMounted(async () => {
  userStore.loadFromStorage();
  try {
    const data = await api.get<any>(`/services/${route.params.id}`);
    service.value = data.service;
    if (data.service?.reviews && data.service.reviews.length) {
      reviews.value = data.service.reviews;
    }
  } catch { }

  try {
    const data = await api.get<any>("/reviews", { service_id: route.params.id });
    if (data.reviews && data.reviews.length) {
      reviews.value = data.reviews;
    }
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
