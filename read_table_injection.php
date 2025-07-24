<?php
require 'vendor/autoload.php';

use Google\Cloud\BigQuery\BigQueryClient;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/key.json');
$projectId = $_ENV['GOOGLE_CLOUD_PROJECT_ID'];
$datasetId = 'sample_dataset';
$tableId = 'sample_table';

$bigQuery = new BigQueryClient([
    'projectId' => $projectId,
]);

// 外部入力（本来はGETやPOSTから）今回はベタ書き
$unsafeName = "Bob'; DROP TABLE `{$projectId}.{$datasetId}.{$tableId}`; --";

$query = "SELECT * FROM `$projectId.$datasetId.$tableId` WHERE name = '$unsafeName'";

$queryJobConfig = $bigQuery->query($query);
$queryResults = $bigQuery->runQuery($queryJobConfig);

if ($queryResults->isComplete()) {
    foreach ($queryResults->rows() as $row) {
        echo "ID: {$row['id']}, Name: {$row['name']}, Score: {$row['score']}\n";
    }
} else {
    echo "Query failed.\n";
}
