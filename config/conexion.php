<?php
$servername = "localhost";
$username = "lehabim";
$password = "u9wt&4r&Bsk3V"; 
$dbname = "reservaciones_metodo";


$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
