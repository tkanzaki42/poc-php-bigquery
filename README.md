# poc-php-bigquery
## 事前準備

### .env に Google Cloud のプロジェクトIDを設定

### サービスアカウント作成
[サービスアカウントキー取得手順](./docs/サービスアカウントキー取得手順.md)

### BigQueryのデータセット作成
[データセット作成手順](./docs/データセット作成手順.md)

### Composer Install
```sh
composer install
```

## テーブル作成
```sh
$ php create_table.php
Table created: sample_table
Data inserted successfully
```

## テーブル読み取り(SQLインジェクションなし)
```sh
$ php read_table.php
ID: 2, Name: Bob, Score: 82
```

## テーブル読み取り(SQLインジェクションあり)
```sh
$ php read_table_injectable.php 
PHP Warning:  Undefined array key "schema" in /Users/t_kanzaki/poc-php-bigquery/vendor/google/cloud-bigquery/src/QueryResults.php on line 170

Warning: Undefined array key "schema" in /Users/t_kanzaki/poc-php-bigquery/vendor/google/cloud-bigquery/src/QueryResults.php on line 170
PHP Warning:  Trying to access array offset on null in /Users/t_kanzaki/poc-php-bigquery/vendor/google/cloud-bigquery/src/QueryResults.php on line 170

Warning: Trying to access array offset on null in /Users/t_kanzaki/poc-php-bigquery/vendor/google/cloud-bigquery/src/QueryResults.php on line 170
```

↑テーブルがDROPされる
