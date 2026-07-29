<template>
  <div class="auth-page-wrap">
    
    <!-- STEP 1: Enter Mobile Number -->
    <div v-if="step === 1" class="auth-card-container">
      <div class="auth-icon-badge">
        <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
      </div>

      <div class="auth-header">
        <h1 class="auth-title">Forgot Password?</h1>
        <p class="auth-sub">Enter your registered mobile number to receive a 6-digit OTP verification code.</p>
      </div>

      <form @submit.prevent="handleSendOtp" class="space-y-5" novalidate>
        <div>
          <label class="auth-label">Mobile Number <span class="text-rose-600">*</span></label>
          <input
            v-model="phone"
            type="tel"
            placeholder="e.g. 9876543210"
            class="auth-input"
            :class="{ 'auth-input-invalid': fieldErrors.phone }"
          />
          <p v-if="fieldErrors.phone" class="text-rose-600 text-xs font-semibold mt-1.5">{{ fieldErrors.phone }}</p>
        </div>

        <p v-if="generalError" class="text-rose-600 text-xs font-semibold p-3 bg-rose-50 dark:bg-rose-950/30 rounded-xl border border-rose-200 dark:border-rose-800">{{ generalError }}</p>

        <button
          type="submit"
          :disabled="loading"
          class="auth-btn-submit"
          style="color: #ffffff !important;"
        >
          {{ loading ? 'Sending Code...' : 'Send Verification Code' }}
        </button>

        <div class="text-center pt-2">
          <NuxtLink to="/login" class="text-xs text-slate-500 hover:text-slate-900 dark:hover:text-white font-bold transition-colors">
            ← Back to Sign In
          </NuxtLink>
        </div>
      </form>
    </div>

    <!-- STEP 2: Professional 6-Digit OTP Verification -->
    <div v-else-if="step === 2" class="auth-card-container">
      <div class="auth-icon-badge">
        <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
      </div>

      <div class="auth-header">
        <h1 class="auth-title">Verify Phone Number</h1>
        <p class="auth-sub">
          We sent a 6-digit code to <strong class="text-slate-900 dark:text-white font-bold">{{ formattedPhone }}</strong>.
        </p>
      </div>

      <!-- Dev OTP Banner -->
      <div v-if="otpPreview" class="otp-dev-badge">
        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
        <span>Demo OTP Code: <strong class="font-mono text-rose-600 font-extrabold text-sm">{{ otpPreview }}</strong></span>
        <button type="button" @click="autoFillOtp" class="ml-auto text-[11px] font-bold text-rose-600 underline hover:text-rose-700">Auto-fill</button>
      </div>

      <form @submit.prevent="handleVerifyOtp" class="space-y-6" novalidate>
        <!-- 6 Separate OTP Pin Inputs -->
        <div>
          <label class="auth-label text-center block mb-3">Enter 6-Digit Code</label>
          <div class="flex items-center justify-between gap-2 sm:gap-2.5 max-w-xs mx-auto">
            <input
              v-for="(digit, index) in otpDigits"
              :key="index"
              :ref="el => { if (el) otpInputs[index] = el as HTMLInputElement }"
              v-model="otpDigits[index]"
              type="text"
              inputmode="numeric"
              maxlength="1"
              class="otp-pin-box"
              :class="{ 'otp-pin-invalid': fieldErrors.otp, 'otp-pin-filled': otpDigits[index] }"
              @input="onOtpInput(index, $event)"
              @keydown="onOtpKeydown(index, $event)"
              @paste="onOtpPaste"
            />
          </div>
          <p v-if="fieldErrors.otp" class="text-rose-600 text-xs font-semibold text-center mt-2.5">{{ fieldErrors.otp }}</p>
        </div>

        <!-- Timer & Resend Option -->
        <div class="text-center text-xs font-medium text-slate-500 dark:text-slate-400">
          <span v-if="resendTimer > 0">
            Resend OTP code in <strong class="text-slate-900 dark:text-white font-bold">{{ resendTimer }}s</strong>
          </span>
          <button
            v-else
            type="button"
            @click="handleResendOtp"
            class="text-rose-600 font-extrabold hover:underline cursor-pointer"
          >
            Didn't receive code? Resend OTP
          </button>
        </div>

        <p v-if="generalError" class="text-rose-600 text-xs font-semibold p-3 bg-rose-50 dark:bg-rose-950/30 rounded-xl border border-rose-200 dark:border-rose-800 text-center">{{ generalError }}</p>

        <div class="flex items-center gap-3">
          <button
            type="button"
            @click="step = 1"
            class="auth-btn-secondary"
          >
            Change Number
          </button>
          <button
            type="submit"
            :disabled="loading || fullOtpCode.length < 6"
            class="auth-btn-submit flex-1"
            style="color: #ffffff !important;"
          >
            {{ loading ? 'Verifying...' : 'Verify Code' }}
          </button>
        </div>
      </form>
    </div>

    <!-- STEP 3: Create New Password -->
    <div v-else-if="step === 3" class="auth-card-container">
      <div class="auth-icon-badge">
        <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
      </div>

      <div class="auth-header">
        <h1 class="auth-title">Create New Password</h1>
        <p class="auth-sub">Set a strong password for your account with at least 8 characters.</p>
      </div>

      <form @submit.prevent="handleResetPassword" class="space-y-4" novalidate>
        <!-- New Password with Eye Toggle -->
        <div>
          <label class="auth-label">New Password <span class="text-rose-600">*</span></label>
          <div class="relative flex items-center">
            <input
              v-model="password"
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          
          <!-- Password Strength Meter -->
          <div v-if="password" class="mt-2 space-y-1">
            <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden flex">
              <div
                class="h-full transition-all duration-300 rounded-full"
                :class="passStrengthClass"
                :style="{ width: passStrengthPercent + '%' }"
              ></div>
            </div>
            <p class="text-[10px] font-bold" :class="passStrengthTextClass">{{ passStrengthLabel }}</p>
          </div>
          <p v-else class="text-[11px] text-slate-400 mt-1">Must be min 8 characters with 1 letter, 1 number & 1 special symbol (e.g. @$!%*#?&).</p>
          <p v-if="fieldErrors.password" class="text-rose-600 text-xs font-semibold mt-1">{{ fieldErrors.password }}</p>
        </div>

        <!-- Confirm Password with Eye Toggle -->
        <div>
          <label class="auth-label">Confirm New Password <span class="text-rose-600">*</span></label>
          <div class="relative flex items-center">
            <input
              v-model="passwordConfirmation"
              :type="showConfirmPassword ? 'text' : 'password'"
              placeholder="••••••••"
              class="auth-input pr-11"
              :class="{ 'auth-input-invalid': fieldErrors.password_confirmation }"
            />
            <button
              type="button"
              @click="showConfirmPassword = !showConfirmPassword"
              class="absolute right-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer"
              :title="showConfirmPassword ? 'Hide password' : 'Show password'"
            >
              <svg v-if="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          <p v-if="fieldErrors.password_confirmation" class="text-rose-600 text-xs font-semibold mt-1">{{ fieldErrors.password_confirmation }}</p>
        </div>

        <p v-if="generalError" class="text-rose-600 text-xs font-semibold p-3 bg-rose-50 dark:bg-rose-950/30 rounded-xl border border-rose-200 dark:border-rose-800">{{ generalError }}</p>

        <button
          type="submit"
          :disabled="loading"
          class="auth-btn-submit mt-2"
          style="color: #ffffff !important;"
        >
          {{ loading ? 'Resetting Password...' : 'Update Password & Sign In' }}
        </button>
      </form>
    </div>

    <!-- STEP 4: Success Screen -->
    <div v-else-if="step === 4" class="auth-card-container text-center space-y-4">
      <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto shadow-sm">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
      </div>

      <div class="space-y-1">
        <h2 class="text-xl font-heading font-extrabold text-slate-900 dark:text-white">Password Reset Complete</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Your account password has been updated successfully.</p>
      </div>

      <NuxtLink
        to="/login"
        class="auth-btn-submit block text-center mt-4"
        style="color: #ffffff !important;"
      >
        Sign In to Account
      </NuxtLink>
    </div>

  </div>
</template>

<script setup lang="ts">
const api = useApi();
const { initTheme } = useTheme();

const step = ref(1);
const phone = ref("");
const otpDigits = reactive<string[]>(["", "", "", "", "", ""]);
const otpInputs = reactive<HTMLInputElement[]>([]);
const password = ref("");
const passwordConfirmation = ref("");

const otpPreview = ref("");
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const loading = ref(false);
const generalError = ref("");
const resendTimer = ref(60);
let timerInterval: any = null;

const fieldErrors = reactive<Record<string, string>>({
  phone: "",
  otp: "",
  password: "",
  password_confirmation: ""
});

onMounted(() => {
  initTheme();
});

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval);
});

