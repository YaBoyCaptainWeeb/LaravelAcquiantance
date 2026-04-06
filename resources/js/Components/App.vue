<script setup lang="ts">
import {useToast, Toast} from "primevue";
import {setToast} from "@/Utils/axios.ts";
import NavigationBar from "@/NavigationBar.vue";

const toast = useToast();

setToast(toast);
</script>

<template>
    <Toast/>
    <main class="w-screen h-screen flex flex-col overflow-hidden bg-surface-100 p-2">
        <NavigationBar class="mb-1"/>
        <router-view class="flex-1 min-h-0"/>
    </main>
</template>

<style scoped>

</style>
