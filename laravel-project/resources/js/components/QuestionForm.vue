<template>
  <div class="mb-4">
    <label for="question_text"><span class="text-red-600">*</span> 質問内容</label>
    <input
      type="text"
      id="question_text"
      name="question_text"
      v-model="form.question_text"
      class="border w-full"
    />
    <p v-if="errors.question_text" class="text-red-500 text-sm">{{ errors.question_text[0] }}</p>
  </div>

  <div class="mb-4">
    <label for="images">画像</label>
    <input
      type="file"
      id="images"
      name="images[]"
      multiple
      @change="handleFiles"
      class="border w-full"
    />
    <p v-if="errors.images" class="text-red-500 text-sm">{{ errors.images[0] }}</p>
    <div v-if="hasImageErrors">
      <ul>
        <li v-for="(message, index) in imageErrorMessages" :key="index" class="text-red-500">
          {{ message }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { reactive, computed } from 'vue'

const props = defineProps({
  errors: Object,
  old: Object,
})

// ローカル状態
const form = reactive({
  question_text: props.old.question_text || '',
  images: [],
})

// 親に公開するメソッド
const getFormData = () => form
defineExpose({ getFormData })

// ファイル変更をトリガーに手動でセット（multipart/form-data対応 ）
const handleFiles = (event) => {
  form.images = Array.from(event.target.files)
}

// バリデーションエラー処理
const hasImageErrors = computed(() =>
  Object.keys(props.errors || {}).some(key => key.startsWith('images.'))
)

// イメージのエラーを展開
const imageErrorMessages = computed(() =>
  Object.entries(props.errors || {})
    .filter(([key]) => key.startsWith('images.'))
    .flatMap(([, messages]) => messages)
)
</script>
