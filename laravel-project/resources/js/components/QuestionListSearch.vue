<template>
  <form @submit.prevent="submitForm" class="shadow-md rounded-md bg-white w-full max-w-4xl p-6 space-y-4 mx-auto mb-6">
    <div>
      <label for="title" class="block text-sm font-medium text-gray-700">タイトル</label>
      <input
        type="text"
        id="title"
        name="title"
        v-model="form.title"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
      />
      <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title[0] }}</p>
    </div>

    <div>
      <label for="body" class="block text-sm font-medium text-gray-700">本文</label>
      <input
        type="text"
        id="body"
        name="body"
        v-model="form.body"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
      />
      <p v-if="errors.body" class="text-red-500 text-sm mt-1">{{ errors.body[0] }}</p>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">投稿日時</label>
      <div class="flex items-center gap-2 mt-1">
        <input
          type="date"
          id="pub_date_from"
          name="pub_date_from"
          v-model="form.pub_date_from"
          class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
        />
        <span>〜</span>
        <input
          type="date"
          id="pub_date_to"
          name="pub_date_to"
          v-model="form.pub_date_to"
          class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
        />
      </div>
      <p v-if="errors.pub_date_from" class="text-red-500 text-sm mt-1">{{ errors.pub_date_from[0] }}</p>
      <p v-if="errors.pub_date_to" class="text-red-500 text-sm mt-1">{{ errors.pub_date_to[0] }}</p>
    </div>

    <div>
      <button
        type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition"
      >
        投稿を再取得
      </button>
    </div>
  </form>
</template>


<script setup>
import { ref } from 'vue'
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
  title: '',
  body: '',
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
