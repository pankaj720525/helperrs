<template>
  <div class="auth-page-outer">
    <!-- Premium subtle mesh grid and modern glow circles -->
    <div class="bg-grid-pattern"></div>
    <div class="bg-ambient bg-ambient-top"></div>
    <div class="bg-ambient bg-ambient-bottom"></div>

    <div class="auth-page-wrap relative z-10">
      
      <!-- STEP 1: Enter Registration Details -->
      <div v-if="step === 1" class="auth-card-container">
        <div class="auth-header">
          <h1 class="auth-title">Fill the form to create your Account</h1>
        </div>

        <form @submit.prevent="handleSendOtp" class="space-y-4" novalidate>

          <!-- Full Name -->
          <div>
            <label class="auth-label">Full Name <span class="text-rose-600">*</span></label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Full Name"
              class="auth-input"
              :class="{ 'auth-input-invalid': fieldErrors.name }"
            />
            <p v-if="fieldErrors.name" class="text-rose-600 text-xs font-semibold mt-1">{{ fieldErrors.name }}</p>
          </div>

          <!-- Email & Phone Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Email -->
            <div>
              <label class="auth-label">Email <span class="text-rose-600">*</span></label>
              <input
                v-model="form.email"
                type="email"
                placeholder="Email"
                class="auth-input"
                :class="{ 'auth-input-invalid': fieldErrors.email }"
              />
              <p v-if="fieldErrors.email" class="text-rose-600 text-xs font-semibold mt-1">{{ fieldErrors.email }}</p>
            </div>

            <!-- Phone Number -->
            <div>
              <label class="auth-label">Phone Number <span class="text-rose-600">*</span></label>
              <input
                v-model="form.phone"
                type="tel"
                placeholder="Phone Number"
                class="auth-input"
                :class="{ 'auth-input-invalid': fieldErrors.phone }"
              />
              <p v-if="fieldErrors.phone" class="text-rose-600 text-xs font-semibold mt-1">{{ fieldErrors.phone }}</p>
            </div>
          </div>

          <!-- Password & Confirm Password Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Password with Eye Toggle & Requirements Popover -->
            <div class="relative">
              <label class="auth-label">Password <span class="text-rose-600">*</span></label>
              <div class="relative flex items-center">
                <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Password"
                  class="auth-input pr-11"
                  :class="{ 'auth-input-invalid': fieldErrors.password }"
                  @focus="showPasswordPopover = true"
                  @blur="showPasswordPopover = false"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer z-10"
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
              <p v-if="fieldErrors.password" class="text-rose-600 text-xs font-semibold mt-1">{{ fieldErrors.password }}</p>

              <!-- Password Requirement Floating Tooltip Popover -->
              <Transition name="popover-fade">
                <div
                  v-if="isPopoverVisible"
                  class="password-req-popover"
                >
                  <div class="popover-arrow"></div>
                  <div class="flex items-center justify-between mb-2 pb-1.5 border-b border-slate-100 dark:border-slate-800">
                    <h5 class="text-xs font-extrabold text-slate-900 dark:text-white" style="color: #0f172a !important;">
                      Password must meet requirements:
                    </h5>
                    <button
                      type="button"
                      @click="dismissPopover"
                      class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-100 p-0.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                      title="Close"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                    </button>
                  </div>
                  <ul class="space-y-1.5 text-[11px] font-semibold">
                    <li class="flex items-center gap-2" :class="pwdChecks.lowercase ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'">
                      <svg v-if="pwdChecks.lowercase" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <svg v-else class="w-3.5 h-3.5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span>At least one lowercase letter</span>
                    </li>
                    <li class="flex items-center gap-2" :class="pwdChecks.uppercase ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'">
                      <svg v-if="pwdChecks.uppercase" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <svg v-else class="w-3.5 h-3.5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span>At least one capital letter</span>
                    </li>
                    <li class="flex items-center gap-2" :class="pwdChecks.number ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'">
                      <svg v-if="pwdChecks.number" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <svg v-else class="w-3.5 h-3.5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span>At least one number</span>
                    </li>
                    <li class="flex items-center gap-2" :class="pwdChecks.minLen ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'">
                      <svg v-if="pwdChecks.minLen" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <svg v-else class="w-3.5 h-3.5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span>Be at least 8 characters</span>
                    </li>
                    <li class="flex items-center gap-2" :class="pwdChecks.special ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'">
                      <svg v-if="pwdChecks.special" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <svg v-else class="w-3.5 h-3.5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span>At least one special character</span>
                    </li>
                    <li class="flex items-center gap-2" :class="pwdChecks.notMatchUser ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'">
                      <svg v-if="pwdChecks.notMatchUser" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                      </svg>
                      <svg v-else class="w-3.5 h-3.5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      <span>Not to be same as email & name</span>
                    </li>
                  </ul>
                </div>
              </Transition>
            </div>

            <!-- Confirm Password with Eye Toggle -->
            <div>
              <label class="auth-label">Confirm Password <span class="text-rose-600">*</span></label>
              <div class="relative flex items-center">
                <input
                  v-model="form.password_confirmation"
                  :type="showConfirmPassword ? 'text' : 'password'"
                  placeholder="Confirm Password"
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
          </div>

          <!-- Account Type Selector Cards -->
          <div class="pt-1">
            <label class="auth-label-group">Account Type <span class="text-rose-600">*</span></label>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Select what type of account you're interested in</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <!-- Buying -->
              <div
                @click="form.role = 'user'"
                class="role-card"
                :class="{ 'role-card-active': form.role === 'user' }"
              >
                <div class="role-icon-box">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                  </svg>
                </div>
                <div>
                  <h4 class="role-title">Buying</h4>
                  <p class="role-desc">Buying services to grow my business</p>
                </div>
              </div>

              <!-- Selling -->
              <div
                @click="form.role = 'worker'"
                class="role-card"
                :class="{ 'role-card-active': form.role === 'worker' }"
              >
                <div class="role-icon-box">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                  </svg>
                </div>
                <div>
                  <h4 class="role-title">Selling</h4>
                  <p class="role-desc">Selling my services to make money</p>
                </div>
              </div>

              <!-- Buying & Selling -->
              <div
                @click="form.role = 'both'"
                class="role-card"
                :class="{ 'role-card-active': form.role === 'both' }"
              >
                <div class="role-icon-box">
                  <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <div>
                  <h4 class="role-title">Buying & Selling</h4>
                  <p class="role-desc">The best of both worlds</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Terms Agreement Checkbox -->
          <div class="pt-2">
            <label class="flex items-center gap-2.5 cursor-pointer text-xs font-bold">
              <input
                v-model="termsAgreed"
                type="checkbox"
                class="w-4 h-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500 cursor-pointer"
              />
              <span class="auth-text-dark" style="color: #0f172a !important;">
                I agree to the
                <a href="#" @click.prevent class="text-purple-600 font-extrabold hover:underline" style="color: #7c3aed !important;">terms of service</a>
                <span style="color: #0f172a !important;">and</span>
                <a href="#" @click.prevent class="text-purple-600 font-extrabold hover:underline" style="color: #7c3aed !important;">privacy policy</a>
              </span>
            </label>
            <p v-if="fieldErrors.terms" class="text-rose-600 text-xs font-semibold mt-1">{{ fieldErrors.terms }}</p>
          </div>

          <!-- General Error -->
          <p v-if="generalError" class="text-rose-600 text-xs font-semibold p-3 bg-rose-50 dark:bg-rose-950/30 rounded-xl border border-rose-200 dark:border-rose-800">{{ generalError }}</p>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading"
            class="auth-btn-purple mt-3"
            style="color: #ffffff !important;"
          >
            {{ loading ? 'Creating Account...' : 'Register' }}
          </button>
        </form>

        <p class="text-left text-xs font-bold mt-6" style="color: #0f172a !important;">
          Existing User?
          <NuxtLink to="/login" class="font-extrabold hover:underline" style="color: #7c3aed !important;">Log in</NuxtLink>
        </p>
      </div>

    <!-- STEP 2: Professional 6-Digit Mobile OTP Verification -->
    <div v-else-if="step === 2" class="auth-card-container">
      <div class="auth-icon-badge">
        <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
      </div>

      <div class="auth-header">
        <h1 class="auth-title">Verify Phone Number</h1>
        <p class="auth-sub">
          We sent a 6-digit verification code to <strong class="text-slate-900 dark:text-white font-bold">{{ form.phone }}</strong>.
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
            Change Details
          </button>
          <button
            type="submit"
            :disabled="loading || fullOtpCode.length < 6"
            class="auth-btn-submit flex-1"
            style="color: #ffffff !important;"
          >
            {{ loading ? 'Verifying...' : 'Verify Code & Create Account' }}
          </button>
        </div>
      </form>
    </div>

  </div>
  </div>
