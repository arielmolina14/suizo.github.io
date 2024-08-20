<?php
$servername = "10.0.10.28";
$username = "llbdcgcn_amolina";
$password = "%UgMv8u^2WHyTT3!";
$dbname = "llbdcgcn_suizo";

// Crear conexi¨®n
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexi¨®n
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
