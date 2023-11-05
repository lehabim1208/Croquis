<?php 
include '../config/conexion.php';

if (isset($_POST['idReporte'])) {
    $idReporte = $_POST['idReporte'];

    $sqlAcciones = "SELECT estado, mensaje_admin FROM reportes WHERE idReporte = ?";
    $stmtAcciones = $conn->prepare($sqlAcciones);
    $stmtAcciones->bind_param("i", $idReporte);
    $stmtAcciones->execute();
    $stmtAcciones->bind_result($estado, $mensaje_admin);
    $stmtAcciones->fetch(); // Recuperar valores de la consulta

    // Crear un arreglo asociativo con los valores que deseas enviar
    $response = array(
        'estado' => $estado,
        'mensaje_admin' => $mensaje_admin
    );

    // Devolver la respuesta como JSON
    echo json_encode($response);
} else {
    echo "No se recibió ningún valor en PHP.";
}
?>