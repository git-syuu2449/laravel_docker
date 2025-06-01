<template>
  <div>
    <h4 class="text-xl font-semibold text-gray-800 mb-4">あなたの投稿</h4>

    <template v-if="questions.length === 0">
      <p class="text-gray-500">投稿はありません。</p>
    </template>

    <template v-else>
      <!-- 質問一覧全体に高さを設定してスクロール -->
      <ul class="overflow-y-auto max-h-[40vh] space-y-4 pr-2">
        <li
          v-for="question in questions"
          :key="question.id"
          class="bg-white border border-gray-200 rounded-lg shadow p-4 transition hover:shadow-lg"
        >
          <!-- 質問のヘッダー -->
          <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-semibold text-gray-800">{{ question.title }}</h3>
            <span class="text-sm text-gray-500">{{ question.pub_date }}</span>
          </div>

          <!-- 質問本文 -->
          <p class="text-gray-700 whitespace-pre-line mb-4">{{ question.body }}</p>

          <!-- 評価一覧アコーディオン -->
          <div v-if="question.choices.length > 0">
            <button
              @click="toggleAccordion(question.id)"
              class="text-blue-600 text-sm font-medium underline mb-2"
            >
              {{ opened.includes(question.id) ? '評価を隠す' : '評価を見る' }}
            </button>

            <transition name="fade">
              <ul
                v-show="opened.includes(question.id)"
                class="overflow-y-auto max-h-48 space-y-2 bg-gray-200 rounded p-2"
              >
                <li
                  v-for="choice in question.choices"
                  :key="choice.id"
                  class="bg-green-100 border rounded shadow-sm p-2 text-sm"
                >
                  <div class="text-gray-600">{{ choice.updated_at }}</div>
                  <div class="font-semibold text-gray-800">{{ choice.choice_text }}</div>
                  <div class="text-xs text-gray-500">評価数: {{ choice.votes }}</div>
                </li>
              </ul>
            </transition>
          </div>

          <div v-else class="text-gray-500 text-sm">評価はありません。</div>

          <!-- 削除ボタン -->
          <button
            @click="() => doDelete(question)"
            class="mt-4 text-red-600 text-sm hover:underline"
          >
            削除
          </button>
        </li>
      </ul>
    </template>
  </div>
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

// アコーディオン開閉
const opened = ref([])

const toggleAccordion = (id) => {
  if (opened.value.includes(id)) {
    opened.value = opened.value.filter((i) => i !== id)
  } else {
    opened.value.push(id)
  }
}


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


<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: all 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>