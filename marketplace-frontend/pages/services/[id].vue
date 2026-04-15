<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <div v-if="service" class="space-y-6">
      <!-- Image + Header -->
      <div class="glass rounded-2xl overflow-hidden">
        <div class="h-64 md:h-80 bg-surface-light relative">
          <img v-if="service.image" :src="service.image" :alt="service.title" class="w-full h-full object-cover" />
          <div v-else class="w-full h-full flex items-center justify-center text-6xl opacity-20">🔧</div>
        </div>
        <div class="p-6 md:p-8">
          <div class="flex items-start justify-between flex-wrap gap-4 mb-4">
            <div>
              <span class="px-3 py-1 rounded-full bg-primary/15 text-primary-light text-xs font-medium">{{ service.category?.name }}</span>
              <h1 class="text-2xl md:text-3xl font-heading font-bold text-white mt-3">{{ service.title }}</h1>
            </div>
            <div class="text-right">
              <div v-if="service.price_min || service.price_max" class="text-xl font-bold text-primary-light">
                ₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}
              </div>
              <div class="flex items-center gap-1 mt-1 justify-end">
                <span class="text-warning">★</span>
                <span class="text-sm text-white font-medium">{{ service.reviews_avg || '0.0' }}</span>
                <span class="text-xs text-slate-400">({{ service.reviews_count || 0 }} reviews)</span>
              </div>
            </div>
          </div>
          <p class="text-slate-300 whitespace-pre-wrap">{{ service.description || 'No description provided.' }}</p>

          <!-- Worker Info + Chat Button -->
          <div class="mt-6 pt-6 border-t border-white/5 flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white text-lg font-semibold">
                {{ service.user?.name?.charAt(0) }}
              </div>
              <div>
                <p class="font-medium text-white">{{ service.user?.name }}</p>
                <p class="text-xs text-slate-400">Service Provider</p>
              </div>
            </div>
            <button
              v-if="userStore.isAuthenticated"
              @click="startChat"
              :disabled="chatLoading"
              class="px-6 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all disabled:opacity-50"
            >
              {{ chatLoading ? 'Starting...' : '💬 Start Chat' }}
            </button>
            <NuxtLink v-else to="/login" class="px-6 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all">
              Login to Chat
            </NuxtLink>
          </div>
        </div>
      </div>

      <!-- Write a Review -->
      <div v-if="userStore.isAuthenticated && canReview" class="glass rounded-2xl p-6 md:p-8">
        <h2 class="text-xl font-heading font-semibold text-white mb-4">✍️ Write a Review</h2>
        <form @submit.prevent="submitReview" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Rating</label>
            <div class="flex gap-2">
              <button
                v-for="star in 5"
                :key="star"
                type="button"
                @click="reviewForm.rating = star"
                class="text-2xl transition-transform hover:scale-110"
                :class="star <= reviewForm.rating ? 'text-warning' : 'text-slate-600'"
              >★</button>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Comment</label>
            <textarea
              v-model="reviewForm.comment"
              rows="3"
              placeholder="Share your experience..."
              class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm resize-none"
            />
          </div>
          <p v-if="reviewError" class="text-danger text-sm">{{ reviewError }}</p>
          <p v-if="reviewSuccess" class="text-success text-sm animate-fade-in">✅ Review submitted!</p>
          <button type="submit" :disabled="!reviewForm.rating || reviewSubmitting" class="px-5 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all disabled:opacity-50">
            {{ reviewSubmitting ? 'Submitting...' : 'Submit Review' }}
          </button>
        </form>
      </div>

      <!-- Reviews List -->
      <div class="glass rounded-2xl p-6 md:p-8">
        <h2 class="text-xl font-heading font-semibold text-white mb-6">Reviews</h2>
        <div v-if="reviews.length" class="space-y-4">
          <div v-for="review in reviews" :key="review.id" class="p-4 rounded-xl bg-white/3 border border-white/5">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-surface-lighter flex items-center justify-center text-white text-xs font-semibold">
                  {{ review.user?.name?.charAt(0) }}
                </div>
                <span class="text-sm font-medium text-slate-200">{{ review.user?.name }}</span>
              </div>
              <span class="text-warning text-sm">{{ '★'.repeat(review.rating) }}{{ '☆'.repeat(5 - review.rating) }}</span>
            </div>
            <p class="text-sm text-slate-400">{{ review.comment || 'No comment.' }}</p>
          </div>
        </div>
        <p v-else class="text-slate-500">No reviews yet. Be the first!</p>
      </div>
    </div>

    <div v-else class="glass rounded-2xl p-16 text-center text-slate-500">Loading...</div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const api = useApi();
const userStore = useUserStore();

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
