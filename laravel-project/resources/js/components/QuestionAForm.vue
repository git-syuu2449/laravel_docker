<template>
  <form @submit.prevent="submitForm" class="shadow-md rounded-md bg-white w-full max-w-2xl p-10">
    <div class="flex sm:items-center mb-6 flex-col sm:flex-row">
      <label for="question_text" class="block sm:w-1/3 font-bold sm:text-right mb-1 pr-4"><span class="text-red-600"> * </span>質問内容</label>
      <input 
          type="text" 
          v-model="form.question_text" 
          id="question_text" 
          class="block w-full sm:w-2/3 bg-gray-200 py-2 px-3 text-gray-700 border border-gray-200 rounded focus:outline-none focus:bg-white" />
      <p v-if="errors.question_text" class="text-red-500">{{ errors.question_text[0] }}</p>
    </div>

    <div class="flex justify-center">
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded fucus:outline-none focus:shadow-outline mt-3">登録</button>
    </div>
    <p v-if="success" class="text-green-600 mt-2">登録が完了しました</p>
  </form>
</template>
  
<script>
import axios from 'axios'

export default {
  name: 'QuestionForm',
  props: ['postUrl'],
  data() {
    return {
      form: {
        question_text: '',
      },
      errors: {},
      success: false,
    }
  },
  methods: {
    async submitForm() {
      this.errors = {}
      this.success = false
      try {
        await axios.post(this.postUrl, this.form)
        this.success = true
        this.form.question_text = ''
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors
        } else {
          console.error(error)
        }
      }
    }
  }
}
</script>