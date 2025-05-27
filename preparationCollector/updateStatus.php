<?php
// Disable error display to prevent HTML in JSON response
error_reporting(0);
ini_set('display_errors', 0);

// Set content type to JSON
header('Content-Type: application/json');

// Start output buffering to catch any unexpected output
ob_start();

try {
    include('../include/pgsql_connection.php');
    
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Check if JSON decode was successful
    if ($data === null) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
        exit;
    }
    
    $documentID = isset($data['documentID']) ? $data['documentID'] : null;
    
    // Check for both 'status' and 'newStatus' for compatibility
    $status = isset($data['newStatus']) ? $data['newStatus'] : (isset($data['status']) ? $data['status'] : null);
    
    // Get additional fields for finish operation
    $nbrColier = isset($data['nbrColier']) ? $data['nbrColier'] : null;
    
    if (!$documentID || !$status) {
        ob_clean();
        echo json_encode(['success' => false, 'error' => 'Missing documentID or status']);
        exit;
    }
    
    // Prepare the base query
    if ($nbrColier !== null) {
        // Update with basket numbers (for finish operation)
        $query = 'UPDATE public."ordersPharmacy" SET status = $1, "nbrColier" = $2 WHERE "documentID" = $3';
        $params = [$status, $nbrColier, $documentID];
    } else {
        // Simple status update (for start operation)  
        $query = 'UPDATE public."ordersPharmacy" SET status = $1 WHERE "documentID" = $2';
        $params = [$status, $documentID];
    }
    
    $result = pg_query_params($pg_conn, $query, $params);
    
    if ($result) {
        $affected_rows = pg_affected_rows($result);
        if ($affected_rows > 0) {
            ob_clean();
            echo json_encode([
                'success' => true, 
                'message' => 'Status updated successfully',
                'affected_rows' => $affected_rows
            ]);
        } else {
            ob_clean();
            echo json_encode([
                'success' => false, 
                'error' => 'No rows updated - documentID might not exist'
            ]);
        }
    } else {
        ob_clean();
        echo json_encode([
            'success' => false, 
            'error' => 'Database error: ' . pg_last_error($pg_conn)
        ]);
    }
    
} catch (Exception $e) {
    // Clear any output buffer
    ob_clean();
    echo json_encode([
        'success' => false, 
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}

// End output buffering
ob_end_flush();
?>