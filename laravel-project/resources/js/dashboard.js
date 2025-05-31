import { createApp } from 'vue';
import QuestionList from './components/dashboard/QuestionList.vue'
import ChoiceList from './components/dashboard/ChoiceList.vue'

const app = createApp({});
// 質問一覧
app.component('questions-list', QuestionList);
// 評価一覧
app.component('choice-list', ChoiceList);
app.mount('#app-vue');
