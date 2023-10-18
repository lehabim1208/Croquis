<?php
// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../config/conexion.php';

    // Recuperar los datos del formulario
    $horario = $_POST["horario"];
    $catalogoId = $_POST["seleccionCatalogo"];

    // Consulta SQL para insertar el nuevo horario en la base de datos
    $sql = "INSERT INTO horarios (horario, idCatalogo) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $horario, $catalogoId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // El horario se ha creado con éxito
            header("Location: ../paginas/horarios.php");
        } else {
            echo "Ocurrió un error al crear el horario";
        }
        
        $stmt->close();
    } else {
        echo "Ocurrió un error en la preparación de la consulta";
    }

    $conn->close();
} else {
    echo "ERROR EN LA PETICIÓN";
}
?>