<!-- ランキングの親コンポーネント -->
<template>
  <div class="w-full">
    <top-tab
    :tabs="props.types"
    :selectType="selectType"
    @selectedTab="handleUpdate"
    />

    <top-list
    :rankings="rankings"
    :showBaseUrl="props.showBaseUrl"
    />
  </div>

</template>

<script setup lang="ts">
import axios from 'axios'
import { ref, onMounted, computed } from 'vue'

// 一覧
import TopList from './TopList.vue'
// タブ
import TopTab from './TopTab.vue'


const props = defineProps({
  types: Array,
  getUrl: String,
  defaultType: String,
  showBaseUrl: String,
})

// API取得後の値
const rankings = ref([])
// 選択されたタイプ
const selectType = ref(props.defaultType);
const success = ref(false)
// 条件 タブ選択で変更
const params = computed(() => ({
  type: selectType.value,
}))

// 一覧をリフレッシュ
const doSearch = async () => {
  success.value = false
  try {
    await axios.get(props.getUrl, {
      // 送信パラメータ
      params: params.value
    })
    .then(res => {
      // ランキングを更新
      rankings.value = res.data.rankings
    })
    success.value = true
  } catch (error) {
    alert('ランキングの取得に失敗しました。')
    console.error(error)
  }
}

// 表示時に取得
onMounted(() => {
  doSearch()
})

// タブ選択時
const handleUpdate = (tab) => {
  selectType.value = tab
  doSearch()
}

</script>