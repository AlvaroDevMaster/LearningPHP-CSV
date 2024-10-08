<?php
function getListMarkup($list)
{
    $output = "<ol>";
    foreach ($list as $key => $item) {
        if (is_array($item)) {
            $output .= getListMarkup($item);
        } else {
            $output .= "<li>$item</li></br>";
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
    try {
        // Intentar abrir el archivo
        $openCsv = fopen($csvFile, "r");
        
        // Comprobar si el archivo no se pudo abrir
        if ($openCsv === false) {
            throw new Exception("No se pudo abrir el archivo CSV: " . $csvFile);
        }

        // Obtener la primera fila (cabeceras)
        $headers = fgetcsv($openCsv);
        if ($headers === false) {
            throw new Exception("El archivo CSV está vacío o es inválido.");
        }

        // Leer cada línea después de la cabecera
        while (($csvLine = fgetcsv($openCsv)) !== false) {
            // Combinar la cabecera con la línea para crear un array asociativo
            $csv[] = array_combine($headers, $csvLine);
        }
    } catch (Exception $e) {
        $csv = [];
        //echo "Error al leer el archivo: " . $e->getMessage();
    } finally {
        // Asegurarse de que el archivo se cierra si fue abierto correctamente
        if ($openCsv !== false) {
            fclose($openCsv);
        }
        return $csv;
    }
}
function getTableFromCSVArray($csvList = [], $header = true, $showID = false) {
    $tableOutput = "<table>";

    // Si tenemos datos en el CSV
    if (!empty($csvList)) {
        // Extraer las claves (cabeceras) del primer array asociativo
        $firstRow = array_keys($csvList[0]); 
        $idColumnIndex = [];

        // Crear encabezados y determinar columnas que NO contienen 'id'
        $tableOutput .= "<tr>";
        foreach ($firstRow as $column) {
            // Excluir columnas que contengan 'id' en su encabezado
            if ($showID === true || strpos(strtolower($column), "id") === false) {
                $tableOutput .= "<th>$column</th>";  // Agregar encabezado
                $idColumnIndex[] = $column;          // Guardar columnas válidas
            }
        }
        $tableOutput .= "</tr>";

        // Procesar las filas de datos, excluyendo columnas con 'id'
        foreach ($csvList as $csvRow) {
            $tableOutput .= "<tr>";
            foreach ($csvRow as $column => $item) {
                // Solo mostrar columnas que no tienen 'id' o si showID es true
                if (in_array($column, $idColumnIndex)) {
                    $tableOutput .= isImage($item)? "<td><img src='$item' height = '200px' width='auto' alt='Avatar_{$csvRow['Username']}'></td>" : "<td>$item</td>";
                }
            }
            $tableOutput .= "</tr>";
        }
    }

    $tableOutput .= "</table>";
    return $tableOutput;
}
function CsvCombine($csv1 = [], $csv2 = []) {
    return array_replace_recursive($csv1, $csv2);
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
                $tableOutput .= "<th>$headercolumn</th>";
            }
            $tableOutput .= '</tr>';
        }
        // Enseñar contenidos
        while ($csv = fgetcsv($handle)) {
            $tableOutput .= '<tr>';
            foreach ($csv as $column) {
                $tableOutput .= "<td>$column</td>";
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
