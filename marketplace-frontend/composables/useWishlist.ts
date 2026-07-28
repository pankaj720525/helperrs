/**
 * Wishlist management composable.
 * Stores bookmarked service IDs in localStorage.
 */

export const useWishlist = () => {
  const wishlistedIds = useState<string[]>('wishlisted-ids', () => []);
  const toastMessage = useState<string | null>('wishlist-toast', () => null);

  const initWishlist = () => {
    if (import.meta.server) return;
    try {
      const saved = localStorage.getItem('user_wishlist');
      if (saved) {
        wishlistedIds.value = JSON.parse(saved);
      }
    } catch {
      wishlistedIds.value = [];
    }
  };

  const isWishlisted = (serviceId: string | number) => {
    return wishlistedIds.value.includes(String(serviceId));
  };

  const toggleWishlist = (serviceId: string | number, serviceTitle?: string) => {
    const id = String(serviceId);
    const index = wishlistedIds.value.indexOf(id);
    if (index >= 0) {
      wishlistedIds.value.splice(index, 1);
      showToast(`Removed "${serviceTitle || 'Service'}" from favorites`);
    } else {
      wishlistedIds.value.push(id);
      showToast(`Added "${serviceTitle || 'Service'}" to favorites ❤️`);
    }

    if (import.meta.client) {
      localStorage.setItem('user_wishlist', JSON.stringify(wishlistedIds.value));
    }
  };

  const showToast = (msg: string) => {
    toastMessage.value = msg;
    setTimeout(() => {
      if (toastMessage.value === msg) {
        toastMessage.value = null;
      }
    }, 2800);
  };

  const wishlistCount = computed(() => wishlistedIds.value.length);

  return {
    wishlistedIds,
    wishlistCount,
    toastMessage,
    initWishlist,
    isWishlisted,
    toggleWishlist
  };
};
