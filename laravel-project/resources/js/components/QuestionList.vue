<template>
  <div>
    <template v-if="questions.length === 0">
      <span>投稿はありません。</span>
    </template>
    <template v-else>
      <ul class="question_list">
        <li v-for="question in questions" :key="question.id">
          <span>{{ question.pub_date }}</span>
          <p>{{ question.question_text }}</p>
          <a :href="`/questions/${question.id}`">評価</a>
        </li>
      </ul>
    </template>
  </div>
</template>

<script setup>
import { ref, defineProps } from 'vue'

const props = defineProps({
  questions: Array,
})

</script>
