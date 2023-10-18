<?php
// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../config/conexion.php';

    // Recuperar los datos del formulario
    $idHorario = $_POST["idHorario"];

    // Consulta SQL para eliminar el horario en la base de datos
    $sql = "DELETE FROM horarios WHERE idHorario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $idHorario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // La eliminación fue exitosa
            echo 'success';
        } else {
            echo 'error';
        }

        $stmt->close();
    } else {
        echo 'error';
    }

    $conn->close();
} else {
    echo 'error';
}
?>
