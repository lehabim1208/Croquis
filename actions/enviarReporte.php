<?php
include '../config/conexion.php';

// Recibe los datos JSON desde la solicitud AJAX
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $idReserva = $data['idReserva'];
    $asunto = $data['asunto'];
    $descripcion = $data['descripcion'];
    $estado = "En espera";

    //OBTENER ID CLIENTE DE RESERVA
    $sqlReserva = "SELECT id_cliente FROM reserva WHERE idReserva = '$idReserva'";
    $resultadoReserva = $conn->query($sqlReserva);

    if ($resultadoReserva) {
        $filaReserva = $resultadoReserva->fetch_assoc(); // Obtener la primera fila de resultados
        $id_cliente = $filaReserva['id_cliente'];
    } else {
        // Manejar el error si la consulta falla
        echo json_encode(['success' => false, 'message' => 'Error en la consulta del id cliente: ' . $conn->error]);
    }

    // Obtiene la fecha y hora actual en formato TIMESTAMP en la zona horaria de México
    date_default_timezone_set('America/Mexico_City');
    $fecha_hora = date('Y-m-d H:i:s');

    // Prepara la sentencia SQL para insertar el reporte
    $sql = "INSERT INTO reportes (asunto, descripcion, fecha_hora, idReserva, idCliente, estado) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Asocia los parámetros
        $stmt->bind_param("sssiis", $asunto, $descripcion, $fecha_hora, $idReserva, $id_cliente, $estado);

        // Ejecuta la sentencia SQL
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Reporte enviado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al insertar el reporte: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la sentencia SQL: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos no recibidos correctamente.']);
}

// Cierra la conexión a la base de datos
$conn->close();
?>