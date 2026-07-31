<template>
  <div class="min-h-screen transition-colors duration-300 font-sans" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-100 text-slate-900'">
    <!-- Header Control Bar -->
    <header class="border-b p-4 sticky top-0 z-50 backdrop-blur-md transition-colors" :class="isDarkMode ? 'bg-slate-900/90 border-slate-800' : 'bg-white/90 border-slate-200 shadow-sm'">
      <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        
        <!-- Brand Title -->
        <div class="flex items-center gap-3">
          <NuxtLink to="/" class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#B20537] to-[#F43F5E] flex items-center justify-center font-bold text-white text-sm shadow-md">
            DO
          </NuxtLink>
          <div>
            <h1 class="text-base font-heading font-extrabold flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-slate-900'">
              <span>DO-PRO Mobile App Design Suite</span>
              <span class="text-[10px] uppercase font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                1080 × 2400 High-Res
              </span>
            </h1>
            <p class="text-xs" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Light-First Mobile UI/UX redesign derived from marketplace-frontend</p>
          </div>
        </div>

        <!-- Controls: Theme Toggle + View Mode Toggle -->
        <div class="flex items-center gap-3 flex-wrap">
          <!-- Light / Dark Mode Switcher -->
          <button
            @click="isDarkMode = !isDarkMode"
            class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 border transition-all cursor-pointer"
            :class="isDarkMode ? 'bg-slate-800 border-slate-700 text-slate-200 hover:border-rose-500' : 'bg-white border-slate-300 text-slate-800 shadow-sm hover:border-rose-500'"
          >
            <!-- Sun / Moon SVG Icon -->
            <svg v-if="!isDarkMode" class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg v-else class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <span>{{ isDarkMode ? 'Dark Mode' : 'Light Mode (Default)' }}</span>
          </button>

          <!-- Mode: Single Device vs Grid Gallery -->
          <div class="flex items-center p-1 rounded-xl border text-xs font-bold" :class="isDarkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-300 shadow-sm'">
            <button
              @click="displayMode = 'single'"
              class="px-3 py-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1.5"
              :class="displayMode === 'single' ? 'bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white shadow-md' : (isDarkMode ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900')"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              <span>Interactive Device</span>
            </button>
            <button
              @click="displayMode = 'grid'"
              class="px-3 py-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1.5"
              :class="displayMode === 'grid' ? 'bg-gradient-to-r from-[#B20537] to-[#D4064A] text-white shadow-md' : (isDarkMode ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900')"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
              <span>Grid Gallery</span>
            </button>
          </div>
        </div>

      </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 md:p-8">
      
      <!-- MODE 1: SINGLE INTERACTIVE DEVICE FRAME -->
      <div v-if="displayMode === 'single'" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Sidebar: Screen Selector Directory -->
        <div class="lg:col-span-4 rounded-3xl p-5 space-y-5 border transition-colors shadow-lg" :class="isDarkMode ? 'bg-slate-900/80 border-slate-800' : 'bg-white border-slate-200'">
          <div>
            <h3 class="text-xs font-heading font-extrabold uppercase tracking-wider text-rose-600 mb-1 flex items-center gap-1.5">
              <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
              </svg>
              <span>Screen Directory (21 Screens)</span>
            </h3>
            <p class="text-[11px]" :class="isDarkMode ? 'text-slate-400' : 'text-slate-500'">Select any screen to render inside the mobile frame</p>
          </div>

          <!-- Grouped Screen Categories -->
          <div class="space-y-4 max-h-[600px] overflow-y-auto pr-1 scrollbar-thin">
            <!-- Entry & Auth -->
            <div class="space-y-1.5">
              <span class="text-[10px] font-bold uppercase tracking-widest block" :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">App Entry & Auth</span>
              <div class="grid grid-cols-1 gap-1.5">
                <button
                  v-for="s in entryScreens"
                  :key="s.id"
                  @click="activeScreen = s.id"
                  class="px-3.5 py-2.5 rounded-xl text-left text-xs font-semibold transition-all border flex items-center justify-between cursor-pointer"
                  :class="activeScreen === s.id ? 'bg-rose-600 text-white border-rose-600 shadow-md font-bold' : (isDarkMode ? 'bg-slate-800/80 text-slate-300 border-slate-700 hover:border-rose-500/50' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50 hover:border-rose-300')"
                >
                  <span class="truncate">{{ s.label }}</span>
                  <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Customer Marketplace -->
            <div class="space-y-1.5">
              <span class="text-[10px] font-bold uppercase tracking-widest block" :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">Customer Marketplace</span>
              <div class="grid grid-cols-1 gap-1.5">
                <button
                  v-for="s in customerScreens"
                  :key="s.id"
                  @click="activeScreen = s.id"
                  class="px-3.5 py-2.5 rounded-xl text-left text-xs font-semibold transition-all border flex items-center justify-between cursor-pointer"
                  :class="activeScreen === s.id ? 'bg-rose-600 text-white border-rose-600 shadow-md font-bold' : (isDarkMode ? 'bg-slate-800/80 text-slate-300 border-slate-700 hover:border-rose-500/50' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50 hover:border-rose-300')"
                >
                  <span class="truncate">{{ s.label }}</span>
                  <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Messages & Account -->
            <div class="space-y-1.5">
              <span class="text-[10px] font-bold uppercase tracking-widest block" :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">Direct Messaging & Account</span>
              <div class="grid grid-cols-1 gap-1.5">
                <button
                  v-for="s in messagingScreens"
                  :key="s.id"
                  @click="activeScreen = s.id"
                  class="px-3.5 py-2.5 rounded-xl text-left text-xs font-semibold transition-all border flex items-center justify-between cursor-pointer"
                  :class="activeScreen === s.id ? 'bg-rose-600 text-white border-rose-600 shadow-md font-bold' : (isDarkMode ? 'bg-slate-800/80 text-slate-300 border-slate-700 hover:border-rose-500/50' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50 hover:border-rose-300')"
                >
                  <span class="truncate">{{ s.label }}</span>
                  <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Provider / Vendor Features -->
            <div class="space-y-1.5">
              <span class="text-[10px] font-bold uppercase tracking-widest block" :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">Service Worker & Vendor Tools</span>
              <div class="grid grid-cols-1 gap-1.5">
                <button
                  v-for="s in workerScreens"
                  :key="s.id"
                  @click="activeScreen = s.id"
                  class="px-3.5 py-2.5 rounded-xl text-left text-xs font-semibold transition-all border flex items-center justify-between cursor-pointer"
                  :class="activeScreen === s.id ? 'bg-rose-600 text-white border-rose-600 shadow-md font-bold' : (isDarkMode ? 'bg-slate-800/80 text-slate-300 border-slate-700 hover:border-rose-500/50' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50 hover:border-rose-300')"
                >
                  <span class="truncate">{{ s.label }}</span>
                  <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- System States -->
            <div class="space-y-1.5">
              <span class="text-[10px] font-bold uppercase tracking-widest block" :class="isDarkMode ? 'text-slate-500' : 'text-slate-400'">App States & Alerts</span>
              <div class="grid grid-cols-1 gap-1.5">
                <button
                  v-for="s in systemScreens"
                  :key="s.id"
                  @click="activeScreen = s.id"
                  class="px-3.5 py-2.5 rounded-xl text-left text-xs font-semibold transition-all border flex items-center justify-between cursor-pointer"
                  :class="activeScreen === s.id ? 'bg-rose-600 text-white border-rose-600 shadow-md font-bold' : (isDarkMode ? 'bg-slate-800/80 text-slate-300 border-slate-700 hover:border-rose-500/50' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50 hover:border-rose-300')"
                >
                  <span class="truncate">{{ s.label }}</span>
                  <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Preview: High-Resolution Mobile Device Mockup -->
        <div class="lg:col-span-8 flex flex-col items-center justify-center">
          
          <!-- Outer Device Frame -->
          <div
            class="w-[360px] h-[740px] rounded-[48px] border-[10px] shadow-2xl relative overflow-hidden flex flex-col transition-all duration-300"
            :class="isDarkMode ? 'bg-slate-900 border-slate-800 shadow-rose-950/40 ring-1 ring-white/10' : 'bg-slate-900 border-slate-800 shadow-slate-400/40 ring-1 ring-black/10'"
          >
            
            <!-- Phone Dynamic Notch / Status Bar -->
            <div class="h-10 px-6 flex items-center justify-between z-40 select-none border-b transition-colors" :class="isDarkMode ? 'bg-slate-950 text-white border-white/5' : 'bg-white text-slate-900 border-slate-200'">
              <span class="text-[11px] font-bold tracking-tight">9:41</span>
              <!-- Dynamic Island Notch -->
              <div class="w-20 h-4 bg-slate-900 rounded-full flex items-center justify-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-slate-950"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
              </div>
              <div class="flex items-center gap-2 text-[10px]">
                <!-- Cellular SVG -->
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 19.35A.996.996 0 0 0 5 21h14c.26 0 .52-.1.71-.29.39-.39.39-1.02 0-1.41l-.62-.63C20.26 17.14 21 15.19 21 13c0-4.97-4.03-9-9-9z"/>
                </svg>
                <!-- Battery SVG -->
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17 4h-3V2h-4v2H7c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/>
                </svg>
              </div>
            </div>

            <!-- Screen Container -->
            <div class="flex-1 w-full relative overflow-hidden transition-colors" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
              <SplashScreen v-if="activeScreen === 'splash'" :is-dark-mode="isDarkMode" />
              <OnboardingScreen v-else-if="activeScreen === 'onboarding'" :is-dark-mode="isDarkMode" @complete="activeScreen = 'home'" @skip="activeScreen = 'home'" />
              <LoginScreen v-else-if="activeScreen === 'login'" :is-dark-mode="isDarkMode" @nav="setScreen" @login-success="activeScreen = 'home'" />
              <SignupScreen v-else-if="activeScreen === 'signup'" :is-dark-mode="isDarkMode" @nav="setScreen" @signup-success="activeScreen = 'home'" />
              <ForgotPasswordScreen v-else-if="activeScreen === 'forgot'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <HomeDashboardScreen v-else-if="activeScreen === 'home'" :is-dark-mode="isDarkMode" @nav="setScreen" @open-location-modal="isLocationModalOpen = true" />
              <CategoriesScreen v-else-if="activeScreen === 'categories'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <SearchScreen v-else-if="activeScreen === 'search'" :is-dark-mode="isDarkMode" @nav="setScreen" @open-location-modal="isLocationModalOpen = true" />
              <ServiceListingScreen v-else-if="activeScreen === 'services'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <ServiceDetailsScreen v-else-if="activeScreen === 'service-details'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <ChatsListScreen v-else-if="activeScreen === 'chats'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <ChatDetailScreen v-else-if="activeScreen === 'chat-detail'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <WishlistScreen v-else-if="activeScreen === 'wishlist'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <SubscriptionScreen v-else-if="activeScreen === 'subscription'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <ProfileScreen v-else-if="activeScreen === 'profile'" :is-dark-mode="isDarkMode" @nav="setScreen" @open-location-modal="isLocationModalOpen = true" />
              <ProviderServiceManagerScreen v-else-if="activeScreen === 'provider-services'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <ProviderServiceCreateScreen v-else-if="activeScreen === 'create-service'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <NotificationsScreen v-else-if="activeScreen === 'notifications'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <SettingsScreen v-else-if="activeScreen === 'settings'" :is-dark-mode="isDarkMode" @nav="setScreen" @toggle-theme="isDarkMode = !isDarkMode" @open-location-modal="isLocationModalOpen = true" />
              <EmptyStatesScreen v-else-if="activeScreen === 'empty-states'" :is-dark-mode="isDarkMode" @nav="setScreen" />
              <ErrorStatesScreen v-else-if="activeScreen === 'error-states'" :is-dark-mode="isDarkMode" @nav="setScreen" />
            </div>

            <!-- Bottom Home Indicator Bar -->
            <div class="h-5 flex items-center justify-center z-40 transition-colors" :class="isDarkMode ? 'bg-slate-950' : 'bg-white border-t border-slate-200'">
              <div class="w-32 h-1 rounded-full" :class="isDarkMode ? 'bg-slate-700' : 'bg-slate-300'"></div>
            </div>
          </div>
        </div>

      </div>

      <!-- MODE 2: GRID GALLERY (ALL SCREENS AT ONCE) -->
      <div v-else class="space-y-6">
        <div class="rounded-2xl p-4 flex justify-between items-center border transition-colors" :class="isDarkMode ? 'bg-slate-900/60 border-slate-800' : 'bg-white border-slate-200 shadow-sm'">
          <p class="text-xs" :class="isDarkMode ? 'text-slate-300' : 'text-slate-700'">Displaying all 21 Mobile App UI screens simultaneously in Light-First design system.</p>
          <span class="text-xs font-bold text-rose-600">1080 × 2400 Scale View</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="s in allScreens" :key="s.id" class="flex flex-col items-center space-y-2">
            <span class="text-xs font-heading font-extrabold" :class="isDarkMode ? 'text-slate-300' : 'text-slate-800'">{{ s.label }}</span>
            <div class="w-[300px] h-[600px] rounded-[36px] border-[8px] shadow-xl overflow-hidden relative flex flex-col" :class="isDarkMode ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-300 shadow-slate-300/40'">
              <!-- Mini status bar -->
              <div class="h-8 px-4 flex items-center justify-between text-[10px] font-bold border-b" :class="isDarkMode ? 'bg-slate-950 text-white border-slate-800' : 'bg-slate-50 text-slate-900 border-slate-200'">
                <span>9:41</span>
                <span class="w-12 h-3 bg-slate-800 rounded-full"></span>
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17 4h-3V2h-4v2H7c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/>
                </svg>
              </div>
              
              <!-- Screen -->
              <div class="flex-1 w-full relative overflow-hidden" :class="isDarkMode ? 'bg-slate-950 text-slate-100' : 'bg-slate-50 text-slate-900'">
                <SplashScreen v-if="s.id === 'splash'" :is-dark-mode="isDarkMode" />
                <OnboardingScreen v-else-if="s.id === 'onboarding'" :is-dark-mode="isDarkMode" />
                <LoginScreen v-else-if="s.id === 'login'" :is-dark-mode="isDarkMode" />
                <SignupScreen v-else-if="s.id === 'signup'" :is-dark-mode="isDarkMode" />
                <ForgotPasswordScreen v-else-if="s.id === 'forgot'" :is-dark-mode="isDarkMode" />
                <HomeDashboardScreen v-else-if="s.id === 'home'" :is-dark-mode="isDarkMode" />
                <CategoriesScreen v-else-if="s.id === 'categories'" :is-dark-mode="isDarkMode" />
                <SearchScreen v-else-if="s.id === 'search'" :is-dark-mode="isDarkMode" />
                <ServiceListingScreen v-else-if="s.id === 'services'" :is-dark-mode="isDarkMode" />
                <ServiceDetailsScreen v-else-if="s.id === 'service-details'" :is-dark-mode="isDarkMode" />
                <ChatsListScreen v-else-if="s.id === 'chats'" :is-dark-mode="isDarkMode" />
                <ChatDetailScreen v-else-if="s.id === 'chat-detail'" :is-dark-mode="isDarkMode" />
                <WishlistScreen v-else-if="s.id === 'wishlist'" :is-dark-mode="isDarkMode" />
                <SubscriptionScreen v-else-if="s.id === 'subscription'" :is-dark-mode="isDarkMode" />
                <ProfileScreen v-else-if="s.id === 'profile'" :is-dark-mode="isDarkMode" />
                <ProviderServiceManagerScreen v-else-if="s.id === 'provider-services'" :is-dark-mode="isDarkMode" />
                <ProviderServiceCreateScreen v-else-if="s.id === 'create-service'" :is-dark-mode="isDarkMode" />
                <NotificationsScreen v-else-if="s.id === 'notifications'" :is-dark-mode="isDarkMode" />
                <SettingsScreen v-else-if="s.id === 'settings'" :is-dark-mode="isDarkMode" />
                <EmptyStatesScreen v-else-if="s.id === 'empty-states'" :is-dark-mode="isDarkMode" />
                <ErrorStatesScreen v-else-if="s.id === 'error-states'" :is-dark-mode="isDarkMode" />
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>

    <!-- Location Modal trigger integration -->
    <LocationModal v-if="isLocationModalOpen" @close="isLocationModalOpen = false" />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import SplashScreen from './mobile/SplashScreen.vue'
