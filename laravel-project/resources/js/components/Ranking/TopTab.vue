<template>
    <div class="flex space-x-2 border-b mb-4">
      <button
        v-for="tab in props.tabs"
        :key="tab.value"
        @click="() => selectedTab(tab.value)"
        :class="[
          'px-4 py-2 text-sm font-medium border-b-2',
          props.selectType === tab.value
            ? 'border-blue-500 text-blue-600'
            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
        ]"
      >
        {{ tab.label }}
      </button>
    </div>
</template>

<script setup lang="ts">

const props = defineProps({
  tabs: Array,
  selectType: String,
})

// emitの定義
const emit = defineEmits(["selectedTab"])

// タブ選択
const selectedTab = (tab) => {
    emit('selectedTab', tab)
}

</script>