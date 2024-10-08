<?php
$trashDir = "../dump/";  // Carpeta de basura
$csvDir = "../csv/";  // Carpeta original

// Si no existe la carpeta de basura, la creamos
if (!file_exists($trashDir)) {
    mkdir($trashDir, 0755, true);
}

// Leer el contenido de cada archivo y escribirlo en la carpeta de basura
foreach (glob($csvDir . "*.csv") as $file) {
    $content = file_get_contents($file);  // Leer el contenido del archivo
    $newFile = $trashDir . basename($file);  // Obtener solo el nombre del archivo

    $handle = fopen($newFile, "w");  // Abrir en modo escritura (sobrescribe si ya existe)
    if ($handle) {
        fwrite($handle, $content);  // Escribir el contenido
        fclose($handle);  // Cerrar el archivo
        unlink($file);  // Eliminar el archivo original después de copiarlo
    } else {
        echo "No se pudo escribir en el archivo $newFile";
    }
}

// Redirigir a la página principal después de mover los archivos
header("Location: ..");
