<?php
include_once("functions.php");

// Verifica si se han enviado los parámetros correctamente
if (isset($_GET['file']) && isset($_GET['id'])) {
    $file = $_GET['file'];
    $id = $_GET['id'];

    // Verifica que el archivo existe antes de intentar abrirlo
    $csvFilePath = "../csv/" . $file;
    if (file_exists($csvFilePath)) {
        $csv = getArrayFromCsv($csvFilePath);

        // Asegúrate de que el ID solicitado es válido
        if (isset($csv['data'][$id])) {
            // Aquí puedes mostrar el registro completo
            echo "<h1>Registro completo de {$file}</h1>";
            echo "<pre>";
            print_r($csv['data'][$id]); // O cualquier otra forma de mostrar el registro
            echo "</pre>";
        } else {
            echo "Registro no encontrado.";
        }
    } else {
        echo "El archivo no existe.";
    }
} else {
    echo "Parámetros no válidos.";
}