const formattedPhone = computed(() => {
  return phone.value.trim();
});

const fullOtpCode = computed(() => otpDigits.join(""));

const startResendTimer = () => {
  resendTimer.value = 60;
  if (timerInterval) clearInterval(timerInterval);
  timerInterval = setInterval(() => {
    if (resendTimer.value > 0) {
      resendTimer.value--;
    } else {
      clearInterval(timerInterval);
    }
  }, 1000);
};

const clearErrors = () => {
  fieldErrors.phone = "";
  fieldErrors.otp = "";
  fieldErrors.password = "";
  fieldErrors.password_confirmation = "";
  generalError.value = "";
};

const handleSendOtp = async () => {
  clearErrors();
  const rawPhone = phone.value.trim();
  const cleanDigits = rawPhone.replace(/[^0-9]/g, "");

  if (!rawPhone) {
    fieldErrors.phone = "Please enter your registered phone number.";
    return;
  } else if (!/^[0-9\-\s\+]{10,15}$/.test(rawPhone) || cleanDigits.length < 10) {
    fieldErrors.phone = "Please enter a valid 10-digit phone number (numbers only).";
    return;
  }

  loading.value = true;
  try {
    const data = await api.post<any>("/auth/forgot-password/send-otp", {
      phone: phone.value.trim()
    });
    otpPreview.value = data.otp || "";
    step.value = 2;
    startResendTimer();
    nextTick(() => {
      if (otpInputs[0]) otpInputs[0].focus();
    });
  } catch (e: any) {
    const errors = e?.data?.errors;
    if (errors && errors.phone) {
      fieldErrors.phone = errors.phone[0];
    } else {
      generalError.value = e?.data?.message || "No account found registered with this mobile number.";
    }
  } finally {
    loading.value = false;
  }
};

