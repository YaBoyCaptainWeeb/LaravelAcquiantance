<script setup lang="ts">
import Home from "@/Components/Home.vue";

function handleSubmit(value: number) {
    console.log('App получил значение: ', value);
    alert(`App получил значение от Home: ${value}`);
}
</script>

<template>
    <div>
        <h2>App component</h2>

        <Home @submit="handleSubmit" />
    </div>
</template>

<style scoped>

</style>
