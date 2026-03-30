import { createRouter, createWebHistory, RouteRecordRaw} from "vue-router";
import Home from '../Components/Home.vue';

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        name: 'home',
        component: Home
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

export default router;
