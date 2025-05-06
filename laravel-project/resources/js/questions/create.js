import { createApp } from 'vue';
import QuestionForm from '../components/QuestionForm.vue';
import TestComponent from '../components/Test.vue';

const app = createApp({});
app.component('question-form', QuestionForm);
app.component('test-component', TestComponent);
app.mount('#app-vue');
