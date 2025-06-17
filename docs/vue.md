# vue.js

メモ。

- スプレッド構文

```js
// ...
const members = ['Taro', 'Jiro', 'Saburo'];
const members2 = [...members, 'Shiro'];
// 以下と同じ
const members2 = ['Taro', 'Jiro', 'Saburo', 'Shiro'];
```

- ref

object  
refにセットした後は.valueで値を参照、再セット

- v-bind

バインディングをする

```js
// コンポーネントに渡す際の指定
:v-bind:hoge="'hogemoge'"
// 省略系 vsコードだと警告が出ることがあるため、基本は省略しない
:hoge="'hogemoge'"

// tips
// 以下の書き方はvueに渡す際にプロパティではなく文字列として渡るため、解決ができなくてエラーになる
// v-bind:hoge='{{$hoge}}'
// 以下なら解決できる
// v-bind:hoge="'{{$hoge}}'"

```

- v-on

ハンドラー  
jsでいうonClick等

```js
//　コンポーネントに渡す際の指定
v-on:click="handler"
// 省略系 基本的に省略して書くことが多い
@click="handler"


```

- v-model

value属性＋@inputイベントを簡略化したもの

```js
// コンポーネントに渡す際の指定
:v-model:"form.hoge"
// 以下と同じ
:value="form.hoge" @input="form.hoge = $event.target.value"

```

- watch

リアクティブな要素を監視して発火

```js
// パスワードの整合性チェックをリアルタイムで行いたい時
watch(() => [password.value, confirmPassword.value], ([pw, confirm]) => {
  isMatch.value = pw === confirm
})

```


> 参考：

https://ja.vuejs.org/guide/introduction.html