<?php
require __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

set_time_limit(0);
ob_start();

// Conexión a MySQL
$mysqli = new mysqli("localhost", "root", "", "sireco");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Si no hay parámetro hoja, mostrar saludo y botón
if (!isset($_GET['hoja'])) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Iniciar carga</title>
        <style>
            body { font-family: sans-serif; text-align: center; margin-top: 100px; }
            button { padding: 15px 30px; font-size: 16px; border: none; background-color: green; color: white; border-radius: 10px; cursor: pointer; }
        </style>
    </head>
    <body>
        <h2>¡Hola! Bienvenido al cargador de datos postales.</h2>
        <p>Presiona el siguiente botón para insertar solo la <strong>primera fila</strong> de cada hoja del Excel.</p>
        <form method='get' action=''>
            <input type='hidden' name='hoja' value='0'>
            <button type='submit'>Iniciar Carga</button>
        </form>
    </body>
    </html>";
    exit;
}

// Parámetro hoja
$indice = intval($_GET['hoja']);

// Cargar Excel
$archivo = __DIR__ . '/Todo.xlsx';
$reader = new Xlsx();
$reader->setReadDataOnly(true);
$reader->setReadEmptyCells(false);
$spreadsheet = $reader->load($archivo);

// Hojas disponibles
$hojas = $spreadsheet->getSheetNames();
$totalHojas = count($hojas);

// Verificación
if ($indice >= $totalHojas) {
    echo "<script>alert('Todas las hojas procesadas correctamente.');</script>";
    exit;
}

$hoja = $spreadsheet->getSheet($indice);
$nombreHoja = $hojas[$indice];
$rowIterator = $hoja->getRowIterator();

// Mostrar HTML y barra
echo "<!DOCTYPE html>
<html>
<head>
    <title>Cargando Primera Fila</title>
    <style>
        .bar { width: 100%; background: #ccc; border-radius: 10px; }
        .progress { height: 25px; width: 100%; background: green; color: white; text-align: center; border-radius: 10px; }
    </style>
</head>
<body>
<h3>Insertando primera fila de hoja: <strong>$nombreHoja</strong></h3>
<div class='bar'><div id='progress' class='progress'>100%</div></div>";

ob_flush();
flush();

// Preparar consulta
$stmt = $mysqli->prepare("INSERT INTO estado (codigoPos, colBarrio, ciudadMun, estado, claveEdo) VALUES (?, ?, ?, ?, ?)");

// Obtener solo la primera fila
$row = $rowIterator->current();
$cellIterator = $row->getCellIterator();
$cellIterator->setIterateOnlyExistingCells(true);
$fila = [];

foreach ($cellIterator as $cell) {
    $fila[] = $cell->getFormattedValue(); // mantiene ceros a la izquierda
}

// Insertar la fila
$codigoPos  = strval($fila[0] ?? '');
$colBarrio  = $fila[1] ?? '';
$ciudadMun  = $fila[2] ?? '';
$estado     = $fila[3] ?? '';
$claveEdo   = intval($fila[4] ?? 0);

$stmt->bind_param("ssssi", $codigoPos, $colBarrio, $ciudadMun, $estado, $claveEdo);
$stmt->execute();

$stmt->close();
$mysqli->close();

// Continuar a la siguiente hoja
$siguiente = $indice + 1;
echo "<script>
    setTimeout(function() {
        if (confirm('Primera fila de \"$nombreHoja\" cargada correctamente. ¿Cargar la siguiente hoja?')) {
            window.location.href = 'cargarExcel.php?hoja=$siguiente';
        }
    }, 800);
</script>
</body>
</html>";
