<template>
  <div class="max-w-md mx-auto px-4 py-16">
    <div class="text-center mb-8">
      <h1 class="text-3xl font-heading font-bold text-white mb-2">Welcome Back</h1>
      <p class="text-slate-400">Login to your account</p>
    </div>

    <div class="glass rounded-2xl p-8">
      <form @submit.prevent="handleLogin" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
          <input v-model="form.email" type="email" required placeholder="you@example.com" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all text-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
          <input v-model="form.password" type="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-all text-sm" />
        </div>
        <p v-if="errorMsg" class="text-danger text-sm">{{ errorMsg }}</p>
        <button type="submit" :disabled="loading" class="w-full py-3 rounded-xl gradient-primary text-white font-semibold text-sm hover:shadow-glow transition-all disabled:opacity-50">
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
      </form>
      <p class="text-center text-sm text-slate-400 mt-5">
        Don't have an account? <NuxtLink to="/register" class="text-primary-light hover:underline">Register</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
const userStore = useUserStore();
const api = useApi();
const form = reactive({ email: "", password: "" });
const loading = ref(false);
const errorMsg = ref("");

onMounted(() => {
  userStore.loadFromStorage();
  if (userStore.isAuthenticated) navigateTo("/dashboard");
});

const handleLogin = async () => {
  loading.value = true;
  errorMsg.value = "";
  try {
    const data = await api.post<any>("/auth/login", form);
    userStore.setAuth(data.user, data.token);
    navigateTo("/dashboard");
  } catch (e: any) {
    errorMsg.value = e?.data?.message || "Invalid credentials.";
  } finally {
    loading.value = false;
  }
};
</script>
