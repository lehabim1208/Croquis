<?php
require '../config/conexion.php';

// Consulta la base de datos y recupera los datos de la tabla "reportes"
$query = "SELECT idReporte, idCliente, asunto, descripcion, estado, fecha_hora, idReserva, mensaje_admin FROM reportes";
$resultado = $conn->query($query);

header('Content-Type: text/csv; charset=utf-8');

// Configura el nombre del archivo
$filename = 'reporte.csv';

// Configura la cabecera para descargar el archivo CSV con codificación UTF-8
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Abre un archivo temporal en modo escritura
$handle = fopen('php://output', 'w');

// Agrega una fila con los encabezados
fputcsv($handle, ['ID Reporte', 'ID Cliente', 'Asunto', 'Descripción', 'Estado', 'Fecha y Hora', 'ID Reserva', 'Mensaje Admin']);

// Recorre los resultados y agrega filas al archivo CSV
while ($row = $resultado->fetch_assoc()) {
    // Convierte cada campo a UTF-8 antes de agregarlo al archivo CSV
    array_walk($row, function (&$value) {
        $value = utf8_encode($value);
    });
    fputcsv($handle, $row);
}


// Cierra la conexión a la base de datos
$conn->close();

// Cierra el archivo CSV
fclose($handle);