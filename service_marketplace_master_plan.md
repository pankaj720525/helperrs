# Service Marketplace Platform - Final Master Plan

## 1. Executive Summary
A comprehensive dual-sided marketplace platform connecting local service professionals (e.g., wiremen, plumbers, AC repairmen) with end-users. The platform utilizes advanced geospatial searching, real-time communication, and a robust subscription and approval system governed by a central administrative dashboard.

## 2. Final Technology Stack
* **Public Frontend (User Panel):** Nuxt.js (with Server-Side Rendering enabled for maximum SEO and performance)
* **Admin Panel Frontend:** Nuxt.js (configured as a Single Page Application for lightning-fast internal navigation)
* **Backend API & Core Logic:** Laravel 12
* **Database:** MySQL (utilizing spatial data types like `POINT` for lat/long distance calculations)
* **Caching & Background Queues:** Redis
* **Real-time Engine:** Laravel Reverb (Native PHP WebSockets)

---

## 3. User Roles
1.  **End-User (Customer):** Browses and searches for local services based on proximity. Can also be a registered worker.
2.  **Worker (Service Provider):** Registers their specialized services, manages their availability, and responds to customer inquiries.
3.  **Administrator:** Manages the entire platform ecosystem, approves services, handles user states, and configures global settings.

---

## 4. Platform Features

### 4.1 Public Discovery & End-User Features
* **Geospatial Search:** Users can view available services dynamically sorted by their current physical location (proximity mapping using `ST_Distance_Sphere`).
* **Public Catalog:** Unregistered users can view basic service details, worker profiles, and expected pricing ranges.
* **Restricted Contact:** Users must register and log in via Laravel Sanctum to initiate chats, view direct contact details (phone/email), or book a service.
* **Dual-Capability:** A registered worker can natively act as an end-user to hire other workers (e.g., a wireman hiring a plumber).
* **Review System:** Users can leave ratings and text reviews for services rendered.

### 4.2 Worker Dashboard Features
* **Profile Management:** Workers upload contact details (phone, email), location (latitude/longitude), and trade specialties.
* **Service Registration:** Workers create service listings detailing their work and basic expected price ranges.
* **Subscription Management:**
    * Automatic 1-Year Trial upon initial registration.
    * Dashboard view of trial expiry and active subscription status.
* **Inquiry Management:** Receive real-time notifications for incoming customer inquiries and access direct chat functionalities.
* **Preference Center:** Granular control over personal notification settings (Email, Push, Audio toggles) saved as JSON preferences.

### 4.3 Admin Panel Features
* **Service Approval Workflow:** * All new worker services default to `Pending` status.
    * Admin reviews, approves, or rejects services.
    * Mandatory text input for "Rejection Reason" to inform the worker via email.
* **User Management Module:** View, edit, and toggle active/deactive status of any user or worker with confirmation prompts.
* **Category Management Module:** Add, edit, or remove service categories (e.g., Plumber, Electrician, Cleaning).
* **Review Management Module:** Moderate customer reviews for compliance.
* **System Logs:** Audit trail tracking all critical admin actions (approvals, user deactivations).
* **Subscription Control:** Ability to manually adjust subscription pricing, extend worker plans, or override expiration dates.

---

## 5. Advanced Systems & Architecture

### 5.1 Real-Time Notification & Audio System
* **WebSockets:** Utilizes Laravel Echo and Laravel Reverb to push real-time alerts to the specific user's private channel without page refreshes.
* **Audio Alerts:** Integrated browser audio triggers for urgent updates (e.g., new chat inquiry, service approval), wrapped in browser-safe logic.
* **Header Badges:** Unread notification counters dynamically update in the user's dashboard header.

### 5.2 Hierarchical Notification Control (The Gatekeeper)
A three-tier permission system governing all outbound communications (Email and WebSockets):
1.  **Global Master Switch (Admin):** Admins can disable all outgoing emails or push notifications system-wide.
2.  **Admin User-Override (Admin):** Admins can penalize or mute specific users by revoking their push/email privileges directly from the Admin Panel.
3.  **User Preferences (Worker/User):** If not overridden by the Admin, users can toggle their own preferences for emails, push alerts, and audio alerts.

### 5.3 Asynchronous Processing
* All heavy tasks, such as sending Emails (via SMTP) for service approvals/rejections and processing subscription expirations, are dispatched as Jobs to Redis queues to maintain a lightning-fast UI.

### 5.4 Security Measures
* **Authentication:** Laravel Sanctum handles secure token and cookie-based sessions for the Nuxt apps.
* **Content Security Policy (CSP):** Implemented via Laravel nonces to prevent XSS attacks.
* **Rate Limiting:** Strict throttling on authentication and chat endpoints.
* **Data Obfuscation:** Precise lat/long coordinates are kept backend-only; the frontend only receives calculated distance strings (e.g., "5 km away").
