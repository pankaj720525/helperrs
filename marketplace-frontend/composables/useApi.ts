export const useApi = () => {
  const config = useRuntimeConfig();

  const getToken = () => {
    if (import.meta.client) {
      return localStorage.getItem("user_token");
    }
    return null;
  };

  const apiFetch = async <T>(
    endpoint: string,
    options: Record<string, any> = {}
  ): Promise<T> => {
    const headers: Record<string, string> = {
      Accept: "application/json",
      ...((options.headers as Record<string, string>) || {}),
    };

    const token = getToken();
    if (token) {
      headers.Authorization = `Bearer ${token}`;
    }

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
      if (error?.response?.status === 401 && import.meta.client) {
        localStorage.removeItem("user_token");
        localStorage.removeItem("user_data");
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
  };
};
