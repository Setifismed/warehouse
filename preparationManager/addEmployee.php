<?php
// Include both database connections
include("../include/sqlserver_connection.php");
include("../include/pgsql_connection.php"); // Assuming you have this for PostgreSQL

try {
    // SQL Server query
    $sql = "
        SELECT
            d.Oid AS documentID,
            il.Label1 AS LocationName
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
    ";
    
    // Prepare and execute the SQL Server query
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll();

    // PostgreSQL insert query
    $pg_query = "INSERT INTO public.\"ordersPharmacy\"(
        id, \"documentID\", status, zone, date, \"heurCreation\", \"heurFin\")
        VALUES (DEFAULT, $1, 'pending', $2, CURRENT_DATE, CURRENT_TIME, NULL)";
    
    // Prepare the PostgreSQL statement
    $pg_stmt = pg_prepare($pg_conn, "insert_order", $pg_query);
    
    if (!$pg_stmt) {
        die("Error preparing PostgreSQL statement: " . pg_last_error($pg_conn));
    }

    // Process each row and insert into PostgreSQL
    $inserted_count = 0;
    foreach ($results as $row) {
        $documentID = $row['documentID'];
        $location = $row['LocationName'];
        $zone = substr($location, 0, 1); // Get first letter of location
        
        // Execute the prepared statement with parameters
        $result = pg_execute($pg_conn, "insert_order", array($documentID, $zone));
        
        if ($result) {
            $inserted_count++;
        } else {
            error_log("Error inserting documentID $documentID: " . pg_last_error($pg_conn));
        }
    }

} catch (PDOException $e) {
    die("Error executing SQL Server query: " . $e->getMessage());
} catch (Exception $e) {
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['documentID']) ?></td>
                            <td><?= htmlspecialchars($row['LocationName']) ?></td>
                            <td><?= htmlspecialchars(substr($row['LocationName'], 0, 1)) ?></td>
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