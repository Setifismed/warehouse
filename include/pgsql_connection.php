<?php
$connection_string = sprintf("host=localhost port=5432 dbname=bdd user=postgres password=postgres");
$pg_conn = pg_connect($connection_string) or die('PostgreSQL connection failed: ' . pg_last_error());
if(!$pg_conn)echo("not connectd");
?>
