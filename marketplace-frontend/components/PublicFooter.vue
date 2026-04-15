<template>
  <footer class="border-t border-white/5 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Brand -->
        <div class="md:col-span-2">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-xl gradient-primary flex items-center justify-center">
              <span class="text-white font-bold text-sm">SM</span>
            </div>
            <span class="font-heading font-semibold text-white text-lg">ServiceMarket</span>
          </div>
          <p class="text-sm text-slate-400 max-w-sm">
            Find trusted local service professionals near you. Connect with skilled workers for all your home service needs.
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="text-sm font-semibold text-white mb-3">Quick Links</h4>
          <div class="space-y-2">
            <NuxtLink to="/services" class="block text-sm text-slate-400 hover:text-primary-light transition-colors">Browse Services</NuxtLink>
            <NuxtLink to="/search" class="block text-sm text-slate-400 hover:text-primary-light transition-colors">Search by Location</NuxtLink>
            <NuxtLink to="/register" class="block text-sm text-slate-400 hover:text-primary-light transition-colors">Register as Worker</NuxtLink>
          </div>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="text-sm font-semibold text-white mb-3">Contact</h4>
          <div class="space-y-2 text-sm text-slate-400">
            <p>📧 support@servicemarketplace.com</p>
            <p>📞 +1 (555) 123-4567</p>
          </div>
        </div>
      </div>

      <div class="border-t border-white/5 mt-8 pt-8 text-center">
        <p class="text-xs text-slate-500">© {{ new Date().getFullYear() }} Service Marketplace. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>
