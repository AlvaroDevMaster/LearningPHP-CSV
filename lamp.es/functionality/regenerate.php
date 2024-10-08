<?php
$trashDir = "../dump/";  // Carpeta de basura
$csvDir = "../csv/";  // Carpeta de CSV original

// Si la carpeta de basura existe y contiene archivos, los restauramos
foreach (glob($trashDir . "*.csv") as $file) {
    $content = file_get_contents($file);  // Leer el contenido del archivo desde la carpeta de basura
    $newFile = $csvDir . basename($file);  // Obtener solo el nombre del archivo

    $handle = fopen($newFile, "w");  // Abrir en modo escritura (sobrescribe si ya existe)
    if ($handle) {
        fwrite($handle, $content);  // Escribir el contenido
        fclose($handle);  // Cerrar el archivo
        unlink($file);  // Eliminar el archivo de la carpeta de basura después de restaurarlo
    } else {
        echo "No se pudo abrir el archivo para escribir $newFile.";
    }
}

// Redirigir a la página principal después de restaurar los archivos
header("Location: ..");

