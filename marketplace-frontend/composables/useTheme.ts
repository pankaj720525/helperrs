/**
 * Theme composable — toggles dark/light mode.
 * Persists preference in localStorage and applies class to <html>.
 */
export const useTheme = () => {
  const isDark = useState<boolean>('theme-dark', () => true);

  const applyTheme = (dark: boolean) => {
    if (import.meta.server) return;
    const html = document.documentElement;
    if (dark) {
      html.classList.add('dark');
      html.classList.remove('light');
    } else {
      html.classList.add('light');
      html.classList.remove('dark');
    }
    localStorage.setItem('theme', dark ? 'dark' : 'light');
  };

  const initTheme = () => {
    if (import.meta.server) return;
    const saved = localStorage.getItem('theme');
    // Default: dark mode
    const dark = saved ? saved === 'dark' : true;
    isDark.value = dark;
    applyTheme(dark);
  };

  const toggleTheme = () => {
    isDark.value = !isDark.value;
    applyTheme(isDark.value);
  };

  return { isDark, toggleTheme, initTheme, applyTheme };
};
