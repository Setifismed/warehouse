<?php
include('../include/pgsql_connection.php');

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('No ID provided');
    }

    $id = intval($_GET['id']);
    if ($id < 0) {
        throw new Exception('Invalid ID');
    }

    $query = "SELECT * FROM users WHERE id =".$id;
    $result = pg_query_params($pg_conn, $query);

    if (!$result) {
        throw new Exception('Database query failed');
    }

    if (pg_num_rows($result) === 0) {
        throw new Exception('Member not found');
    }

    $member = pg_fetch_assoc($result);
    echo json_encode($member);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>