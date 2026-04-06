import { createRouter, createWebHistory, RouteRecordRaw} from "vue-router";
import Dashboard from "@/Components/DashboardPage/Dashboard.vue";
import TableTabs from "@/Components/TablePage/TableTabs.vue";

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        name: 'home',
        component: TableTabs
    },
    {
        path: '/Dashboard',
        name: 'dashboard',
        component: Dashboard
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

export default router;
