<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-heading font-bold text-white">💬 Chats</h1>
    </div>

    <div v-if="chats.length" class="space-y-3">
      <NuxtLink
        v-for="chat in chats"
        :key="chat.id"
        :to="`/chats/${chat.id}`"
        class="glass rounded-2xl p-5 flex items-center gap-4 hover:border-primary/20 transition-all group"
      >
        <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white font-semibold flex-shrink-0">
          {{ otherParty(chat)?.charAt(0) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between mb-1">
            <h3 class="font-medium text-white group-hover:text-primary-light transition-colors truncate">{{ otherParty(chat) }}</h3>
            <span class="text-xs text-slate-500">{{ chat.updated_at?.split('T')[0] }}</span>
          </div>
          <p class="text-sm text-slate-400 truncate">{{ chat.service?.title }}</p>
          <p v-if="chat.latest_message" class="text-xs text-slate-500 truncate mt-1">{{ chat.latest_message.message }}</p>
        </div>
        <div v-if="chat.unread_count" class="w-6 h-6 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-bold">
          {{ chat.unread_count }}
        </div>
      </NuxtLink>
    </div>

    <div v-else class="glass rounded-2xl p-16 text-center text-slate-500">
      <p class="text-4xl mb-3">💬</p>
      <p>No chats yet. Start by browsing services!</p>
    </div>
  </div>
</template>

<script setup lang="ts">
const api = useApi();
const userStore = useUserStore();
const chats = ref<any[]>([]);

const otherParty = (chat: any) => {
  if (!userStore.user) return '';
  return chat.user?.id === userStore.user.id ? chat.worker?.name : chat.user?.name;
};

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo("/login");
  try {
    const data = await api.get<any>("/chats");
    chats.value = data.chats || [];
  } catch { }
});
</script>
