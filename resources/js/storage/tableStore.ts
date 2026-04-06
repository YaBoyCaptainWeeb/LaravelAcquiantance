import {defineStore} from "pinia";
import { reactive } from "vue";
import type {TableState, TableResponse, TableError} from "@/types/tables.ts";
import api from "@/Utils/axios.ts";

interface fetchDataOptions {
    tableName: string,
    page?: number,
    limit?: number,
    endpoint: string
}
export const useTableStore = defineStore('tables', () => {
    const tables = reactive<Record<string, TableState>>({});
    const getTableState = (tableName: string): TableState => {
        if (!tables[tableName]) {
            tables[tableName] = {
                data: [],
                meta: null,
                loading: false,
                error: null
            };
        }
        return tables[tableName];
    };

    const fetchData = async (
        {tableName, page = 1, limit = 100, endpoint}: fetchDataOptions
    ) => {
        const state = getTableState(tableName);
        state.loading = true;
        state.error = null;

        try {
            const response = await api.get<TableResponse>(endpoint, {
                params: {page: page, limit: limit}
            });
            state.data = response.data.data ?? [];
            state.meta = response.data.meta;
        } catch (err: any) {
            state.error = err as TableError;
            state.data = [];
            state.meta = null;
            console.error(`Ошибка загрузки таблицы ${tableName}, код ${err.error}:`, err.message);
        } finally {
            state.loading = false;
        }
    };

    const changePage = (tableName: string, page: number, endpoint: string) => {
        const state = getTableState(tableName);
        const limit = state.meta?.limit ?? 100;
        return fetchData({tableName, page, limit, endpoint});
    };

    const changeLimit = (tableName: string, limit: number, endpoint: string) => {
        return fetchData({tableName: tableName, limit: limit, endpoint: endpoint});
    }

    return {
        tables,
        getTableState,
        fetchData,
        changePage,
        changeLimit
    };
});
