<template>

  <h4 class="text-xl font-semibold text-gray-800 mb-4">あなたの評価</h4>
    <template v-if="choices.length === 0">
      <p class="text-gray-500">評価はありません。</p>
    </template>
    <template v-else>
      <ul class="divide-y divide-gray-200 bg-gray-50 rounded-md shadow-sm">
          <li v-for="choice in choices" :key="choice.id"  class="p-4">
              <div class="flex justify-between items-center mb-1 text-sm text-gray-500">
              <span>更新日時: {{ choice.updated_at }}</span>
              <span class="text-blue-600 font-medium">得点: {{ choice.votes }}</span>
              </div>
              <p class="text-gray-800">{{ choice.choice_text }}</p>
              <!-- 削除処理 -->
              <button @click="() => doDelete(choice)">削除</button>
          </li>
      </ul>
    </template>

</template>


<script setup>

import axios from 'axios'
import { ref } from 'vue'

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
    doSeaech();
  } catch (error) {
    if (error.response?.status === 404) {
      alert('削除済みです')
      doSeaech();
    } else {
      alert('削除に失敗しました')
      console.error(error)
    }
  }

}

// 一覧をリフレッシュ
const doSeaech = async () => {
  success.value = false
  console.log(props.getUrl)
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
window:onload = function() {  
  doSeaech();
}


</script>