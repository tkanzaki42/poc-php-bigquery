<?php
require 'vendor/autoload.php';

use Google\Cloud\BigQuery\BigQueryClient;
use Dotenv\Dotenv;

// .env 読み込み
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// 環境変数セット（サービスアカウント認証）
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/key.json');

$projectId = $_ENV['GOOGLE_CLOUD_PROJECT_ID'];
$datasetId = 'sample_dataset';
$tableId = 'sample_table';

$bigQuery = new BigQueryClient([
    'projectId' => $projectId,
]);

$dataset = $bigQuery->dataset($datasetId);

// スキーマ定義
$schema = [
    ['name' => 'id', 'type' => 'INTEGER'],
    ['name' => 'name', 'type' => 'STRING'],
    ['name' => 'score', 'type' => 'FLOAT'],
];

// テーブル作成（なければ）
$table = $dataset->table($tableId);
if (!$table->exists()) {
    $table = $dataset->createTable($tableId, [
        'schema' => ['fields' => $schema],
    ]);
    echo "Table created: $tableId\n";
} else {
    echo "Table already exists: $tableId\n";
}

// サンプルデータを挿入
$insertResponse = $table->insertRows([
    ['data' => ['id' => 1, 'name' => 'Alice', 'score' => 90.5]],
    ['data' => ['id' => 2, 'name' => 'Bob', 'score' => 82.0]],
    ['data' => ['id' => 3, 'name' => 'Charlie', 'score' => 88.8]],
]);

if ($insertResponse->isSuccessful()) {
    echo "Data inserted successfully\n";
} else {
    foreach ($insertResponse->errors() as $error) {
        print_r($error);
    }
}
