import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useStorage = defineStore('storage', () => {
    const count = ref(0);
    const doubleCount = computed(() => count.value * 2);

    function incr()
    {
        count.value++;
    }

    return { count, doubleCount, incr };
})