</template>

<script setup lang="ts">
const userStore = useUserStore();
const api = useApi();
const { initTheme } = useTheme();

const step = ref(1);

const form = reactive({
  name: "",
  email: "",
  phone: "",
  role: "user",
  password: "",
  password_confirmation: ""
});

const otpDigits = reactive<string[]>(["", "", "", "", "", ""]);
const otpInputs = reactive<HTMLInputElement[]>([]);
const otpPreview = ref("");
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const loading = ref(false);
const generalError = ref("");
const termsAgreed = ref(false);
const resendTimer = ref(60);
let timerInterval: any = null;
const showPasswordPopover = ref(false);
const userDismissedPopover = ref(false);

const fieldErrors = reactive<Record<string, string>>({
  name: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: "",
  terms: "",
  otp: ""
});

const pwdChecks = computed(() => {
  const p = form.password;
  const nameLower = form.name.toLowerCase().trim();
  const emailLower = form.email.toLowerCase().trim();
  const pLower = p.toLowerCase();

  return {
    lowercase: /[a-z]/.test(p),
    uppercase: /[A-Z]/.test(p),
    number: /[0-9]/.test(p),
    minLen: p.length >= 8,
    special: /[^a-zA-Z0-9]/.test(p),
    notMatchUser: p.length > 0 &&
      (!nameLower || !pLower.includes(nameLower)) &&
      (!emailLower || !pLower.includes(emailLower.split('@')[0]))
  };
});

