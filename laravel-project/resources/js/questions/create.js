import { createApp } from 'vue';
import QuestionForm from '../components/QuestionForm.vue';

const app = createApp({});
app.component('question-form', QuestionForm);
app.mount('#app-vue');
