# Part 6: Api

## 導入方法

Laravel標準で機構が用意されている。  
導入は以下

- php artisan install:api

API関連のファイルの追加や設定の追加を行う。  
Sanctumがインストールされる。


- ~~php artisan breeze:install api~~

画面を使わない場合に使用する。  
画面+API構成だと画面用のファイルが削除されるので要注意

- composer require laravel/sanctum

あとから認証機能付きのAPIを作成したい時に個別に追加する。  
ログインは画面からbreezeでログインを行い、APIのみcsrf認証したい時。  

## 認証

前述の通り、Sanctumを利用する。  
認証方式は以下の通り

- SPA認証(クッキー認証)

- APIトークン認証

- モバイルアプリケーション認証


---

## 補足:

postman
テスト用。  
POST,GETのリクエストを投げてくれる支援ツール  
curlをいちいち発行しなくていいので楽。