const handleResendOtp = async () => {
  await handleSendOtp();
};

const autoFillOtp = () => {
  if (!otpPreview.value) return;
  const digits = otpPreview.value.split("");
  for (let i = 0; i < 6; i++) {
    otpDigits[i] = digits[i] || "";
  }
  if (otpInputs[5]) otpInputs[5].focus();
};

const onOtpInput = (index: number, e: Event) => {
  const target = e.target as HTMLInputElement;
  const val = target.value.replace(/[^0-9]/g, "");
  otpDigits[index] = val ? val.slice(-1) : "";
  fieldErrors.otp = "";

  if (val && index < 5) {
    nextTick(() => {
      if (otpInputs[index + 1]) otpInputs[index + 1].focus();
    });
  }
};

const onOtpKeydown = (index: number, e: KeyboardEvent) => {
  if (e.key === "Backspace" && !otpDigits[index] && index > 0) {
    nextTick(() => {
      if (otpInputs[index - 1]) otpInputs[index - 1].focus();
    });
  }
};

const onOtpPaste = (e: ClipboardEvent) => {
  e.preventDefault();
  const pasteData = e.clipboardData?.getData("text") || "";
  const cleanDigits = pasteData.replace(/[^0-9]/g, "").slice(0, 6).split("");

  for (let i = 0; i < 6; i++) {
    otpDigits[i] = cleanDigits[i] || "";
  }

  const focusIdx = Math.min(cleanDigits.length, 5);
  nextTick(() => {
    if (otpInputs[focusIdx]) otpInputs[focusIdx].focus();
  });
};

