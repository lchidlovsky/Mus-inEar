<?php
require_once "../../autoload/Autoloader.php";
use autoload\Autoloader;
Autoloader::register();
require_once "../nav/retourNav.php";

$insertionAlbum = new Models\Classes\InsertionAlbum();
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../Static/css/creationAlbum.css">
        <script src="../../Static/js/creationAlbum.js" defer></script>
        <title>Création d'album - Mus'inEar</title>
    </head>
    <body>
        <?php echo $insertionAlbum; ?>
    </body>
</html>