const allPwdChecksPass = computed(() => {
  const c = pwdChecks.value;
  return c.lowercase && c.uppercase && c.number && c.minLen && c.special && c.notMatchUser;
});

const isPopoverVisible = computed(() => {
  if (userDismissedPopover.value) return false;
  if (allPwdChecksPass.value) return false;
  return showPasswordPopover.value || form.password.length > 0;
});

const dismissPopover = () => {
  userDismissedPopover.value = true;
};

// Live inline error clearing on input change
watch(() => form.name, (val) => {
  if (val.trim() && fieldErrors.name) fieldErrors.name = "";
});

watch(() => form.email, (val) => {
  if (val.trim() && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val.trim()) && fieldErrors.email) {
    fieldErrors.email = "";
  }
});

watch(() => form.phone, (val) => {
  const clean = val.replace(/[^0-9]/g, "");
  if (val.trim() && clean.length >= 10 && fieldErrors.phone) {
    fieldErrors.phone = "";
  }
});

watch(() => form.password, (val) => {
  if (!val) {
    userDismissedPopover.value = false;
  }
  if (fieldErrors.password) {
    const err = validatePasswordStrength(form.password);
    if (!err) fieldErrors.password = "";
  }
});

watch(() => form.password_confirmation, (val) => {
  if (val && val === form.password && fieldErrors.password_confirmation) {
    fieldErrors.password_confirmation = "";
  }
});

