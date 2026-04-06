import './bootstrap';
import {createApp} from "vue";
import {createPinia} from "pinia";
import PrimeVue from "primevue/config";
import router from './router/router';
import App from './Components/App.vue';
import ToastService from "primevue/toastservice";
import Aura from '@primeuix/themes/aura';

const app = createApp(App);

app.use(createPinia());
app.use(ToastService);
app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            prefix: 'p',
            darkModeSelector: 'class',
            ripple: true,
            cssLayer: {
                name: 'primevue',
                order: 'theme, base, primevue'
            }
        }
    }
});
app.use(router);

app.mount('#app');
