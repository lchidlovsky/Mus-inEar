<?php
require_once __DIR__ . '../../../autoload/Autoloader.php';
\autoload\Autoloader::register();
require_once "../nav/retourNav.php";

$verifConnexion= new Models\Classes\Verification\VerifConnexion();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../Static/css/connexion.css">
        <title>Connexion - Mus'inEar</title>
    </head>
    <body>
        <?php echo $verifConnexion; ?>
    </body>
</html>
