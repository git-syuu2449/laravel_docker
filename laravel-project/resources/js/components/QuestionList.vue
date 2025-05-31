<template>
  <div>
    <template v-if="questions.length === 0">
      <p class="text-gray-500">投稿はありません。</p>
    </template>
    <template v-else>
      <ul class="divide-y divide-gray-200 bg-white shadow-md rounded-md">
        <li v-for="question in questions" :key="question.id" class="p-4 hover:bg-gray-50 transition">
          <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-semibold text-gray-800">{{ question.title }}</h3>
            <span class="text-sm text-gray-500 px-2">{{ question.pub_date }}</span>
          </div>
          <p class="text-gray-700 whitespace-pre-line mb-2">
            {{ truncate(question.body, 100) }}
          </p>
          <a v-if="question.can_be_evaluated === true" :href="`/questions/${question.id}`" class="text-blue-600 hover:underline">評価</a>
        </li>
      </ul>
    </template>
  </div>
</template>


<script setup>
import { ref } from 'vue'

const props = defineProps({
  questions: Array,
})

// 丸め
function truncate(str, length) {
  return str.length > length ? str.slice(0, length) + '…' : str;
}

</script>
