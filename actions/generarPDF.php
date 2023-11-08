<?php
session_start();
$idUsuario = $_SESSION['idUsuario'];
$nombre = $_SESSION['nombre'];

$fechaHoraActual = date('Y-m-d H:i:s');
require '../config/conexion.php';
require_once '../mpdf/vendor/autoload.php';


$html = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Reporte</title>
    <link rel="stylesheet" href="style.css" media="all" />
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
      <img src="../img/calendario.svg" id="icon" alt="User Icon" style="width: 70px;"/>
      </div>
      <h1>Reporte Semanal Sistema Reservaciones</h1>
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th class="service">#Reporte</th>
            <th class="desc">Asunto</th>
            <th>Descripción</th>
            <th>Fecha y Hora</th>
            <th>#Reservación</th>
            <th>IdUsuario</th>
            <th>Estado</th>
            <th>Respuesta</th>
          </tr>
        </thead>
        <tbody>';

// Realiza la consulta SQL
$query = "SELECT idReporte, idCliente, asunto, descripcion, estado, fecha_hora, idReserva, mensaje_admin FROM reportes";
$resultado = $conn->query($query);

// Itera a través de los resultados y agrega las filas de la tabla
while ($fila = $resultado->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td class="service">' . $fila['idReporte'] . '</td>';
    $html .= '<td class="desc">' . $fila['asunto'] . '</td>';
    $html .= '<td>' . $fila['descripcion'] . '</td>';
    $html .= '<td>' . $fila['fecha_hora'] . '</td>';
    $html .= '<td>' . $fila['idReserva'] . '</td>';
    $html .= '<td>' . $fila['idCliente'] . '</td>';
    $html .= '<td>' . $fila['estado'] . '</td>';
    
    if (empty($fila['mensaje_admin'])) {
        $html .= '<td style="color: #949217;">Aún no se le ha dado respuesta</td>';
    } else {
        $html .= '<td>' . $fila['mensaje_admin'] . '</td>';
    }
    
    $html .= '</tr>';
}
$html .= '</tbody>
      </table>
      <div id="notices">
      <span>Id Administrador: </span><span class="notice">' . $idUsuario . '</span><br>
      <span>Reporte obtenido por: </span><span class="notice">' . $nombre . '</span><br>
      <span>Fecha y hora: </span><span class="notice">' . $fechaHoraActual . '</span><br>
      </div>
    </main>
  </body>
</html>';

$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$css = file_get_contents('../estilos/estilo_pdf.css');
$mpdf->WriteHTML($css, 1);
$mpdf->WriteHTML($html);

$mpdf->Output('reporte.pdf', 'D');