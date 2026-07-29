<template>
  <div class="space-y-6">

    <!-- Query Setup Location Banner -->
    <div v-if="locationSetupRequired" class="p-5 rounded-3xl bg-amber-50 border-2 border-amber-300 shadow-md flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-amber-200 text-amber-900 flex items-center justify-center text-xl flex-shrink-0">
          <svg class="w-5 h-5 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        </div>
        <div>
          <h3 class="font-extrabold text-amber-900 text-base">Location Setup Required</h3>
          <p class="text-xs text-amber-800 font-medium">Please select your location on the map below and save it before creating services.</p>
        </div>
      </div>
    </div>

    <!-- Page Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex items-center justify-between flex-wrap gap-4">
      <div>
        <h1 class="text-2xl font-heading font-extrabold text-slate-900 mb-1 flex items-center gap-2">
          <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
          <span>Account Profile</span>
        </h1>
        <p class="text-sm text-slate-500 font-medium">Manage your personal information, location coordinates & password</p>
      </div>
    </div>

    <!-- Location Setup & Map Card -->
    <div id="location-section" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
      <div class="flex items-center justify-between flex-wrap gap-2 pb-4 border-b border-slate-100">
        <div>
          <h2 class="text-lg font-heading font-extrabold text-slate-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Location & Service Coverage Setup</span>
          </h2>
          <p class="text-xs text-slate-500 font-medium">Select your primary location using the search box or by dragging the pin on the map.</p>
        </div>
        <span v-if="hasLocationSaved" class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200 flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full bg-emerald-600" />
          Location Set
        </span>
        <span v-else class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200 flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full bg-rose-600" />
          Location Missing
        </span>
      </div>

      <LocationPickerMap
        :initial-lat="locationForm.latitude"
        :initial-lng="locationForm.longitude"
        :initial-address="locationForm.address"
        :initial-city="locationForm.city"
        @update:location="onLocationPicked"
      />

      <form @submit.prevent="saveLocation" class="space-y-4 pt-2">
        <p v-if="locationError" class="text-rose-600 text-sm font-semibold">❌ {{ locationError }}</p>
        <p v-if="locationSuccess" class="text-emerald-600 text-sm font-semibold">✅ Location saved successfully!</p>

        <div class="flex items-center gap-4">
          <button
            type="submit"
            :disabled="locationSaving"
            class="px-8 py-3 rounded-xl bg-gradient-to-r from-rose-600 via-rose-700 to-rose-800 text-white text-xs font-extrabold hover:shadow-lg transition-all disabled:opacity-50 cursor-pointer flex items-center gap-2"
            style="color: #ffffff !important;"
          >
            <span>{{ locationSaving ? 'Saving Location...' : 'Save Location & Coordinates' }}</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Basic Info -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
      <h2 class="text-lg font-heading font-extrabold text-slate-900 mb-4">Basic Information</h2>
      <form @submit.prevent="updateProfile" class="space-y-4 max-w-lg" novalidate>
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name <span class="text-rose-600">*</span></label>
          <input
            v-model="profileForm.name"
            type="text"
            placeholder="John Doe"
            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium transition-all"
            :class="{ 'border-rose-500 bg-rose-50/30 text-rose-900': profileErrors.name }"
          />
          <p v-if="profileErrors.name" class="text-rose-600 text-xs font-semibold mt-1">{{ profileErrors.name }}</p>
        </div>
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Phone Number</label>
          <input
            v-model="profileForm.phone"
            type="tel"
            placeholder="e.g. 9876543210"
            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium transition-all"
            :class="{ 'border-rose-500 bg-rose-50/30 text-rose-900': profileErrors.phone }"
          />
          <p v-if="profileErrors.phone" class="text-rose-600 text-xs font-semibold mt-1">{{ profileErrors.phone }}</p>
        </div>
        <p v-if="profileError" class="text-rose-600 text-xs font-semibold p-3 bg-rose-50 rounded-xl border border-rose-200">{{ profileError }}</p>
        <p v-if="profileSuccess" class="text-emerald-600 text-xs font-semibold p-3 bg-emerald-50 rounded-xl border border-emerald-200">Profile updated successfully!</p>
        <button
          type="submit"
          :disabled="profileSaving"
          class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-extrabold hover:shadow-md transition-all disabled:opacity-50 cursor-pointer"
          style="color: #ffffff !important;"
        >
          {{ profileSaving ? 'Saving...' : 'Update Profile' }}
        </button>
      </form>
    </div>

    <!-- Password Change -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
      <h2 class="text-lg font-heading font-extrabold text-slate-900 mb-4">Change Password</h2>
      <form @submit.prevent="updatePassword" class="space-y-4 max-w-lg" novalidate>
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            Current Password <span class="text-rose-600">*</span>
          </label>
          <div class="relative flex items-center">
            <input
              v-model="passForm.current_password"
              :type="showCurrentPass ? 'text' : 'password'"
              class="w-full pl-4 pr-11 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium transition-all"
              :class="{ 'border-rose-500 bg-rose-50/30 text-rose-900': passErrors.current_password }"
            />
            <button
              type="button"
              @click="showCurrentPass = !showCurrentPass"
              class="absolute right-3.5 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
              :title="showCurrentPass ? 'Hide password' : 'Show password'"
            >
              <svg v-if="showCurrentPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          <p v-if="passErrors.current_password" class="text-rose-600 text-xs font-semibold mt-1">{{ passErrors.current_password }}</p>
        </div>

        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            New Password <span class="text-rose-600">*</span>
          </label>
          <div class="relative flex items-center">
            <input
              v-model="passForm.new_password"
              :type="showNewPass ? 'text' : 'password'"
              class="w-full pl-4 pr-11 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 focus:outline-none focus:border-rose-600 text-sm font-medium transition-all"
              :class="{ 'border-rose-500 bg-rose-50/30 text-rose-900': passErrors.new_password }"
            />
            <button
              type="button"
              @click="showNewPass = !showNewPass"
              class="absolute right-3.5 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
              :title="showNewPass ? 'Hide password' : 'Show password'"
            >
              <svg v-if="showNewPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          <p class="text-[11px] text-slate-400 mt-1">Must be min 8 characters with 1 letter, 1 number & 1 special symbol (e.g. @$!%*#?&).</p>
          <p v-if="passErrors.new_password" class="text-rose-600 text-xs font-semibold mt-1">{{ passErrors.new_password }}</p>
        </div>

        <p v-if="passError" class="text-rose-600 text-xs font-semibold p-3 bg-rose-50 rounded-xl border border-rose-200">{{ passError }}</p>
        <p v-if="passSuccess" class="text-emerald-600 text-xs font-semibold p-3 bg-emerald-50 rounded-xl border border-emerald-200">Password changed successfully!</p>
        <button
          type="submit"
          :disabled="passSaving"
          class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 text-white text-xs font-extrabold hover:shadow-md transition-all disabled:opacity-50 cursor-pointer"
          style="color: #ffffff !important;"
        >
          {{ passSaving ? 'Saving...' : 'Change Password' }}
        </button>
      </form>
    </div>

  </div>
