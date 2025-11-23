<template>
    <div class="flex h-screen bg-gray-50 font-sans">
        <aside
            class="w-52 bg-white border-r border-gray-200 hidden md:block flex-shrink-0"
        >
            <div
                class="h-16 flex items-center justify-center border-b border-gray-200"
            >
                <h1 class="ml-5 text-xl font-bold text-black-600 tracking-wide">
                    management inventaris.
                </h1>
            </div>

            <nav class="mt-6 px-4 space-y-2">
                <router-link
                    to="/members"
                    class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200"
                    active-class="bg-blue-50 text-blue-700 font-semibold"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-3"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                        />
                    </svg>
                    Management Anggota
                </router-link>

                <!-- MENU INVENTORY -->
                <router-link
                    to="/inventory"
                    class="flex items-center px-4 py-3 rounded-lg text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-200"
                    active-class="bg-blue-50 text-blue-700 font-semibold"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-3"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                        />
                    </svg>
                    Data Inventaris
                </router-link>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50">
            <header
                class="bg-white shadow-sm h-16 flex items-center px-6 md:hidden"
            >
                <h1 class="text-lg font-bold text-gray-800">Inventory</h1>
            </header>

            <div class="p-8">
                <router-view v-slot="{ Component }">
                    <transition name="fade" mode="out-in">
                        <component :is="Component" />
                    </transition>
                </router-view>
            </div>
        </main>
    </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>

<script setup>
</script>
