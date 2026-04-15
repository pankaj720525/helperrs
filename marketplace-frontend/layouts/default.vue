<template>
  <div class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <PublicNavbar />

    <!-- Page Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <PublicFooter />
  </div>
</template>
