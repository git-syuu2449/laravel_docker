<template>
  <form @submit.prevent="submitForm" class="space-y-6" enctype="multipart/form-data">
    <QuestionFormBody
      ref="formBodyRef"
      :errors="errors"
      :old="old"
    />
    <p v-if="success" class="text-green-600 mt-2">登録が完了しました</p>

    <!-- 送信ボタン -->
    <div class="text-right">
      <button
        type="submit"
        class="u-form-primary-btn"
      >
        登録
      </button>
    </div>

  </form>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import QuestionFormBody from './QuestionForm.vue'

const props = defineProps({ postUrl: String })

const formBodyRef = ref(null)
const errors = ref({})
const old = ref({})
const success = ref(false)

const submitForm = async () => {
  errors.value = {}
  success.value = false

  const form = new FormData()
  // 子の値をセット
  const formData = formBodyRef.value.getFormData()

  form.append('title', formData.title)
  form.append('body', formData.body)
  for (let i = 0; i < formData.images.length; i++) {
    form.append('images[]', formData.images[i])
  }

  try {
    await axios.post(props.postUrl, form)
    success.value = true
  } catch (error) {
    if (error.response?.status === 422) {
      //　エラーを注入
      errors.value = error.response.data.errors
    } else {
      console.error(error)
    }
  }
}
</script>
