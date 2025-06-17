import { createApp } from 'vue';
import RankingTopArea from '../components/Ranking/TopArea.vue';

const app = createApp({});
app.component('ranking_top_area', RankingTopArea);
app.mount('#app-vue');
