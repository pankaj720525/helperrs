/**
 * Shared emoji icon map for Iconify/MDI icon names used in category seeds.
 * Allows displaying icons without installing @iconify/vue.
 */
const ICON_EMOJI_MAP: Record<string, string> = {
  'mdi:lightning-bolt': '⚡',
  'mdi:pipe-wrench': '🔧',
  'mdi:air-conditioner': '❄️',
  'mdi:hammer-screwdriver': '🪛',
  'mdi:format-paint': '🎨',
  'mdi:broom': '🧹',
  'mdi:bug': '🪲',
  'mdi:washing-machine': '🫧',
  'mdi:flower': '🌸',
  'mdi:shield-home': '🛡️',
  'mdi:sofa': '🛋️',
  'mdi:truck': '🚚',
};

export const useIconEmoji = () => {
  const iconEmoji = (icon: string, fallback = '📦'): string => {
    if (!icon) return fallback;
    if (ICON_EMOJI_MAP[icon]) return ICON_EMOJI_MAP[icon];
    // Already an emoji or unknown mdi: string → use fallback for mdi:
    if (icon.startsWith('mdi:')) return fallback;
    return icon;
  };

  return { iconEmoji };
};
