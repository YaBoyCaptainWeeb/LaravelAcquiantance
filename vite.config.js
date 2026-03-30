import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        tailwindcss(),
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        origin: 'http://127.0.0.1:5173',
        cors: {
          origin: 'http://127.0.0.1:8000'
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
