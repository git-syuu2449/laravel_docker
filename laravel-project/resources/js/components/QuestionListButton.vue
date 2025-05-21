<template>
  <button  class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800" @click="doClick">
        投稿を再取得
    </button>
</template>

<script setup>
import { ref, defineProps } from 'vue'
import axios from 'axios'

axios.defaults.withCredentials = true
axios.defaults.xsrfCookieName = 'XSRF-TOKEN'
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN'

const props = defineProps({
    getUrl: String,
})

// emitの定義
const emit = defineEmits(["questionsLoaded"])

const form = ref({
})
const errors = ref({})
const success = ref(false)

const doClick = async () => {
  errors.value = {}
  success.value = false
  try {
    // 認証
    await axios.get('/sanctum/csrf-cookie')
    .then(res => {
    })
    
    await axios.get(props.getUrl, {
    })
    .then(res => {
      // console.log(res.data); // debug
      // 一覧の内容をリフレッシュする 親にemitで受け渡す
      emit('questionsLoaded', res.data.questions)
    })
    success.value = true
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error(error)
    }
  }
}
</script>
