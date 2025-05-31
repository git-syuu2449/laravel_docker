# part9 ポリシー

##  ポリシーについて

Laravelにおける**認可処理（ユーザーに対する操作権限の制御）**をまとめて定義する仕組み。  
コントローラやビューなどで `Gate` や `$this->authorize()` を通じて呼び出し、ユーザーの操作可否を判定する。

---

##  ポリシーメソッドの構成

```php
public function update(User $user, Post $post)
```

- **第一引数**：常に `User`（ログインユーザー）
- **第二引数以降**：対象モデルやその他の引数

---

##  Gate の呼び出し方（サービス層・コレクション等）

### 基本形（モデルあり）

```php
Gate::allows('update', $post); // Auth::user(), $post が渡る
```

### 引数が複数ある場合

```php
public function vote(User $user, Post $post, bool $isAnonymous)

Gate::allows('vote', [$post, true]);
```

### User引数のみの場合

```php
public function accessDashboard(User $user)

Gate::allows('accessDashboard');
```

---

##  `$this->authorize()` の使い方（コントローラ用）

```php
public function show(Post $post)
{
    $this->authorize('view', $post);
    
    return view('posts.show', compact('post'));
}
```

- 認可失敗時は `403 Forbidden` の例外が自動で発生
- Gateの記述よりシンプル＆明示的
- 特に「そのページ自体を誰かに見せていいか」の判定に向いている

---

##  ポリシーの作成

```bash
php artisan make:policy PostPolicy --model=Post
```

---

##  ポリシーの登録（App\Providers\AuthServiceProvider）

```php
use App\Models\Post;
use App\Policies\PostPolicy;

protected $policies = [
    Post::class => PostPolicy::class,
];
```

---

##  `before()` フック（全ポリシー共通）

```php
public function before(User $user, string $ability)
{
    if ($user->isAdmin()) {
        return true; // すべての操作を許可
    }
}
```

---

##  UI 表示制御で使う例（コレクション）

```php
$questions->each(function ($question) {
    $question['can_be_evaluated'] = Gate::allows('evaluate', $question);
});
```

---

##  サービス層で使う例

```php
use Illuminate\Support\Facades\Gate;

if (Gate::allows('evaluate', $question)) {
    // OK
}
```

---

##  ミドルウェアとポリシーの使い分け

| 用途 | 方法 | 用例 |
|------|------|------|
| ロール（admin、userなど）に基づくアクセス制限 | `middleware('role:admin')` | 管理画面アクセス制限 |
| モデル個別の操作可否（所有権など） | `Gate`, `$this->authorize()` | 自分の投稿だけ編集/閲覧可 |

---

##  具体例

### ルートミドルウェアでは制限不可のケース


コントローラでポリシーを使用することで制限をする  
権限がない場合は403

```php
public function show(Question $question)
{
    $this->authorize('view', $question); // PostPolicy@view に処理を委譲

    ...後続の処理
}
```

---

##  まとめ

- Laravelのポリシーは **User を自動で補完してくれる**
- 表示制御や操作制限など、**ビジネスロジック由来の「操作できるか」判定**はポリシーで切り出すべき
- `Gate::allows()` は**サービス層・コレクション操作など非コントローラ部**で使える
- `$this->authorize()` は**コントローラ内の明示的なアクセス制御**で便利

