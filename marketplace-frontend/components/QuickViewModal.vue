<template>
  <Teleport to="body">
    <Transition name="qv-fade">
      <div v-if="service" class="qv-overlay" @click.self="$emit('close')">
        <div class="qv-card">
          <button type="button" @click="$emit('close')" class="qv-close-btn" title="Close">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>

          <div class="qv-grid">
            <!-- Left: Image & Badges -->
            <div class="qv-media">
              <img :src="getServiceImage(service)" :alt="service.title" class="qv-img" />

              <div class="qv-badges">
                <span v-if="service.badge" class="qv-badge badge-hot">{{ service.badge }}</span>
                <span class="qv-badge badge-cat">{{ service.category?.name || service.category_name || 'Service' }}</span>
                <span v-if="service.express" class="qv-badge badge-express">⚡ Express 2hr</span>
              </div>
            </div>

            <!-- Right: Details -->
            <div class="qv-details">
              <h2 class="qv-title">{{ service.title }}</h2>

              <div class="qv-meta-row">
                <div class="qv-rating">
                  <span class="qv-star">★</span>
                  <span class="qv-rating-val">{{ service.reviews_avg || service.rating || '4.8' }}</span>
                  <span class="qv-reviews">({{ service.reviews_count || service.reviews || 12 }} reviews)</span>
                </div>
                <div class="qv-location flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 text-rose-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  <span>{{ service.address || service.location || currentLocation.formatted }}</span>
                </div>
              </div>

              <div class="qv-price-tag">
                <span class="qv-price-label">Estimated Cost:</span>
                <span class="qv-price-value">
                  <template v-if="service.price_min || service.price_max">
                    ₹{{ service.price_min || 0 }} – ₹{{ service.price_max || 0 }}
                  </template>
                  <template v-else>₹{{ service.price || 499 }}</template>
                </span>
              </div>

              <p class="qv-desc">{{ service.description || 'Professional and verified service provider available in your area. Booking includes 30-day service guarantee.' }}</p>

              <!-- Features checklist -->
              <div class="qv-features">
                <div class="qv-feat-item">
                  <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                  </svg>
                  <span>Verified Expert</span>
                </div>
                <div class="qv-feat-item">
                  <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                  <span>30-Day Warranty</span>
                </div>
                <div class="qv-feat-item">
                  <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span>On-Time Doorstep</span>
                </div>
                <div class="qv-feat-item">
                  <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span>Fixed Pricing</span>
                </div>
              </div>

              <!-- Actions -->
              <div class="qv-actions">
                <NuxtLink
                  :to="`/services/${service.id}`"
                  class="qv-book-btn"
                  @click="$emit('close')"
                >
                  Book Service Now →
                </NuxtLink>
                <button
                  type="button"
                  @click="toggleWishlist(service.id, service.title)"
                  class="qv-wish-btn"
                  :class="{ active: isWishlisted(service.id) }"
                  :title="isWishlisted(service.id) ? 'Remove from favorites' : 'Add to favorites'"
                >
                  <svg class="w-4 h-4 inline mr-1 text-rose-500 fill-current" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                  </svg>
                  <span>{{ isWishlisted(service.id) ? 'Saved' : 'Save' }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
const props = defineProps<{
  service: any;
}>();

defineEmits(['close']);

const { currentLocation } = useUserLocation();
const { isWishlisted, toggleWishlist } = useWishlist();
const { getServiceImage } = useDefaultImage();
</script>

<style scoped>
.qv-overlay {
  position: fixed; inset: 0; z-index: 1000;
  background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px);
  display: flex; align-items: center; justify-content: center; padding: 1.25rem;
}
.qv-card {
  position: relative; width: 100%; max-width: 52rem;
  background: #ffffff; border: 1px solid #e2e8f0;
  border-radius: 1.25rem; overflow: hidden;
  box-shadow: 0 20px 50px rgba(15, 23, 42, 0.2); color: #0f172a;
}
:global(html.dark) .qv-card {
  background: #1e293b; border-color: rgba(255,255,255,0.12); color: #f8fafc;
}

