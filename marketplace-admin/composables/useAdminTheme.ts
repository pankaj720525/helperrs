/**
 * Admin theme composable — defaults to Light Mode (white design).
 * Toggles .dark class on <html> only when explicitly activated by user.
 */
export const useAdminTheme = () => {
  const isDark = useState<boolean>('admin-theme-dark', () => false);

  const applyTheme = (dark: boolean) => {
    if (import.meta.server) return;
    const html = document.documentElement;
    if (dark) {
      html.classList.add('dark');
      html.classList.add('admin-dark');
      html.classList.remove('admin-light');
    } else {
      html.classList.remove('dark');
      html.classList.remove('admin-dark');
      html.classList.add('admin-light');
    }
  };

  const initTheme = () => {
    if (import.meta.server) return;
    const saved = localStorage.getItem('admin-theme');
    // Default mode is LIGHT (false) unless explicitly saved as 'dark'
    const dark = saved === 'dark';
    isDark.value = dark;
    applyTheme(dark);
  };

  const toggleTheme = () => {
    isDark.value = !isDark.value;
    applyTheme(isDark.value);
    if (!import.meta.server) {
      localStorage.setItem('admin-theme', isDark.value ? 'dark' : 'light');
    }
  };

  return { isDark, toggleTheme, initTheme };
};
