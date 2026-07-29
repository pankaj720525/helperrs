<template>
  <div class="space-y-6">

    <!-- ── Shimmer Loading Skeleton State ──────────────── -->
    <div v-if="loading" class="space-y-6">
      <div class="bg-white rounded-3xl p-8 border border-slate-200 space-y-4">
        <div class="h-8 shimmer-skeleton rounded-lg w-1/3" />
        <div class="h-4 shimmer-skeleton rounded w-1/2" />
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="n in 6" :key="n" class="bg-white rounded-2xl p-6 border border-slate-200 space-y-4">
          <div class="w-12 h-12 shimmer-skeleton rounded-xl" />
          <div class="h-5 shimmer-skeleton rounded w-3/4" />
          <div class="h-3.5 shimmer-skeleton rounded w-full" />
          <div class="h-9 shimmer-skeleton rounded-xl w-full pt-2" />
        </div>
      </div>
    </div>

    <!-- ── Overview Pane ──────────────────────────────── -->
    <div v-else-if="user" class="space-y-6">

      <!-- Top Welcome Banner -->
      <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-2 gradient-primary" />

        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pt-2">
          <div>
            <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-900 mb-1">Welcome back, {{ user.name }}! 👋</h1>
            <p class="text-sm text-slate-500 font-medium flex items-center gap-1.5 mt-1">
              <svg class="w-4 h-4 text-rose-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span>Servicing Zone: <strong>{{ currentLocation.formatted }}</strong></span>
            </p>
          </div>

          <!-- Subscription Badge -->
          <div v-if="subscription" class="bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-3 text-left md:text-right">
            <div class="text-xs font-bold text-emerald-800 uppercase tracking-wider">
              {{ subscription.is_trial ? 'Free Trial Active' : subscription.plan_type }}
            </div>
            <div class="text-xs text-emerald-700 font-semibold mt-0.5">
              {{ subscription.days_remaining }} days remaining
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Action Modules -->
      <div>
        <h2 class="text-lg font-heading font-bold text-slate-900 mb-4">Quick Actions</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

          <!-- 1. My Messages -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-rose-400 hover:shadow-md transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
              </div>
              <h3 class="font-heading font-bold text-slate-900 text-base mb-1">Messages & Chats</h3>
              <p class="text-xs text-slate-500 leading-relaxed mb-5">Talk directly with service providers or clients.</p>
            </div>
            <NuxtLink
              to="/chats"
              class="w-full text-center py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-bold hover:shadow-md transition-all"
              style="color: #ffffff !important;"
            >
              Open Messages →
            </NuxtLink>
          </div>

          <!-- 2. My Offered Services (For Worker Accounts) -->
          <div v-if="userStore.isWorker" class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-rose-400 hover:shadow-md transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <h3 class="font-heading font-bold text-slate-900 text-base mb-1">My Offered Services</h3>
              <p class="text-xs text-slate-500 leading-relaxed mb-5">Add, edit, or update services and pricing rates you offer.</p>
            </div>
            <NuxtLink
              to="/dashboard/services"
              class="w-full text-center py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-bold hover:shadow-md transition-all"
              style="color: #ffffff !important;"
            >
              Manage My Services →
            </NuxtLink>
          </div>

          <!-- 3. Saved Services (Wishlist) -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-rose-400 hover:shadow-md transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 mb-4">
                <svg class="w-6 h-6 text-rose-500 fill-rose-500" viewBox="0 0 24 24">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
              </div>
              <h3 class="font-heading font-bold text-slate-900 text-base mb-1">Saved Services</h3>
              <p class="text-xs text-slate-500 leading-relaxed mb-5">View and manage bookmarked service professionals.</p>
            </div>
            <NuxtLink
              to="/dashboard/wishlist"
              class="w-full text-center py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all"
              style="color: #ffffff !important;"
            >
              View Saved Services →
            </NuxtLink>
          </div>

          <!-- 4. Browse Catalog -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-rose-400 hover:shadow-md transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-xl bg-sky-50 border border-sky-200 flex items-center justify-center text-sky-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
              </div>
              <h3 class="font-heading font-bold text-slate-900 text-base mb-1">Browse Catalog</h3>
              <p class="text-xs text-slate-500 leading-relaxed mb-5">View full catalog of available home repair & maintenance services.</p>
            </div>
            <NuxtLink
              to="/services"
              class="w-full text-center py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-all border border-slate-200"
            >
              Explore Directory →
            </NuxtLink>
          </div>

          <!-- 5. Edit Profile -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-rose-400 hover:shadow-md transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <h3 class="font-heading font-bold text-slate-900 text-base mb-1">Account Profile</h3>
              <p class="text-xs text-slate-500 leading-relaxed mb-5">Update your mobile phone number, name & credentials.</p>
            </div>
            <NuxtLink
              to="/dashboard/profile"
              class="w-full text-center py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-all border border-slate-200"
            >
              Update Profile →
            </NuxtLink>
          </div>

          <!-- 6. Notifications -->
          <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:border-rose-400 hover:shadow-md transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center text-purple-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
              </div>
              <h3 class="font-heading font-bold text-slate-900 text-base mb-1">Notification Settings</h3>
              <p class="text-xs text-slate-500 leading-relaxed mb-5">Manage email alerts & booking updates preferences.</p>
            </div>
            <NuxtLink
              to="/dashboard/notifications"
              class="w-full text-center py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-all border border-slate-200"
            >
              Manage Preferences →
            </NuxtLink>
          </div>

        </div>
      </div>

      <!-- Customer Support Card -->
      <div class="bg-white border border-slate-200 rounded-3xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-lg">🎧</div>
          <div>
            <h4 class="font-bold text-slate-900 text-sm">Need help or have questions?</h4>
            <p class="text-xs text-slate-500">Contact our 24/7 customer helpline at <strong>1800-123-4567</strong></p>
          </div>
        </div>
        <button
          @click="startSupportChat"
          :disabled="startingSupport"
          class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-bold whitespace-nowrap shadow-sm hover:shadow-md transition-all cursor-pointer disabled:opacity-50"
          style="color: #ffffff !important;"
        >
          {{ startingSupport ? 'Connecting...' : 'Live Chat Support' }}
        </button>
      </div>

    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard' });

const userStore = useUserStore();
const api = useApi();
const { currentLocation } = useUserLocation();

const user = ref<any>(null);
const subscription = ref<any>(null);
const loading = ref(true);
const startingSupport = ref(false);

const startSupportChat = async () => {
  startingSupport.value = true;
  try {
    const data = await api.post<any>("/chats/support");
    if (data.chat?.id) {
      navigateTo(`/chats/${data.chat.id}`);
    } else {
      navigateTo("/chats");
    }
  } catch {
    navigateTo("/chats");
  } finally {
    startingSupport.value = false;
  }
};

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo("/login");

  try {
    const data = await api.get<any>("/auth/user");
    user.value = data.user;
    userStore.updateUser(data.user);
  } catch { }

  if (userStore.isWorker) {
    try {
      const data = await api.get<any>("/subscription");
      subscription.value = data.subscription;
    } catch { }
  }

  loading.value = false;
});
</script>
