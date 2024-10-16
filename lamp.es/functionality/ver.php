<?php 
if (isset($_POST["action"]) && !empty($_POST["action"])) {
    include_once("functions.php");
    $csvFilePath = "../csv/" . $_POST["csvFile"];

    // Función para convertir el array de vuelta a formato CSV
    function getCSVFromArray($array) {
        $csv = "";
        if (!empty($array)) {
            // Agregar encabezado (claves del primer elemento)
            $header = array_keys($array[0]);
            $csv .= implode(",", $header) . "\n";

            // Agregar las filas
            foreach ($array as $row) {
                $csv .= implode(",", $row) . "\n";
            }
        }
        return $csv;
    }

    switch ($_POST["action"]) {
        case "eliminar":
            // Obtener el CSV como un array
            $csv = getArrayFromCsv($csvFilePath);

            // Depurar para ver la estructura del array
            var_dump($csv['data'][0]);  // Para asegurarnos de que tenemos el array esperado

            // Función para eliminar las filas seleccionadas
            function deleteSelectedRowsOfArray($array, $rowsToDelete) {
                $newArray = [];
                foreach ($array as $row => $value) {
                    if (!in_array($row, $rowsToDelete)) {
                        array_push($newArray, $value);
                    }
                }
                return $newArray; 
            }
            // Obtener las filas seleccionadas desde el formulario
            $rowsToDelete = $_POST["selectedRecords"];
            $csv = deleteSelectedRowsOfArray($csv['data'], $rowsToDelete);

            // Escribir el contenido de vuelta al archivo CSV
            file_put_contents($csvFilePath, getCSVFromArray($csv));

            // Redirigir después de la operación
            header("Location: ..");
            break;

            case "editar":
                // Obtener el CSV como un array
                $csv = getArrayFromCsv($csvFilePath);
    
                // Obtener las filas seleccionadas para editar
                $rowsToEdit = $_POST["selectedRecords"];
    
                // Crear un formulario para mostrar las filas editables
                echo "<form method='post' action='./updateDeleteController.php'>";
                echo "<input type='hidden' name='csvFile' value='" . $_POST["csvFile"] . "'>";
                echo "<input type='hidden' name='action' value='guardar'>";  // Esto es para manejar el guardado después de la edición
    
                // Mostrar la tabla con las filas seleccionadas en un formulario editable
                echo "<table border='1'>";
                foreach ($rowsToEdit as $index) {
                    if (isset($csv['data'][$index])) {
                        echo "<tr>";
                        foreach ($csv['data'][$index] as $key => $value) {
                            echo "<td>";
                            echo "<input type='text' name='editedRecords[$index][$key]' value='" . htmlspecialchars((string)$value, ENT_QUOTES) . "'>";
                            echo "</td>";
                        }
                        echo "</tr>";
                    }
                }
                echo "</table>";
    
                // Botón para guardar cambios
                echo "<button type='submit'>Guardar Cambios</button>";
                echo "</form>";
                break;
    
            case "guardar":
                // Obtener el CSV como un array
                $csv = getArrayFromCsv($csvFilePath);
    
                // Obtener los registros editados desde el formulario
                $editedRecords = $_POST["editedRecords"];
    
                // Actualizar el CSV con los nuevos valores
                foreach ($editedRecords as $index => $newRow) {
                    if (isset($csv['data'][$index])) {
                        $csv['data'][$index] = $newRow;  // Reemplazar la fila con la nueva
                    }
                }                
    
                // Escribir el contenido de vuelta al archivo CSV
                file_put_contents($csvFilePath, getCSVFromArray($csv['data']));
    
                // Redirigir después de guardar los cambios
                header("Location: ..");
                break;
    }
}
