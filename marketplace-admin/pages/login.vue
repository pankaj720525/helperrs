<template>
  <div class="w-full max-w-md">
    <!-- Logo -->
    <div class="text-center mb-8 animate-fade-up">
      <div class="w-16 h-16 rounded-2xl gradient-primary flex items-center justify-center mx-auto mb-4 shadow-glow">
        <span class="text-white font-bold text-2xl">SM</span>
      </div>
      <h1 class="text-2xl font-heading font-bold text-white">Admin Portal</h1>
      <p class="text-slate-400 text-sm mt-1">Service Marketplace Administration</p>
    </div>

    <!-- Login Card -->
    <div class="glass rounded-2xl p-8 animate-fade-up" style="animation-delay: 0.1s">
      <form @submit.prevent="handleLogin" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
          <input
            v-model="form.email"
            type="email"
            required
            placeholder="admin@servicemarketplace.com"
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all duration-200 text-sm"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            placeholder="••••••••"
            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all duration-200 text-sm"
          />
        </div>

        <p v-if="errorMsg" class="text-danger text-sm">{{ errorMsg }}</p>

        <button
          type="submit"
          :disabled="loading"
          class="w-full py-3 rounded-xl gradient-primary text-white font-semibold text-sm hover:shadow-glow transition-all duration-300 disabled:opacity-50"
        >
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: "auth" });

const authStore = useAuthStore();
const api = useApi();
const toast = useToast();

const form = reactive({ email: "", password: "" });
const loading = ref(false);
const errorMsg = ref("");

// Redirect if already logged in
onMounted(() => {
  authStore.loadFromStorage();
  if (authStore.isAuthenticated) navigateTo("/");
});

const handleLogin = async () => {
  loading.value = true;
  errorMsg.value = "";

  try {
    const data = await api.post<{ admin: any; token: string }>("/admin/login", {
      email: form.email,
      password: form.password,
    });

    authStore.setAuth(data.admin, data.token);
    toast.success("Welcome back, " + data.admin.name + "!");
    navigateTo("/");
  } catch (error: any) {
    errorMsg.value = error?.data?.message || "Invalid credentials.";
  } finally {
    loading.value = false;
  }
};
</script>
