<?php
// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../config/conexion.php';

    // Recuperar los datos del formulario
    $horario = $_POST["horario"];
    $catalogoId = $_POST["seleccionCatalogo"];

    // Consulta SQL para verificar si el horario ya existe para el espacio seleccionado
    $sqlVerificar = "SELECT COUNT(*) as count FROM horarios WHERE horario = ? AND idCatalogo = ?";
    $stmtVerificar = $conn->prepare($sqlVerificar);

    if ($stmtVerificar) {
        $stmtVerificar->bind_param("si", $horario, $catalogoId);
        $stmtVerificar->execute();
        $stmtVerificar->bind_result($count);
        $stmtVerificar->fetch();
        $stmtVerificar->close();

        if ($count > 0) {
            // El horario ya existe para el espacio seleccionado
            echo json_encode(["error" => "El horario ya se encontraba registrado para este espacio"]);
        } else {
            // El horario no existe, se puede insertar
            // Consulta SQL para insertar el nuevo horario en la base de datos
            $sql = "INSERT INTO horarios (horario, idCatalogo) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("si", $horario, $catalogoId);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // El horario se ha creado con éxito
                    echo json_encode(["success" => "El horario se ha creado con éxito"]);
                } else {
                    echo json_encode(["error" => "Ocurrió un error al crear el horario"]);
                }
                
                $stmt->close();
            } else {
                echo json_encode(["error" => "Ocurrió un error en la preparación de la consulta"]);
            }
        }
    } else {
        echo json_encode(["error" => "Ocurrió un error en la preparación de la consulta"]);
    }

    $conn->close();
} else {
    echo json_encode(["error" => "ERROR EN LA PETICIÓN"]);
}
?>