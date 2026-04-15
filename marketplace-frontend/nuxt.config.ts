import tailwindcss from "@tailwindcss/vite";

export default defineNuxtConfig({
  compatibilityDate: "2025-05-10",

  ssr: true, // SSR enabled for SEO

  css: ["~/assets/css/main.css"],

  vite: {
    plugins: [tailwindcss()],
  },

  modules: ["@pinia/nuxt"],

  app: {
    head: {
      title: "Service Marketplace — Find Local Professionals Near You",
      meta: [
        { charset: "utf-8" },
        { name: "viewport", content: "width=device-width, initial-scale=1" },
        {
          name: "description",
          content:
            "Find trusted local service professionals — plumbers, electricians, AC repairmen, and more. Search by location and connect instantly.",
        },
        { name: "keywords", content: "service marketplace, local services, plumber, electrician, AC repair, handyman" },
        { property: "og:title", content: "Service Marketplace — Find Local Professionals" },
        { property: "og:description", content: "Connect with trusted local service professionals near you." },
        { property: "og:type", content: "website" },
      ],
      link: [
        {
          rel: "stylesheet",
          href: "https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap",
        },
      ],
    },
  },

  runtimeConfig: {
    public: {
      apiBase: "http://localhost:8000/api",
    },
  },

  devtools: { enabled: true },
});
