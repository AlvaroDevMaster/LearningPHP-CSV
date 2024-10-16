<?php
function getListMarkup($list)
{
    $output = "<ol>";
    foreach ($list as $key => $item) {
        if (is_array($item)) {
            $output .= getListMarkup($item);
        } else {
            $output .= "<li>".htmlspecialchars($item)."</li></br>";
        }
    }
    $output .= "</ol>";
    return $output;
}
function getListFromDB()
{
    $list = [];
    array_push($list, "Hacer la compra", "Ir al gimnasio", ["Aprender PHP", "Estudiar Oposiciones"], ["Hacer Tareas", "Pegarme un tiro por no poder hacerlas todas"]);
    return $list;
}
function isImage($url) {
    // Expresión regular que incluye soporte para URLs con http:// o https:// al inicio
    return preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $url);
}
function getArrayFromCsv($csvFile) {
    $csv = [];
    $fileName = basename($csvFile); // Obtiene el nombre del archivo

    try {
        // Intentar abrir el archivo CSV
        if (!file_exists($csvFile)) {
            throw new Exception("El archivo $csvFile no existe.");
        }

        $openCsv = fopen($csvFile, "r");
        if (!$openCsv) {
            throw new Exception("No se pudo abrir el archivo $csvFile.");
        }

        $headers = fgetcsv($openCsv); // Leer la primera fila como encabezado
        
        // Verificar si el archivo tiene cabeceras válidas
        if ($headers === false) {
            throw new Exception("No se pudieron leer las cabeceras del archivo $csvFile.");
        }

        // Leer el contenido del archivo
        while(($csvLine = fgetcsv($openCsv)) !== false) {
            if (count($csvLine) === count($headers)) {
                $csv[] = array_combine($headers, $csvLine); // Combina encabezado y datos
            } else {
                throw new Exception("El número de columnas no coincide con las cabeceras en el archivo $csvFile.");
            }
        }
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        if (isset($openCsv) && is_resource($openCsv)) {
            fclose($openCsv);
        }
    }

    return [
        'data' => $csv,      // El contenido del CSV
        'fileName' => $fileName // El nombre del archivo
    ];
}


function getTableFromCSVArray($csvArray = [], $header = true, $showID = false) {
    // Obtener el nombre del archivo y el contenido del CSV
    $csvList = $csvArray['data'];        // El contenido del CSV
    $csvFileName = $csvArray['fileName']; // El nombre del archivo CSV

    $tableOutput = "<form method='POST' action='functionality/crear.php'>"; // Abre un formulario

    // Agregar el nombre del archivo CSV como un campo oculto
    $tableOutput .= "<input type='hidden' name='csvFile' value='".htmlspecialchars($csvFileName)."'>";
    
    $tableOutput .= "<button type='submit'>Añadir nuevo registro</button>";
    $tableOutput .= "</form>";
    $tableOutput .= "<table>";

    $tableOutput .= "<form method='POST' action='functionality/updateDeleteController.php'>";
    $tableOutput .= "<input type='hidden' name='csvFile' value='".htmlspecialchars($csvFileName)."'>";
    // Si tenemos datos en el CSV
    if (!empty($csvList)) {
        // Extraer las claves (cabeceras) del primer array asociativo
        $firstRow = array_keys($csvList[0]); 
        $idColumnIndex = [];

        // Crear encabezados
        $tableOutput .= "<tr>";
        $tableOutput .= "<th>Seleccionar</th>"; // Columna para checkbox
        foreach ($firstRow as $column) {
            // Excluir columnas que contengan 'id' en su encabezado
            if ($showID === true || strpos(strtolower($column), "id") === false) {
                $tableOutput .= "<th>".htmlspecialchars($column)."</th>";  // Agregar encabezado
                $idColumnIndex[] = $column;          // Guardar columnas válidas
            }
        }
        $tableOutput .= "<th>Visualizar</th>";
        $tableOutput .= "</tr>";

        // Procesar las filas de datos, excluyendo columnas con 'id'
        foreach ($csvList as $csvRow => $csvLine) {
            $tableOutput .= "<tr>";
            
            // Checkbox para seleccionar el registro con el índice de la fila
            $tableOutput .= "<td><input type='checkbox' name='selectedRecords[]' value=\"$csvRow\"></td>";
            
            foreach ($csvLine as $column => $item) {
                // Solo mostrar columnas que no tienen 'id' o si showID es true
                if (in_array($column, $idColumnIndex)) {
                    $tableOutput .= isImage($item) ? 
                        "<td><img src='$item' height='200px' width='auto' alt='Avatar_{$csvLine['Username']}'></td>" 
                        : "<td>".htmlspecialchars($item)."</td>";
                }
            }
            $tableOutput .= "<td><a href=\"functionality/ver.php?id=$csvRow&file=$csvFileName\">Ver</a></td>";
            $tableOutput .= "</tr>";
        }
    }

    $tableOutput .= "</table>";
    $tableOutput .= "<select name='action'>
                        <option value='eliminar'>Eliminar</option>
                        <option value='editar'>Editar</option>
                    </select>";
    $tableOutput .= "<button type='submit'>Aplicar seleccionado</button>";
    $tableOutput .= "</form>";
    

    return $tableOutput;
}

function CsvCombine($csv1 = [], $csv2 = []) {
    return [
        'data' => array_replace_recursive($csv1['data'], $csv2['data']), //Array combinado
        'fileName' => $csv1['fileName']. ",". $csv2['fileName'] //Nombre de fichero combinado
    ];
}

function getTableFromCsvFile($filename, $header = false)
{
    $tableOutput = "";
    try {
        $handle = fopen($filename, "r");
        $tableOutput .= '<table>';
        //Si hay header en la tabla
        if ($header) {
            $csv = fgetcsv($handle);
            $tableOutput .= '<tr>';
            foreach ($csv as $headercolumn) {
                $tableOutput .= "<th>".htmlspecialchars($headercolumn)."</th>";
            }
            $tableOutput .= '</tr>';
        }
        // Enseñar contenidos
        while ($csv = fgetcsv($handle)) {
            $tableOutput .= '<tr>';
            foreach ($csv as $column) {
                $tableOutput .= "<td>". htmlspecialchars($column) ."</td>";
            }
            $tableOutput .= '</tr>';
        }
        $tableOutput .= '</table>';
    } catch (Exception $ex) {
        echo "Error al leer el archivo: " . $ex->getMessage();
    } finally {
        fclose($handle);
        return $tableOutput;
    }
}
function getRandomRegistry($csv = []){
    $randomIndex = random_int(0, count($csv) - 1);
    return "<p>" . implode(', ' , $csv[$randomIndex]) . "</p>";
}
function dump($input){
    echo $input;
}
