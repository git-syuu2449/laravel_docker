<template>
    <form @submit.prevent="submitForm">
      <div>
        <label for="title">タイトル</label>
        <input type="text" v-model="form.title" id="title" />
        <p v-if="errors.title" class="text-red-500">{{ errors.title[0] }}</p>
      </div>
  
      <div>
        <label for="body">内容</label>
        <textarea v-model="form.body" id="body"></textarea>
        <p v-if="errors.body" class="text-red-500">{{ errors.body[0] }}</p>
      </div>
  
      <button type="submit">登録</button>
    </form>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  
  const form = ref({
    title: '',
    body: '',
  })
  const errors = ref({})
  
  const submitForm = async () => {
    try {
      const response = await axios.post('/questions', form.value)
      alert('登録完了')
      form.value.title = ''
      form.value.body = ''
      errors.value = {}
    } catch (error) {
      if (error.response && error.response.status === 422) {
        errors.value = error.response.data.errors
      }
    }
  }
  </script>