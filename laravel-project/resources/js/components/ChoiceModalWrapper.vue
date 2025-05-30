<template>
  <Modal v-if="show" @close="show = false">
    <ChoiceForm
      :post-url="postUrl"
      :question-id="questionId"
      @submitted="onSubmitted"
    />
  </Modal>

  <button
    @click="show = true"
    class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
  >
    評価を追加
  </button>
</template>
  
  <script setup>
  import { ref } from 'vue'
  import Modal from './Modal.vue'
  import ChoiceForm from './ChoiceForm.vue'
  
  const props = defineProps({
    postUrl: String,
    questionId: Number
  })
  
  const show = ref(false)
  
  const onSubmitted = () => {
    show.value = false
    // 任意で親に emit して一覧更新も可能
  }
  </script>