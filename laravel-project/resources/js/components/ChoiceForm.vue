<template>
<form @submit.prevent="submitForm">
    <!-- <input type="hidden" :value="questionId" name="question_id" /> -->
    
    <div>
        <label class="block text-sm font-medium text-gray-700">評価内容</label>
        <textarea v-model="form.choice_text" class="mt-1 block w-full border rounded p-2"></textarea>
        <p v-if="errors.choice_text" class="text-red-500">{{ errors.choice_text[0] }}</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">スコア</label>
        <input type="number" v-model="form.votes" min="1" max="5" class="mt-1 block w-full border rounded p-2" />
        <p v-if="errors.votes" class="text-red-500">{{ errors.votes[0] }}</p>
    </div>

    <button type="submit">登録</button>
</form>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { defineProps } from 'vue'

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
