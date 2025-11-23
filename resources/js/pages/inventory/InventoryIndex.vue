<template>
    <div>
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Inventory Assets</h2>
                <p class="text-gray-500 text-sm">Manage items, serial numbers, and assignments.</p>
            </div>
            <button @click="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2">
                <span>+ Add Item</span>
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                        <th class="px-6 py-4 first:rounded-tl-xl">Kode</th>
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Assigned To</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right last:rounded-tr-xl">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="item in inventories" :key="item.id" class="hover:bg-gray-50 transition relative">
                        <!-- 1. KODE INVENTARIS (INV-XXX) -->
                        <td class="px-6 py-4 font-mono text-xs text-blue-600 font-bold">
                            {{ item.kode_inventaris }}
                        </td>

                        <!-- 2. NAMA BARANG & SN -->
                        <td class="px-6 py-4">
                            <!-- Pastikan pakai name_barang sesuai JSON -->
                            <div class="font-medium text-gray-800">{{ item.name_barang }}</div>
                            <div class="text-xs text-gray-400">{{ item.serial_number || 'No SN' }}</div>
                        </td>

                        <!-- 3. KATEGORI -->
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            {{ item.category?.name || '-' }}
                        </td>

                        <!-- 4. MEMBER (ASSIGNED TO) -->
                        <td class="px-6 py-4 text-sm">
                            <span v-if="item.member" class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-semibold">
                                {{ item.member.name }}
                            </span>
                            <span v-else class="text-gray-400 text-xs italic">Unassigned</span>
                        </td>

                        <!-- 5. STATUS (Sesuaikan warna dengan Bahasa Indonesia/Inggris) -->
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold"
                                :class="{
                                    'bg-green-100 text-green-700': item.status === 'Good' || item.status === 'Bagus',
                                    'bg-red-100 text-red-700': item.status === 'Broken' || item.status === 'Rusak',
                                    'bg-yellow-100 text-yellow-700': item.status === 'Maintenance'
                                }">
                                {{ item.status }}
                            </span>
                        </td>
                        
                        <!-- 6. ACTION -->
                        <td class="px-6 py-4 text-right relative">
                            <button @click.stop="toggleMenu(item.id)" class="p-2 rounded-full hover:bg-gray-200 border border-gray-300 text-gray-500 transition focus:outline-none bg-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM17.25 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                            </button>
                            <!-- Dropdown -->
                            <div v-if="activeMenuId === item.id" class="absolute right-0 top-8 mt-1 w-36 bg-black text-white rounded-xl shadow-xl z-50 overflow-hidden origin-top-right mr-4">
                                <div class="py-1">
                                    <button @click="openModal(item)" class="group flex items-center w-full text-left px-4 py-3 text-sm hover:bg-gray-800 transition">Edit</button>
                                    <button @click="deleteItem(item.id)" class="group flex items-center w-full text-left px-4 py-3 text-sm text-red-400 hover:bg-gray-800 hover:text-red-300 transition">Delete</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                     <tr v-if="inventories.length === 0">
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                            No data available.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- MODAL FORM -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ isEditing ? 'Edit Item' : 'Add New Item' }}</h3>
                
                <form @submit.prevent="saveInventory" class="mt-6">
                    <!-- Name Barang & Category -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name Barang <span class="text-red-500">*</span></label>
                            <input v-model="form.name_barang" type="text" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                            <select v-model="form.category_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-white" required>
                                <option value="" disabled>Select Type</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- SN & Qty -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
                            <input v-model="form.serial_number" type="text" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                            <input v-model="form.quantity" type="number" min="0" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                        </div>
                    </div>

                    <!-- Status & Dept -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select v-model="form.status" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                <!-- Sesuaikan value dengan yang ada di DB Anda -->
                                <option value="Good">Good</option>
                                <option value="Bagus">Bagus</option> 
                                <option value="Broken">Broken</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                             <input v-model="form.department" type="text" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                        </div>
                    </div>

                    <!-- Assign To -->
                    <div class="mb-4">
                         <label class="block text-sm font-medium text-gray-700 mb-1">Assign To (Optional)</label>
                         <select v-model="form.member_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                <option :value="null">-- Not Assigned --</option>
                                <option v-for="mem in members" :key="mem.id" :value="mem.id">{{ mem.name }} - {{ mem.department }}</option>
                        </select>
                    </div>

                    <!-- Specs -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Specification</label>
                        <textarea v-model="form.spesification" rows="3" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-lg text-sm font-medium text-white shadow-md transition" :class="isEditing ? 'bg-orange-600 hover:bg-orange-700' : 'bg-blue-600 hover:bg-blue-700'">
                            {{ isEditing ? 'Update Item' : 'Save Item' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, reactive } from 'vue';
import axios from 'axios';

// --- STATE ---
const inventories = ref([]);
const categories = ref([]);
const members = ref([]);   
const showModal = ref(false);
const activeMenuId = ref(null);
const isEditing = ref(false);
const editingId = ref(null);

// Form state (sesuai field DB)
const form = reactive({
    name_barang: '',
    category_id: '',
    member_id: null,
    serial_number: '',
    status: 'Good',
    quantity: 1,
    department: '',
    spesification: ''
});

// --- FETCH DATA ---
const fetchInventories = async () => {
    try {
        const res = await axios.get('/api/inventory');
        
        // DEBUG: Cek di Console Browser (F12)
        console.log("DATA DARI API:", res.data); 

        // PENTING: Ambil .data.data karena Postman Anda menunjukkan ada wrapper 'data'
        inventories.value = res.data.data; 
        
    } catch (e) { 
        console.error("Gagal ambil data:", e); 
    }
};

const fetchDropdowns = async () => {
    try {
        const [resCat, resMem] = await Promise.all([
            axios.get('/api/categories'),
            axios.get('/api/members')
        ]);
        // Asumsi Controller Category & Member juga sudah distandarisasi pakai 'data' wrapper
        categories.value = resCat.data.data || resCat.data;
        members.value = resMem.data.data || resMem.data;
    } catch (e) { console.error(e); }
};

// --- MODAL LOGIC ---
const openModal = (item = null) => {
    activeMenuId.value = null;
    if (item) {
        isEditing.value = true;
        editingId.value = item.id;
        
        form.name_barang = item.name_barang;
        form.category_id = item.category_id;
        form.member_id = item.member_id;
        form.serial_number = item.serial_number;
        form.status = item.status;
        form.quantity = item.quantity;
        form.department = item.department;
        form.spesification = item.spesification;
    } else {
        isEditing.value = false;
        editingId.value = null;
        
        form.name_barang = '';
        form.category_id = '';
        form.member_id = null;
        form.serial_number = '';
        form.status = 'Good';
        form.quantity = 1;
        form.department = '';
        form.spesification = '';
    }
    showModal.value = true;
};

const saveInventory = async () => {
    try {
        if (isEditing.value) {
            await axios.put(`/api/inventory/${editingId.value}`, form);
        } else {
            await axios.post('/api/inventory', form);
        }
        showModal.value = false;
        fetchInventories();
    } catch (error) {
         if (error.response && error.response.status === 422) {
            alert(Object.values(error.response.data.errors).flat().join('\n'));
        } else {
            alert("Error saving data");
        }
    }
};

const deleteItem = async (id) => {
    activeMenuId.value = null;
    if(!confirm('Delete this item?')) return;
    try {
        await axios.delete(`/api/inventory/${id}`);
        fetchInventories();
    } catch (e) { console.error(e); }
};

const toggleMenu = (id) => {
    activeMenuId.value = (activeMenuId.value === id) ? null : id;
};
const closeMenuOnClickOutside = () => {
    if (activeMenuId.value !== null) activeMenuId.value = null;
};

onMounted(() => {
    fetchInventories();
    fetchDropdowns();
    setTimeout(() => document.addEventListener('click', closeMenuOnClickOutside), 100);
});

onUnmounted(() => {
    document.removeEventListener('click', closeMenuOnClickOutside);
});
</script>