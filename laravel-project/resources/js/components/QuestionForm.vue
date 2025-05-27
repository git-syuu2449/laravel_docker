<template>

    <div class="mb-4">
      <label for="title"><span class="text-red-600"> * </span>質問内容</label>
      <input
        type="text"
        name="question_text"
        id="question_text"
        :value="form.question_text"
        @input="$emit('update:question_text', $event.target.value)"
        class="border w-full"
      />
      <p v-if="errors.question_text" class="text-red-500 text-sm">{{ errors.question_text[0] }}</p>
    </div>
    <div class="mb-4">
      <label for="title">画像</label>
      <input
        type="file"
        multiple
        name="images[]"
        id="images"
        :value="form.images"
        class="border w-full"
      />
      <p v-if="errors.images" class="text-red-500 text-sm">{{ errors.images[0] }}</p>
      <!-- multipleを使用するとimages.0のような形でエラーメッセージを受け取れるが、v-if等で判定ができないため判定処理を独自に入れる -->
      <div v-if="hasImageErrors">
        <ul>
          <li v-for="(message, index) in imageErrorMessages" :key="index" class="text-red-500">
            {{ message }}
          </li>
        </ul>
      </div>
    </div>

</template>
  
<script>
  export default {
    name: 'QuestionForm',
    props: {
      errors: Object,
      old: Object,
      formData: Object,
    },
    data() {
      return {
        form: {
            question_text: this.old.question_text || '',
            images: this.old.images || '',
        },
        // csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    },
    emits: ['update:question_text'],
    computed: {
      // イメージでのエラーが起きているか
      hasImageErrors() {
        return Object.keys(this.errors).some(key => key.startsWith('images.'));
      },
      // イメージのエラーメッセージをフィルタ
      imageErrorMessages() {
        return Object.entries(this.errors)
          .filter(([key]) => key.startsWith('images.'))
          .flatMap(([, messages]) => messages);
      }
    }
  }
</script>