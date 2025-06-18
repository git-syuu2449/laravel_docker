# Docker 開発環境の初期化とスナップショット運用メモ

## ✅ 1. 全コンテナの停止と削除

```bash
docker container stop $(docker container ls -aq)
docker container rm $(docker container ls -aq)
```

## ✅ 2. 未使用のリソースを一括削除（イメージ、ボリューム、ネットワーク）

```bash
docker system prune -a --volumes
```

- `-a`：未使用のすべてのイメージ削除
- `--volumes`：未使用のボリュームも削除

## ✅ 3. ボリュームをすべて削除（必要に応じて）

```bash
docker volume rm $(docker volume ls -q)
```

## ✅ 4. カスタムネットワークも削除

```bash
docker network rm $(docker network ls -q)
```

> ※ デフォルトの `bridge`, `host`, `none` は削除不可

## ✅ 5. Docker Compose プロジェクトを削除（ボリューム含む）

```bash
docker compose down --volumes --remove-orphans
```

---

## ✅ 6. 完全初期化ワンライナー（要注意）

```bash
docker container stop $(docker ps -aq) && \
docker container rm $(docker ps -aq) && \
docker system prune -a --volumes -f && \
docker volume rm $(docker volume ls -q) && \
docker network rm $(docker network ls -q)
```

> DB・ファイルなどすべて削除されるため注意。

---

## ✅ 7. イメージのスナップショット（変更前バックアップ）

```bash
docker container ls 
docker container stop コンテナ
docker image tag laravel_docker-app:latest laravel_docker-app:snapshot-before-apt
```

- `my-app:latest` → `my-app:snapshot-before-apt` にタグ付けして保存

---

## ✅ 8. コンテナの状態をコミット（即席バックアップ）

```bash
docker container ls 
docker container stop コンテナID
docker commit <container_id> my-backup-image:before-apt
```

- `apt install` などの前に保存しておくと、すぐ戻せる

---

## ✅ 9. コンテナをアーカイブとして保存（ファイルでバックアップ）

```bash
docker export <container_id> > backup.tar
# 復元
cat backup.tar | docker import - my-app:restored
```

> 履歴やタグ情報は保持されないため、用途は限定的。

---

## 🔍 補足：<none> タグのイメージが大量にできる理由

- `docker build` 時に `-t` でタグを付けないと `<none>` の中間イメージが残る
- `docker image prune` で削除可能（`-a` で未使用イメージ全部）

```bash
docker image prune          # Dangling（<none>）イメージのみ
docker image prune -a       # タグ付き含めて全部
```

---

## ✅ 10. 再構築の流れ（初期化後）

```bash
docker compose build
docker compose up -d
```

マイグレーション・シーディングは別途手動 or スクリプトで行う。


---

docker run

イメージから新しいコンテナを作って、それを実行する

- docker run -it --rm ubuntu bash → テスト用に中身確認
- docker run -d -p 8080:80 --name web nginx → 実際の起動用途
- docker compose exec app bash → 開発中に中に入る
- --rm つければ dry-run 的に壊しても安心
- dry-run は自作スクリプトで制御 or 確認コマンドで代用
- docker run	新しいコンテナ作成＋実行（1回限り）	テスト、1回きりコマンド実行  
- docker-compose up	複数サービスをまとめて起動・管理	開発環境の常時起動

docker run opt

- -d	デタッチ（バックグラウンド）実行
- -p 8080:80	ポートフォワード（host:container）
- --name foo	コンテナに名前をつける（後で参照しやすくなる）
- -v /path:/path	ホストとコンテナ間のファイル共有（bind mount）
- --rm	実行終了後にコンテナを削除（使い捨て用途に便利）
- --entrypoint	DockerfileのENTRYPOINTを上書きしてコマンドだけ実行
- -e KEY=VALUE	環境変数の指定（LaravelでAPP_ENV=localとか）