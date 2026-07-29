import tailwindcss from "@tailwindcss/vite";

export default defineNuxtConfig({
  compatibilityDate: "2025-05-10",

  ssr: false, // SPA mode for admin panel

  css: ["~/assets/css/main.css"],

  vite: {
    plugins: [tailwindcss()],
  },

  modules: ["@pinia/nuxt"],

  app: {
    head: {
      title: "Service Market",
      meta: [
        { charset: "utf-8" },
        { name: "viewport", content: "width=device-width, initial-scale=1" },
        {
          name: "description",
          content: "Service Marketplace Administration Dashboard",
        },
      ],
      link: [
        {
          rel: "stylesheet",
          href: "https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap",
        },
      ],
    },
  },

  spaLoadingTemplate: true,

  runtimeConfig: {
    public: {
      apiBase: "http://localhost:8000/api",
    },
  },

  devtools: { enabled: true },
});
