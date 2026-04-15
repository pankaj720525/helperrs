<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="text-2xl font-heading font-bold text-white mb-2">📋 Subscription</h1>
    <p class="text-slate-400 mb-8">Manage your plan to unlock more services and features.</p>

    <!-- Current Plan Card -->
    <div class="glass rounded-2xl p-6 mb-8 border"
      :class="currentPlan?.plan_type === 'trial' || !currentPlan
        ? 'border-warning/20'
        : 'border-success/20'">
      <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
          <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Current Plan</p>
          <h2 class="text-2xl font-heading font-bold text-white capitalize">
            {{ currentPlan?.plan_type || 'Free Trial' }}
          </h2>
          <p v-if="currentPlan" class="text-sm text-slate-400 mt-1">
            Expires: <span :class="currentPlan.days_remaining <= 7 ? 'text-danger font-semibold' : 'text-slate-300'">
              {{ currentPlan.expires_at?.split('T')[0] }} ({{ currentPlan.days_remaining }} days left)
            </span>
          </p>
          <p v-else class="text-sm text-warning mt-1">
            🔒 Limited to 2 services — upgrade to add more
          </p>
        </div>
        <span class="px-4 py-2 rounded-full text-sm font-semibold"
          :class="currentPlan?.is_expired
            ? 'bg-danger/15 text-danger'
            : currentPlan?.plan_type === 'trial' || !currentPlan
              ? 'bg-warning/15 text-warning'
              : 'bg-success/15 text-success'">
          {{ currentPlan?.is_expired ? 'Expired' : currentPlan ? 'Active' : 'Trial' }}
        </span>
      </div>
    </div>

    <!-- Trial restriction notice -->
    <div v-if="!currentPlan || currentPlan.plan_type === 'trial'" class="glass rounded-2xl p-6 mb-8 border border-warning/20">
      <div class="flex items-start gap-4">
        <div class="text-3xl flex-shrink-0">⚠️</div>
        <div>
          <h3 class="font-semibold text-white mb-1">You are on the Free Trial</h3>
          <p class="text-sm text-slate-400">
            The free trial allows you to create up to <strong class="text-warning">2 services</strong>.
            To add more services and access premium features, subscribe to a paid plan below.
          </p>
        </div>
      </div>
    </div>

    <!-- Upgrade Plans -->
    <h2 class="text-lg font-heading font-semibold text-white mb-4">Upgrade Your Plan</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">

      <!-- Monthly Plan -->
      <div class="glass rounded-2xl p-6 border border-white/5 hover:border-primary/20 transition-all duration-300 flex flex-col">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="text-lg font-heading font-bold text-white">Monthly</h3>
            <p class="text-xs text-slate-400">Billed monthly</p>
          </div>
          <span class="text-2xl font-bold text-primary-light">₹499<span class="text-sm text-slate-400 font-normal">/mo</span></span>
        </div>
        <ul class="space-y-2 mb-6 flex-1">
          <li v-for="f in planFeatures" :key="f" class="flex items-center gap-2 text-sm text-slate-300">
            <span class="text-success text-xs">✓</span> {{ f }}
          </li>
        </ul>
        <button
          @click="subscribe('monthly')"
          :disabled="subscribing === 'monthly'"
          class="w-full py-3 rounded-xl gradient-primary text-white font-semibold text-sm hover:shadow-glow transition-all disabled:opacity-60"
        >
          {{ subscribing === 'monthly' ? 'Processing...' : 'Subscribe Monthly' }}
        </button>
      </div>

      <!-- Yearly Plan -->
      <div class="glass rounded-2xl p-6 border border-primary/20 hover:border-primary/40 transition-all duration-300 flex flex-col relative overflow-hidden">
        <div class="absolute top-3 right-3 px-2 py-0.5 rounded-full bg-primary/15 text-primary-light text-xs font-semibold">Best Value</div>
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="text-lg font-heading font-bold text-white">Yearly</h3>
            <p class="text-xs text-slate-400">Save 30% vs monthly</p>
          </div>
          <span class="text-2xl font-bold text-primary-light">₹3,499<span class="text-sm text-slate-400 font-normal">/yr</span></span>
        </div>
        <ul class="space-y-2 mb-6 flex-1">
          <li v-for="f in planFeatures" :key="f" class="flex items-center gap-2 text-sm text-slate-300">
            <span class="text-success text-xs">✓</span> {{ f }}
          </li>
          <li class="flex items-center gap-2 text-sm text-primary-light font-medium">
            <span class="text-primary-light text-xs">★</span> Priority Support
          </li>
        </ul>
        <button
          @click="subscribe('yearly')"
          :disabled="subscribing === 'yearly'"
          class="w-full py-3 rounded-xl gradient-primary text-white font-semibold text-sm hover:shadow-glow transition-all disabled:opacity-60"
        >
          {{ subscribing === 'yearly' ? 'Processing...' : 'Subscribe Yearly' }}
        </button>
      </div>
    </div>

    <!-- Contact note -->
    <p class="text-center text-xs text-slate-500">
      Need a custom plan? <a href="mailto:support@servicemarketplace.com" class="text-primary-light hover:underline">Contact us</a>
    </p>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const api = useApi();
const userStore = useUserStore();
const toast = useToast();

const currentPlan = ref<any>(null);
const subscribing = ref<string | null>(null);

const planFeatures = [
  'Unlimited services',
  'Priority listing in search',
  'Real-time chat with clients',
  'Review & rating management',
  'Service analytics dashboard',
];

const loadSubscription = async () => {
  try {
    const data = await api.get<any>('/subscription');
    currentPlan.value = data.subscription ?? null;
  } catch { }
};

const subscribe = async (plan: string) => {
  subscribing.value = plan;
  try {
    await api.post('/subscription', { plan_type: plan });
    toast.success(`Subscribed to ${plan} plan! 🎉`);
    await loadSubscription();
  } catch (e: any) {
    toast.error(e?.data?.message || 'Failed to subscribe. Please try again.');
  } finally {
    subscribing.value = null;
  }
};

onMounted(async () => {
  userStore.loadFromStorage();
  if (!userStore.isAuthenticated) return navigateTo('/login');
  await loadSubscription();
});
</script>