</template>

<script setup lang="ts">
definePageMeta({ layout: 'dashboard' });

const api = useApi();
const route = useRoute();
const userStore = useUserStore();
const { setLocation: setUserLocationState } = useUserLocation();

const locationSetupRequired = ref(false);

const profileForm = reactive({ name: '', phone: '' });
const profileErrors = reactive({ name: '', phone: '' });
const profileError = ref('');
const profileSaving = ref(false);
const profileSuccess = ref(false);

const passForm = reactive({ current_password: '', new_password: '' });
const passErrors = reactive({ current_password: '', new_password: '' });
const showCurrentPass = ref(false);
const showNewPass = ref(false);
const passSaving = ref(false);
const passError = ref('');
const passSuccess = ref(false);

const locationForm = reactive({
  latitude: 23.0225,
  longitude: 72.5714,
  address: '',
  city: 'Ahmedabad',
});
const locationSaving = ref(false);
const locationError = ref('');
const locationSuccess = ref(false);

const hasLocationSaved = computed(() => {
  const wp = userStore.user?.worker_profile;
  return !!(wp && wp.latitude && wp.longitude && wp.address && wp.city);
});

onMounted(() => {
  userStore.loadFromStorage();
  if (route.query.setup_location) {
    locationSetupRequired.value = true;
  }

  if (userStore.user) {
    profileForm.name = userStore.user.name || '';
    profileForm.phone = userStore.user.phone || '';

    const wp = userStore.user.worker_profile;
    if (wp) {
      if (wp.latitude) locationForm.latitude = wp.latitude;
      if (wp.longitude) locationForm.longitude = wp.longitude;
      if (wp.address) locationForm.address = wp.address;
      if (wp.city) locationForm.city = wp.city;
    }
  }
});

