<template>
  <footer class="croma-footer">

    <!-- ── Top Feature Banner ──────────────────────────── -->
    <div class="ft-banner">
      <div class="ft-banner-inner">
        <div class="ft-feature">
          <div class="ft-feat-icon">
            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <div>
            <h5 class="ft-feat-title">100% Service Guarantee</h5>
            <p class="ft-feat-sub">30-day warranty on all repairs & bookings</p>
          </div>
        </div>

        <div class="ft-feature">
          <div class="ft-feat-icon">
            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
          </div>
          <div>
            <h5 class="ft-feat-title">Verified Professionals</h5>
            <p class="ft-feat-sub">Background checked & trained technicians</p>
          </div>
        </div>

        <div class="ft-feature">
          <div class="ft-feat-icon">
            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div>
            <h5 class="ft-feat-title">Transparent Pricing</h5>
            <p class="ft-feat-sub">Fixed rates with no hidden doorstep fees</p>
          </div>
        </div>

        <div class="ft-feature">
          <div class="ft-feat-icon">
            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div>
            <h5 class="ft-feat-title">24x7 Customer Support</h5>
            <p class="ft-feat-sub">Instant live chat & phone resolution</p>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Main Footer Columns ─────────────────────────── -->
    <div class="ft-main">
      <div class="ft-main-inner">

        <!-- Col 1: Brand Info -->
        <div class="ft-col ft-col-brand">
          <div class="ft-brand-logo">
            <div class="ft-logo-icon">SM</div>
            <span class="ft-logo-text">ServiceMarket</span>
          </div>
          <p class="ft-brand-desc">
            India's most trusted local service marketplace. Connecting homeowners with background-verified experts for all repair, maintenance, and cleaning needs.
          </p>
          <div class="ft-rating-badge">
            <span class="ft-rb-star">★ 4.9/5</span>
            <span class="ft-rb-text">based on 25,000+ customer reviews</span>
          </div>
        </div>

        <!-- Col 2: Serviceable Cities -->
        <div class="ft-col">
          <h4 class="ft-col-title">Top Cities</h4>
          <ul class="ft-list">
            <li><button type="button" @click="selectCity('Ahmedabad')" class="ft-link-btn active">Ahmedabad (Main Hub)</button></li>
            <li><button type="button" @click="selectCity('Surat')" class="ft-link-btn">Surat</button></li>
            <li><button type="button" @click="selectCity('Vadodara')" class="ft-link-btn">Vadodara</button></li>
            <li><button type="button" @click="selectCity('Mumbai')" class="ft-link-btn">Mumbai</button></li>
            <li><button type="button" @click="selectCity('Delhi NCR')" class="ft-link-btn">Delhi NCR</button></li>
            <li><button type="button" @click="selectCity('Bengaluru')" class="ft-link-btn">Bengaluru</button></li>
            <li><button type="button" @click="selectCity('Pune')" class="ft-link-btn">Pune</button></li>
            <li><button type="button" @click="selectCity('Hyderabad')" class="ft-link-btn">Hyderabad</button></li>
          </ul>
        </div>

        <!-- Col 3: Popular Services -->
        <div class="ft-col">
          <h4 class="ft-col-title">Popular Services</h4>
          <ul class="ft-list">
            <li><NuxtLink to="/services?category=ac-repair" class="ft-link">AC Installation & Repair</NuxtLink></li>
            <li><NuxtLink to="/services?category=plumbing" class="ft-link">Plumbing & Pipe Repair</NuxtLink></li>
            <li><NuxtLink to="/services?category=electrician" class="ft-link">Electrical Wiring & Switchboard</NuxtLink></li>
            <li><NuxtLink to="/services?category=cleaning" class="ft-link">Full House Deep Cleaning</NuxtLink></li>
            <li><NuxtLink to="/services?category=painting" class="ft-link">Wall Painting & Waterproofing</NuxtLink></li>
            <li><NuxtLink to="/services?category=appliance" class="ft-link">Washing Machine & RO Repair</NuxtLink></li>
            <li><NuxtLink to="/services?category=pest-control" class="ft-link">Pest Control & Disinfection</NuxtLink></li>
          </ul>
        </div>

        <!-- Col 4: Customer Support -->
        <div class="ft-col">
          <h4 class="ft-col-title">Customer Support</h4>
          <ul class="ft-list">
            <li><NuxtLink to="/chats" class="ft-link">Live Worker Chat</NuxtLink></li>
            <li><NuxtLink to="/dashboard" class="ft-link">Track My Bookings</NuxtLink></li>
            <li><NuxtLink to="/register" class="ft-link">Become a Service Provider</NuxtLink></li>
            <li><a href="#" class="ft-link">Help & FAQs</a></li>
            <li><a href="#" class="ft-link">Safety & Quality Guidelines</a></li>
            <li><a href="#" class="ft-link">Terms & Conditions</a></li>
            <li><a href="#" class="ft-link">Privacy Policy</a></li>
          </ul>
        </div>

        <!-- Col 5: Newsletter & App Download -->
        <div class="ft-col ft-col-news">
          <h4 class="ft-col-title">Get Service Updates</h4>
          <p class="ft-news-sub">Subscribe to get instant notifications and service offers in {{ currentLocation.city }}.</p>

          <form @submit.prevent="handleSubscribe" class="ft-news-form">
            <input
              v-model="emailInput"
              type="email"
              placeholder="Enter your email address"
              required
              class="ft-news-input"
            />
            <button type="submit" class="ft-news-btn">Subscribe</button>
          </form>
          <p v-if="subscribed" class="ft-news-success">✅ Subscribed to service updates!</p>

          <div class="ft-apps">
            <h5 class="ft-apps-title">Download Mobile App</h5>
            <div class="ft-apps-row">
              <a href="#" class="ft-app-btn">
                <span>App Store</span>
              </a>
              <a href="#" class="ft-app-btn">
                <span>Google Play</span>
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ── Bottom Bar: Payment Badges & Copyright ───────── -->
    <div class="ft-bottom">
      <div class="ft-bottom-inner">

        <div class="ft-active-loc">
          <span class="ft-al-dot" />
          <span>Currently Servicing: <strong>{{ currentLocation.formatted }}</strong></span>
          <button type="button" @click="openModal" class="ft-chg-loc">Change Location</button>
        </div>

        <!-- Payment icons -->
        <div class="ft-payments">
          <span class="ft-pay-text">100% Safe Payments:</span>
          <span class="ft-pay-badge">UPI / GPay</span>
          <span class="ft-pay-badge">RuPay</span>
          <span class="ft-pay-badge">VISA</span>
          <span class="ft-pay-badge">MasterCard</span>
          <span class="ft-pay-badge">NetBanking</span>
          <span class="ft-pay-badge">Pay on Service</span>
        </div>

        <div class="ft-copy-row">
          <p class="ft-copy">© {{ new Date().getFullYear() }} ServiceMarket. All rights reserved.</p>
          <div class="ft-legal-links">
            <a href="#" class="ft-ll">Privacy</a>
            <a href="#" class="ft-ll">Terms</a>
            <a href="#" class="ft-ll">Sitemap</a>
            <a href="#" class="ft-ll">Security</a>
          </div>
        </div>

      </div>
    </div>

  </footer>
