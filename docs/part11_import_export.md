# 入出力関連

## 前置き

2025/07現在

## 共通事項

自前実装をするか、パッケージを使用する  
理由がない限りは自前実装より、Laravel対応済みのパッケージを使用した方がよい。  
パフォーマンスの問題や、実装工数の削減が理由。  
特殊なケースとして、Shift-JIS、マクロ対応、特殊なフォーマットに対応するなどの場合は自前実装も検討が必要。

## csv

自前実装をする場合は、`fopen`、`fclose`、`fputcsv`等の昔ながらのPHP標準の関数を使用する。  
ヘッダー固定、少量の出力であれば自前実装でも問題はないが、あえて自前実装をするメリットは薄い。

### パッケージ(ライブラリ)候補

- Laravel-Excel

  MITライセンスで商用利用可。  
  Laravel用に作成されているパッケージなので親和性がある。  
  Laravel11以降にはメジャー、マイナー共に公式では非対応  
  多機能で柔軟。import/export 両対応、バリデーション、イベント、Job連携なども可能。大規模プロジェクト向き。  
  利用者も多く情報が豊富

  対応フォーマット  
  CSV / XLSX / ODS 等一般的なフォーマットには対応している。  
  エクスポート／インポート対応。  

- spatie/simple-excel  

MITライセンスで商用利用可。  
シンプルなCSV/XLSX処理に特化した軽量ライブラリ。大量データでも高速。レイアウト制御などは不可。  
Laravel用に作成されているパッケージ。(Laravel 12対応済み)  
box/spoutを内部で使用している。
かなり直感的に実装可能  

対応フォーマット  
CSV / XLSX
エクスポート／インポート対応。  

---

## excel

csvと同様ではあるが、自前実装をする場合でもPHPライブラリを使用する
よほどの理由が無い限りは、Laravel-Excelを使用する。  

### パッケージ(ライブラリ)候補

- Laravel-Excel

  csvの項で触れているので省略

- spatie/simple-excel

  csvの項で触れているので省略

- PhpSpreadsheet

  Laravel-Excelの内部でも使用されているPHPライブラリ  
  MITライセンスで商用利用可。
  Laravel Excel は PhpSpreadsheet にラッパーをかぶせているので、細かいセル操作やスタイルをしたいなら直接 PhpSpreadsheet を使う場面もある。  
  ただし、処理が遅く、設計が煩雑になりがちなので、基本は Laravel Excel 優先でOK。  
  Laravel 11以降かつ、細かい操作が必要なら採用を検討する。

- Spout

  メモリ効率重視の軽量Excelライブラリ  
  MITライセンスで商用利用可。  
  csvにも対応済み

---

## pdf

出力用のbladeを作成してpdf出力するのが一般的な模様。  
必要になったら調査、実装をする。  
Laravelに対応していない場合はbladeを使えず、自前実装の部分が増えるので回避したい。


### パッケージ候補

- Laravel DomPDF

  Bladeベースで作成可。日本語対応はフォント指定必須。CSS対応に制限あり。

- Laravel Snappy

  wkhtmltopdfベースでCSS対応が強い（BootstrapなどOK）。別途バイナリインストールが必要。

- laravel-mpdf

  高度な日本語PDF出力に向いているが、Laravelとの統合はやや手間。


## 選定方針まとめ

| 処理内容 | 推奨 |
|----------|------|
| 少量のCSV出力（一覧など） | 自前実装 or simple-excel |
| 大量データのCSV/XLSX入出力 | Laravel Excel |
| 日本語PDF出力（帳票・報告書など） | Laravel DomPDF or Snappy |
| 特殊なExcel仕様（マクロ・罫線等） | PhpSpreadsheetを直接利用 |


## 今回の実装

環境がLaravel 11なのでsimple-excelを採用する。  
が、Laravel-Excelも一応の回避は可能なので両方試す。


### 導入手順

- simple-excel  
composer require spatie/simple-excel

- Laravel-Excel  
composer require maatwebsite/excel:3.1.x-dev@dev  
事前にGD関連のモジュールを追加する


> 参考
https://reffect.co.jp/laravel/laravel_excel_master  

