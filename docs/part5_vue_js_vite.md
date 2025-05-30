# Part 5: Vue / JavaScript / Vite

## Vueとの連携（非同期処理）

このアプリでは、投票部分をVue 3で非同期化し、ユーザー体験を向上させています。

### 採用理由

- Bladeだけでは難しい「即時反映」「非同期通信」に対応したかったため
- Laravelとの親和性が高く、Viteでのビルドがシンプル

### 基本構成

- Vue 3（Composition API）
- Axios（Laravel APIと非同期通信）
- 単一ファイルコンポーネント（SFC）

```vue
<!-- VoteButton.vue -->
<template>
  <button @click="vote(choiceId)">投票</button>
</template>

<script setup>
import axios from 'axios';

const props = defineProps({ choiceId: Number });
const vote = async (id) => {
  await axios.post(`/api/choices/${id}/vote`);
};
</script>
```

---

## ⚙️ JavaScript構成とアセット管理

- 全JSファイルは `resources/js` 以下に配置
- `app.js` は全体読み込み、ページ個別JSは `questions/index.js` などで分離

### Bladeからの呼び出し方法

```blade
@push('scripts')
<script type="module" src="{{ Vite::asset('resources/js/questions/index.js') }}"></script>
@endpush
```

---

## Viteの構成とビルド

### 特徴

- Laravel 11 では Vite(ヴィット) がデフォルトで採用されており、高速ビルドとHMRに対応
- Vue・Tailwindとの連携も容易

### vite.config.js（抜粋）

```js
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': '/resources/js',
    },
  },
});
```

### ビルド方法

```bash
npm install

# 開発用（HMR対応）
npm run dev

# 本番ビルド
npm run build
```

### TailWindの設定

np, run dev実行時に追加、設定したclassをホットリロードでビルドする。  
tailwind.config.jsに設定を追記する。  
追加をしないと毎回手動で立ち上げ直す必要がある。


```js
content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.css',
        './resources/**/*.vue',
    ],

```

---

## 工夫点と課題

| 項目 | 内容 |
|------|------|
| Vueとの共存 | Bladeで構築したページ内にVueコンポーネントをマウントする構成を採用（インクリメンタルSPA） |
| スコープの分離 | グローバルCSSとページ単位のJS/CSSを分離管理し、Viteのキャッシュ問題を回避 |
| vite/client多重読み込み問題 | Bladeでの`@vite`使用箇所を1箇所に限定。各ページでは`@push('scripts')`と`@stack('scripts')`で追加読込 |
| propsの受け渡し | サーバー側で埋め込んだデータをVueへ渡す際に `data-*` 属性や `window` 経由で対応 |

---

## 補足

- Vue導入は投票機能の非同期化を目的とし、Blade中心のアプリでもスムーズに取り入れられる構成としました。
- Laravel + Vite + Vue の構成により、モダンな開発フロー（HMR・分割ビルド）を体験できるようにしています。