.qv-close-btn {
  position: absolute; top: 1rem; right: 1rem; z-index: 10;
  width: 2.25rem; height: 2.25rem; border-radius: 50%;
  background: #f1f5f9; border: 1px solid #e2e8f0; color: #64748b;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: all 0.15s;
}
:global(html.dark) .qv-close-btn { background: rgba(0,0,0,0.4); border-color: transparent; color: #fff; }
.qv-close-btn:hover { background: #fee2e2; color: #ef4444; }

.qv-grid { display: grid; grid-template-columns: 1fr 1fr; }
@media (max-width: 768px) { .qv-grid { grid-template-columns: 1fr; } }

.qv-media { position: relative; height: 100%; min-height: 18rem; background: #f8fafc; }
:global(html.dark) .qv-media { background: #0f172a; }
.qv-img { width: 100%; height: 100%; object-fit: cover; }
.qv-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }

.qv-badges { position: absolute; top: 1rem; left: 1rem; display: flex; flex-direction: column; gap: 0.375rem; align-items: flex-start; }
.qv-badge { padding: 4px 10px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; }
.badge-hot { background: #ef4444; color: #fff; }
.badge-cat { background: rgba(15,23,42,0.85); color: #38bdf8; backdrop-filter: blur(8px); }
.badge-express { background: #f59e0b; color: #000; }

.qv-details { padding: 2rem; display: flex; flex-direction: column; gap: 1rem; }
.qv-title { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.3; }
:global(html.dark) .qv-title { color: #fff; }

.qv-meta-row { display: flex; align-items: center; gap: 1rem; font-size: 0.8125rem; color: #64748b; }
.qv-rating { display: flex; align-items: center; gap: 4px; }
.qv-star { color: #d97706; }
:global(html.dark) .qv-star { color: #f59e0b; }
.qv-rating-val { font-weight: 700; color: #0f172a; }
:global(html.dark) .qv-rating-val { color: #fff; }
.qv-location { color: #0284c7; font-weight: 600; }

.qv-price-tag {
  display: flex; align-items: baseline; gap: 0.5rem;
  padding: 0.75rem 1rem; border-radius: 0.75rem;
  background: #fef2f5; border: 1px solid #fbc0d0;
}
:global(html.dark) .qv-price-tag { background: rgba(178,5,55,0.1); border-color: rgba(178,5,55,0.25); }
.qv-price-label { font-size: 0.8125rem; color: #64748b; font-weight: 500; }
.qv-price-value { font-size: 1.375rem; font-weight: 800; color: #b20537; }
:global(html.dark) .qv-price-value { color: #f43f5e; }

.qv-desc { font-size: 0.875rem; color: #475569; line-height: 1.6; margin: 0; }
:global(html.dark) .qv-desc { color: #cbd5e1; }

.qv-features { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.78rem; color: #475569; font-weight: 500; }
:global(html.dark) .qv-features { color: #94a3b8; }
.qv-feat-item { display: flex; align-items: center; gap: 6px; }

.qv-actions { display: flex; gap: 0.75rem; margin-top: 0.5rem; }
.qv-book-btn {
  flex: 1; text-align: center; padding: 0.75rem 1.25rem; border-radius: 0.75rem;
  background: linear-gradient(135deg, #B20537, #D4064A, #F43F5E);
  color: #fff; font-weight: 700; text-decoration: none; font-size: 0.9375rem;
  box-shadow: 0 4px 15px rgba(178,5,55,0.3); transition: transform 0.15s;
}
.qv-book-btn:hover { transform: translateY(-1px); }

.qv-wish-btn {
  padding: 0 1.25rem; border-radius: 0.75rem;
  background: #f1f5f9; border: 1px solid #e2e8f0;
  color: #475569; font-weight: 600; font-size: 0.875rem; cursor: pointer;
  transition: all 0.15s; display: flex; align-items: center; justify-content: center;
}
:global(html.dark) .qv-wish-btn { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.12); color: #94a3b8; }
.qv-wish-btn.active { color: #ef4444; border-color: rgba(239,68,68,0.4); background: rgba(239,68,68,0.1); }

.qv-fade-enter-active, .qv-fade-leave-active { transition: opacity 0.2s ease, transform 0.2s ease; }
.qv-fade-enter-from, .qv-fade-leave-to { opacity: 0; transform: scale(0.96); }
</style>
