/**
 * Admin theme composable — dark/light mode with localStorage persistence.
 * Applies .admin-light class to <html> for admin panel theming.
 */
export const useAdminTheme = () => {
  const isDark = useState<boolean>('admin-theme-dark', () => true);

  const applyTheme = (dark: boolean) => {
    if (import.meta.server) return;
    const html = document.documentElement;
    if (dark) {
      html.classList.remove('admin-light');
    } else {
      html.classList.add('admin-light');
    }
    localStorage.setItem('admin-theme', dark ? 'dark' : 'light');
  };

  const initTheme = () => {
    if (import.meta.server) return;
    const saved = localStorage.getItem('admin-theme');
    const dark = saved ? saved === 'dark' : true;
    isDark.value = dark;
    applyTheme(dark);
  };

  const toggleTheme = () => {
    isDark.value = !isDark.value;
    applyTheme(isDark.value);
  };

  return { isDark, toggleTheme, initTheme };
};
