export const useDefaultImage = () => {
  const DEFAULT_IMAGES: Record<string, string> = {
    'ac-repair': 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=800&q=80',
    'electrician': 'https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=800&q=80',
    'plumbing': 'https://images.unsplash.com/photo-1585704032915-c3400ca199e7?auto=format&fit=crop&w=800&q=80',
    'cleaning': 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=800&q=80',
    'painting': 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?auto=format&fit=crop&w=800&q=80',
    'carpenter': 'https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=800&q=80',
    'appliance': 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80',
    'pest-control': 'https://images.unsplash.com/photo-1606857521015-7f9fcf423740?auto=format&fit=crop&w=800&q=80',
    'default': 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=800&q=80',
  };

  const getServiceImage = (service: any): string => {
    if (service?.image) return service.image;
    const catSlug = service?.category?.slug || service?.category_slug || '';
    if (catSlug && DEFAULT_IMAGES[catSlug]) return DEFAULT_IMAGES[catSlug];
    return DEFAULT_IMAGES['default'];
  };

  return { getServiceImage, DEFAULT_IMAGES };
};