import OnboardingScreen from './mobile/OnboardingScreen.vue'
import LoginScreen from './mobile/LoginScreen.vue'
import SignupScreen from './mobile/SignupScreen.vue'
import ForgotPasswordScreen from './mobile/ForgotPasswordScreen.vue'
import HomeDashboardScreen from './mobile/HomeDashboardScreen.vue'
import CategoriesScreen from './mobile/CategoriesScreen.vue'
import SearchScreen from './mobile/SearchScreen.vue'
import ServiceListingScreen from './mobile/ServiceListingScreen.vue'
import ServiceDetailsScreen from './mobile/ServiceDetailsScreen.vue'
import ChatsListScreen from './mobile/ChatsListScreen.vue'
import ChatDetailScreen from './mobile/ChatDetailScreen.vue'
import WishlistScreen from './mobile/WishlistScreen.vue'
import SubscriptionScreen from './mobile/SubscriptionScreen.vue'
import ProfileScreen from './mobile/ProfileScreen.vue'
import ProviderServiceManagerScreen from './mobile/ProviderServiceManagerScreen.vue'
import ProviderServiceCreateScreen from './mobile/ProviderServiceCreateScreen.vue'
import NotificationsScreen from './mobile/NotificationsScreen.vue'
import SettingsScreen from './mobile/SettingsScreen.vue'
import EmptyStatesScreen from './mobile/EmptyStatesScreen.vue'
import ErrorStatesScreen from './mobile/ErrorStatesScreen.vue'