const handleVerifyOtp = () => {
  clearErrors();
  if (fullOtpCode.value.length < 6) {
    fieldErrors.otp = "Please enter all 6 digits of the OTP verification code.";
    return;
  }

  step.value = 3;
};

// Password strength calculation
const passStrengthPercent = computed(() => {
  const p = password.value;
  if (!p) return 0;
  let score = 0;
  if (p.length >= 8) score += 30;
  if (/[a-zA-Z]/.test(p)) score += 25;
  if (/[0-9]/.test(p)) score += 25;
  if (/[^a-zA-Z0-9]/.test(p)) score += 20;
  return score;
});

const passStrengthLabel = computed(() => {
  const p = passStrengthPercent.value;
  if (p < 50) return "Weak Password";
  if (p < 80) return "Medium Strength";
  return "Strong Password";
});

const passStrengthClass = computed(() => {
  const p = passStrengthPercent.value;
  if (p < 50) return "bg-rose-500";
  if (p < 80) return "bg-amber-500";
  return "bg-emerald-500";
});

const passStrengthTextClass = computed(() => {
  const p = passStrengthPercent.value;
  if (p < 50) return "text-rose-600 dark:text-rose-400";
  if (p < 80) return "text-amber-600 dark:text-amber-400";
  return "text-emerald-600 dark:text-emerald-400";
});

const validatePasswordStrength = (pass: string): string | null => {
  if (pass.length < 8) return "Password must be at least 8 characters long.";
  if (!/[a-zA-Z]/.test(pass)) return "Password must contain at least 1 letter/character.";
  if (!/[0-9]/.test(pass)) return "Password must contain at least 1 number.";
  if (!/[^a-zA-Z0-9]/.test(pass)) return "Password must contain at least 1 special symbol (e.g. @$!%*#?&).";
  return null;
};

