<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../include/pgsql_connection.php');
header('Content-Type: application/json');

if (!isset($_GET['documentID'])) {
    echo json_encode([]);
    exit();
}

$documentID = $_GET['documentID'];

$sql = 'SELECT zone, nbrcolier AS basketNum , status
        FROM public."ordersPharmacy" 
        WHERE documentID = $1 AND zone <> $2';

$result = pg_query_params($pg_conn, $sql, [$documentID, 'P']);

if (!$result) {
    echo json_encode(['error' => pg_last_error($pg_conn)]);
    exit();
}

$data = [];
while ($row = pg_fetch_assoc($result)) {
    $row['zone'] = substr($row['zone'], 0, 1); // Get first letter
    $row['status'] = preg_replace('/\s+/u', ' ', trim($row['status'])); // Clean up status
    $data[] = $row;
}

echo json_encode($data);
?>
