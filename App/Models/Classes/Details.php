<?php
namespace Models\Classes;
use autoload\Autoloader;
Autoloader::register();

class Details{

    protected int $me;
    protected BD\BaseDeDonnee $database;

    public function __construct(){
        $this->database = new BD\BaseDeDonnee(__DIR__);
        $this->me = isset($_SESSION['me']) ? (int) $_SESSION['me'] : 0;
    }

}

?>
