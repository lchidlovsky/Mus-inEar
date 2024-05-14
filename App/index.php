<?php
require_once "autoload/Autoloader.php";
use autoload\Autoloader;
Autoloader::register();
require_once "./Models/nav/nav.php";
$accueil = new Models\Classes\Accueil();

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./Static/css/accueil.css">
        <title>Mus'inEar</title>
    </head>
    <body>
        <?php echo $accueil; ?>
    </body>
</html>
