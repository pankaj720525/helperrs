<template>
  <div class="max-w-4xl mx-auto px-2 sm:px-6 py-4 md:py-10">
    <div v-if="chat" class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-md overflow-hidden flex flex-col h-[75vh] min-h-[480px] max-h-[750px] w-full">

      <!-- ── Chat Room Header Bar ────────────────────────── -->
      <div class="p-3 sm:p-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between gap-2 flex-wrap">
        <div class="flex items-center gap-2.5 min-w-0">
          <NuxtLink to="/chats" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-rose-600 transition-colors flex-shrink-0" title="Back to All Chats">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
          </NuxtLink>

          <!-- Avatar & Online Status -->
          <div class="relative flex-shrink-0">
            <div v-if="chat.is_support" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-r from-rose-600 to-rose-700 flex items-center justify-center text-white text-base shadow-sm">
              🎧
            </div>
            <div v-else class="w-9 h-9 sm:w-10 sm:h-10 rounded-full gradient-primary flex items-center justify-center text-white font-extrabold text-xs sm:text-sm shadow-sm">
              {{ otherParty?.charAt(0)?.toUpperCase() || 'U' }}
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-white absolute bottom-0 right-0" />
          </div>

          <div class="min-w-0">
            <div class="flex items-center gap-1.5 flex-wrap">
              <h2 class="font-bold text-slate-900 text-xs sm:text-sm leading-tight truncate max-w-[130px] sm:max-w-xs">{{ otherParty }}</h2>
              <span class="text-[9px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded-full flex-shrink-0" :class="chat.status === 'closed' ? 'bg-slate-200 text-slate-700' : 'bg-emerald-100 text-emerald-800'">
                {{ chat.status === 'closed' ? 'Closed' : 'Active' }}
              </span>
            </div>
            <p class="text-[10px] sm:text-[11px] text-slate-500 font-medium truncate max-w-[150px] sm:max-w-xs">{{ chat.is_support ? '🎧 Live Customer Support' : (chat.service?.title || 'Service Conversation') }}</p>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <NuxtLink
            v-if="chat.service?.id"
            :to="`/services/${chat.service.id}`"
            class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-100 transition-all hidden sm:inline-flex items-center gap-1"
          >
            <span>View Service Details →</span>
          </NuxtLink>

          <!-- Close Chat Button -->
          <button
            v-if="chat.status !== 'closed'"
            @click="closeChat"
            :disabled="closingChat"
            class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold hover:bg-rose-100 transition-colors disabled:opacity-50 flex items-center gap-1 cursor-pointer"
          >
            <span>✕ Close Chat</span>
          </button>
        </div>
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
            :key="msg.id || msg.temp_id"
            class="flex"
            :class="msg.sender_id === userStore.user?.id || msg.is_me ? 'justify-end' : 'justify-start'"
          >
            <!-- Message Bubble -->
            <div
              class="max-w-[80%] sm:max-w-[70%] px-4 py-3 rounded-2xl shadow-sm text-sm"
              :class="msg.sender_id === userStore.user?.id || msg.is_me
                ? 'bg-gradient-to-r from-rose-600 to-rose-700 text-white rounded-br-none'
                : 'bg-white text-slate-900 border border-slate-200 rounded-bl-none'"
              :style="msg.sender_id === userStore.user?.id || msg.is_me ? 'color: #ffffff !important;' : ''"
            >
              <!-- Attached Image Preview -->
              <div v-if="msg.image_url" class="mb-2">
                <img
                  :src="msg.image_url"
                  alt="Attachment"
                  class="rounded-xl max-h-60 object-cover border border-white/20 shadow-xs cursor-pointer hover:opacity-95 transition-opacity"
                  @click="selectedPreviewImage = msg.image_url"
                />
              </div>

              <p v-if="msg.message" class="leading-relaxed font-medium whitespace-pre-wrap">{{ msg.message }}</p>
              
              <!-- Timestamp & Delivery Status Indicator (Sending... / Sent) -->
              <div
                class="text-[10px] mt-1 text-right font-semibold flex items-center justify-end gap-1"
                :class="msg.sender_id === userStore.user?.id || msg.is_me ? 'text-white/80' : 'text-slate-400'"
              >
                <span>{{ formatTime(msg.created_at) }}</span>
                <span v-if="msg.status === 'sending'" class="inline-flex items-center text-amber-200">
                  <svg class="w-3 h-3 animate-spin inline ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <span v-else-if="msg.sender_id === userStore.user?.id || msg.is_me" class="text-emerald-200">✓</span>
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
          v-for="reply in currentQuickReplies"
          :key="reply"
          type="button"
          @click="newMessage = reply; sendMsg()"
          class="px-3 py-1 rounded-full bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 text-xs font-semibold whitespace-nowrap transition-all cursor-pointer flex-shrink-0"
        >
          {{ reply }}
        </button>
      </div>

      <!-- Image Attachment Preview Bar -->
      <div v-if="attachedImagePreview" class="px-4 py-2 bg-rose-50 border-t border-rose-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <img :src="attachedImagePreview" class="w-10 h-10 rounded-lg object-cover border border-rose-200 shadow-xs" />
          <span class="text-xs font-bold text-rose-700">Image Attached</span>
        </div>
        <button @click="removeAttachedImage" class="p-1 rounded-full bg-rose-200 text-rose-800 text-xs font-bold hover:bg-rose-300">
          ✕
        </button>
      </div>

      <!-- ── Message Input Bar ──────────────────────────── -->
      <div class="p-3 sm:p-4 bg-white border-t border-slate-200">
        <form @submit.prevent="sendMsg" class="flex items-center gap-2 sm:gap-3">
          <!-- Image Upload Button -->
          <input
            ref="fileInputRef"
            type="file"
            accept="image/*"
            class="hidden"
            @change="handleImageSelect"
          />
          <button
            type="button"
            @click="triggerFileSelect"
            class="p-2.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition-colors flex-shrink-0"
            title="Attach Image"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </button>

          <input
            v-model="newMessage"
            @input="handleTyping"
            type="text"
            placeholder="Type your message here..."
            class="flex-1 px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-rose-600 text-sm font-medium"
          />

          <button
            type="submit"
            :disabled="(!newMessage.trim() && !attachedFile) || sending"
            class="px-5 py-3 rounded-xl bg-gradient-to-r from-rose-600 via-rose-700 to-rose-800 text-white text-sm font-extrabold hover:shadow-md transition-all disabled:opacity-50 flex items-center gap-1.5 cursor-pointer whitespace-nowrap"
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

    <!-- Image Enlarge Modal -->
    <div v-if="selectedPreviewImage" class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" @click="selectedPreviewImage = null">
      <div class="relative max-w-3xl max-h-[90vh]">
        <img :src="selectedPreviewImage" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain" />
        <button @click="selectedPreviewImage = null" class="absolute -top-4 -right-4 w-8 h-8 rounded-full bg-white text-slate-900 font-bold flex items-center justify-center shadow-lg">✕</button>
      </div>
    </div>

    <div v-else-if="!chat" class="bg-white rounded-3xl p-16 text-center text-slate-400 border border-slate-200">
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
const closingChat = ref(false);
const loadingMessages = ref(true);
const messagesContainer = ref<HTMLElement>();

