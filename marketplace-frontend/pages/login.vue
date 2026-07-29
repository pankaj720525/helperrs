<template>
  <div class="auth-page-wrap">
    <div class="auth-header">
      <h1 class="auth-title">Welcome Back</h1>
      <p class="auth-sub">Login to your account with Email or Phone Number</p>
    </div>

    <div class="auth-card">
      <form @submit.prevent="handleLogin" class="space-y-5" novalidate>

        <!-- Email or Phone Number -->
        <div>
          <label class="auth-label">
            Email or Phone Number <span class="text-rose-600">*</span>
          </label>
          <input
            v-model="form.login"
            type="text"
            placeholder="you@example.com or +91 9876543210"
            class="auth-input"
            :class="{ 'auth-input-invalid': fieldErrors.login }"
          />
          <p v-if="fieldErrors.login" class="text-rose-600 text-xs font-semibold mt-1.5">{{ fieldErrors.login }}</p>
        </div>

        <!-- Password with Eye Toggle -->
        <div>
          <div class="flex items-center justify-between mb-1">
            <label class="auth-label mb-0">
              Password <span class="text-rose-600">*</span>
            </label>
            <NuxtLink to="/forgot-password" class="text-xs text-rose-600 font-extrabold hover:underline cursor-pointer">
              Forgot Password?
            </NuxtLink>
          </div>
          <div class="relative flex items-center">
            <input
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="••••••••"
              class="auth-input pr-11"
              :class="{ 'auth-input-invalid': fieldErrors.password }"
            />
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute right-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer"
              :title="showPassword ? 'Hide password' : 'Show password'"
            >
              <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          <p v-if="fieldErrors.password" class="text-rose-600 text-xs font-semibold mt-1.5">{{ fieldErrors.password }}</p>
        </div>

        <!-- General Error -->
        <p v-if="generalError" class="text-rose-600 text-xs font-semibold p-3 bg-rose-50 dark:bg-rose-950/30 rounded-xl border border-rose-200 dark:border-rose-800">{{ generalError }}</p>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="loading"
          class="auth-btn-submit"
          style="color: #ffffff !important;"
        >
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
      </form>

      <p class="text-center text-xs font-medium text-slate-600 dark:text-slate-400 mt-6">
        Don't have an account?
        <NuxtLink to="/register" class="text-rose-600 font-extrabold hover:underline">Register</NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
const userStore = useUserStore();
const api = useApi();
const { initTheme } = useTheme();

const form = reactive({ login: "", password: "" });
const showPassword = ref(false);
const loading = ref(false);
const generalError = ref("");
const fieldErrors = reactive<Record<string, string>>({ login: "", password: "" });

onMounted(() => {
  initTheme();
  userStore.loadFromStorage();
  if (userStore.isAuthenticated) navigateTo("/dashboard");
});

const handleLogin = async () => {
  fieldErrors.login = "";
  fieldErrors.password = "";
  generalError.value = "";

  if (!form.login.trim()) {
    fieldErrors.login = "Please enter your email address or phone number.";
    return;
  }
  if (!form.password) {
    fieldErrors.password = "Please enter your password.";
    return;
  }

  loading.value = true;

  try {
    const data = await api.post<any>("/auth/login", {
      login: form.login.trim(),
      email: form.login.trim(),
      password: form.password,
    });
    userStore.setAuth(data.user, data.token);
    navigateTo("/dashboard");
  } catch (e: any) {
    const errors = e?.data?.errors;
    if (errors) {
      if (errors.login) fieldErrors.login = errors.login[0];
      if (errors.email) fieldErrors.login = errors.email[0];
      if (errors.password) fieldErrors.password = errors.password[0];
    }
    generalError.value = e?.data?.message || "Invalid credentials. Please check your login details and try again.";
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.auth-page-wrap { max-width: 28rem; margin: 0 auto; padding: 4rem 1rem; }
.auth-header { text-align: center; margin-bottom: 2rem; }
.auth-title { font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
:global(html.dark) .auth-title { color: #f8fafc; }
.auth-sub { font-size: 0.875rem; font-weight: 500; color: #64748b; margin: 0; }
:global(html.dark) .auth-sub { color: #94a3b8; }

.auth-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 1.5rem;
  padding: 2rem;
  box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
  transition: all 0.2s ease-in-out;
}
:global(html.dark) .auth-card {
  background: #1e293b;
  border-color: rgba(255, 255, 255, 0.1);
  box-shadow: none;
}

.auth-label { display: block; font-size: 0.8125rem; font-weight: 700; color: #334155; margin-bottom: 0.375rem; }
:global(html.dark) .auth-label { color: #cbd5e1; }

.auth-input {
  width: 100%;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  background: #f8fafc;
  border: 1.5px solid #cbd5e1;
  color: #0f172a;
  font-size: 0.875rem;
  font-weight: 500;
  outline: none;
  transition: all 0.15s ease;
}
:global(html.dark) .auth-input {
  background: rgba(255, 255, 255, 0.05);
  border-color: rgba(255, 255, 255, 0.15);
  color: #ffffff;
}
.auth-input::placeholder { color: #94a3b8; }
.auth-input:focus { border-color: #b20537; box-shadow: 0 0 0 3px rgba(178, 5, 55, 0.12); }

.auth-input-invalid {
  border-color: #f43f5e !important;
  background-color: #fff1f2 !important;
  color: #9f1239 !important;
}
:global(html.dark) .auth-input-invalid {
  background-color: rgba(244, 63, 94, 0.15) !important;
  color: #fda4af !important;
}

.auth-btn-submit {
  width: 100%;
  padding: 0.875rem;
  border-radius: 0.75rem;
  border: none;
  background: linear-gradient(135deg, #b20537, #d4064a, #f43f5e);
  color: #ffffff !important;
  font-weight: 800;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0 4px 14px rgba(178, 5, 55, 0.3);
}
.auth-btn-submit:hover:not(:disabled) {
  box-shadow: 0 6px 20px rgba(178, 5, 55, 0.45);
  transform: translateY(-1px);
}
</style>