const onLocationPicked = (data: { lat: number; lng: number; address: string; city: string }) => {
  locationForm.latitude = data.lat;
  locationForm.longitude = data.lng;
  locationForm.address = data.address;
  locationForm.city = data.city;
};

const saveLocation = async () => {
  if (!locationForm.address.trim() || !locationForm.city.trim()) {
    locationError.value = 'Please provide full address and city.';
    return;
  }

  locationSaving.value = true;
  locationError.value = '';
  locationSuccess.value = false;

  try {
    const payload = {
      address: locationForm.address,
      city: locationForm.city,
      latitude: locationForm.latitude,
      longitude: locationForm.longitude,
    };

    const res = await api.post<any>('/worker-profile', payload);
    locationSuccess.value = true;
    locationSetupRequired.value = false;

    // Refresh user profile
    const profileRes = await api.get<any>('/profile');
    if (profileRes.user) {
      userStore.updateUser(profileRes.user);
    }

    // Also update global useUserLocation composable state
    setUserLocationState({
      city: locationForm.city,
      area: locationForm.address.split(',')[0] || locationForm.city,
      lat: locationForm.latitude,
      lng: locationForm.longitude,
    });
  } catch (e: any) {
    const errors = e?.data?.errors;
    locationError.value = errors ? Object.values(errors).flat().join(' ') : (e?.data?.message || 'Failed to save location.');
  } finally {
    locationSaving.value = false;
  }
};

const updateProfile = async () => {
  profileErrors.name = '';
  profileErrors.phone = '';
  profileError.value = '';
  profileSuccess.value = false;

  let hasError = false;
  if (!profileForm.name.trim()) {
    profileErrors.name = 'Full name is required.';
    hasError = true;
  }

  const p = profileForm.phone ? profileForm.phone.trim() : '';
  const digits = p.replace(/[^0-9]/g, '');
  if (p && (!/^[0-9\-\s\+]{10,15}$/.test(p) || digits.length < 10)) {
    profileErrors.phone = 'Please enter a valid 10-digit phone number (numbers only).';
    hasError = true;
  }

  if (hasError) return;

  profileSaving.value = true;
  try {
    const data = await api.put<any>('/auth/profile', profileForm);
    if (data.user) userStore.updateUser(data.user);
    profileSuccess.value = true;
  } catch (e: any) {
    const errors = e?.data?.errors;
    if (errors) {
      if (errors.name) profileErrors.name = errors.name[0];
      if (errors.phone) profileErrors.phone = errors.phone[0];
    } else {
      profileError.value = e?.data?.message || 'Failed to update profile.';
    }
  } finally { profileSaving.value = false; }
};

const updatePassword = async () => {
  passErrors.current_password = '';
  passErrors.new_password = '';
  passError.value = '';
  passSuccess.value = false;

  let hasError = false;

  if (!passForm.current_password) {
    passErrors.current_password = 'Please enter your current password.';
    hasError = true;
  }

  const newPass = passForm.new_password;
  if (!newPass) {
    passErrors.new_password = 'Please enter a new password.';
    hasError = true;
  } else if (newPass.length < 8) {
    passErrors.new_password = 'Password must be at least 8 characters long.';
    hasError = true;
  } else if (!/[a-zA-Z]/.test(newPass)) {
    passErrors.new_password = 'Password must contain at least 1 letter/character.';
    hasError = true;
  } else if (!/[0-9]/.test(newPass)) {
    passErrors.new_password = 'Password must contain at least 1 number.';
    hasError = true;
  } else if (!/[^a-zA-Z0-9]/.test(newPass)) {
    passErrors.new_password = 'Password must contain at least 1 special symbol (e.g. @$!%*#?&).';
    hasError = true;
  }

  if (hasError) return;

  passSaving.value = true;
  try {
    await api.put('/auth/password', passForm);
    passSuccess.value = true;
    passForm.current_password = '';
    passForm.new_password = '';
  } catch (e: any) {
    const errors = e?.data?.errors;
    if (errors) {
      if (errors.current_password) passErrors.current_password = errors.current_password[0];
      if (errors.new_password) passErrors.new_password = errors.new_password[0];
    } else {
      passError.value = e?.data?.message || 'Failed to update password.';
    }
  } finally { passSaving.value = false; }
};
</script>
