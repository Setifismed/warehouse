<?php
try {
    $serverName = "192.168.36.124";
    $database = "IT-Test";
    $username = "sa";
    $password = "Setifismed@2022@Setifismed";

    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to SQL Server: " . $e->getMessage());
}
?>
