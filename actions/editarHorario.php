<?php
// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../config/conexion.php';

    // Recuperar los datos del formulario
    $idHorario = $_POST["idHorario"];
    $horario = $_POST["horario"];
    $catalogoId = $_POST["seleccionCatalogo"];

    // Consulta SQL para actualizar el horario en la base de datos
    $sql = "UPDATE horarios SET horario = ?, idCatalogo = ? WHERE idHorario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sii", $horario, $catalogoId, $idHorario);
        $stmt->execute();

        if ($stmt->execute()) {
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