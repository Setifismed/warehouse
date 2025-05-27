<?php
// Include the database connection file
include('../include/pgsql_connection.php');

// Check if the ID parameter is set
if (isset($_POST['id'])) {
    // Get the employee ID
    $id = intval($_POST['id']);

    // Prepare the query to get employee data
    $query = "SELECT * FROM users WHERE id = $1";

    // Execute the query with parameter
    $result = pg_query_params($pg_conn, $query, array($id));

    // Check if the query was successful
    if ($result && pg_num_rows($result) > 0) {
        // Fetch the employee data
        $employee = pg_fetch_assoc($result);

        // Return the employee data as JSON
        echo json_encode($employee);
    } else {
        // Return an error message
        echo json_encode(array('error' => 'Employee not found'));
    }
} else {
    // Return an error message
    echo json_encode(array('error' => 'No employee ID provided'));
}
?>