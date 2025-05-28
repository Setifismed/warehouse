<?php
require('../include/pgsql_connection.php');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['documentID'])) {
    echo json_encode(['error' => 'Missing documentID']);
    exit();
}

$documentID = $data['documentID'];
$startTime = date('H:i:s');

$sql = 'UPDATE public."ordersCollectors" SET status = $1, "heurDebut" = $2 WHERE "documentID" = $3';
$result = pg_query_params($pg_conn, $sql, ['En cours', $startTime, $documentID]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Update failed']);
}
?>
