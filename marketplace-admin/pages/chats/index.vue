<template>
  <div class="space-y-4">
    <!-- Page Title Bar -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-xl font-heading font-extrabold text-[#12263f] dark:text-white">Support Chat Console</h1>
        <p class="text-xs text-slate-500">Live customer support, real-time inquiry messaging, and query resolution center.</p>
      </div>
    </div>

    <!-- Main Chat Workspace Card -->
    <div class="bg-white dark:bg-[#121e2d] rounded-2xl border border-[#edf2f9] dark:border-[#1e2c40] shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[730px]">

      <!-- ── LEFT SIDEBAR: Chats List (lg:col-span-4) ────────────────────────── -->
      <div
        class="lg:col-span-4 border-r border-[#edf2f9] dark:border-[#1e2c40] flex flex-col bg-[#f8fafc] dark:bg-[#0b1727]"
        :class="[ activeMobileView === 'room' ? 'hidden lg:flex' : 'flex' ]"
      >

        <!-- Sidebar Header: "Chats" + Actions -->
        <div class="p-4 border-b border-[#edf2f9] dark:border-[#1e2c40] flex items-center justify-between bg-white dark:bg-[#121e2d]">
          <h2 class="text-lg font-bold text-[#12263f] dark:text-white">Chats</h2>
          <div class="flex items-center gap-2 text-slate-400">
            <!-- Notification Bell Icon -->
            <button class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
            </button>
            <!-- Vertical 3-dots Menu Icon -->
            <button class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Search Input ("Search users") -->
        <div class="p-3.5 pb-2 bg-white dark:bg-[#121e2d]">
          <div class="relative">
            <input
              v-model="search"
              type="text"
              placeholder="Search users"
              @keyup.enter="loadChats"
              class="w-full pl-4 pr-10 py-2 rounded-xl bg-white dark:bg-[#0b1727] border border-slate-200 dark:border-[#1e2c40] text-[#12263f] dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:border-[#5c52e9] text-xs font-medium shadow-2xs"
            />
            <svg class="w-4 h-4 text-slate-400 absolute right-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>

        <!-- Outlined Tab Buttons (All, Open, Closed) matching reference -->
        <div class="px-3.5 py-2.5 bg-white dark:bg-[#121e2d] border-b border-[#edf2f9] dark:border-[#1e2c40] grid grid-cols-3 gap-2">
          <button
            v-for="st in ['all', 'open', 'closed']"
            :key="st"
            @click="statusFilter = st === 'all' ? '' : st; loadChats()"
            class="py-1.5 px-3 rounded-lg text-xs font-bold capitalize transition-all text-center border"
            :class="[(statusFilter === '' && st === 'all') || statusFilter === st
              ? 'border-2 border-red-500 text-red-600 bg-white font-extrabold shadow-2xs'
              : 'border border-slate-200 dark:border-[#1e2c40] text-slate-600 dark:text-slate-400 hover:border-slate-300 bg-white dark:bg-[#0b1727]']"
          >
            {{ st === 'all' ? 'All' : st === 'open' ? 'Open' : 'Closed' }}
          </button>
        </div>

        <!-- Conversations List -->
        <div class="flex-1 overflow-y-auto p-3 space-y-2">
          <div v-if="loadingChats" class="p-8 text-center text-slate-400 text-xs">
            Loading conversations...
          </div>

          <template v-else-if="chats.length">
            <div
              v-for="c in chats"
              :key="c.id"
              @click="selectChat(c)"
              class="p-3.5 rounded-xl transition-all cursor-pointer flex items-center gap-3 relative border"
              :class="selectedChat?.id === c.id
                ? 'bg-[#5c52e9] border-[#5c52e9] text-white shadow-sm'
                : 'bg-white dark:bg-[#121e2d] border-slate-100 dark:border-[#1e2c40] hover:border-slate-200 dark:hover:border-slate-700 text-[#12263f] dark:text-slate-100'"
            >
              <!-- Avatar Circle with Status Dot -->
              <div class="relative flex-shrink-0">
                <div
                  class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm shadow-2xs"
                  :class="selectedChat?.id === c.id ? 'bg-white/20 text-white' : 'bg-slate-100 text-[#5c52e9] dark:bg-slate-800 dark:text-slate-200'"
                >
                  {{ c.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                </div>
                <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white absolute bottom-0 right-0" />
              </div>

              <!-- Chat Info -->
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-1 mb-1">
                  <h4 class="font-bold text-xs truncate" :class="selectedChat?.id === c.id ? 'text-white' : 'text-slate-900 dark:text-white'">
                    {{ c.user?.name || 'Customer' }}
                  </h4>
                  <span class="text-[11px] font-medium whitespace-nowrap" :class="selectedChat?.id === c.id ? 'text-white/80' : 'text-slate-400'">
                    {{ formatTime(c.updated_at) }}
                  </span>
                </div>

                <div class="flex items-center justify-between gap-1">
                  <p class="text-[11px] truncate font-normal" :class="selectedChat?.id === c.id ? 'text-white/90' : 'text-slate-500 dark:text-slate-400'">
                    {{ c.latest_message?.message || 'No messages yet' }}
                  </p>
                  <span
                    v-if="c.unread_count"
                    class="w-5 h-5 rounded-full text-[10px] font-extrabold flex items-center justify-center flex-shrink-0 shadow-2xs"
                    :class="selectedChat?.id === c.id ? 'bg-white text-[#5c52e9]' : 'bg-[#5c52e9] text-white'"
                  >
                    {{ c.unread_count }}
                  </span>
                </div>
              </div>
            </div>
          </template>

          <div v-else class="p-10 text-center text-slate-400 text-xs">
            No support conversations found.
          </div>
        </div>
      </div>

      <!-- ── RIGHT MAIN WINDOW: Active Chat Conversation (lg:col-span-8) ──────────── -->
      <div
        class="lg:col-span-8 flex flex-col h-[730px] bg-white dark:bg-[#121e2d]"
        :class="[ activeMobileView === 'list' ? 'hidden lg:flex' : 'flex' ]"
      >

        <template v-if="selectedChat">
          <!-- Room Header Bar -->
          <div class="p-4 border-b border-[#edf2f9] dark:border-[#1e2c40] bg-white dark:bg-[#121e2d] flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <!-- Back to List Button (Mobile Only) -->
              <button
                @click="activeMobileView = 'list'"
                class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold flex items-center gap-1 lg:hidden"
                title="Back to All Chats"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back</span>
              </button>

              <div class="relative">
                <div class="w-11 h-11 rounded-full bg-[#5c52e9] text-white flex items-center justify-center font-bold text-sm shadow-2xs">
                  {{ selectedChat.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                </div>
                <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white absolute bottom-0 right-0" />
              </div>
              <div>
                <h3 class="font-bold text-sm text-[#12263f] dark:text-white leading-tight">
                  {{ selectedChat.user?.name }}
                </h3>
                <p class="text-[11px] text-emerald-600 font-semibold">
                  Online
                </p>
              </div>
            </div>

            <!-- Top Right Action Icons & Close Ticket Button -->
            <div class="flex items-center gap-3">
              <button
                v-if="selectedChat.status !== 'closed'"
                @click="closeChat"
                :disabled="closing"
                class="px-3.5 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 border border-rose-200 dark:border-rose-900 text-xs font-bold hover:bg-rose-100 transition-colors disabled:opacity-50 flex items-center gap-1 cursor-pointer"
              >
                <span>✕ Close / Resolve Ticket</span>
              </button>

              <button class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              </button>
              <button class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              </button>
              <button class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
              </button>
            </div>
          </div>

          <!-- Messages Thread Container -->
          <div ref="msgContainer" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6 bg-[#f8fafc]/50 dark:bg-[#0b1727]/30">
            <!-- Date Separator -->
            <div class="text-center">
              <span class="px-3.5 py-1 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[11px] font-medium text-slate-500 shadow-2xs">
                Today
              </span>
            </div>

            <div v-if="loadingMessages" class="p-6 text-center text-slate-400 text-xs">
              Loading chat messages...
            </div>

            <template v-else-if="messages.length">
              <div
                v-for="m in messages"
                :key="m.id || m.temp_id"
                class="flex items-start gap-3"
                :class="m.is_admin ? 'flex-row-reverse' : 'flex-row'"
              >
                <!-- Avatar Beside Bubble -->
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs text-white flex-shrink-0 shadow-2xs"
                  :class="m.is_admin ? 'bg-[#5c52e9]' : 'bg-slate-500 dark:bg-slate-700'">
                  {{ m.is_admin ? 'A' : (selectedChat.user?.name?.charAt(0)?.toUpperCase() || 'U') }}
                </div>

                <!-- Message Content & Timestamp -->
                <div class="max-w-[65%] space-y-1">
                  <div
                    class="px-5 py-3.5 rounded-2xl text-xs font-normal shadow-2xs leading-relaxed"
                    :class="m.is_admin
                      ? 'bg-[#5c52e9] text-white rounded-tr-none'
                      : 'bg-[#f1f5f9] dark:bg-[#1e293b] text-slate-800 dark:text-slate-100 rounded-tl-none'"
                  >
                    <!-- Attached Image Preview -->
                    <div v-if="m.image_url" class="mb-2">
                      <img
                        :src="m.image_url"
                        alt="Attachment"
                        class="rounded-xl max-h-60 object-cover border border-white/20 shadow-2xs cursor-pointer hover:opacity-95 transition-opacity"
                        @click="selectedPreviewImage = m.image_url"
                      />
                    </div>

                    <p v-if="m.message" class="whitespace-pre-wrap">{{ m.message }}</p>
                  </div>

                  <!-- Timestamp & Delivery Status Indicator (Sending... / Sent) -->
                  <div class="text-[10px] text-slate-400 font-medium px-1 flex items-center gap-1" :class="m.is_admin ? 'justify-end' : 'justify-start'">
                    <span>... {{ formatTime(m.created_at) }}</span>
                    <span v-if="m.status === 'sending'" class="inline-flex items-center text-amber-500 font-bold ml-1">
                      🕒 Sending...
                    </span>
                    <span v-else-if="m.is_admin" class="text-emerald-500 font-bold ml-0.5">✓</span>
                  </div>
                </div>
              </div>
            </template>

            <div v-else class="p-10 text-center text-slate-400 text-xs">
              No messages in this chat conversation yet.
            </div>
          </div>

          <!-- Quick Reply Suggestion Chips -->
          <template v-if="selectedChat.status !== 'closed'">
            <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/60 border-t border-[#edf2f9] dark:border-[#1e2c40] flex items-center gap-2 overflow-x-auto">
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex-shrink-0">QUICK REPLY:</span>
              <button
                v-for="qr in quickReplies"
                :key="qr"
                @click="replyMessage = qr; sendAdminReply()"
                class="px-3 py-1 rounded-xl bg-white dark:bg-[#121e2d] hover:bg-[#5c52e9]/10 text-[#5c52e9] border border-slate-200 dark:border-slate-700 text-[11px] font-semibold whitespace-nowrap transition-colors"
              >
                {{ qr }}
              </button>
            </div>

            <!-- Image Attachment Preview Bar -->
            <div v-if="adminAttachedImagePreview" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-950/60 border-t border-indigo-100 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <img :src="adminAttachedImagePreview" class="w-10 h-10 rounded-lg object-cover border border-indigo-200 shadow-2xs" />
                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-300">Image Attached</span>
              </div>
              <button @click="removeAdminAttachedImage" class="p-1 rounded-full bg-indigo-200 text-indigo-800 text-xs font-bold hover:bg-indigo-300">
                ✕
              </button>
            </div>

            <!-- Message Input Bar (Matching Reference) -->
            <div class="p-4 border-t border-[#edf2f9] dark:border-[#1e2c40] bg-white dark:bg-[#121e2d]">
              <form @submit.prevent="sendAdminReply" class="flex items-center gap-3">
                <!-- Plus Attachment Image Button -->
                <input
                  ref="adminFileInputRef"
                  type="file"
                  accept="image/*"
                  class="hidden"
                  @change="handleAdminImageSelect"
                />
                <button
                  type="button"
                  @click="triggerAdminFileSelect"
                  class="w-9 h-9 rounded-full border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-[#5c52e9] hover:border-[#5c52e9] flex items-center justify-center flex-shrink-0 transition-colors"
                  title="Attach Image"
                >
                  <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </button>

                <!-- Rounded Message Input -->
                <input
                  v-model="replyMessage"
                  type="text"
                  placeholder="Type your message here..."
                  class="flex-1 px-5 py-3 rounded-full bg-slate-50 dark:bg-[#0b1727] border border-slate-200 dark:border-[#1e2c40] text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:border-[#5c52e9] text-xs font-normal"
                />

                <!-- Emoji Icon -->
                <button type="button" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>

                <!-- Circular Send Arrow Button -->
                <button
                  type="submit"
                  :disabled="(!replyMessage.trim() && !adminAttachedFile) || sending"
                  class="w-11 h-11 rounded-full bg-[#5c52e9] hover:bg-[#4d43db] text-white flex items-center justify-center transition-all disabled:opacity-40 flex-shrink-0 shadow-md cursor-pointer"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                  </svg>
                </button>
              </form>
            </div>
          </template>

          <!-- ── Closed/Resolved Ticket Banner ─────────────── -->
          <div v-else class="p-4 border-t border-[#edf2f9] dark:border-[#1e2c40] bg-slate-50 dark:bg-slate-800/40 text-center">
            <div class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800">
              <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
              </svg>
              <span class="text-sm font-bold text-emerald-700 dark:text-emerald-300">This support ticket has been resolved and closed.</span>
            </div>
          </div>
        </template>

        <!-- Placeholder when no chat selected -->
        <div v-else class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-400">
          <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-3xl mb-3">
            💬
          </div>
          <h3 class="font-bold text-slate-700 dark:text-slate-200 text-sm mb-1">Select a Support Ticket</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm">
            Choose a customer or provider conversation from the left panel to reply and resolve support queries.
          </p>
        </div>

      </div>

    </div>

    <!-- Image Enlarge Modal -->
    <div v-if="selectedPreviewImage" class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4" @click="selectedPreviewImage = null">
      <div class="relative max-w-3xl max-h-[90vh]">
        <img :src="selectedPreviewImage" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain" />
        <button @click="selectedPreviewImage = null" class="absolute -top-4 -right-4 w-8 h-8 rounded-full bg-white text-slate-900 font-bold flex items-center justify-center shadow-lg">✕</button>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const api = useApi();
const toast = useToast();

const chats = ref<any[]>([]);
const selectedChat = ref<any>(null);
const messages = ref<any[]>([]);

const search = ref("");
const statusFilter = ref("");
const replyMessage = ref("");

const loadingChats = ref(true);
const loadingMessages = ref(false);
const sending = ref(false);
const closing = ref(false);

const msgContainer = ref<HTMLElement>();
const selectedPreviewImage = ref<string | null>(null);

const adminFileInputRef = ref<HTMLInputElement>();
const adminAttachedFile = ref<File | null>(null);
const adminAttachedImagePreview = ref<string | null>(null);

const triggerAdminFileSelect = () => {
  adminFileInputRef.value?.click();
};

const handleAdminImageSelect = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    adminAttachedFile.value = file;
    adminAttachedImagePreview.value = URL.createObjectURL(file);
  }
};

const removeAdminAttachedImage = () => {
  adminAttachedFile.value = null;
  adminAttachedImagePreview.value = null;
  if (adminFileInputRef.value) adminFileInputRef.value.value = "";
};

const quickReplies = [
  "Hello! How can we assist you today?",
  "We are actively reviewing your request.",
  "Your issue has been resolved. Thank you!"
];

const formatTime = (dt: string) => {
  if (!dt) return 'Just now';
  const d = new Date(dt);
  return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const scrollToBottom = () => {
  nextTick(() => {
    if (msgContainer.value) {
      msgContainer.value.scrollTop = msgContainer.value.scrollHeight;
    }
  });
};

const loadChats = async () => {
  loadingChats.value = true;
  try {
    const params: any = { per_page: 30 };
    if (search.value) params.search = search.value;
    if (statusFilter.value) params.status = statusFilter.value;

    const data = await api.get<any>("/admin/chats", params);
    chats.value = data.chats || [];

    if (chats.value.length && !selectedChat.value) {
      selectChat(chats.value[0]);
    }
  } catch {
    toast.error("Failed to load support chats.");
  } finally {
    loadingChats.value = false;
  }
};

const activeMobileView = ref<'list' | 'room'>('list');

const selectChat = async (chatItem: any) => {
  selectedChat.value = chatItem;
  activeMobileView.value = 'room';
  loadingMessages.value = true;

  try {
    const data = await api.get<any>(`/admin/chats/${chatItem.id}/messages`, { per_page: 100 });
    messages.value = (data.messages || []).reverse();
    scrollToBottom();
  } catch {
    toast.error("Failed to load messages.");
  } finally {
    loadingMessages.value = false;
  }
};

const sendAdminReply = async () => {
  if (!replyMessage.value.trim() && !adminAttachedFile.value) return;
  if (!selectedChat.value) return;

  const text = replyMessage.value.trim();
  const fileToSend = adminAttachedFile.value;
  const imagePreview = adminAttachedImagePreview.value;

  replyMessage.value = "";
  removeAdminAttachedImage();
  sending.value = true;

  // 🚀 Instant Optimistic UI Update for Admin
  const tempId = 'temp_admin_' + Date.now();
  const tempMsg = {
    temp_id: tempId,
    is_admin: true,
    message: text,
    image_url: imagePreview,
    created_at: new Date().toISOString(),
    status: 'sending', // 'sending' -> 'sent'
  };
  messages.value.push(tempMsg);
  scrollToBottom();

  try {
    let data: any;
    if (fileToSend) {
      const formData = new FormData();
      if (text) formData.append('message', text);
      formData.append('image', fileToSend);

      data = await api.post<any>(`/admin/chats/${selectedChat.value.id}/messages`, formData);
    } else {
      data = await api.post<any>(`/admin/chats/${selectedChat.value.id}/messages`, {
        message: text,
      });
    }

    const idx = messages.value.findIndex(m => m.temp_id === tempId);
    if (idx !== -1 && data.message) {
      data.message.status = 'sent';
      messages.value[idx] = data.message;
    }
  } catch (error: any) {
    const idx = messages.value.findIndex(m => m.temp_id === tempId);
    if (idx !== -1) {
      messages.value[idx].status = 'error';
    }
    toast.error(error?.data?.message || "Failed to send message.");
  } finally {
    sending.value = false;
  }
};

const closeChat = async () => {
  if (!selectedChat.value) return;

  closing.value = true;
  try {
    const data = await api.put<any>(`/admin/chats/${selectedChat.value.id}/close`);
    toast.success("Support ticket resolved and closed.");
    if (data.chat) {
      selectedChat.value = data.chat;
    }
    loadChats();
  } catch (error: any) {
    toast.error(error?.data?.message || "Failed to close chat.");
  } finally {
    closing.value = false;
  }
};

let pollInterval: any = null;

const pollMessages = async () => {
  if (!selectedChat.value) return;
  try {
    const data = await api.get<any>(`/admin/chats/${selectedChat.value.id}/messages`, { per_page: 100 });
    const fetched = (data.messages || []).reverse();
    if (fetched.length !== messages.value.length) {
      messages.value = fetched;
      scrollToBottom();
    }
  } catch { }
};

onMounted(() => {
  loadChats();
  pollInterval = setInterval(pollMessages, 3000);
});

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval);
});
</script>
