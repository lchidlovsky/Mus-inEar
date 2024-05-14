<?php
namespace Models\Classes;
use autoload\Autoloader;
Autoloader::register();

class Accueil {
    protected BD\BaseDeDonnee $database;
    protected array $albums;
    protected array $artistes;

    public function __construct(){
        $this->database = new BD\BaseDeDonnee(__DIR__);
        $this->albums = $this->database->getEveryAlbums();
        $this->artistes = $this->database->getEveryArtistes();

        if (isset($_GET['keywords'])){
            $keywords = str_replace("'", '', $_GET['keywords']);
            $this->albums = $this->database->getAlbumsByKeywords($keywords);
            $this->artistes = $this->database->getArtistesByKeywords($keywords);
        }
    }

    public function __toString(){
        $output ="<main>
                    <h2 class='titre'><img src='../Static/fixtures/images/line.png'> Albums <img src='../Static/fixtures/images/line.png'></h2>
                    <section class='container'>
                        <section class='albums'>";
        foreach ($this->albums as $alb) {
            $output .= $alb;
                }
        $output .="</section>
                    </section>
                    <h2 class='titre'><img src='../Static/fixtures/images/line.png'> Artistes <img src='../Static/fixtures/images/line.png'></h2><section class='container'>
                        <section class='artiste'>";
        foreach ($this->artistes as $art){
            $output .= $art;
        }
        $output .= "</section>
                    </section>
                </main>";

        return $output;
    }
}
