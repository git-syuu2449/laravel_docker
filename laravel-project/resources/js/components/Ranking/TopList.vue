<template>
    <!-- ランキング一覧 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

        <template v-if="rankings.length === 0">
            <p class="text-gray-500">ランキングはありません。</p>
        </template>

      <div
        v-for="item in rankings"
        :key="item.id"
        class="bg-white rounded-2xl shadow p-4"
      >
        <a :href="showBaseUrl + item.question.id">
            <div class="text-lg font-semibold text-gray-800">
            {{ item.question.title }}
            </div>
            <div class="mt-1 text-sm text-gray-500">
            {{ item.description }}
            </div>
            <div class="mt-2 text-xs text-gray-400">
            トータルスコア: {{ item.total }}
            </div>
            <div class="mt-2 text-xs text-gray-400">
            平均スコア: {{ item.average }}
            </div>
            <div class="mt-2 text-xs text-gray-400">
            投票数: {{ item.count }}
            </div>
        </a>
      </div>
    </div>
</template>

<script setup lang="ts">

const props = defineProps({
  rankings: Object,
  showBaseUrl: String,
})

</script>