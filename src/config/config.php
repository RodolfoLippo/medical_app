<?php
// Archivo de configuración principal

// Configuración de la base de datos
$servername = "localhost"; 
$username = "root"; // nombre de usuario
$password = "newpassword"; //contraseña de la bbdd
$dbname = "MedicalApp"; // Nombre base de datos

// establecer conexion a la bbdd
$conn = new mysqli($servername, $username, $password, $dbname);

// verificar conexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
