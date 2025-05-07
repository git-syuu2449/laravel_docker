<template>
    <form @submit.prevent="submitForm" class="shadow-md rounded-md bg-white w-full max-w-2xl p-10">
      <div class="flex sm:items-center mb-6 flex-col sm:flex-row">
        <label for="title" class="block sm:w-1/3 font-bold sm:text-right mb-1 pr-4"><span class="text-red-600"> * </span>タイトル</label>
        <input 
            type="text" 
            v-model="form.title" 
            id="title" 
            class="block w-full sm:w-2/3 bg-gray-200 py-2 px-3 text-gray-700 border border-gray-200 rounded focus:outline-none focus:bg-white" />
        <p v-if="errors.title" class="text-red-500">{{ errors.title[0] }}</p>
      </div>
  
      <div class="flex sm:items-center mb-6 flex-col sm:flex-row">
        <label for="body" class="block sm:w-1/3 font-bold sm:text-right mb-1 pr-4"><span class="text-red-600"> * </span>内容</label>
        <textarea v-model="form.body" id="body"  class="block w-full sm:w-2/3 bg-gray-200 py-2 px-3 text-gray-700 border border-gray-200 rounded focus:outline-none focus:bg-white"></textarea>
        <p v-if="errors.body" class="text-red-500">{{ errors.body[0] }}</p>
      </div>

      <div class="flex justify-center">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded fucus:outline-none focus:shadow-outline mt-3">登録</button>
        </div>
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
      const response = await axios.post("route('questions.store')", form.value)
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