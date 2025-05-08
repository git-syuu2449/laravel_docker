import { createApp } from 'vue';
import QuestionForm from '../components/QuestionAForm.vue';

const app = createApp({});
app.component('question-form', QuestionForm);
app.mount('#app-vue');
