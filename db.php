<?php
$host = "rei.cs.ndsu.nodak.edu";
$user = "moses_francis_371s26";
$pass = "FErpPHP8dN4!";
$dbname = "moses_francis_db371s26";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed.");
}
