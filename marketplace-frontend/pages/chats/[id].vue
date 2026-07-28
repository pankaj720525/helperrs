<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 md:py-10">
    <div v-if="chat" class="bg-white rounded-3xl border border-slate-200 shadow-md overflow-hidden flex flex-col" style="height: 75vh;">

      <!-- ── Chat Room Header Bar ────────────────────────── -->
      <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <NuxtLink to="/chats" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-rose-600 transition-colors" title="Back to All Chats">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
          </NuxtLink>

          <!-- Avatar & Online Status -->
          <div class="relative">
            <div class="w-10 h-10 rounded-full gradient-primary flex items-center justify-center text-white font-extrabold text-sm shadow-sm">
              {{ otherParty?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <span class="w-3 h-3 rounded-full bg-emerald-500 border-2 border-white absolute bottom-0 right-0" />
          </div>

          <div>
            <h2 class="font-bold text-slate-900 text-sm leading-tight">{{ otherParty }}</h2>
            <p class="text-[11px] text-slate-500 font-medium truncate max-w-xs">{{ chat.service?.title || 'Service Conversation' }}</p>
          </div>
        </div>

        <NuxtLink
          v-if="chat.service?.id"
          :to="`/services/${chat.service.id}`"
          class="px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-all hidden sm:inline-flex items-center gap-1"
        >
          <span>View Service Details →</span>
        </NuxtLink>
      </div>

      <!-- ── Messages Area ──────────────────────────────── -->
      <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 bg-slate-50/50 scrollbar-thin">

        <!-- Shimmer loading skeleton when fetching messages -->
        <div v-if="loadingMessages" class="space-y-3">
          <div class="flex justify-start"><div class="w-1/2 h-10 shimmer-skeleton rounded-2xl" /></div>
          <div class="flex justify-end"><div class="w-1/2 h-10 shimmer-skeleton rounded-2xl" /></div>
          <div class="flex justify-start"><div class="w-1/3 h-10 shimmer-skeleton rounded-2xl" /></div>
        </div>

        <template v-else>
          <div
            v-for="msg in messages"
            :key="msg.id"
            class="flex"
            :class="msg.sender_id === userStore.user?.id ? 'justify-end' : 'justify-start'"
          >
            <!-- Message Bubble -->
            <div
              class="max-w-[80%] sm:max-w-[70%] px-4 py-3 rounded-2xl shadow-sm text-sm"
              :class="msg.sender_id === userStore.user?.id
                ? 'bg-gradient-to-r from-rose-600 to-rose-700 text-white rounded-br-none'
                : 'bg-white text-slate-900 border border-slate-200 rounded-bl-none'"
              :style="msg.sender_id === userStore.user?.id ? 'color: #ffffff !important;' : ''"
            >
              <p class="leading-relaxed font-medium whitespace-pre-wrap">{{ msg.message }}</p>
              <div
                class="text-[10px] mt-1 text-right font-semibold"
                :class="msg.sender_id === userStore.user?.id ? 'text-white/80' : 'text-slate-400'"
              >
                {{ formatTime(msg.created_at) }}
              </div>
            </div>
          </div>
          <!-- Typing Indicator -->
          <div v-if="otherPartyTyping" class="flex justify-start items-center pt-1">
            <div class="px-4 py-3 rounded-2xl bg-white border border-slate-200 shadow-sm rounded-bl-none text-xs text-slate-500 font-semibold flex items-center gap-1.5 animate-pulse">
              <span class="inline-flex gap-1 items-center">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-bounce" style="animation-delay: 0s"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-bounce" style="animation-delay: 0.15s"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-bounce" style="animation-delay: 0.3s"></span>
              </span>
              <span>{{ otherParty }} is typing...</span>
            </div>
          </div>
        </template>

      </div>

      <!-- ── Quick Reply Suggestion Chips ───────────────── -->
      <div class="px-4 py-2 bg-white border-t border-slate-100 flex items-center gap-2 overflow-x-auto scrollbar-none">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex-shrink-0">Quick Reply:</span>
        <button
          v-for="reply in quickReplies"
          :key="reply"
          type="button"
          @click="newMessage = reply; sendMsg()"
          class="px-3 py-1 rounded-full bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 text-xs font-semibold whitespace-nowrap transition-all cursor-pointer flex-shrink-0"
        >
          {{ reply }}
        </button>
      </div>

      <!-- ── Message Input Bar ──────────────────────────── -->
      <div class="p-3 sm:p-4 bg-white border-t border-slate-200">
        <form @submit.prevent="sendMsg" class="flex gap-2 sm:gap-3">
          <input
            v-model="newMessage"
            @input="handleTyping"
            type="text"
            placeholder="Type your message here..."
            class="flex-1 px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-rose-600 text-sm font-medium"
          />
          <button
            type="submit"
            :disabled="!newMessage.trim() || sending"
            class="px-6 py-3 rounded-xl bg-gradient-to-r from-rose-600 via-rose-700 to-rose-800 text-white text-sm font-extrabold hover:shadow-md transition-all disabled:opacity-50 flex items-center gap-1.5 cursor-pointer whitespace-nowrap"
            style="color: #ffffff !important;"
          >
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            <span class="text-white font-bold">{{ sending ? 'Sending...' : 'Send' }}</span>
          </button>
        </form>
      </div>

    </div>

    <div v-else class="bg-white rounded-3xl p-16 text-center text-slate-400 border border-slate-200">
      Loading chat room...
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard' });

const route = useRoute();
const api = useApi();
const userStore = useUserStore();
const { listenPrivate, echo } = useEcho();
const { playNotificationSound } = useAudio();

const chat = ref<any>(null);
const messages = ref<any[]>([]);
const newMessage = ref("");
const sending = ref(false);
const loadingMessages = ref(true);
const messagesContainer = ref<HTMLElement>();

const otherPartyTyping = ref(false);
let typingTimeout: any = null;

const quickReplies = [
  "Is technician available today?",
  "What is the estimated service cost?",
  "Please share your exact location.",
  "Can you arrive by 2 PM?"
];

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
  loadingMessages.value = true;
  try {
    const data = await api.get<any>(`/chats/${route.params.id}/messages`, { per_page: 100 });
    chat.value = data.chat;
    messages.value = (data.messages || []).reverse();
    scrollToBottom();
  } catch { }
  finally {
    loadingMessages.value = false;
  }
};

const handleTyping = () => {
  if (!echo) return;
  echo.private(`chat.${route.params.id}`)
    .whisper('typing', {
      userId: userStore.user?.id,
      typing: true
    });

  if (typingTimeout) clearTimeout(typingTimeout);
  typingTimeout = setTimeout(() => {
    if (!echo) return;
    echo.private(`chat.${route.params.id}`)
      .whisper('typing', {
        userId: userStore.user?.id,
        typing: false
      });
  }, 2000);
};

const sendMsg = async () => {
  if (!newMessage.value.trim()) return;
  const msgText = newMessage.value.trim();
  newMessage.value = "";
  sending.value = true;

  if (typingTimeout) clearTimeout(typingTimeout);
  if (echo) {
    echo.private(`chat.${route.params.id}`)
      .whisper('typing', {
        userId: userStore.user?.id,
        typing: false
      });
  }

  try {
    const data = await api.post<any>(`/chats/${route.params.id}/messages`, { message: msgText });
    messages.value.push(data.message);
    scrollToBottom();
  } catch { }
  finally { sending.value = false; }
};

watch(otherPartyTyping, (newVal) => {
  if (newVal) {
    scrollToBottom();
  }
});

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo("/login");
  await loadMessages();

  listenPrivate(`chat.${route.params.id}`, 'message.new', (data: any) => {
    const exists = messages.value.some((m: any) => m.id === data.id);
    if (!exists) {
      messages.value.push(data);
      scrollToBottom();
      if (data.sender_id !== userStore.user?.id) {
        playNotificationSound();
      }
    }
  });

  if (echo) {
    echo.private(`chat.${route.params.id}`)
      .listenForWhisper('typing', (e: any) => {
        if (e.userId !== userStore.user?.id) {
          otherPartyTyping.value = e.typing;
        }
      });
  }
});
</script>
