<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Registry</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
    <div style="text-align:center">
<?php 
// Función para generar la tabla de entrada de datos
function getAdditionTable($csvHeaders){
    $tableOutput = "<table>";
    $tableOutput .= "<tr>";
    foreach($csvHeaders as $header){
        $tableOutput .= "<th>" . htmlspecialchars($header) . "</th>";
    }
    $tableOutput .= "</tr>";
    $tableOutput .= "<tr>";
    foreach($csvHeaders as $header){
        $tableOutput .= "<td>"."<input style=\"width:150px\" type='text' name='inputValues[]' placeholder=\"".htmlspecialchars($header)."\" />"."</td>";
    }
    $tableOutput .= "</tr>";
    $tableOutput .= "</table>";
    return $tableOutput;
}

// Verificamos si se han enviado los datos del formulario
if (isset($_POST["inputValues"]) && !empty($_POST["inputValues"]) && isset($_POST["csvFile"]) && !empty($_POST["csvFile"])) {
    $csvFilePath = "../csv/" . $_POST["csvFile"];

    // Abrimos el archivo en modo 'a+' (append), que permite tanto leer como agregar
    $file = fopen($csvFilePath, "a+");

    // Verificamos si el archivo ya tiene un salto de línea al final
    fseek($file, -1, SEEK_END);
    $lastChar = fgetc($file);

    // Si no hay un salto de línea al final del archivo, lo agregamos
    if ($lastChar !== "\n") {
        fwrite($file, "\n");
    }

    // Escribimos el nuevo registro en una nueva línea
    fputcsv($file, $_POST["inputValues"]);

    // Cerramos el archivo
    fclose($file);

    // Redirigimos a la página principal o a otra página después de agregar el registro
    header("Location: ..");
    exit();
} 
// Si se ha seleccionado un archivo CSV, generamos el formulario para agregar un registro
else if (isset($_POST["csvFile"]) && !empty($_POST["csvFile"])) {
    include_once("functions.php");

    // Cargamos el CSV para obtener los encabezados
    $csvToEdit = getArrayFromCsv("../csv/" . $_POST["csvFile"]);

    // Si hay datos en el CSV, tomamos la primera fila como encabezados
    if (!empty($csvToEdit['data'])) {
        $csvHeader = array_keys($csvToEdit['data'][0]);

        // Creamos el formulario para ingresar los datos
        $formOutput = "<form method='POST' action='crear.php'>";
        $formOutput .= getAdditionTable($csvHeader);
        $formOutput .= "<input type='hidden' name='csvFile' value=\"" . htmlspecialchars($_POST["csvFile"]) . "\" />";
        $formOutput .= "<input type='submit' value='Agregar registro' />";
        $formOutput .= "</form>";

        echo $formOutput;
    } else {
        echo "El archivo CSV no tiene datos.";
    }
}
?>
</div>
</body>
</html>

