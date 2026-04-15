interface ToastOptions {
  message: string;
  type?: "success" | "error" | "info" | "warning";
  duration?: number;
}

interface Toast extends ToastOptions {
  id: number;
}

const toasts = ref<Toast[]>([]);
let nextId = 0;

export const useToast = () => {
  const show = (options: ToastOptions) => {
    const id = nextId++;
    const toast: Toast = {
      id,
      message: options.message,
      type: options.type || "info",
      duration: options.duration || 4000,
    };

    toasts.value.push(toast);

    setTimeout(() => {
      remove(id);
    }, toast.duration);
  };

  const remove = (id: number) => {
    toasts.value = toasts.value.filter((t) => t.id !== id);
  };

  const success = (message: string) => show({ message, type: "success" });
  const error = (message: string) => show({ message, type: "error" });
  const info = (message: string) => show({ message, type: "info" });
  const warning = (message: string) => show({ message, type: "warning" });

  return { toasts, show, remove, success, error, info, warning };
};
