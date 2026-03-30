<script setup lang="ts">
import {useStorage} from "@/storage/storage";

import Button from "primevue/button";

interface Properties {
    title?: string
}

withDefaults(defineProps<Properties>(), {
    title: "Попытки подружить Vue с Laravel"
});

const emit = defineEmits<{
    (e: 'submit', value: number): void
}>();

const counter = useStorage();
</script>

<template>
    <div class="p-8 text-center">
        <h1 class="text-3xl font-bold text-green-900 pi pi-asterisk pi-align-center pe-2.5">
            {{ title }}
        </h1>
        <p>Счётчик: {{ counter.count }}</p>
        <p>Удвоенное значение {{ counter.doubleCount }}</p>
        <Button label="Увеличить" @click="counter.incr()" />
        <Button label="Отправить" @click="emit('submit', counter.count)" />
    </div>
</template>

<style scoped>

</style>
