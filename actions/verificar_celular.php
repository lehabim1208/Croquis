<?php
include("../config/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["phone"])) {
    $phone = $_POST["phone"];
    $sql = "SELECT COUNT(*) FROM usuario WHERE celular = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
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