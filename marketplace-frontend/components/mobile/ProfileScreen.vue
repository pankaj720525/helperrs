<template>
  <div class="h-full w-full flex flex-col justify-between relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
    <!-- Header -->
    <div class="p-4 pt-6 border-b flex items-center justify-between sticky top-0 z-20 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-xs'">
      <h1 class="text-sm font-heading font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">My Account Profile</h1>
      <button @click="$emit('nav', 'settings')" class="p-1 rounded-lg text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
      </button>
    </div>

    <!-- Body -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 pb-20 scrollbar-none">
      
      <!-- User Profile Header Card -->
      <div class="p-4 rounded-2xl border flex items-center gap-3 relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-gradient-to-r from-slate-900 to-[#1E293B] border-slate-800' : 'bg-gradient-to-r from-white to-rose-50/40 border-slate-200 shadow-sm'">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#B20537] to-amber-500 flex items-center justify-center font-extrabold text-white text-lg shadow-md">
          JD
        </div>

        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-1.5">
            <h3 class="text-sm font-bold truncate" :class="isDarkMode ? 'text-white' : 'text-slate-900'">John Doe</h3>
            <span class="px-1.5 py-0.2 bg-amber-400 text-slate-950 font-black text-[8px] rounded uppercase">PRO</span>
          </div>
          <p class="text-[11px] truncate" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">john.doe@example.com</p>
          <span class="inline-block mt-1 text-[9px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
            Role: Service Worker / Vendor
          </span>
        </div>
      </div>

      <!-- Servicing Zone Location Pill -->
      <div class="p-3.5 rounded-2xl border space-y-2 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
        <div class="flex justify-between items-center text-xs">
          <span class="font-semibold" :class="isDarkMode ? 'text-slate-400' : 'text-slate-600'">Current Servicing Zone</span>
          <button @click="$emit('open-location-modal')" class="text-rose-600 font-bold cursor-pointer flex items-center gap-1">
            Change
            <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
          </button>
        </div>
        <div class="text-xs font-bold flex items-center gap-2 p-2.5 rounded-xl border" :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-white' : 'bg-slate-100 border-slate-200 text-slate-900'">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
          <span>Downtown Zone (50+ Cities Covered)</span>
        </div>
      </div>

      <!-- Quick Metrics Stats Grid -->
      <div class="grid grid-cols-3 gap-2.5 text-center">
        <div @click="$emit('nav', 'wishlist')" class="p-3 rounded-2xl border cursor-pointer transition-all" :class="isDarkMode ? 'bg-slate-900 border-slate-800 hover:border-rose-500/40' : 'bg-white border-slate-200 hover:border-rose-300 shadow-xs'">
          <span class="text-lg font-extrabold text-rose-600 block">2</span>
          <span class="text-[10px]" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Wishlist</span>
        </div>
        <div @click="$emit('nav', 'chats')" class="p-3 rounded-2xl border cursor-pointer transition-all" :class="isDarkMode ? 'bg-slate-900 border-slate-800 hover:border-rose-500/40' : 'bg-white border-slate-200 hover:border-rose-300 shadow-xs'">
          <span class="text-lg font-extrabold text-amber-500 block">3</span>
          <span class="text-[10px]" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Active Chats</span>
        </div>
        <div @click="$emit('nav', 'subscription')" class="p-3 rounded-2xl border cursor-pointer transition-all" :class="isDarkMode ? 'bg-slate-900 border-slate-800 hover:border-rose-500/40' : 'bg-white border-slate-200 hover:border-rose-300 shadow-xs'">
          <span class="text-lg font-extrabold text-emerald-600 block">Trial</span>
          <span class="text-[10px]" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Plan</span>
        </div>
      </div>

      <!-- Navigation Links List with Clean SVG Icons -->
      <div class="rounded-2xl border divide-y text-xs transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800 divide-slate-800' : 'bg-white border-slate-200 divide-slate-100 shadow-sm'">
        
        <!-- 1. Manage My Services (For Workers) -->
        <button @click="$emit('nav', 'provider-services')" class="w-full p-3.5 flex items-center justify-between hover:bg-rose-50/40 text-left cursor-pointer">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="font-bold" :class="isDarkMode ? 'text-white' : 'text-slate-900'">My Offered Services</span>
          </div>
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- 2. Wishlist / Saved -->
        <button @click="$emit('nav', 'wishlist')" class="w-full p-3.5 flex items-center justify-between hover:bg-rose-50/40 text-left cursor-pointer">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <span class="font-semibold" :class="isDarkMode ? 'text-slate-200' : 'text-slate-800'">Saved Wishlist</span>
          </div>
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- 3. Subscriptions & Membership -->
        <button @click="$emit('nav', 'subscription')" class="w-full p-3.5 flex items-center justify-between hover:bg-rose-50/40 text-left cursor-pointer">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            <span class="font-semibold" :class="isDarkMode ? 'text-slate-200' : 'text-slate-800'">Membership Subscription</span>
          </div>
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- 4. Notifications -->
        <button @click="$emit('nav', 'notifications')" class="w-full p-3.5 flex items-center justify-between hover:bg-rose-50/40 text-left cursor-pointer">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span class="font-semibold" :class="isDarkMode ? 'text-slate-200' : 'text-slate-800'">Alerts & Notifications</span>
          </div>
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- 5. Settings -->
        <button @click="$emit('nav', 'settings')" class="w-full p-3.5 flex items-center justify-between hover:bg-rose-50/40 text-left cursor-pointer">
          <div class="flex items-center gap-3">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
            <span class="font-semibold" :class="isDarkMode ? 'text-slate-200' : 'text-slate-800'">App Preferences</span>
          </div>
          <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
      </div>

      <!-- Logout button -->
      <button @click="$emit('nav', 'login')" class="w-full py-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 font-bold text-xs hover:bg-rose-100 cursor-pointer flex items-center justify-center gap-2">
        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
        Log Out of Account
      </button>
    </div>

    <!-- Bottom Nav -->
    <div class="h-16 border-t flex items-center justify-around px-2 absolute bottom-0 inset-x-0 z-30 transition-colors" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200 shadow-lg'">
      <button @click="$emit('nav', 'home')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span class="text-[9px] font-medium">Home</span>
      </button>
      <button @click="$emit('nav', 'search')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <span class="text-[9px] font-medium">Search</span>
      </button>
      <button @click="$emit('nav', 'services')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <span class="text-[9px] font-medium">Services</span>
      </button>
      <button @click="$emit('nav', 'chats')" class="flex flex-col items-center gap-1 text-slate-500 hover:text-slate-900 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        <span class="text-[9px] font-medium">Messages</span>
      </button>
      <button @click="$emit('nav', 'profile')" class="flex flex-col items-center gap-1 text-rose-600 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        <span class="text-[9px] font-bold">Account</span>
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({ isDarkMode: Boolean })
defineEmits(['nav', 'open-location-modal'])
</script>
