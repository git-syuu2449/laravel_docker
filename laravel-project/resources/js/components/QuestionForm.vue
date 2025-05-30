<template>
  <!-- タイトル -->
  <div>
    <label for="title" class="block text-sm font-medium text-gray-700">
      <span class="text-red-600">*</span> タイトル
    </label>
    <input
      type="text"
      id="title"
      name="title"
      v-model="form.title"
      class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
    />
    <p v-if="errors.title" class="text-red-500 text-sm mt-1">{{ errors.title[0] }}</p>
  </div>

  <!-- 本文 -->
  <div>
    <label for="body" class="block text-sm font-medium text-gray-700">
      <span class="text-red-600">*</span> 本文
    </label>
    <textarea
      id="body"
      name="body"
      v-model="form.body"
      rows="6"
      class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
    ></textarea>
    <p v-if="errors.body" class="text-red-500 text-sm mt-1">{{ errors.body[0] }}</p>
  </div>

  <!-- 画像 -->
  <div>
    <label for="images" class="block text-sm font-medium text-gray-700">画像</label>
    <input
      type="file"
      id="images"
      name="images[]"
      multiple
      @change="handleFiles"
      class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
            file:rounded-md file:border-0 file:text-sm file:font-semibold
            file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
    />
    <p v-if="errors.images" class="text-red-500 text-sm mt-1">{{ errors.images[0] }}</p>
    <ul v-if="hasImageErrors" class="mt-2 list-disc list-inside text-red-500 text-sm">
      <li v-for="(message, index) in imageErrorMessages" :key="index">{{ message }}</li>
    </ul>
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