const handleResetPassword = async () => {
  clearErrors();
  let hasError = false;

  const passErr = validatePasswordStrength(password.value);
  if (passErr) {
    fieldErrors.password = passErr;
    hasError = true;
  }

  if (password.value !== passwordConfirmation.value) {
    fieldErrors.password_confirmation = "Passwords do not match.";
    hasError = true;
  }

  if (hasError) return;

  loading.value = true;
  try {
    await api.post("/auth/forgot-password/reset", {
      phone: phone.value.trim(),
      otp: fullOtpCode.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value
    });
    step.value = 4;
  } catch (e: any) {
    const errors = e?.data?.errors;
    if (errors) {
      if (errors.phone) fieldErrors.phone = errors.phone[0];
      if (errors.otp) fieldErrors.otp = errors.otp[0];
      if (errors.password) fieldErrors.password = errors.password[0];
    } else {
      generalError.value = e?.data?.message || "Failed to reset password. Please check your OTP and inputs.";
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.auth-page-wrap { max-width: 28rem; margin: 0 auto; padding: 4rem 1rem; }

.auth-card-container {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 1.5rem;
  padding: 2.25rem 2rem;
  box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
  transition: all 0.2s ease-in-out;
}
:global(html.dark) .auth-card-container {
  background: #1e293b;
  border-color: rgba(255, 255, 255, 0.1);
  box-shadow: none;
}

.auth-icon-badge {
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 1.25rem;
  background: #fef2f2;
  border: 1px solid #fecdd3;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.25rem auto;
}
:global(html.dark) .auth-icon-badge {
  background: rgba(178, 5, 55, 0.15);
  border-color: rgba(178, 5, 55, 0.3);
}

.auth-header { text-align: center; margin-bottom: 1.75rem; }
.auth-title { font-family: 'Outfit', sans-serif; font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 0.375rem; }
:global(html.dark) .auth-title { color: #f8fafc; }
.auth-sub { font-size: 0.875rem; font-weight: 500; color: #64748b; margin: 0; line-height: 1.4; }
:global(html.dark) .auth-sub { color: #94a3b8; }

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

/* 6-Digit OTP Box Styling */
.otp-pin-box {
  width: 2.85rem;
  height: 3.5rem;
  text-align: center;
  font-family: monospace;
  font-size: 1.35rem;
  font-weight: 800;
  border-radius: 0.875rem;
  background: #f8fafc;
  border: 1.5px solid #cbd5e1;
  color: #0f172a;
  outline: none;
  transition: all 0.15s ease;
}
:global(html.dark) .otp-pin-box {
  background: rgba(255, 255, 255, 0.05);
  border-color: rgba(255, 255, 255, 0.15);
  color: #ffffff;
}
.otp-pin-box:focus {
  border-color: #b20537;
  box-shadow: 0 0 0 3px rgba(178, 5, 55, 0.18);
  background: #ffffff;
}
:global(html.dark) .otp-pin-box:focus {
  background: rgba(255, 255, 255, 0.1);
}
.otp-pin-filled {
  border-color: #b20537;
  background: #fef2f5;
  color: #b20537;
}
:global(html.dark) .otp-pin-filled {
  background: rgba(178, 5, 55, 0.2);
  color: #f43f5e;
}
.otp-pin-invalid {
  border-color: #f43f5e !important;
  background-color: #fff1f2 !important;
  color: #9f1239 !important;
}

.otp-dev-badge {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.625rem 0.875rem;
  background: #fffbeb;
  border: 1px solid #fde68a;
  border-radius: 0.75rem;
  font-size: 0.75rem;
  color: #92400e;
  margin-bottom: 1.25rem;
}
:global(html.dark) .otp-dev-badge {
  background: rgba(245, 158, 11, 0.1);
  border-color: rgba(245, 158, 11, 0.25);
  color: #fcd34d;
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

.auth-btn-secondary {
  padding: 0.875rem 1.25rem;
  border-radius: 0.75rem;
  border: 1.5px solid #cbd5e1;
  background: #f8fafc;
  color: #334155 !important;
  font-weight: 700;
  font-size: 0.8125rem;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}
.auth-btn-secondary:hover {
  background: #e2e8f0 !important;
  border-color: #94a3b8 !important;
  color: #0f172a !important;
}
:global(html.dark) .auth-btn-secondary {
  background: rgba(255, 255, 255, 0.08);
  border-color: rgba(255, 255, 255, 0.15);
  color: #cbd5e1 !important;
}
:global(html.dark) .auth-btn-secondary:hover {
  background: rgba(255, 255, 255, 0.15) !important;
  color: #ffffff !important;
}

/* Phone Input Group Styles */
.phone-input-group {
  display: flex;
  align-items: center;
  border-radius: 0.75rem;
  background: #ffffff;
  border: 1.5px solid #cbd5e1;
  overflow: hidden;
  transition: all 0.15s ease;
}
:global(html.dark) .phone-input-group {
  background: rgba(255, 255, 255, 0.05);
  border-color: rgba(255, 255, 255, 0.15);
}
.phone-input-group:focus-within {
  border-color: #b20537 !important;
  box-shadow: 0 0 0 3px rgba(178, 5, 55, 0.12);
}

.phone-prefix-badge {
  padding: 0.75rem 0.875rem;
  background: #f8fafc;
  border-right: 1.5px solid #e2e8f0;
  font-size: 0.8125rem;
  font-weight: 700;
  color: #334155;
  display: flex;
  align-items: center;
  gap: 0.375rem;
  user-select: none;
  flex-shrink: 0;
}
:global(html.dark) .phone-prefix-badge {
  background: rgba(255, 255, 255, 0.08);
  border-color: rgba(255, 255, 255, 0.1);
  color: #cbd5e1;
}

.phone-input-field {
  width: 100%;
  padding: 0.75rem 1rem;
  background: transparent;
  border: none;
  outline: none;
  color: #0f172a;
  font-size: 0.875rem;
  font-weight: 600;
}
:global(html.dark) .phone-input-field {
  color: #ffffff;
}
.phone-input-field::placeholder { color: #94a3b8; }

.phone-input-invalid {
  border-color: #f43f5e !important;
  background-color: #fff1f2 !important;
}
</style>
