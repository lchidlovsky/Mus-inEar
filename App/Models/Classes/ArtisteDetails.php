<?php
namespace Models\Classes;

class ArtisteDetails extends Details{
    protected int $idArtiste;
    protected string $nomArtiste;
    protected string $biographie;
    protected string $lienImg;

    public function __construct(){
        parent::__construct();
        $this->idArtiste = $_GET['id'];
        if (isset($_GET['follow']))$this->gererSuivi();

        $artiste = $this->database->getArtisteById($this->idArtiste);
        $this->nomArtiste = $artiste["nom"];
        $this->biographie = $artiste["biographie"];
        $this->lienImg = $artiste["cheminPhoto"];
    }

    private function gererSuivi(){
        if ($this->me != 0) //si l'utilisateur est connecté
        {
            $suivi = $this->database->artisteSuivi($this->me, $this->idArtiste);
            echo("<h1>".$suivi."</h1>");
            if ($suivi){
                $this->database->abandonnerArtiste($this->me, $this->idArtiste);}
            else{
                $this->database->suivreArtiste($this->me, $this->idArtiste);}

            header('Location: artisteDetail.php?id='.$this->idArtiste);
        }
    }

    public function getNomArtiste(): string
    {return $this->nomArtiste;}

    public function __toString(){
        $output = "<main>
                    <section class='classification'>
                        <img class='artiste' src='$this->lienImg'>
                        <h1>$this->nomArtiste</h1>
                        <ul class='style'>Styles:";
        foreach($this->database->getStylesArtiste($this->idArtiste) as $style){
            $output .= "<li>$style</li>";
        }

        if ($this->me == 0){    //si l'utilisateur n'est pas connecté
            $coeur = "<a href='connexion.php'><img class='coeur' src='../../Static/fixtures/images/coeur.png'></a>";}
        else{
            $src = '../../Static/fixtures/images/coeur.png';
            if ($this->database->artisteSuivi($this->me, $this->idArtiste)){
                $src = '../../Static/fixtures/images/coeur_plein.png';
            }

            $coeur = "<form method='get'>
                        <input type='hidden' name='id' value='".$this->idArtiste."'/>
                        <input type='hidden' name='follow' value='true'/>
                        <input type='image' class='coeur' src=$src>
                    </form>";
        }

        $output .= "</ul>".$coeur."</section>
                <section class='infos'>
                    <p class='description'>$this->biographie</p>
                    <h2><img src='../../Static/fixtures/images/line.png'> Albums <img src='../../Static/fixtures/images/line.png'></h2>
                    <div class='content'>";

        foreach($this->database->getAlbumsByArtist($this->idArtiste) as $album){
            $output .= $album;
        }
        $output .= "</div></section></main>";

        return $output;
    }

}

?>
