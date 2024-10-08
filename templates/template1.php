<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PruebaPHP</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>
<body>
    <?php 
    //echo $bodyOutput;
    echo $vmOutput;
    echo $userOutput;
    echo $totalOutput;
    ?>
    <button>
        <a style="text-decoration: none; color: red" href="functionality/erase.php">Borrar Tablas</a>
    </button>
    <button>
        <a style="text-decoration: none; color: green" href="functionality/regenerate.php">Regenerar Tablas</a>
    </button>
</body>
</html>