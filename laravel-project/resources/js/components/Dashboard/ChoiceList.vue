<template>
  <div>
    <h4 class="text-xl font-semibold text-gray-800 mb-4">あなたの評価</h4>

    <template v-if="choices.length === 0">
      <p class="text-gray-500">評価はありません。</p>
    </template>

    <template v-else>
      <!-- 評価一覧にスクロール制限をかけて表示 -->
      <ul class="overflow-y-auto max-h-[40vh] space-y-4 pr-2">
        <li
          v-for="choice in choices"
          :key="choice.id"
          class="bg-white border border-gray-200 rounded-lg shadow p-4 transition hover:shadow-lg"
        >
            <!-- 詳細ボタン -->
            <!-- <button
              @click="() => doShow(choice)"
              class="text-blue-600 text-sm hover:underline"
              >
              投稿詳細を見る
            </button> -->
            <!-- 動的にバインドが必要 -->
            <a 
              v-bind:href="choice.question_show_url"
              target="_blank"
              class="text-blue-600 text-sm hover:underline"
            >投稿詳細を見る</a>

          <div class="flex justify-between items-center mb-2 text-sm text-gray-500">
            <span>更新日時: {{ choice.updated_at }}</span>
            <span class="text-blue-600 font-medium">得点: {{ choice.votes }}</span>
          </div>
          <p class="text-gray-800 text-sm mb-2">{{ choice.choice_text }}</p>

          <!-- 削除ボタン -->
          <button
            @click="() => doDelete(choice)"
            class="text-red-600 text-sm hover:underline"
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
  choices: Array,
  getUrl: String,
})

// valueで一覧リフレッシュ
const choices = ref([...props.choices])
// 結果
const success = ref(false)

// 削除押下
const doDelete = async (choice) => {
  if(!confirm('削除を行います。よろしいですか？'))
  {
    return false;
  }
  success.value = false

  try {
    await axios.delete(choice.delete_url)
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
      // choicesを更新
      choices.value = res.data.choices
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

// 詳細押下
const doShow = (choice) => {
  
}


</script>