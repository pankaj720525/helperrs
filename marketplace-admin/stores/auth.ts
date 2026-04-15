import { defineStore } from "pinia";

interface AdminUser {
  id: string;
  name: string;
  email: string;
}

export const useAuthStore = defineStore("auth", {
  state: () => ({
    admin: null as AdminUser | null,
    token: null as string | null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    setAuth(admin: AdminUser, token: string) {
      this.admin = admin;
      this.token = token;
      if (import.meta.client) {
        localStorage.setItem("admin_token", token);
        localStorage.setItem("admin_user", JSON.stringify(admin));
      }
    },

    loadFromStorage() {
      if (import.meta.client) {
        const token = localStorage.getItem("admin_token");
        const user = localStorage.getItem("admin_user");
        if (token && user) {
          this.token = token;
          this.admin = JSON.parse(user);
        }
      }
    },

    logout() {
      this.admin = null;
      this.token = null;
      if (import.meta.client) {
        localStorage.removeItem("admin_token");
        localStorage.removeItem("admin_user");
      }
    },
  },
});