import LocationModal from './LocationModal.vue'

const activeScreen = ref('home')
const displayMode = ref('single')
// DEFAULT TO LIGHT MODE FIRST (User requested light mode by default)
const isDarkMode = ref(false)
const isLocationModalOpen = ref(false)

function setScreen(id) {
  activeScreen.value = id
}

const entryScreens = [
  { id: 'splash', label: 'Splash Screen' },
  { id: 'onboarding', label: 'Onboarding Tour' },
  { id: 'login', label: 'Login Screen' },
  { id: 'signup', label: 'Sign Up Register' },
  { id: 'forgot', label: 'Forgot Password OTP' }
]

const customerScreens = [
  { id: 'home', label: 'Home Dashboard' },
  { id: 'categories', label: 'Categories Grid' },
  { id: 'search', label: 'Search & Map Filter' },
  { id: 'services', label: 'Services Listing' },
  { id: 'service-details', label: 'Service Details Page' }
]

const messagingScreens = [
  { id: 'chats', label: 'Messages List' },
  { id: 'chat-detail', label: 'Direct Chat View' },
  { id: 'wishlist', label: 'Wishlist / Saved' },
  { id: 'profile', label: 'My Profile & Account' },
  { id: 'notifications', label: 'Alerts & Notifications' }
]

const workerScreens = [
  { id: 'subscription', label: 'Membership Plans' },
  { id: 'provider-services', label: 'My Offered Services' },
  { id: 'create-service', label: 'Post New Service' }
]

const systemScreens = [
  { id: 'settings', label: 'App Preferences' },
  { id: 'empty-states', label: 'Empty States' },
  { id: 'error-states', label: 'Error States' }
]

const allScreens = [
  ...entryScreens,
  ...customerScreens,
  ...messagingScreens,
  ...workerScreens,
  ...systemScreens
]
</script>

<style scoped>
.scrollbar-thin::-webkit-scrollbar {
  width: 4px;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.15);
  border-radius: 2px;
}
</style>