</template>

<script setup lang="ts">
const { currentLocation, selectCity, openModal } = useUserLocation();

const emailInput = ref('');
const subscribed = ref(false);

const handleSubscribe = () => {
  if (!emailInput.value) return;
  subscribed.value = true;
  emailInput.value = '';
  setTimeout(() => { subscribed.value = false; }, 5000);
};
</script>

<style scoped>
.croma-footer {
  background: #f8fafc; border-top: 1px solid #e2e8f0;
  color: #475569; font-size: 0.875rem; margin-top: 4rem;
}
:global(html.dark) .croma-footer {
  background: #090d16; color: #94a3b8; border-top-color: rgba(255,255,255,0.08);
}

/* ── Top Feature Banner ──────────────────────────── */
.ft-banner {
  background: #ffffff; border-bottom: 1px solid #e2e8f0;
  padding: 1.75rem 0;
}
:global(html.dark) .ft-banner { background: rgba(30, 41, 59, 0.5); border-bottom-color: rgba(255,255,255,0.06); }

.ft-banner-inner {
  max-width: 80rem; margin: 0 auto; padding: 0 1.25rem;
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;
}
@media (max-width: 1024px) { .ft-banner-inner { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px) { .ft-banner-inner { grid-template-columns: 1fr; } }

.ft-feature { display: flex; align-items: flex-start; gap: 0.875rem; }
.ft-feat-icon {
  width: 2.75rem; height: 2.75rem; border-radius: 0.75rem;
  background: #fef2f5; border: 1px solid #fbc0d0;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
:global(html.dark) .ft-feat-icon { background: rgba(178,5,55,0.15); border-color: rgba(178,5,55,0.3); }

.ft-feat-title { font-family: 'Outfit', sans-serif; font-size: 0.9375rem; font-weight: 700; color: #0f172a; margin: 0 0 2px; }
:global(html.dark) .ft-feat-title { color: #fff; }
.ft-feat-sub { font-size: 0.78rem; color: #64748b; margin: 0; line-height: 1.35; }
:global(html.dark) .ft-feat-sub { color: #94a3b8; }

/* ── Main Columns ────────────────────────────────── */
.ft-main { padding: 3.5rem 0; }
.ft-main-inner {
  max-width: 80rem; margin: 0 auto; padding: 0 1.25rem;
  display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 2fr; gap: 2rem;
}
@media (max-width: 1200px) { .ft-main-inner { grid-template-columns: 1fr 1fr 1fr; } }
@media (max-width: 640px) { .ft-main-inner { grid-template-columns: 1fr; } }

.ft-col-brand { padding-right: 1rem; }
.ft-brand-logo { display: flex; align-items: center; gap: 0.625rem; margin-bottom: 1rem; }
.ft-logo-icon {
  width: 2.25rem; height: 2.25rem; border-radius: 0.625rem;
  background: linear-gradient(135deg, #B20537, #F43F5E);
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-weight: 800; font-size: 0.875rem;
}
.ft-logo-text { font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 700; color: #0f172a; }
:global(html.dark) .ft-logo-text { color: #fff; }

.ft-brand-desc { font-size: 0.8125rem; color: #64748b; line-height: 1.6; margin-bottom: 1rem; }
:global(html.dark) .ft-brand-desc { color: #94a3b8; }

.ft-rating-badge {
  display: inline-flex; align-items: center; gap: 0.5rem;
  padding: 6px 12px; border-radius: 9999px; background: #fffbeb;
  border: 1px solid #fde68a; margin-bottom: 1.25rem;
}
:global(html.dark) .ft-rating-badge { background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.3); }
.ft-rb-star { color: #d97706; font-weight: 700; font-size: 0.8125rem; }
:global(html.dark) .ft-rb-star { color: #f59e0b; }
.ft-rb-text { font-size: 0.72rem; color: #475569; }
:global(html.dark) .ft-rb-text { color: #cbd5e1; }

.ft-col-title { font-family: 'Outfit', sans-serif; font-size: 0.9375rem; font-weight: 700; color: #0f172a; margin: 0 0 1.25rem; }
:global(html.dark) .ft-col-title { color: #fff; }

.ft-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.625rem; }
.ft-link { color: #64748b; text-decoration: none; font-size: 0.8125rem; transition: color 0.15s; font-weight: 500; }
:global(html.dark) .ft-link { color: #94a3b8; }
.ft-link:hover { color: #b20537; }

.ft-link-btn {
  background: none; border: none; padding: 0; color: #64748b;
  font-size: 0.8125rem; cursor: pointer; text-align: left; transition: color 0.15s; font-weight: 500;
}
:global(html.dark) .ft-link-btn { color: #94a3b8; }
.ft-link-btn:hover, .ft-link-btn.active { color: #b20537; font-weight: 600; }

.ft-news-sub { font-size: 0.8125rem; color: #64748b; line-height: 1.5; margin-bottom: 1rem; }
:global(html.dark) .ft-news-sub { color: #94a3b8; }

.ft-news-form { display: flex; gap: 0.5rem; margin-bottom: 0.5rem; }
.ft-news-input {
  flex: 1; padding: 0.625rem 0.875rem; border-radius: 0.625rem;
  background: #ffffff; border: 1.5px solid #cbd5e1;
  color: #0f172a; font-size: 0.8125rem; outline: none;
}
:global(html.dark) .ft-news-input { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.12); color: #fff; }
.ft-news-input:focus { border-color: #B20537; }

.ft-news-btn {
  padding: 0 1rem; border-radius: 0.625rem; border: none;
  background: #B20537; color: #fff; font-weight: 700; font-size: 0.8125rem;
  cursor: pointer; transition: background 0.15s;
}
.ft-news-btn:hover { background: #8A042B; }
.ft-news-success { font-size: 0.78rem; color: #10b981; margin: 0; font-weight: 600; }

.ft-apps { margin-top: 1.5rem; }
.ft-apps-title { font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin: 0 0 0.625rem; }
.ft-apps-row { display: flex; gap: 0.5rem; }
.ft-app-btn {
  flex: 1; padding: 0.5rem 0.75rem; border-radius: 0.625rem;
  background: #ffffff; border: 1px solid #cbd5e1;
  color: #0f172a; font-size: 0.78rem; font-weight: 600; text-decoration: none;
  display: flex; align-items: center; justify-content: center; gap: 4px;
  transition: background 0.15s;
}
:global(html.dark) .ft-app-btn { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.12); color: #fff; }
.ft-app-btn:hover { background: #f1f5f9; }

/* ── Bottom Bar ──────────────────────────────────── */
.ft-bottom { background: #ffffff; border-top: 1px solid #e2e8f0; padding: 1.5rem 0; }
:global(html.dark) .ft-bottom { background: #05080f; border-top-color: rgba(255,255,255,0.06); }
.ft-bottom-inner {
  max-width: 80rem; margin: 0 auto; padding: 0 1.25rem;
  display: flex; flex-direction: column; gap: 1.25rem;
}

.ft-active-loc {
  display: flex; align-items: center; gap: 0.625rem; flex-wrap: wrap;
  padding: 0.625rem 1rem; border-radius: 0.75rem;
  background: #ecfdf5; border: 1px solid #a7f3d0;
  color: #047857; font-size: 0.8125rem;
}
:global(html.dark) .ft-active-loc { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.25); color: #10b981; }
.ft-al-dot { width: 8px; height: 8px; border-radius: 50%; background: #10b981; flex-shrink: 0; }
.ft-active-loc strong { color: #0f172a; }
:global(html.dark) .ft-active-loc strong { color: #fff; }

.ft-chg-loc {
  margin-left: auto; background: #ffffff; border: 1px solid #a7f3d0;
  color: #047857; font-weight: 700; font-size: 0.75rem; padding: 4px 10px;
  border-radius: 9999px; cursor: pointer; transition: background 0.15s;
}
:global(html.dark) .ft-chg-loc { background: rgba(255,255,255,0.08); border-color: transparent; color: #fff; }
.ft-chg-loc:hover { background: #B20537; color: #fff; border-color: #B20537; }

.ft-payments { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; font-size: 0.78rem; color: #64748b; }
.ft-pay-text { font-weight: 600; color: #475569; }
.ft-pay-badge {
  padding: 3px 8px; border-radius: 4px;
  background: #f1f5f9; border: 1px solid #e2e8f0;
  color: #334155; font-size: 0.72rem; font-weight: 600;
}
:global(html.dark) .ft-pay-badge { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); color: #cbd5e1; }

.ft-copy-row {
  display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;
  border-top: 1px solid #e2e8f0; padding-top: 1rem; font-size: 0.78rem; color: #64748b;
}
:global(html.dark) .ft-copy-row { border-top-color: rgba(255,255,255,0.05); color: #64748b; }
.ft-copy { margin: 0; }
.ft-legal-links { display: flex; gap: 1rem; }
.ft-ll { color: #64748b; text-decoration: none; transition: color 0.15s; }
.ft-ll:hover { color: #b20537; }
</style>
