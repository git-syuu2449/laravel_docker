import { createApp } from 'vue';
import ChoiceModalWarapper from '../components/ChoiceModalWrapper.vue';

const app = createApp({});
app.component('choice-modal-wrapper', ChoiceModalWarapper);
app.mount('#app-vue');