watch(termsAgreed, (val) => {
  if (val && fieldErrors.terms) fieldErrors.terms = "";
});

onMounted(() => {
  initTheme();
});

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval);
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
  fieldErrors.name = "";
  fieldErrors.email = "";
  fieldErrors.phone = "";
  fieldErrors.password = "";
  fieldErrors.password_confirmation = "";
  fieldErrors.terms = "";
  fieldErrors.otp = "";
  generalError.value = "";
};

const validatePasswordStrength = (pass: string): string | null => {
  if (pass.length < 8) return "Password must be at least 8 characters long.";
  if (!/[a-zA-Z]/.test(pass)) return "Password must contain at least 1 letter/character.";
  if (!/[0-9]/.test(pass)) return "Password must contain at least 1 number.";
  if (!/[^a-zA-Z0-9]/.test(pass)) return "Password must contain at least 1 special symbol (e.g. @$!%*#?&).";
  return null;
};

const handleSendOtp = async () => {
  clearErrors();
  let hasError = false;

  if (!form.name.trim()) {
    fieldErrors.name = "Full name is required.";
    hasError = true;
  }

  if (!form.email.trim()) {
    fieldErrors.email = "Email address is required.";
    hasError = true;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email.trim())) {
    fieldErrors.email = "Please enter a valid email address.";
    hasError = true;
  }

  const rawPhone = form.phone.trim();
  const cleanPhoneDigits = rawPhone.replace(/[^0-9]/g, "");

  if (!rawPhone) {
    fieldErrors.phone = "Phone number is required.";
    hasError = true;
  } else if (!/^[0-9\-\s\+]{10,15}$/.test(rawPhone) || cleanPhoneDigits.length < 10) {
    fieldErrors.phone = "Please enter a valid 10-digit phone number (numbers only).";
    hasError = true;
  }

  const passErr = validatePasswordStrength(form.password);
  if (passErr) {
    fieldErrors.password = passErr;
    hasError = true;
  }

  if (form.password !== form.password_confirmation) {
    fieldErrors.password_confirmation = "Passwords do not match.";
    hasError = true;
  }

  if (!termsAgreed.value) {
    fieldErrors.terms = "You must agree to the terms of service and privacy policy.";
    hasError = true;
  }

  if (hasError) return;

  loading.value = true;

  try {
    const data = await api.post<any>("/auth/register/send-otp", form);
    otpPreview.value = data.otp || "";
    step.value = 2;
    startResendTimer();
    nextTick(() => {
      if (otpInputs[0]) otpInputs[0].focus();
    });
  } catch (e: any) {
    const errors = e?.data?.errors;
    if (errors) {
      if (errors.name) fieldErrors.name = errors.name[0];
      if (errors.email) fieldErrors.email = errors.email[0];
      if (errors.phone) fieldErrors.phone = errors.phone[0];
      if (errors.password) fieldErrors.password = errors.password[0];
    } else {
      generalError.value = e?.data?.message || "Failed to send verification code. Please check your inputs.";
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

const handleVerifyOtp = async () => {
  clearErrors();
  if (fullOtpCode.value.length < 6) {
    fieldErrors.otp = "Please enter all 6 digits of the OTP verification code.";
    return;
  }

  loading.value = true;
  try {
    const data = await api.post<any>("/auth/register/verify-otp", {
      phone: form.phone.trim(),
      otp: fullOtpCode.value
    });
    userStore.setAuth(data.user, data.token);
    navigateTo("/dashboard");
  } catch (e: any) {
    const errors = e?.data?.errors;
    if (errors && errors.otp) {
      fieldErrors.otp = errors.otp[0];
    } else {
      generalError.value = e?.data?.message || "Invalid OTP code. Please check and try again.";
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.auth-page-outer {
  position: relative;
  min-height: calc(100vh - 120px);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background: radial-gradient(circle at 50% 10%, rgba(245, 243, 255, 1) 0%, rgba(248, 250, 252, 1) 80%);
}
:global(html.dark) .auth-page-outer {
  background: radial-gradient(circle at 50% 10%, rgba(139, 92, 246, 0.12) 0%, rgba(15, 23, 42, 1) 80%);
}

/* Subtle background geometric dot-grid texture */
.bg-grid-pattern {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(rgba(148, 163, 184, 0.2) 1px, transparent 1px);
  background-size: 28px 28px;
  pointer-events: none;
  opacity: 0.6;
}
:global(html.dark) .bg-grid-pattern {
  background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
  opacity: 0.4;
}

/* Soft ambient background glow effects */
.bg-ambient {
  position: absolute;
  border-radius: 9999px;
  filter: blur(100px);
  pointer-events: none;
}
.bg-ambient-top {
  top: -15%;
  right: 15%;
  width: 32rem;
  height: 32rem;
  background: radial-gradient(circle, rgba(167, 139, 250, 0.35) 0%, rgba(139, 92, 246, 0.05) 70%);
}
.bg-ambient-bottom {
  bottom: -15%;
  left: 10%;
  width: 36rem;
  height: 36rem;
  background: radial-gradient(circle, rgba(244, 114, 182, 0.25) 0%, rgba(225, 29, 72, 0.05) 70%);
}
:global(html.dark) .bg-ambient { opacity: 0.5; }

.auth-page-wrap { width: 100%; max-width: 44rem; margin: 0 auto; padding: 3rem 1rem; }

.auth-card-container {
  background: rgba(255, 255, 255, 0.96);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(226, 232, 240, 0.8);
  border-radius: 1.5rem;
  padding: 2.5rem 2.25rem;
  box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.12);
  transition: all 0.2s ease-in-out;
}
:global(html.dark) .auth-card-container {
  background: rgba(30, 41, 59, 0.92);
  border-color: rgba(255, 255, 255, 0.1);
  box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4);
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

.auth-header { text-align: center; margin-bottom: 2rem; }
.auth-title { font-family: 'Outfit', sans-serif; font-size: 1.85rem; font-weight: 800; color: #0f172a; margin-bottom: 0.375rem; letter-spacing: -0.02em; }
:global(html.dark) .auth-title { color: #f8fafc; }
.auth-sub { font-size: 0.875rem; font-weight: 500; color: #64748b; margin: 0; line-height: 1.4; }
:global(html.dark) .auth-sub { color: #94a3b8; }

.auth-label { display: block; font-size: 0.8125rem; font-weight: 600; color: #334155; margin-bottom: 0.375rem; }
:global(html.dark) .auth-label { color: #cbd5e1; }
.auth-label-group { display: block; font-size: 0.9375rem; font-weight: 800; color: #0f172a; margin-bottom: 0.125rem; }
:global(html.dark) .auth-label-group { color: #f8fafc; }

.auth-input {
  width: 100%;
  padding: 0.75rem 1rem;
  border-radius: 0.625rem;
  background: #ffffff;
  border: 1.5px solid #e2e8f0;
  color: #0f172a;
  font-size: 0.875rem;
  font-weight: 500;
  outline: none;
  transition: all 0.15s ease;
}
:global(html.dark) .auth-input {
  background: rgba(15, 23, 42, 0.6);
  border-color: rgba(255, 255, 255, 0.15);
  color: #ffffff;
}
.auth-input::placeholder { color: #94a3b8; }
.auth-input:focus { border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15); }

.auth-input-invalid {
  border-color: #f43f5e !important;
  background-color: #fff1f2 !important;
  color: #9f1239 !important;
}
:global(html.dark) .auth-input-invalid {
  background-color: rgba(244, 63, 94, 0.15) !important;
  color: #fda4af !important;
}

/* Password Requirement Floating Tooltip Popover */
.password-req-popover {
  position: absolute;
  top: calc(100% + 8px);
  left: 0;
  width: 100%;
  max-width: 19.5rem;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 1rem;
  padding: 0.875rem 1rem;
  box-shadow: 0 15px 35px rgba(15, 23, 42, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.05);
  z-index: 50;
  color: #0f172a;
}
:global(html.dark) .password-req-popover {
  background: #1e293b;
  border-color: rgba(255, 255, 255, 0.12);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45);
  color: #f8fafc;
}
.password-req-popover h5 {
  color: #0f172a !important;
}
:global(html.dark) .password-req-popover h5 {
  color: #f8fafc !important;
}

.popover-arrow {
  position: absolute;
  top: -6px;
  left: 1.5rem;
  width: 10px;
  height: 10px;
  background: #ffffff;
  border-left: 1px solid #e2e8f0;
  border-top: 1px solid #e2e8f0;
  transform: rotate(45deg);
}
:global(html.dark) .popover-arrow {
  background: #1e293b;
  border-color: rgba(255, 255, 255, 0.12);
}

.popover-fade-enter-active,
.popover-fade-leave-active {
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.popover-fade-enter-from,
.popover-fade-leave-to {
  opacity: 0;
  transform: translateY(-6px) scale(0.97);
}

/* Role Selector Cards */
.role-card {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  padding: 0.875rem;
  border-radius: 0.75rem;
  background: #f8fafc;
  border: 1.5px solid #e2e8f0;
  cursor: pointer;
  transition: all 0.2s ease;
}
:global(html.dark) .role-card {
  background: rgba(15, 23, 42, 0.5);
  border-color: rgba(255, 255, 255, 0.1);
}
.role-card:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
}
.role-card-active {
  background: #f5f3ff !important;
  border-color: #8b5cf6 !important;
  box-shadow: 0 4px 12px rgba(139, 92, 246, 0.12);
}
:global(html.dark) .role-card-active {
  background: rgba(139, 92, 246, 0.15) !important;
  border-color: #a78bfa !important;
}

.role-icon-box {
  width: 2.25rem;
  height: 2.25rem;
  border-radius: 0.5rem;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
:global(html.dark) .role-icon-box {
  background: rgba(30, 41, 59, 0.8);
  border-color: rgba(255, 255, 255, 0.12);
}
.role-title { font-size: 0.8125rem; font-weight: 700; color: #0f172a; margin: 0 0 0.125rem 0; }
:global(html.dark) .role-title { color: #f8fafc; }
.role-desc { font-size: 0.6875rem; color: #64748b; margin: 0; line-height: 1.3; }
:global(html.dark) .role-desc { color: #94a3b8; }

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
  border-color: #8b5cf6;
  box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.18);
  background: #ffffff;
}
:global(html.dark) .otp-pin-box:focus {
  background: rgba(255, 255, 255, 0.1);
}
.otp-pin-filled {
  border-color: #8b5cf6;
  background: #f5f3ff;
  color: #7c3aed;
}
:global(html.dark) .otp-pin-filled {
  background: rgba(139, 92, 246, 0.2);
  color: #a78bfa;
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

.auth-btn-purple {
  width: 100%;
  padding: 0.875rem;
  border-radius: 0.75rem;
  border: none;
  background: linear-gradient(135deg, #8b5cf6, #7c3aed, #6d28d9);
  color: #ffffff !important;
  font-weight: 800;
  font-size: 0.9375rem;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0 4px 16px rgba(124, 58, 237, 0.35);
}
.auth-btn-purple:hover:not(:disabled) {
  box-shadow: 0 6px 22px rgba(124, 58, 237, 0.5);
  transform: translateY(-1.5px);
}

.auth-btn-submit {
  width: 100%;
  padding: 0.875rem;
  border-radius: 0.75rem;
  border: none;
  background: linear-gradient(135deg, #8b5cf6, #7c3aed, #6d28d9);
  color: #ffffff !important;
  font-weight: 800;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);
}
.auth-btn-submit:hover:not(:disabled) {
  box-shadow: 0 6px 20px rgba(124, 58, 237, 0.5);
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
</style>
