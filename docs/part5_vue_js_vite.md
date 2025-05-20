# Part 5: Vue / JavaScript / Vite連携

## Vite構成

- `vite.config.js` でビルド対象を設定
- `resources/js/`, `resources/css/` を管理

```bash
npm install
npm run dev     # 開発モード（HMRあり）
npm run build   # 本番ビルド
```

## BladeでのJS/CSS読み込み

```blade
@vite(['resources/js/app.js', 'resources/css/app.css'])
@push('scripts')
<script type="module" src="{{ Vite::asset('resources/js/questions/index.js') }}"></script>
@endpush
```

## Vue構成

- 単一ファイルコンポーネント（SFC）

```vue
<template></template>
<script setup></script>
<style scoped></style>
```

- コンポーネント分割、props/emit、非同期処理（Axios）などを活用
- Laravel Sanctumと連携し、SPA構成に対応可能