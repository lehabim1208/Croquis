<?php 
include '../config/conexion.php';

// Obtener el valor de idEspacio y fecha de la solicitud AJAX
$idEspacio = '15';
$fecha = '2023-11-15';

// Consulta SQL para obtener los horarios de la tabla "horarios" donde "idCatalogo" sea igual a $idEspacio
$sql = "SELECT horario FROM horarios WHERE idCatalogo = $idEspacio";
$result = $conn->query($sql);

// Consulta SQL para obtener los horarios ocupados en la tabla "reserva" para la fecha dada
$sqlreservas = "SELECT horario FROM reserva WHERE DATE(fecha) = DATE('$fecha') AND idCatalogo = '$idEspacio'";
$resultreservas = $conn->query($sqlreservas);

// Verificar si se obtuvieron resultados
if ($result->num_rows > 0) {
    $horarios = array();

    // Obtener los horarios de la base de datos
    while ($row = $result->fetch_assoc()) {
        $horarios[] = $row['horario'];
    }

    // Obtener los horarios ocupados de la tabla "reserva"
    $horariosOcupados = array();
    while ($row = $resultreservas->fetch_assoc()) {
        $horariosOcupados[] = $row['horario'];
    }

    // Calcular la diferencia entre los horarios disponibles y los horarios ocupados
    $horariosDisponibles = array_diff($horarios, $horariosOcupados);

    echo "<br> ------------------------------------------------- <br>";
    echo "HORARIOS:";
    echo "<ul>";

    // Obtener la hora actual en formato "H:i"
    date_default_timezone_set('America/Mexico_City');
    $horaActual = date('H:i');

    $hoy = new DateTime(); // Obtiene la fecha actual
    $hoy = $hoy->format('Y-m-d'); // Formatea la fecha actual

    foreach ($horariosDisponibles as $horario) {
        // Obtener la hora del horario y convertirla al formato "H:i"
        $horarioPartes = explode('-', $horario);
        $horaInicio = trim(explode(' ', $horarioPartes[0])[0]);
        $horaInicio = date('H:i', strtotime($horaInicio));

        // Si la fecha es hoy y la hora del horario es mayor a la hora actual, se muestra
        if ($fecha == $hoy && $horaInicio > $horaActual) {
            echo "<li>$horario</li>";
        } elseif ($fecha != $hoy) {
            // Si la fecha no es hoy, se muestran todos los horarios disponibles
            echo "<li>$horario</li>";
        }
    }

    echo "</ul>";
    
} else {
    echo "No se encontraron horarios para el espacio con ID: $idEspacio";
}

// Cierra la conexión a la base de datos
$conn->close();
?>