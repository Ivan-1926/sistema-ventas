<?php
$host = '127.0.0.1';
$user = 'root';
$password = '';
$database = 'ventas_db';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>