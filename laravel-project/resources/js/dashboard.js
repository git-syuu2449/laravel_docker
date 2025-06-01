import { createApp } from 'vue';
import QuestionList from './components/Dashboard/QuestionList.vue'
import ChoiceList from './components/Dashboard/ChoiceList.vue'

const app = createApp({});
// 質問一覧
app.component('question-list', QuestionList);
// 評価一覧
app.component('choice-list', ChoiceList);
app.mount('#app-vue');
