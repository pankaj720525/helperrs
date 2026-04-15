<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-heading font-bold text-white">Categories</h2>
      <button
        @click="openCreateModal"
        class="px-4 py-2.5 rounded-xl gradient-primary text-white text-sm font-medium hover:shadow-glow transition-all"
      >
        + Add Category
      </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="cat in categories"
        :key="cat.id"
        class="glass rounded-2xl p-5 animate-fade-up group hover:border-primary/20 transition-all duration-300"
      >
        <div class="flex items-start justify-between mb-3">
          <div class="flex items-center gap-3">
            <span class="text-2xl w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">{{ iconEmoji(cat.icon) }}</span>
            <div>
              <h3 class="font-semibold text-white">{{ cat.name }}</h3>
              <p class="text-xs text-slate-400">/{{ cat.slug }}</p>
            </div>
          </div>
          <UiStatusBadge :status="cat.is_active ? 'active' : 'inactive'" />
        </div>
        <p class="text-sm text-slate-400 mb-4 line-clamp-2">{{ cat.description || 'No description' }}</p>
        <div class="flex items-center justify-between">
          <span class="text-xs text-slate-500">{{ cat.services_count || 0 }} services</span>
          <div class="flex gap-2">
            <button @click="openEditModal(cat)" class="text-xs text-primary-light hover:underline">Edit</button>
            <button @click="deleteCategory(cat)" class="text-xs text-danger hover:underline">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="!categories.length" class="glass rounded-2xl p-10 text-center text-slate-500">
      No categories yet. Create your first one!
    </div>

    <!-- Create/Edit Modal -->
    <UiModal
      v-model="modalOpen"
      :title="editingId ? 'Edit Category' : 'Create Category'"
      :confirm-label="editingId ? 'Save Changes' : 'Create'"
      @confirm="saveCategory"
    >
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-1.5">Name</label>
          <input v-model="form.name" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" placeholder="e.g. Plumber" />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-1.5">Icon (emoji or Iconify)</label>
          <input v-model="form.icon" type="text" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" placeholder="🔧" />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-1.5">Description</label>
          <textarea v-model="form.description" rows="3" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm resize-none" placeholder="Category description..." />
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-300 mb-1.5">Sort Order</label>
          <input v-model.number="form.sort_order" type="number" class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-primary/50 text-sm" placeholder="0" />
        </div>
      </div>
    </UiModal>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const api = useApi();
const toast = useToast();

// Map Iconify/mdi icon names → emoji equivalents
const ICON_MAP: Record<string, string> = {
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

const iconEmoji = (icon: string) => {
  if (!icon) return '📁';
  if (ICON_MAP[icon]) return ICON_MAP[icon];
  // If it's already an emoji (not mdi: prefixed), return as-is
  if (!icon.startsWith('mdi:')) return icon;
  return '📁'; // fallback
};

const categories = ref<any[]>([]);
const modalOpen = ref(false);
const editingId = ref<string | null>(null);
const form = reactive({ name: "", icon: "", description: "", sort_order: 0 });

const loadCategories = async () => {
  try {
    const data = await api.get<any>("/admin/categories", { per_page: 50 });
    categories.value = data.categories || [];
  } catch { toast.error("Failed to load categories."); }
};

const openCreateModal = () => {
  editingId.value = null;
  Object.assign(form, { name: "", icon: "", description: "", sort_order: 0 });
  modalOpen.value = true;
};

const openEditModal = (cat: any) => {
  editingId.value = cat.id;
  Object.assign(form, { name: cat.name, icon: cat.icon || "", description: cat.description || "", sort_order: cat.sort_order || 0 });
  modalOpen.value = true;
};

const saveCategory = async () => {
  try {
    if (editingId.value) {
      await api.put(`/admin/categories/${editingId.value}`, form);
      toast.success("Category updated.");
    } else {
      await api.post("/admin/categories", form);
      toast.success("Category created.");
    }
    modalOpen.value = false;
    loadCategories();
  } catch (error: any) {
    toast.error(error?.data?.message || "Failed to save.");
  }
};

const deleteCategory = async (cat: any) => {
  if (!confirm(`Delete "${cat.name}"?`)) return;
  try {
    await api.delete(`/admin/categories/${cat.id}`);
    toast.success("Category deleted.");
    loadCategories();
  } catch (error: any) {
    toast.error(error?.data?.message || "Cannot delete category with services.");
  }
};

onMounted(loadCategories);
</script>
