<template>
  <div class="mb-4">
    <label for="title"><span class="text-red-600">*</span> タイトル</label>
    <input
      type="text"
      id="title"
      name="title"
      v-model="form.title"
      class="border w-full"
    />
    <p v-if="errors.title" class="text-red-500 text-sm">{{ errors.title[0] }}</p>
  </div>

  <div class="mb-4">
    <label for="body"><span class="text-red-600">*</span> 本文</label>
    <textarea
      id="body"
      name="body"
      v-model="form.body"
      class="border w-full"
      rows="6"
    >
    </textarea>
    <p v-if="errors.body" class="text-red-500 text-sm">{{ errors.body[0] }}</p>
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
  title: props.old.title || '',
  body: props.old.body || '',
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
