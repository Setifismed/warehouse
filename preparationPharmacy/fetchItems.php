<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../include/sqlserver_connection.php');

header('Content-Type: application/json'); // Set header for JSON response

$documentId = $_GET['DocumentID'] ?? null; // Get DocumentID from the request
$zone = $_GET['zone'] ?? null;

if ($documentId) {
    try {
        $sql = "SELECT dt.Label1 AS Name, 
               dt.Quantity AS QTE,
               il.Label1 as itemLocation
               FROM COM_DocumentDetail dt 
               INNER JOIN COM_Batch b ON dt.Batch = b.Oid 
               INNER JOIN COM_Item i on i.Oid=b.Item
               INNER JOIN COM_ItemLocation il on il.Oid=i.Location
               WHERE dt.Document = :documentId";
        
        // Add zone filter if provided
        if ($zone) {
            $sql .= " AND il.Label1 LIKE :zone";
            $zoneParam = $zone . '%';
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':documentId', $documentId);
        if ($zone) {
            $stmt->bindParam(':zone', $zoneParam);
        }

        $stmt->execute();

        $products = [];
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            foreach ($results as $row) {
                $products[] = [
                    'name' => htmlspecialchars($row['Name']),
                    'quantity' => htmlspecialchars($row['QTE']),
                    'location' => htmlspecialchars($row['itemLocation'] ?? '')
                ];
            }
            echo json_encode(['success' => true, 'products' => $products]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No products found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No document ID provided.']);
}
?>