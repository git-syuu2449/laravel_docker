<template>
  <!-- 検索領域 -->

  <form @submit.prevent="submitForm" class="shadow-md rounded-md bg-white w-full max-w-2xl p-10">

    <div class="mb-4">
      <label for="question_text">質問内容</label>
      <input
        type="text"
        id="question_text"
        name="question_text"
        v-model="form.question_text" 
        class="border w-full"
      />
      <p v-if="errors.question_text" class="text-red-500 text-sm">{{ errors.question_text[0] }}</p>
    </div>

    <div class="mb-4">
      <label for="pub_date">投稿日時</label>
      <input
        type="date"
        id="pub_date_from"
        name="pub_date_from"
        v-model="form.pub_date_from" 
        class=""
      />
      〜
      <input
        type="date"
        id="pub_date_to"
        name="pub_date_to"
        v-model="form.pub_date_to" 
        class=""
      />
      <p v-if="errors.pub_date_from" class="text-red-500 text-sm">{{ errors.pub_date_from[0] }}</p>
      <p v-if="errors.pub_date_to" class="text-red-500 text-sm">{{ errors.pub_date_to[0] }}</p>
    </div>

    <div class="flex justify-left">
      <button type="submit" class="">
        投稿を再取得
      </button>
    </div>
  </form>

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
  question_text: '',
  pub_date_to: null,
})
const errors = ref({})
const success = ref(false)

const submitForm = async () => {
  errors.value = {}
  success.value = false
  try {
    await axios.get(props.getUrl, {
      // パラメータの指定　v-modelでバインドされた値
      params: form.value
    })
    .then(res => {
      // 一覧の内容をリフレッシュする 親にemitで受け渡す
      emit('questionsLoaded', res.data.questions)
    })
    success.value = true
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
      console.log(errors) // debug
    } else {
      console.error(error)
    }
  }
}
</script>
