import { defineStore } from "pinia";

interface UserData {
  id: string;
  name: string;
  email: string;
  phone: string | null;
  avatar: string | null;
  role: string;
  is_active: boolean;
  worker_profile?: any;
}

export const useUserStore = defineStore("user", {
  state: () => ({
    user: null as UserData | null,
    token: null as string | null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isWorker: (state) => state.user?.role === "worker" || state.user?.role === "both",
    isUser: (state) => state.user?.role === "user" || state.user?.role === "both",
  },

  actions: {
    setAuth(user: UserData, token: string) {
      this.user = user;
      this.token = token;
      if (import.meta.client) {
        localStorage.setItem("user_token", token);
        localStorage.setItem("user_data", JSON.stringify(user));
      }
    },

    loadFromStorage() {
      if (import.meta.client) {
        const token = localStorage.getItem("user_token");
        const data = localStorage.getItem("user_data");
        if (token && data) {
          this.token = token;
          this.user = JSON.parse(data);
        }
      }
    },

    logout() {
      this.user = null;
      this.token = null;
      if (import.meta.client) {
        localStorage.removeItem("user_token");
        localStorage.removeItem("user_data");
      }
    },

    updateUser(user: UserData) {
      this.user = user;
      if (import.meta.client) {
        localStorage.setItem("user_data", JSON.stringify(user));
      }
    },
  },
});
