<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Include both database connections
include("../include/sqlserver_connection.php");
include("../include/pgsql_connection.php");

try {
    // SQL Server query
    $sql = "
       SELECT top (100) 
    d.Oid AS documentID,
    LEFT(il.Label1, 1) AS LocationName
FROM
    [dbo].[COM_Document] d
INNER JOIN
    [dbo].[COM_DocumentDetail] dt ON dt.Document = d.Oid
INNER JOIN
    [dbo].[COM_Batch] b ON dt.Batch = b.Oid
INNER JOIN
    [dbo].[COM_Item] i ON i.Oid = b.Item
INNER JOIN
    [dbo].[COM_ItemLocation] il ON i.Location = il.Oid
WHERE
    d.type = 10
    AND d.ModifiedBy = 'Samir.Rahim' 
GROUP BY
    d.Oid,LEFT(il.Label1, 1)
ORDER BY
    d.Oid,LocationName
    ";
    
    // Prepare and execute the SQL Server query
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();
    
    // Initialize counter for inserted records
    $inserted_count = 0;
    
    // Prepare PostgreSQL insert statement
    $pg_query = 'INSERT INTO public."ordersPharmacy"(
         "documentID", status, zone, date, "heurCreation", "heurFin")
        VALUES ($1, $2, $3, $4, $5, $6)';
    
    // Begin PostgreSQL transaction
    pg_query($pg_conn, "BEGIN");
    $currentTime = date('H:i:s');
    $currentDate = date('Y-m-d');

    foreach ($results as $row) {
        // Extract zone (first letter of LocationName)
        $zone = substr($row['LocationName'], 0, 1);
        
        // Set status (you can modify this as needed)
        $status = 'pending';
        
        // Extract date and time components
        $date = $currentDate;
        $heurCreation = $currentTime;
        $heurFin = '/';
        
        // Parameters for the PostgreSQL query
        $params = array(
            $row['documentID'],
            $status,
            $zone,
            $date,
            $heurCreation,
            $heurFin
        );
        
        // Execute the insert query
        $result = pg_query_params($pg_conn, $pg_query, $params);
        
        if ($result) {
            $inserted_count++;
        } else {
            // Log error or handle failed insert
            error_log("Failed to insert documentID: " . $row['documentID']);
        }
    }
    
    // Commit the transaction if all inserts succeeded
    pg_query($pg_conn, "COMMIT");

} catch (PDOException $e) {
    // Rollback transaction on error
    if (isset($pg_conn)) {
        pg_query($pg_conn, "ROLLBACK");
    }
    die("Error executing SQL Server query: " . $e->getMessage());
} catch (Exception $e) {
    // Rollback transaction on error
    if (isset($pg_conn)) {
        pg_query($pg_conn, "ROLLBACK");
    }
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Document Report</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .container {
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Document Report</h2>
        
        <?php if (isset($inserted_count)): ?>
            <p class="success">Successfully inserted <?= $inserted_count ?> records into PostgreSQL.</p>
        <?php endif; ?>
        
        <?php if (count($results) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Document ID</th>
                        <th>Location Name</th>
                        <th>Zone (First Letter)</th>
                        <th>Date</th>
                        <th>Creation Time</th>
                        <th>Modification Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['documentID']) ?></td>
                            <td><?= htmlspecialchars($row['LocationName']) ?></td>
                            <td><?= htmlspecialchars(substr($row['LocationName'], 0, 1)) ?></td>
                            <td><?= htmlspecialchars($row['DateCreated']->format('Y-m-d')) ?></td>
                            <td><?= htmlspecialchars($row['DateCreated']->format('H:i:s')) ?></td>
                            <td><?= htmlspecialchars($row['DateModified']->format('H:i:s')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No documents found matching the criteria.</p>
        <?php endif; ?>
    </div>
</body>
</html>