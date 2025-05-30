<!-- 質問一覧のvueのラッパー -->
<template>
  <question-list-search 
      :getUrl="props.getUrl"
      @questionsLoaded="handleUpdate"
  />
  <question-list :questions="questions" />
</template>

<script setup>
import { ref } from 'vue'

// 一覧
import QuestionList from './QuestionList.vue'
import QuestionListSearch from './QuestionListSearch.vue'

const props = defineProps({
  initialQuestions: Array,
  getUrl: String
})

const questions = ref([...props.initialQuestions])

const handleUpdate = (newQuestions) => {
  console.log(newQuestions)
  // 再注入
  questions.value = newQuestions
}

</script>