const fileInputRef = ref<HTMLInputElement>();
const attachedFile = ref<File | null>(null);
const attachedImagePreview = ref<string | null>(null);
const selectedPreviewImage = ref<string | null>(null);

const triggerFileSelect = () => {
  fileInputRef.value?.click();
};

const handleImageSelect = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    attachedFile.value = file;
    attachedImagePreview.value = URL.createObjectURL(file);
  }
};

const removeAttachedImage = () => {
  attachedFile.value = null;
  attachedImagePreview.value = null;
  if (fileInputRef.value) fileInputRef.value.value = "";
};

const closeChat = async () => {
  if (!chat.value) return;
  closingChat.value = true;
  try {
    const data = await api.put<any>(`/chats/${route.params.id}/close`);
    chat.value = data.chat;
  } catch { }
  finally {
    closingChat.value = false;
  }
};

const otherPartyTyping = ref(false);
let typingTimeout: any = null;

const currentQuickReplies = computed(() => {
  if (chat.value?.is_support) {
    return [
      "I need help with a service booking",
      "How do I contact the service provider?",
      "I have a question about payment / pricing",
      "Please connect me with a support representative"
    ];
  }
  return [
    "Is technician available today?",
    "What is the estimated service cost?",
    "Please share your exact location.",
    "Can you arrive by 2 PM?"
  ];
});

const otherParty = computed(() => {
  if (!chat.value || !userStore.user) return '';
  if (chat.value.is_support) return 'Support Chat';
  return chat.value.user?.id === userStore.user.id ? chat.value.worker?.name : chat.value.user?.name;
});

const formatTime = (dt: string) => {
  if (!dt) return 'Just now';
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
  if (!newMessage.value.trim() && !attachedFile.value) return;

  const msgText = newMessage.value.trim();
  const fileToSend = attachedFile.value;
  const imagePreview = attachedImagePreview.value;

  newMessage.value = "";
  removeAttachedImage();
  sending.value = true;

  // 🚀 Instant Optimistic UI Update
  const tempId = 'temp_' + Date.now();
  const tempMessage = {
    temp_id: tempId,
    sender_id: userStore.user?.id,
    is_me: true,
    message: msgText,
    image_url: imagePreview,
    created_at: new Date().toISOString(),
    status: 'sending', // 'sending' -> 'sent'
  };
  messages.value.push(tempMessage);
  scrollToBottom();

  try {
    let data: any;
    if (fileToSend) {
      const formData = new FormData();
      if (msgText) formData.append('message', msgText);
      formData.append('image', fileToSend);

      data = await api.post<any>(`/chats/${route.params.id}/messages`, formData);
    } else {
      data = await api.post<any>(`/chats/${route.params.id}/messages`, { message: msgText });
    }

    // Replace optimistic message with actual response
    const idx = messages.value.findIndex(m => m.temp_id === tempId);
    if (idx !== -1 && data.message) {
      data.message.status = 'sent';
      messages.value[idx] = data.message;
    }
  } catch {
    const idx = messages.value.findIndex(m => m.temp_id === tempId);
    if (idx !== -1) {
      messages.value[idx].status = 'error';
    }
  } finally {
    sending.value = false;
  }
};

let userPollInterval: any = null;

const pollUserMessages = async () => {
  try {
    const data = await api.get<any>(`/chats/${route.params.id}/messages`, { per_page: 100 });
    const fetched = (data.messages || []).reverse();
    if (fetched.length !== messages.value.length) {
      const hasNewMessage = fetched.length > messages.value.length;
      messages.value = fetched;
      scrollToBottom();
      if (hasNewMessage) {
        playNotificationSound();
      }
    }
  } catch { }
};

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo("/login");
  await loadMessages();

  userPollInterval = setInterval(pollUserMessages, 3000);

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

onUnmounted(() => {
  if (userPollInterval) clearInterval(userPollInterval);
});
</script>
