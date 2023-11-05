<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe los datos del formulario
    $idReporte = $_POST['idReporte'];
    $estado = $_POST['estado'];
    $mensaje = $_POST['mensaje'];

    // Realiza las operaciones necesarias con los datos
    $sql = "UPDATE reportes SET estado = ?, mensaje_admin = ? WHERE idReporte = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $estado, $mensaje, $idReporte);

    if ($stmt->execute()) {
        // Actualización exitosa
        $response = array('success' => true, 'message' => 'Acción completada con éxito');
    } else {
        // Error en la actualización
        $response = array('success' => false, 'message' => 'Error al realizar la acción');
    }
    
    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else {
    // Devuelve una respuesta JSON de error si la solicitud no es POST
    $response = array('success' => false, 'message' => 'Solicitud no válida');
    echo json_encode($response);
}
?>