<script setup lang="ts">
import Menubar from "primevue/menubar";
import {useRoute, useRouter} from "vue-router";
import {ref} from "vue";

const router = useRouter();
const route = useRoute();

const items = ref([
    {
        label: 'Главная',
        icon: 'pi pi-home',
        path: '/',
        command: () => router.push('/')
    },
    {
        label: 'Дашборд',
        icon: 'pi pi-cog',
        path: '/dashboard',
        command: () => router.push('/dashboard')
    }
]);
</script>

<template>
    <div>
        <Menubar :model="items"
        :pt="{
            itemContent: ({ context }) => ({
                class: [
                    'transition-colors duration-200',
                    { 'bg-primary-100 text-primary-900': route.path === context.item.path }
                ]
            })
        }"/>
    </div>
</template>

<style scoped>

</style>
