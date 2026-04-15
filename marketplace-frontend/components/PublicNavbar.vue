<template>
  <nav class="sticky top-0 z-50 glass border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <NuxtLink to="/" class="flex items-center gap-3 group">
          <div class="w-9 h-9 rounded-xl gradient-primary flex items-center justify-center transition-transform group-hover:scale-105">
            <span class="text-white font-bold text-sm">SM</span>
          </div>
          <span class="font-heading font-semibold text-white text-lg hidden sm:block">
            Service<span class="text-gradient">Market</span>
          </span>
        </NuxtLink>

        <!-- Nav Links (Desktop) -->
        <div class="hidden md:flex items-center gap-6">
          <NuxtLink to="/" class="text-sm text-slate-300 hover:text-white transition-colors">Home</NuxtLink>
          <NuxtLink to="/services" class="text-sm text-slate-300 hover:text-white transition-colors">Services</NuxtLink>
          <NuxtLink to="/search" class="text-sm text-slate-300 hover:text-white transition-colors">Search</NuxtLink>
        </div>

        <!-- Right side -->
        <div class="flex items-center gap-3">
          <template v-if="userStore.isAuthenticated">
            <NuxtLink to="/dashboard" class="text-sm text-slate-300 hover:text-white transition-colors hidden md:block">Dashboard</NuxtLink>
            <NuxtLink to="/chats" class="text-sm text-slate-300 hover:text-white transition-colors hidden md:block">💬 Chats</NuxtLink>
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-semibold">
                {{ userStore.user?.name?.charAt(0)?.toUpperCase() }}
              </div>
              <button @click="handleLogout" class="text-sm text-slate-400 hover:text-danger transition-colors">Logout</button>
            </div>
          </template>
          <template v-else>
            <NuxtLink to="/login" class="text-sm text-slate-300 hover:text-white transition-colors">Login</NuxtLink>
            <NuxtLink to="/register" class="px-4 py-2 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all">
              Register
            </NuxtLink>
          </template>

          <!-- Mobile menu -->
          <button @click="mobileOpen = !mobileOpen" class="md:hidden text-slate-300 hover:text-white text-xl">
            {{ mobileOpen ? '✕' : '☰' }}
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <Transition name="slide-down">
        <div v-if="mobileOpen" class="md:hidden pb-4 space-y-2 border-t border-white/5 pt-4">
          <NuxtLink to="/" class="block px-3 py-2 text-sm text-slate-300 hover:text-white rounded-lg hover:bg-white/5" @click="mobileOpen = false">Home</NuxtLink>
          <NuxtLink to="/services" class="block px-3 py-2 text-sm text-slate-300 hover:text-white rounded-lg hover:bg-white/5" @click="mobileOpen = false">Services</NuxtLink>
          <NuxtLink to="/search" class="block px-3 py-2 text-sm text-slate-300 hover:text-white rounded-lg hover:bg-white/5" @click="mobileOpen = false">Search</NuxtLink>
          <template v-if="userStore.isAuthenticated">
            <NuxtLink to="/dashboard" class="block px-3 py-2 text-sm text-slate-300 hover:text-white rounded-lg hover:bg-white/5" @click="mobileOpen = false">Dashboard</NuxtLink>
            <NuxtLink to="/chats" class="block px-3 py-2 text-sm text-slate-300 hover:text-white rounded-lg hover:bg-white/5" @click="mobileOpen = false">Chats</NuxtLink>
          </template>
        </div>
      </Transition>
    </div>
  </nav>
</template>

<script setup lang="ts">
const userStore = useUserStore();
const api = useApi();
const mobileOpen = ref(false);

onMounted(() => userStore.loadFromStorage());

const handleLogout = async () => {
  try { await api.post("/auth/logout"); } catch {}
  userStore.logout();
  navigateTo("/");
};
</script>

<style scoped>
.slide-down-enter-active, .slide-down-leave-active { transition: all 0.2s ease; }
.slide-down-enter-from, .slide-down-leave-to { opacity: 0; max-height: 0; }
</style>
