# その他

使ったことのあるLaravel独自の要素  

## Laravel独自の型等

### Collection  

配列を扱いやすい形にして提供する  
定義は以下

```
collect([
    [''=>'', ''=>'']
]);
```

扱えるメソッドは以下（抜粋）

- 配列にして返却
`->all()`

- 最初の要素を返却
`->first()`

- 条件でフィルタリング
`->where('a', '=', 'a')`

- 要素の取り出し（マップ）
`->map(fn($item) => ...)`

- フィルター
`->filter(fn($item) => ...)`

- 除外
`->reject(fn($item) => ...)`

- pluck`->pluck('name')`

- 合計・平均・カウント
`->sum(), ->avg(), ->count()`

- 条件一致チェック
`->contains(fn($item) => ...)`
