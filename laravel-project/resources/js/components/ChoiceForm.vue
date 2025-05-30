<template>
  <form @submit.prevent="submitForm" class="space-y-6">
    <div>
      <label class="block text-sm font-medium text-gray-700">評価内容</label>
      <textarea
        v-model="form.choice_text"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
        rows="4"
      ></textarea>
      <p v-if="errors.choice_text" class="mt-1 text-sm text-red-600">{{ errors.choice_text[0] }}</p>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">スコア（1〜5）</label>
      <input
        type="number"
        v-model="form.votes"
        min="1"
        max="5"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
      />
      <p v-if="errors.votes" class="mt-1 text-sm text-red-600">{{ errors.votes[0] }}</p>
    </div>

    <div class="flex justify-end">
      <button
        type="submit"
        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 transition"
      >
        登録
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
    postUrl: String,
    questionId: Number
})

const form = ref({
    choice_text: '',
    votes: ''
})
const errors = ref({})
const success = ref(false)

const submitForm = async () => {
  errors.value = {}
  success.value = false
  try {
    await axios.post(props.postUrl, form.value)
    success.value = true
    form.value.question_id = ''
    form.value.choice_text = ''
    form.value.votes = ''
    // @todo モーダルを閉じて一覧をリフレッシュ.現状reload処理
    window.location.reload()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error(error)
    }
  }
}

// const emit = defineEmits(['submitted'])
// const submitForm = async () => {
//     await axios.post(props.postUrl, {
//         question_id: props.questionId,
//         choice_text: form.value.choice_text,
//         votes: form.value.votes,
//     })

//     emit('submitted')
// }
</script>
