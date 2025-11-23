<template>
    <div>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Members</h2>
                <p class="text-gray-500 text-sm">
                    Manage who is assigned to inventory
                </p>
            </div>
            <button
                @click="showModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-2"
            >
                <span>+ Add New Member</span>
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold"
                    >
                        <th class="px-6 py-4 first:rounded-tl-xl">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Position</th>
                        <th class="px-6 py-4">Department</th>
                        <th
                            class="px-6 py-4 text-right text-right last:rounded-tr-xl"
                        >
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="member in members"
                        :key="member.id"
                        class="hover:bg-gray-50 transition"
                    >
                        <td class="px-6 py-4 text-gray-600">{{ member.id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ member.name }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ member.position || "-" }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ member.department || "-" }}
                        </td>
                        <td class="px-6 py-4 text-right relative">
                            <button
                                @click.stop="toggleMenu(member.id)"
                                class="p-2 rounded-full hover:bg-gray-200 border border-gray-300 text-gray-500 transition focus:outline-none"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM17.25 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"
                                    />
                                </svg>
                            </button>

                            <div
                                v-if="activeMenuId === member.id"
                                class="absolute right-0 top-8 mt-2 w-36 bg-black text-white rounded-xl shadow-xl z-50 overflow-hidden origin-top-right"
                            >
                                <div class="py-1">
                                    <button
                                        @click="openModal(member)"
                                        class="group flex items-center w-full text-left px-4 py-3 text-sm hover:bg-gray-800 transition"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        @click="deleteMember(member.id)"
                                        class="group flex items-center w-full text-left px-4 py-3 text-sm text-red-400 hover:bg-gray-800 hover:text-red-300 transition"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="members.length === 0">
                        <td
                            colspan="4"
                            class="px-6 py-8 text-center text-gray-400"
                        >
                            No members found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-1">
                    {{ isEditing ? "Edit Member" : "Add New Member" }}
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                    {{
                        isEditing
                            ? "Update data member yang sudah ada."
                            : "Tambahkan member baru ke sistem."
                    }}
                </p>

                <form @submit.prevent="saveMember">
                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Full Name</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Position</label
                        >
                        <input
                            v-model="form.position"
                            type="text"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required
                        />
                    </div>
                    <div class="mb-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Department</label
                        >
                        <input
                            v-model="form.department"
                            type="text"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required
                        />
                    </div>
                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            @click="showModal = false"
                            class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-lg text-sm font-medium text-white shadow-md transition transform active:scale-95"
                            :class="
                                isEditing
                                    ? 'bg-orange-600 hover:bg-orange-700'
                                    : 'bg-blue-600 hover:bg-blue-700'
                            "
                        >
                            {{ isEditing ? "Update Changes" : "Save Member" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, reactive } from "vue";
import axios from "axios";

const members = ref([]);
const showModal = ref(false);
const activeMenuId = ref(null);

const isEditing = ref(false);
const editingId = ref(null);

const form = reactive({
    name: "",
    position: "",
    department: "",
});

const fetchMembers = async () => {
    try {
        const response = await axios.get("/api/members");
        members.value = response.data.data;
    } catch (error) {
        console.error("Error fetching members:", error);
    }
};

const openModal = (member = null) => {
    activeMenuId.value = null;

    if (member) {
        isEditing.value = true;
        editingId.value = member.id;

        form.name = member.name;
        form.position = member.position;
        form.department = member.department;
    } else {
        isEditing.value = false;
        editingId.value = null;

        // Reset form
        form.name = "";
        form.position = "";
        form.department = "";
    }

    showModal.value = true;
};

const saveMember = async () => {
    try {
        if (isEditing.value) {
            await axios.put(`/api/members/${editingId.value}`, form);
        } else {
            await axios.post("/api/members", form);
        }

        // Reset & Refresh
        showModal.value = false;
        fetchMembers();
    } catch (error) {
        if (error.response && error.response.status === 422) {
            alert(Object.values(error.response.data.errors).flat().join("\n"));
        } else {
            alert("Terjadi kesalahan sistem.");
            console.error(error);
        }
    }
};

const deleteMember = async (id) => {
    activeMenuId.value = null;
    if (!confirm("Apakah anda yakin ingin menghapus data ini?")) return;
    try {
        await axios.delete(`/api/members/${id}`);
        fetchMembers();
    } catch (error) {
        console.error(error);
    }
};

const toggleMenu = (id) => {
    if (activeMenuId.value === id) {
        activeMenuId.value = null;
    } else {
        activeMenuId.value = id;
    }
};

const closeMenuOnClickOutside = (event) => {
    if (activeMenuId.value !== null) {
        activeMenuId.value = null;
    }
};

onMounted(() => {
    fetchMembers();
    setTimeout(() => {
        document.addEventListener("click", closeMenuOnClickOutside);
    }, 100);
});

onUnmounted(() => {
    document.removeEventListener("click", closeMenuOnClickOutside);
});
</script>
