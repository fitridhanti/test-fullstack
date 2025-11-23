import { createRouter, createWebHistory } from "vue-router";
import MemberIndex from "../pages/members/MemberIndex.vue";
import InventoryIndex from "../pages/inventory/InventoryIndex.vue"; // Kita buat file ini sebentar lagi

const routes = [
    {
        path: "/members",
        name: "members.index",
        component: MemberIndex,
    },
    { path: "/inventory", name: "inventory.index", component: InventoryIndex },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
