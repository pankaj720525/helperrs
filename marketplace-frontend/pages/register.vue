<template>
  <div class="max-w-md mx-auto px-4 py-16">
    <div class="text-center mb-8">
      <h1 class="text-3xl font-heading font-bold text-white mb-2">Create Account</h1>
      <p class="text-slate-400">Join our marketplace</p>
    </div>

    <div class="glass rounded-2xl p-8">
      <form @submit.prevent="handleRegister" class="space-y-5">
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
          <input v-model="form.name" type="text" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" placeholder="John Doe" />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
          <input v-model="form.email" type="email" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" placeholder="you@example.com" />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-2">Phone</label>
          <input v-model="form.phone" type="tel" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" placeholder="+91 98765 43210" />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-2">I want to</label>
          <select v-model="form.role" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm">
            <option value="user">Hire Professionals</option>
            <option value="worker">Offer My Services</option>
            <option value="both">Both</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-2">Password</label>
          <input v-model="form.password" type="password" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" placeholder="••••••••" />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-2">Confirm Password</label>
          <input v-model="form.password_confirmation" type="password" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" placeholder="••••••••" />
        </div>
        <p v-if="errorMsg" class="text-danger text-sm">{{ errorMsg }}</p>
        <button type="submit" :disabled="loading" class="w-full py-3 rounded-xl gradient-primary text-white font-semibold text-sm hover:shadow-glow transition-all disabled:opacity-50">
          {{ loading ? 'Creating...' : 'Create Account' }}
        </button>
      </form>
      <p class="text-center text-sm text-slate-400 mt-5">
        Already have an account? <NuxtLink to="/login" class="text-primary-light hover:underline">Login</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
const userStore = useUserStore();
const api = useApi();
const form = reactive({ name: "", email: "", phone: "", role: "user", password: "", password_confirmation: "" });
const loading = ref(false);
const errorMsg = ref("");

const handleRegister = async () => {
  loading.value = true;
  errorMsg.value = "";
  try {
    const data = await api.post<any>("/auth/register", form);
    userStore.setAuth(data.user, data.token);
    navigateTo("/dashboard");
  } catch (e: any) {
    const errors = e?.data?.errors;
    if (errors) {
      errorMsg.value = Object.values(errors).flat().join(" ");
    } else {
      errorMsg.value = e?.data?.message || "Registration failed.";
    }
  } finally {
    loading.value = false;
  }
};
</script>
