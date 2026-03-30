import './bootstrap';
import {createApp} from "vue";
import {createPinia} from "pinia";
import PrimeVue from "primevue/config";
import router from './router/router';
import App from './Components/App.vue';

import Aura from '@primeuix/themes/aura';

const app = createApp(App);

app.use(createPinia());
app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            prefix: 'p',
            darkModeSelector: 'class',
            cssLayer: false,
            ripple: true
        }
    }
});
app.use(router);

app.mount('#app');
