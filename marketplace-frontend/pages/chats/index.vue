<template>
  <div class="space-y-6">

    <!-- Header Card -->
    <div
      class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-heading font-extrabold text-slate-900 mb-1 flex items-center gap-2">
          <svg class="w-6 h-6 text-rose-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          <span>My Chat Conversations</span>
        </h1>
        <p class="text-xs text-slate-500 font-medium">Direct live messaging with verified service professionals and
          customers</p>
      </div>

      <NuxtLink to="/services"
        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-extrabold hover:shadow-md transition-all whitespace-nowrap"
        style="color: #ffffff !important;">
        + Browse Services
      </NuxtLink>
    </div>

    <!-- Filter/Search Bar -->
    <div v-if="chats.length"
      class="bg-white rounded-2xl p-3 border border-slate-200 shadow-sm mb-6 flex items-center px-4">
      <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
      <input v-model="searchQuery" type="text" placeholder="Filter chats by provider name or service..."
        class="w-full py-1.5 bg-transparent border-none text-slate-900 text-sm outline-none font-medium placeholder-slate-400" />
    </div>

    <!-- Shimmer Skeleton Loading State -->
    <div v-if="loading" class="space-y-3">
      <div v-for="n in 5" :key="n" class="bg-white rounded-2xl p-4 border border-slate-200 flex items-center gap-4">
        <div class="w-12 h-12 shimmer-skeleton rounded-full flex-shrink-0" />
        <div class="flex-1 space-y-2">
          <div class="h-4 shimmer-skeleton rounded w-1/3" />
          <div class="h-3 shimmer-skeleton rounded w-2/3" />
        </div>
      </div>
    </div>

    <!-- Conversations List -->
    <div v-else-if="filteredChats.length" class="space-y-3">
      <NuxtLink v-for="chat in filteredChats" :key="chat.id" :to="`/chats/${chat.id}`"
        class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 hover:border-rose-400 hover:shadow-md transition-all flex items-center gap-4 group">
        <!-- Avatar Circle with Active Indicator -->
        <div class="relative flex-shrink-0">
          <div
            v-if="chat.is_support"
            class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-gradient-to-r from-rose-600 to-rose-700 flex items-center justify-center text-white text-xl shadow-sm"
          >
            🎧
          </div>
          <div
            v-else
            class="w-12 h-12 sm:w-14 sm:h-14 rounded-full gradient-primary flex items-center justify-center text-white font-extrabold text-lg shadow-sm"
          >
            {{ otherParty(chat)?.charAt(0)?.toUpperCase() || 'U' }}
          </div>
          <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white absolute bottom-0 right-0" />
        </div>

        <!-- Chat Details -->
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between mb-1">
            <h3 class="font-bold text-slate-900 text-base group-hover:text-rose-600 transition-colors truncate">
              {{ otherParty(chat) }}
            </h3>
            <span class="text-[11px] text-slate-400 font-medium">{{ formatDate(chat.updated_at) }}</span>
          </div>

          <div class="flex items-center gap-2 mb-1">
            <span
              class="px-2.5 py-0.5 rounded-full text-[10px] font-bold truncate"
              :class="chat.is_support ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700'"
            >
              {{ chat.is_support ? 'Support Chat' : (chat.service?.title || 'Service Chat') }}
            </span>
          </div>

          <p v-if="chat.latest_message" class="text-xs text-slate-500 truncate font-medium">
            <span class="font-bold text-slate-700" v-if="chat.latest_message.sender_id === userStore.user?.id">You:
            </span>
            {{ chat.latest_message.message }}
          </p>
        </div>

        <!-- Unread Badge -->
        <div v-if="chat.unread_count"
          class="w-6 h-6 rounded-full bg-rose-600 text-white font-extrabold text-xs flex items-center justify-center shadow-sm flex-shrink-0"
          style="color: #ffffff !important;">
          {{ chat.unread_count }}
        </div>
        <svg
          class="w-5 h-5 text-slate-300 group-hover:text-rose-500 group-hover:translate-x-0.5 transition-all flex-shrink-0"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </NuxtLink>
    </div>

    <!-- Empty State -->
    <div v-else-if="!loading" class="bg-white rounded-3xl p-16 text-center border border-slate-200 space-y-4">
      <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
      </div>
      <h3 class="font-extrabold text-slate-900 text-lg">No Active Messages Yet</h3>
      <p class="text-xs text-slate-500 max-w-sm mx-auto">Browse verified services in your city and click "Start Chat" to
        message service technicians directly.</p>
      <NuxtLink to="/services"
        class="inline-block px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-extrabold shadow-md"
        style="color: #ffffff !important;">
        Find Services to Chat
      </NuxtLink>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard' });

const api = useApi();
const userStore = useUserStore();

const chats = ref<any[]>([]);
const searchQuery = ref('');
const loading = ref(true);

const otherParty = (chat: any) => {
  if (chat.is_support) return 'Support Chat';
  if (!userStore.user) return '';
  return chat.user?.id === userStore.user.id ? chat.worker?.name : chat.user?.name;
};

const formatDate = (dt: string) => {
  if (!dt) return '';
  const d = new Date(dt);
  return d.toLocaleDateString([], { month: 'short', day: 'numeric' });
};

const filteredChats = computed(() => {
  if (!searchQuery.value.trim()) return chats.value;
  const q = searchQuery.value.trim().toLowerCase();
  return chats.value.filter((c: any) =>
    otherParty(c).toLowerCase().includes(q) ||
    c.service?.title?.toLowerCase().includes(q)
  );
});

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo("/login");

  try {
    const data = await api.get<any>("/chats");
    chats.value = data.chats || [];
  } catch { }
  finally {
    loading.value = false;
  }
});
</script>
