<script setup lang="ts">
import {ref} from "vue";
import Tabs from "primevue/tabs";
import TabList from "primevue/tablist";
import Tab from "primevue/tab";
import TabPanels from "primevue/tabpanels";
import TabPanel from "primevue/tabpanel";
import TableComponent from "@/Components/TablePage/TableComponent.vue";

const tabs = [
    {label: 'Склады', value: 'stocks', tableName: 'stocks', endpoint: '/getStocks'},
    {label: 'Заказы', value: 'orders', tableName: 'orders', endpoint: '/getOrders'},
    {label: 'Продажи', value: 'sales', tableName: 'sales', endpoint: '/getSales'},
    {label: 'Доходы', value: 'incomes', tableName: 'incomes', endpoint: '/getIncomes'}
];

const activeTab = ref(tabs[0].value);
</script>

<template>
    <div class="h-full flex flex-col">
        <Tabs v-model:value="activeTab" scrollable class="flex flex-col h-full">
            <TabList>
                <Tab v-for="tab in tabs" :key="tab.value" :value="tab.value">
                    {{ tab.label }}
                </Tab>
            </TabList>
            <TabPanels class="flex-1 overflow-hidden p-0">
                <TabPanel v-for="tab in tabs" :key="tab.value" :value="tab.value"
                          :pt="{content: { class: 'h-full' }, root: { class: 'h-full' }}">
                    <TableComponent :activeTab="activeTab === tab.value" :title="tab.label" :table-name="tab.tableName" :endpoint="tab.endpoint"/>
                </TabPanel>
            </TabPanels>
        </Tabs>
    </div>
</template>

<style scoped>

</style>
