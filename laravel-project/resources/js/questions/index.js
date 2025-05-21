import { createApp } from 'vue';
import QuestionArea from '../components/QuestionArea.vue';

const app = createApp({});
app.component('question-area', QuestionArea);
app.mount('#app-vue');
