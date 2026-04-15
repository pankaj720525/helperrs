<template>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
    <div v-if="chat" class="glass rounded-2xl overflow-hidden flex flex-col" style="height: 70vh;">
      <!-- Chat Header -->
      <div class="p-4 border-b border-white/5 flex items-center gap-3">
        <NuxtLink to="/chats" class="text-slate-400 hover:text-white">←</NuxtLink>
        <div class="w-9 h-9 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-semibold">
          {{ otherParty?.charAt(0) }}
        </div>
        <div>
          <p class="font-medium text-white text-sm">{{ otherParty }}</p>
          <p class="text-xs text-slate-400">{{ chat.service?.title }}</p>
        </div>
      </div>

      <!-- Messages -->
      <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-thin">
        <div
          v-for="msg in messages"
          :key="msg.id"
          class="flex"
          :class="msg.sender_id === userStore.user?.id ? 'justify-end' : 'justify-start'"
        >
          <div
            class="max-w-[75%] px-4 py-2.5 rounded-2xl text-sm"
            :class="msg.sender_id === userStore.user?.id
              ? 'gradient-primary text-white rounded-br-md'
              : 'bg-surface-light text-slate-200 rounded-bl-md'"
          >
            {{ msg.message }}
            <p class="text-[10px] mt-1 opacity-60">{{ formatTime(msg.created_at) }}</p>
          </div>
        </div>
      </div>

      <!-- Input -->
      <div class="p-4 border-t border-white/5">
        <form @submit.prevent="sendMsg" class="flex gap-3">
          <input
            v-model="newMessage"
            type="text"
            placeholder="Type a message..."
            class="flex-1 px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm"
          />
          <button type="submit" :disabled="!newMessage.trim() || sending" class="px-5 py-3 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all disabled:opacity-50">
            Send
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const api = useApi();
const userStore = useUserStore();
const { listenOn } = useEcho();
const { playNotificationSound } = useAudio();

const chat = ref<any>(null);
const messages = ref<any[]>([]);
const newMessage = ref("");
const sending = ref(false);
const messagesContainer = ref<HTMLElement>();

const otherParty = computed(() => {
  if (!chat.value || !userStore.user) return '';
  return chat.value.user?.id === userStore.user.id ? chat.value.worker?.name : chat.value.user?.name;
});

const formatTime = (dt: string) => {
  if (!dt) return '';
  return new Date(dt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
  });
};

const loadMessages = async () => {
  try {
    const data = await api.get<any>(`/chats/${route.params.id}/messages`, { per_page: 100 });
    chat.value = data.chat;
    messages.value = (data.messages || []).reverse();
    scrollToBottom();
  } catch { }
};

const sendMsg = async () => {
  if (!newMessage.value.trim()) return;
  sending.value = true;
  try {
    const data = await api.post<any>(`/chats/${route.params.id}/messages`, { message: newMessage.value });
    messages.value.push(data.message);
    newMessage.value = "";
    scrollToBottom();
  } catch { }
  finally { sending.value = false; }
};

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo("/login");
  await loadMessages();

  // Subscribe to real-time chat channel
  if (chat.value) {
    listenOn(`chat.${chat.value.raw_id || route.params.id}`, 'message.new', (data: any) => {
      const exists = messages.value.some((m: any) => m.id === data.id);
      if (!exists) {
        messages.value.push(data);
        scrollToBottom();
        // Play audio alert for incoming messages (not own)
        if (data.sender_id !== userStore.user?.id) {
          playNotificationSound();
        }
      }
    });
  }
});
</script>
