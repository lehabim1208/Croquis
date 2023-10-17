<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera el idEspacio enviado por POST
    $idEspacio = $_POST['idEspacio'];
    $sql = "DELETE FROM catalogo WHERE idCatalogo = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $idEspacio);
        if ($stmt->execute()) {
            echo "Espacio eliminado correctamente.";
        } else {
            // Error en la eliminación
            echo "Error al eliminar el espacio: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la consulta: " . $conn->error;
    }
} else {
    echo "Método de solicitud incorrecto.";
}
?>