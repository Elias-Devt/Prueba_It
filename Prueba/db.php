<?php
$host="localhost";
$usuario="root";
$password="";
$dbname="prueba";


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>