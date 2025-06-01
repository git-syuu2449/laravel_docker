<template>
    
    <h4 class="text-xl font-semibold text-gray-800 mb-4">あなたの投稿</h4>
    
    <template v-if="questions.length === 0">
      <p class="text-gray-500">投稿はありません。</p>
    </template>
    <template v-else>
        <!-- スクロール表示 -->
        <ul class="overflow-scroll divide-y-4 divide-gray-200 bg-white shadow-md rounded-md">
            <template v-for="question in questions" :key="question.id">
                <li class="p-4 hover:bg-gray-50 transition">

                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-semibold text-gray-800">{{ question.title }}</h3>
                        <span class="text-sm text-gray-500 px-2">{{ question.pub_date }}</span>
                    </div>
                    <p class="text-gray-700 whitespace-pre-line mb-2">
                        {{ question.body }}
                    </p>
                    
                    <template v-if="question.choices.length === 0">
                        <p class="text-gray-500">評価はありません。</p>
                    </template>
                    
                    <template v-else>
                        <h5 class="text-xl font-semibold text-gray-800 mb-4">質問に対する評価</h5>
                        <ul class="overflow-scroll divide-y-4 divide-gray-200 bg-white shadow-md rounded-md">
                            <li v-for="choice in question.choices" :key="choice.id" class="p-4 hover:bg-gray-50 transition">
                                {{ choice.updated_at }}
                                {{ choice.choice_text }}
                                {{ choice.votes }}
                            </li>
                        </ul>
                    </template>
                    <!-- 削除処理 -->
                    <button @click="() => doDelete(question)">削除</button>
                </li>
            </template>
        </ul>
    </template>
</template>


<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'

const props = defineProps({
  questions: Array,
  getUrl: String,
})

// valueで一覧リフレッシュ
const questions = ref([...props.questions])
// 結果
const success = ref(false)

// 削除押下
const doDelete = async (question) => {
  if(!confirm('削除を行います。よろしいですか？'))
  {
    return false;
  }
  success.value = false

  try {
    await axios.delete(question.delete_url)
    success.value = true
    alert('削除完了')
    doSearch();
  } catch (error) {
    if (error.response?.status === 404) {
      alert('削除済みです')
      doSearch();
    } else {
      alert('削除に失敗しました')
      console.error(error)
    }
  }

}

// 一覧をリフレッシュ
const doSearch = async () => {
  success.value = false
  try {
    await axios.get(props.getUrl)
    .then(res => {
      // questionsを更新
      questions.value = res.data.questions
    })
    success.value = true
  } catch (error) {
    console.error(error)
  }
}

// 一覧表示時に取得
onMounted(() => {
  doSearch()
})

</script>