<template>
  <form @submit.prevent="submitForm" class="shadow-md rounded-md bg-white w-full max-w-2xl p-10" enctype="multipart/form-data">
    <!-- :はv-bindの省略 -->
     <questionFormBody 
      :errors="errors"
      :old="old"
      v-model="formData"
     />
    <p v-if="success" class="text-green-600 mt-2">登録が完了しました</p>
    <div class="flex justify-center">
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded fucus:outline-none focus:shadow-outline mt-3">登録</button>
    </div>
  </form>
</template>

<!-- Vue2向けの書き方 -->
<script>
import axios from 'axios'
import { ref, reactive } from 'vue'

import QuestionFormBody from './QuestionForm.vue'

axios.defaults.withCredentials = true
axios.defaults.xsrfCookieName = 'XSRF-TOKEN'
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN'

const errors = ref([]);
const old = ref([]);
// v-modelで双方向にバインディングする
const formData = reactive({
  question_text: '',
  images: []
})

export default {
  // vue2だとimportだけだと使えず、明示的に読み込みの記載が必要
  components: {
    QuestionFormBody,
  },
  name: 'QuestionForm',
  // 親から受け取る
  props: {
    postUrl: String, 
  },
  // 子に渡す
  data() {
    return {
      errors: {},
      old: {},
      // form: {
      //   question_text: '',
      //   images: [],
      // },
      success: false,
    }
  },
  methods: {
    async submitForm() {
      this.errors = {}
      this.success = false
      console.log('===================')
      console.log(formData);
      try {
        await axios.post(this.postUrl, formData)
        this.success = true
        // this.form.question_text = ''
        // this.form.images = []
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors
          // エラーの再注入
          console.log(error.response.data.errors)
          errors.value = error.response.data.errors
        } else {
          console.error(error)
        }
      }
    }
  }
}
</script>