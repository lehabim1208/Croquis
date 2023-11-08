<?php
session_start();
$idUsuario = $_SESSION['idUsuario'];
$nombre = $_SESSION['nombre'];

$fechaHoraActual = date('Y-m-d H:i:s');
require '../config/conexion.php';

// Incluye la librería PHPSpreadsheet
require_once '../phpoffice/vendor/autoload.php';

// Crea una instancia de Spreadsheet
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Agrega los encabezados de las columnas
$sheet->setCellValue('A1', '#Reporte');
$sheet->setCellValue('B1', 'Asunto');
$sheet->setCellValue('C1', 'Descripción');
$sheet->setCellValue('D1', 'Fecha y Hora');
$sheet->setCellValue('E1', '#Reservación');
$sheet->setCellValue('F1', 'IdUsuario');
$sheet->setCellValue('G1', 'Estado');
$sheet->setCellValue('H1', 'Respuesta');

// Realiza la consulta SQL
$query = "SELECT idReporte, idCliente, asunto, descripcion, estado, fecha_hora, idReserva, mensaje_admin FROM reportes";
$resultado = $conn->query($query);

$row = 2; // Empieza a partir de la fila 2

while ($fila = $resultado->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $fila['idReporte']);
    $sheet->setCellValue('B' . $row, $fila['asunto']);
    $sheet->setCellValue('C' . $row, $fila['descripcion']);
    $sheet->setCellValue('D' . $row, $fila['fecha_hora']);
    $sheet->setCellValue('E' . $row, $fila['idReserva']);
    $sheet->setCellValue('F' . $row, $fila['idCliente']);
    $sheet->setCellValue('G' . $row, $fila['estado']);

    if (empty($fila['mensaje_admin'])) {
        $sheet->setCellValue('H' . $row, 'Aún no se le ha dado respuesta');
    } else {
        $sheet->setCellValue('H' . $row, $fila['mensaje_admin']);
    }

    $row++;
}

// Agrega la información de Id Administrador, Reporte obtenido por y Fecha y hora
$row = $row+3;

// Agrega la información de Id Administrador, Reporte obtenido por y Fecha y hora
$sheet->setCellValue('A' . $row, 'Id Administrador');
$sheet->setCellValue('B' . $row, $idUsuario);
$row++;
$sheet->setCellValue('A' . $row, 'Reporte obtenido por');
$sheet->setCellValue('B' . $row, $nombre);
$row++;
$sheet->setCellValue('A' . $row, 'Fecha y hora');
$sheet->setCellValue('B' . $row, $fechaHoraActual);

// Estilo para el encabezado
$styleHeader = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'color' => ['rgb' => '333333'],
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];

// Aplica el estilo al encabezado
$sheet->getStyle('A1:H1')->applyFromArray($styleHeader);

// Estilo para las celdas de datos
$styleData = [
    'font' => [
        'color' => ['rgb' => '000000'],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

// Aplica el estilo a las celdas de datos
$sheet->getStyle('A2:H' . $row)->applyFromArray($styleData);

// Crea un objeto Writer para guardar el archivo
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

// Guarda el archivo en una ubicación
$excelFilePath = 'reporte.xlsx';
$writer->save($excelFilePath);

// Proporciona el enlace para que el usuario descargue el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte.xlsx"');
readfile($excelFilePath);

// Borra el archivo temporal después de enviarlo al usuario
unlink($excelFilePath);