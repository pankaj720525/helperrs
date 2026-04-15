<template>
  <div>
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
      <!-- Background -->
      <div class="absolute inset-0 gradient-hero" />
      <div class="absolute inset-0 opacity-30">
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary/20 rounded-full blur-3xl animate-float" />
        <div class="absolute bottom-10 right-20 w-96 h-96 bg-primary/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s" />
      </div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-24 md:py-32">
        <div class="max-w-3xl mx-auto text-center">
          <h1 class="text-4xl md:text-6xl font-heading font-bold text-white mb-6 animate-fade-up">
            Find <span class="text-gradient">Trusted</span> Service
            <br />Professionals Near You
          </h1>
          <p class="text-lg text-slate-300 mb-10 animate-fade-up" style="animation-delay: 0.1s">
            Connect with skilled workers for all your home service needs — plumbers, electricians, AC repair, and more.
          </p>

          <!-- Search Box -->
          <div class="glass rounded-2xl p-2 max-w-2xl mx-auto animate-fade-up" style="animation-delay: 0.2s">
            <form @submit.prevent="handleSearch" class="flex flex-col sm:flex-row gap-2">
              <select v-model="searchCategory" class="flex-1 px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-slate-300 focus:outline-none focus:border-primary/50 text-sm">
                <option value="">All Categories</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.slug">{{ cat.name }}</option>
              </select>
              <button type="submit" class="px-8 py-3 rounded-xl gradient-primary text-white font-semibold text-sm hover:shadow-glow transition-all whitespace-nowrap">
                🔍 Find Services
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Categories Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-20">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-heading font-bold text-white mb-3">Browse by Category</h2>
        <p class="text-slate-400">Choose from our wide range of professional services</p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <NuxtLink
          v-for="(cat, i) in categories"
          :key="cat.id"
          :to="`/services?category=${cat.slug}`"
          class="glass rounded-2xl p-6 text-center group hover:border-primary/20 transition-all duration-300 animate-fade-up"
          :style="{ animationDelay: `${i * 0.05}s` }"
        >
          <div class="text-3xl mb-3 group-hover:scale-110 transition-transform duration-300">
            {{ iconEmoji(cat.icon) }}
          </div>
          <h3 class="font-semibold text-white text-sm group-hover:text-primary-light transition-colors">{{ cat.name }}</h3>
          <p class="text-xs text-slate-500 mt-1">{{ cat.services_count || 0 }} services</p>
        </NuxtLink>
      </div>
    </section>

    <!-- How it Works -->
    <section class="border-t border-white/5 py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-heading font-bold text-white mb-3">How It Works</h2>
          <p class="text-slate-400">Get started in 3 simple steps</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="(step, i) in steps" :key="i" class="text-center animate-fade-up" :style="{ animationDelay: `${i * 0.15}s` }">
            <div class="w-16 h-16 rounded-2xl gradient-primary flex items-center justify-center mx-auto mb-5 text-2xl">
              {{ step.icon }}
            </div>
            <h3 class="text-lg font-heading font-semibold text-white mb-2">{{ step.title }}</h3>
            <p class="text-sm text-slate-400">{{ step.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="py-20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <div class="glass rounded-3xl p-12 relative overflow-hidden">
          <div class="absolute inset-0 gradient-primary opacity-10" />
          <div class="relative">
            <h2 class="text-3xl font-heading font-bold text-white mb-4">Are You a Service Professional?</h2>
            <p class="text-slate-300 mb-8 max-w-lg mx-auto">
              Join our marketplace and start connecting with customers in your area. Get a free trial period to get started.
            </p>
            <NuxtLink
              to="/register"
              class="inline-block px-8 py-3 rounded-xl gradient-primary text-white font-semibold hover:shadow-glow transition-all"
            >
              Register as Worker →
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const api = useApi();
const { iconEmoji } = useIconEmoji();
const categories = ref<any[]>([]);
const searchCategory = ref("");

const steps = [
  { icon: "🔍", title: "Search", description: "Browse categories or search by location to find the right professional." },
  { icon: "💬", title: "Connect", description: "Chat directly with service providers and discuss your requirements." },
  { icon: "⭐", title: "Review", description: "Leave a review after the job is done to help the community." },
];

const handleSearch = () => {
  const query: any = {};
  if (searchCategory.value) query.category = searchCategory.value;
  navigateTo({ path: "/services", query });
};

const loadCategories = async () => {
  try {
    const data = await api.get<any>("/categories");
    categories.value = data.categories || [];
  } catch { }
};

onMounted(loadCategories);
</script>
