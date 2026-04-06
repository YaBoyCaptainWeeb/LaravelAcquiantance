import axios from 'axios';
import {TableError} from "@/types/tables.ts";

let toastInstance: any = null;

export const setToast = (toast: any) => {
    toastInstance = toast
};

const api = axios.create({
    baseURL: '/api',
    timeout: 30000,
    headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
    }
});

api.interceptors.response.use(
    (response) => {
        const contentType = response.headers['content-type'];
        if (contentType && contentType.includes('text/html'))
        {
            if (toastInstance) {
                toastInstance.add({
                    severity: 'error',
                    summary: 'Ошибка 404',
                    detail: "Эндпоинт не найден",
                    life: 5000
                });
            }
            return Promise.reject({
                error: "404",
                message: "Эндпоинт не найден"
            });
        }
        return response;
    },
    (error) => {
        if (toastInstance) {
            const message = error.response?.data?.message || error.message || 'Произошла ошибка';
            toastInstance.add({
                severity: 'error',
                summary: 'Ошибка: ' + (error.response?.code || error.code),
                detail: message,
                life: 5000
            });
        } else {
            console.error('API Error', error);
        }
        const errorObj: TableError = {
            error: error.response?.code || error.code,
            message: error.response?.data?.message || error.message || 'Неизвестная ошибка'
        };
        return Promise.reject(errorObj);
    }
);


export default api;
