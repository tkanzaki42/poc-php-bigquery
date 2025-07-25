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

$safeName = "Bob";

$query = <<<SQL
SELECT id, name, score
FROM (
    SELECT id, name, score
    FROM `$projectId.$datasetId.$tableId`
    WHERE name = @name
    ORDER BY score DESC
    LIMIT 10
)
WHERE score > 50
SQL;

$queryJobConfig = $bigQuery->query($query)
    ->parameters([
        'name' => $safeName,
    ]);

$queryResults = $bigQuery->runQuery($queryJobConfig);

if ($queryResults->isComplete()) {
    foreach ($queryResults->rows() as $row) {
        echo "ID: {$row['id']}, Name: {$row['name']}, Score: {$row['score']}\n";
    }
} else {
    echo "Query failed.\n";
}
