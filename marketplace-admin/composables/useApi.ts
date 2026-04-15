export const useApi = () => {
  const config = useRuntimeConfig();
  const authStore = useAuthStore();

  const apiFetch = async <T>(
    endpoint: string,
    options: Record<string, any> = {}
  ): Promise<T> => {
    const headers: Record<string, string> = {
      Accept: "application/json",
      ...((options.headers as Record<string, string>) || {}),
    };

    if (authStore.token) {
      headers.Authorization = `Bearer ${authStore.token}`;
    }

    // Don't set Content-Type for FormData (browser handles it)
    if (!(options.body instanceof FormData)) {
      headers["Content-Type"] = "application/json";
    }

    try {
      const response = await $fetch<T>(`${config.public.apiBase}${endpoint}`, {
        ...options,
        headers,
      });
      return response;
    } catch (error: any) {
      // Handle 401 — token expired
      if (error?.response?.status === 401) {
        authStore.logout();
        navigateTo("/login");
      }
      throw error;
    }
  };

  return {
    get: <T>(endpoint: string, params?: Record<string, any>) =>
      apiFetch<T>(endpoint, { method: "GET", params }),

    post: <T>(endpoint: string, body?: any) =>
      apiFetch<T>(endpoint, { method: "POST", body }),

    put: <T>(endpoint: string, body?: any) =>
      apiFetch<T>(endpoint, { method: "PUT", body }),

    delete: <T>(endpoint: string) =>
      apiFetch<T>(endpoint, { method: "DELETE" }),

    upload: <T>(endpoint: string, formData: FormData) =>
      apiFetch<T>(endpoint, { method: "POST", body: formData }),
  };
};
