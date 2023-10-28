<?php
include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
    $email = $_POST["email"];
    $sql = "SELECT COUNT(*) FROM usuario WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    if ($count > 0) {
        echo "exists";
    } else {
        echo "not_exists";
    }
} else {
    echo "invalid_request";
}
?>