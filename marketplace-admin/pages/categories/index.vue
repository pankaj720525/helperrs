<template>
  <div class="space-y-6">
    <!-- Page Header with Breadcrumbs -->
    <UiPageHeader
      title="Service Categories"
      description="Organize marketplace offerings into structured service categories and icons."
    >
      <template #actions>
        <button
          @click="openCreateModal"
          class="px-4 py-2 rounded-xl bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition-all shadow-xs"
        >
          + Add Category
        </button>
      </template>
    </UiPageHeader>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="cat in categories"
        :key="cat.id"
        class="bg-white dark:bg-[#1E293B] rounded-2xl p-5 border border-[#EAEDF1] dark:border-[#334155] shadow-sm hover:shadow-md transition-all duration-300 group"
      >
        <div class="flex items-start justify-between mb-3">
          <div class="flex items-center gap-3">
            <span class="text-2xl w-10 h-10 rounded-xl bg-[#EEF2FF] dark:bg-indigo-950/60 flex items-center justify-center flex-shrink-0 shadow-xs">{{ iconEmoji(cat.icon) }}</span>
            <div>
              <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ cat.name }}</h3>
              <p class="text-[11px] text-slate-400">/{{ cat.slug }}</p>
            </div>
          </div>
          <UiStatusBadge :status="cat.is_active ? 'active' : 'inactive'" />
        </div>
        <p class="text-xs text-slate-600 dark:text-slate-400 mb-4 line-clamp-2">{{ cat.description || 'No description' }}</p>
        <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-3">
          <span class="text-[11px] font-medium text-slate-500">{{ cat.services_count || 0 }} services</span>
          <div class="flex gap-3">
            <button @click="openEditModal(cat)" class="text-xs font-semibold text-primary hover:underline">Edit</button>
            <button @click="deleteCategory(cat)" class="text-xs font-semibold text-rose-600 hover:underline">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="!categories.length" class="bg-white dark:bg-[#1E293B] rounded-2xl p-10 text-center text-slate-400 text-xs border border-[#EAEDF1] dark:border-[#334155]">
      No categories yet. Create your first category!
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
          <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Name</label>
          <input v-model="form.name" type="text" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs" placeholder="e.g. Plumbing" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Icon (emoji or icon code)</label>
          <input v-model="form.icon" type="text" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs" placeholder="🔧" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Description</label>
          <textarea v-model="form.description" rows="3" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs resize-none" placeholder="Category description..." />
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Sort Order</label>
          <input v-model.number="form.sort_order" type="number" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 text-xs" placeholder="0" />
        </div>
      </div>
    </UiModal>
  </div>
</template>

<script setup lang="ts">
definePageMeta({ middleware: "auth" });

const api = useApi();
const toast = useToast();

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
  if (!icon.startsWith('mdi:')) return icon;
  return '📁';
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
