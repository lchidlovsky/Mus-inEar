<?php
require_once __DIR__ . '../../../autoload/Autoloader.php';
\autoload\Autoloader::register();
use Models\Classes\Navigation;
$nav = new Navigation();
echo $nav;

?>

<link rel="stylesheet" href="../../Static/css/nav.css">
<link rel="stylesheet" href="../../Static/css/popup.css">
<link rel="icon" href="../../Static/fixtures/images/logoBarre.png" type="image/png">
<script src="../../Static/js/menu.js"></script>
<script src="../../Static/js/popup.js"></script>
