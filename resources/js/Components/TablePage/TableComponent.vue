<script setup lang="ts">
import {useTableStore} from "@/storage/tableStore.ts";
import {computed, ref, watch} from "vue";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Skeleton from "primevue/skeleton";
import Message from "primevue/message";
import {BaseTable} from "@/types/tables.ts";

const props = defineProps<{
    tableName: string,
    endpoint: string,
    title: string,
    activeTab: boolean
}>();
const selectedRow = ref(null);
const tableStore = useTableStore();
const state = computed(() => tableStore.getTableState(props.tableName));
const data = computed(() => state.value.data);
const meta = computed(() => state.value.meta);
const loading = computed(() => state.value.loading);
const error = computed(() => state.value.error);

const limits = [100, 200, 300, 400, 500];
const template = 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown';

const currentLimit = computed(() => meta.value?.limit ?? 100);
const totalRecords = computed(() => meta.value?.total ?? 0);
const firstRowIndex = computed(() => {
    if (!meta.value) return 0;
    return (meta.value.current_page - 1) * meta.value.limit;
});

const columns = computed(() => {
    if (data.value.length === 0) return [];
    const firstItem = data.value[0];
    const excludeFields: string[] = ['none'];
    return Object.keys(firstItem)
        .filter(key => !excludeFields.includes(key))
        .map(key => ({
            field: key,
            header: key.charAt(0).toUpperCase() + key.slice(1).replace(/_/g, ' ')
        }));
});

const onPageChange = (event: any) => {
    const newPage: number = event.page + 1;
    const newLimit: number = event.rows;

    if (newLimit !== currentLimit.value) {
        tableStore.changeLimit(props.tableName, newLimit, props.endpoint);
    } else {
        tableStore.changePage(props.tableName, newPage, props.endpoint);
    }
}

const generateSkeletons = () => {
    state.value.data = Array.from({length: currentLimit.value})
        .map((_, i): BaseTable => ({id: i, date: "", last_change_date: ""}));
}

const formatDate = (value: string | null) => {
    if (!value) return '';
    const date = new Date(value);

    if (isNaN(date.getTime())) return value;

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}.${month}.${year}`;
};

const fetchData = () => {
    state.value.loading = true;
    tableStore.fetchData({
        tableName: props.tableName,
        page: 1,
        limit: currentLimit.value,
        endpoint: props.endpoint
    });
}
watch(loading, (newValue) => {
    if (newValue) {
        generateSkeletons();
    }
});

watch(() => props.activeTab, (newValue) => {
    if (newValue && data.value.length === 0 && !loading.value) {
        fetchData();
    }
}, {immediate: true});
</script>

<template>
    <div class="h-full flex flex-col gap-1 p-1">
        <h2 class="text-xl font-bold shrink-0">{{ title }}</h2>

        <div class="flex-1 overflow-hidden">
            <DataTable
                paginator
                :paginator-template="template"
                :rows-per-page-options="limits"
                scrollable
                scroll-height="flex"
                striped-rows
                :value="data"
                lazy
                :total-records="totalRecords"
                :rows="currentLimit"
                :first="firstRowIndex"
                resizable-columns
                class="h-full shadow-sm rounded-lg border border-surface-200"
                @page="onPageChange"
                pt:root:class="h-full flex flex-col"
                pt:wrapper:class="flex-1"
                pt:pcPaginator:root:class="justify-center gap-6"
                removable-sort
                row-hover
                selection-mode="single"
                v-model:selection="selectedRow"
            >
                <template #header>
                    <div
                        :class="[
                            'w-fit flex items-center gap-2 px-3 py-1 bg-primary-50 rounded-md border border-primary-100 shadow-sm',
                            { 'animate-pulse opacity' : loading }
                        ]">
                        <i class="pi pi-list text-primary-500 text-xs"></i>
                        <span class="text-xs font-bold text-primary-700">
                           Загружено на странице: {{ loading ? '-' : data.length }}
                        </span>
                        <i class="pi pi-list text-primary-500 text-xs"></i>
                        <span class="text-xs font-bold text-primary-700">
                           Всего записей: {{ loading ? '-' : totalRecords }}
                        </span>
                        <i class="pi pi-list text-primary-500 text-xs"></i>
                        <span class="text-xs font-bold text-primary-700">
                           Всего страниц: {{ loading ? '-' : meta?.last_page || 0 }}
                        </span>
                    </div>
                </template>

                <Column
                    v-for="col in columns"
                    :key="col.field"
                    :field="col.field"
                    :header="col.header"
                >
                    <template #body="{data,field}">
                        <template v-if="loading || error !== null">
                            <skeleton width="100%" height="1rem"/>
                        </template>
                        <template v-else>
                            <template v-if="typeof field === 'string' && field.toLowerCase().includes('date')">
                                {{ formatDate(data[field]) }}
                            </template>
                            <template v-else>
                                {{ data[col.field] }}
                            </template>
                        </template>
                    </template>

                </Column>
            </DataTable>
        </div>
        <div v-if="error" class="shrink-0 mt-4">
            <Message severity="error" icon="pi pi-bolt">
                <template #default>
                    <div class="flex flex-col gap-1">
                        <span class="font-bold uppercase text-xs tracking-wider">Код: {{ error.error }}</span>
                        <span class="text-sm font-medium">{{ error.message }}</span>
                    </div>
                </template>
            </Message>
        </div>
    </div>
</template>

<style scoped>

</style>
