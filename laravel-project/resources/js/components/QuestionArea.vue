<!-- 質問一覧のvueのラッパー -->
<template>
    <div>
        <question-list-search 
            :getUrl="props.getUrl"
            @questionsLoaded="handleUpdate"
         />
        <question-list :questions="questions" />
    </div>
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