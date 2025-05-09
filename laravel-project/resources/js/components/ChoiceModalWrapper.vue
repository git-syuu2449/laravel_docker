<template>
    <Modal v-if="show" @close="show = false">
      <Choiceorm :post-url="postUrl" :question-id="questionId" @submitted="onSubmitted" />
    </Modal>
  
    <button @click="show = true">評価を追加</button>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import Modal from './Modal.vue'
  import Choiceorm from './ChoiceForm.vue'
  
  defineProps({
    postUrl: String,
    questionId: Number
  })
  
  const show = ref(false)
  
  const onSubmitted = () => {
    show.value = false
    // 任意で親に emit して一覧更新も可能
  }
  </script>