<template>
  <div class="w-full max-w-md auth-page-layout">
    <!-- Brand Logo & Title Header -->
    <div class="text-center mb-8 animate-fade-up">
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#3858F9] to-[#4F46E5] flex items-center justify-center mx-auto mb-4 shadow-lg shadow-[#3858F9]/20">
        <span class="text-white font-bold text-xl">SM</span>
      </div>
      <h1 class="text-2xl font-heading font-bold text-[#1E293B]">Service Market</h1>
      <p class="text-[#64748B] text-sm mt-1">Administration Portal</p>
    </div>

    <!-- Login Card (Strictly Light Mode) -->
    <div class="bg-white rounded-2xl p-8 border border-[#EAEDF1] shadow-xl shadow-slate-200/50 animate-fade-up" style="animation-delay: 0.1s">
      <h2 class="text-lg font-heading font-semibold text-[#1E293B] mb-2">Welcome Back</h2>
      <p class="text-xs text-[#64748B] mb-6">Please sign in to access your administrative workspace.</p>

      <form @submit.prevent="handleLogin" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-[#475569] mb-2">Email Address</label>
          <input
            v-model="form.email"
            type="email"
            required
            placeholder="admin@servicemarketplace.com"
            class="w-full px-4 py-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:border-[#3858F9] focus:bg-white focus:ring-2 focus:ring-[#3858F9]/10 transition-all duration-200 text-sm"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-[#475569] mb-2">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            placeholder="••••••••"
            class="w-full px-4 py-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-[#1E293B] placeholder-[#94A3B8] focus:outline-none focus:border-[#3858F9] focus:bg-white focus:ring-2 focus:ring-[#3858F9]/10 transition-all duration-200 text-sm"
          />
        </div>

        <p v-if="errorMsg" class="text-danger text-sm bg-danger/10 p-3 rounded-lg">{{ errorMsg }}</p>

        <button
          type="submit"
          :disabled="loading"
          class="w-full py-3 rounded-xl bg-gradient-to-r from-[#3858F9] to-[#4F46E5] text-white font-semibold text-sm hover:shadow-lg hover:shadow-[#3858F9]/30 transition-all duration-300 disabled:opacity-50"
        >
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
      </form>
    </div>

    <!-- Footer Note -->
    <p class="text-center text-xs text-[#94A3B8] mt-6">
      © {{ new Date().getFullYear() }} Service Market — Administration
    </p>
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
      email: form.email.trim(),
      password: form.password.trim(),
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
