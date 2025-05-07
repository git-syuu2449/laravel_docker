<template>

    <div class="mb-4">
      <label for="title">質問内容</label>
      <input
        type="text"
        name="question_text"
        id="question_text"
        :value="form.question_text"
        class="border w-full"
      />
      <p v-if="errors.question_text" class="text-red-500 text-sm">{{ errors.question_text[0] }}</p>
    </div>

</template>
  
<script>
  export default {
    name: 'QuestionForm',
    props: {
      postUrl: String,
      errors: Object,
      old: Object
    },
    data() {
      return {
        form: {
            question_text: this.old.question_text || '',
        },
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    }
  }
</script>