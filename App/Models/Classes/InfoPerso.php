<?php
namespace Models\Classes;
use autoload\Autoloader;
Autoloader::register();


class InfoPerso{
    protected BD\BaseDeDonnee $database;
    protected int $me;

    public function __construct(){
        $this->database = new BD\BaseDeDonnee(__DIR__);
        $this->me = isset($_SESSION['me']) ? (int) $_SESSION['me'] : 0;

        if (!$this->me){
            header('Location: connexion.php');
        }

        if (isset($_POST['modifBio'])){
            $this->database->editerBiographie($this->me, $_POST['biographie']);
        }

        if (isset($_GET['suppression'])){
            $this->database->supprimerAlbum($_GET['id']);
            header('Location: compte.php');
        }

        if (isset($_GET['fermeture'])){
            $this->database->fermerCompte($this->me);
            header('Location: deconnexion.php');
        }
    }

    public function monNom(): string
    {
        return $this->database->getNomCompte($this->me);
    }

    public function __toString(){
        $output = "<main>
            <h1><img src='../../Static/fixtures/images/line.png'> Mes informations <img src='../../Static/fixtures/images/line.png'></h1>
            <h2>Votre nom de compte est : ".$this->monNom()."</h2>";

        if ($this->database->isArtiste($this->me)){
            $output .= "<form class='bio' id='bio' method='POST' >
                            <label class='labelbio'>Modifier votre biographie :</label>
                            <textarea class='textareabio' name='biographie' rows='10' cols='80' >".$this->database->getBiographie($this->me)."</textarea>
                            <button class='buttonbio' type='submit' name='modifBio'>Enregistrer</button>
                        </form>";

                        $output .= "<h2 class='titre'><img src='../../Static/fixtures/images/line.png'> Vos albums <img src='../../Static/fixtures/images/line.png'></h2><section class='container'>
                                    <section class='mesAlbums'>";
                        
                                    foreach($this->database->getAlbumsByArtist($this->me) as $album){
                        $output .= "<div class='content'>"
                                        .$album.
                                        "<button onclick='togglePopup(".$album->getId().");' >Supprimer</button>
                                        <div id='".$album->getId()."' class='popup-overlay'>
                                            <div class='popup-content'>
                                                <div class='contenu-popup'>
                                                    <h2>Supprimer l'album ".$album->getNom()." ?</h2>
                                                </div>
                                                <div class='contenu-popup'>
                                                    <a onclick='togglePopup(".$album->getId().");' ><input id='bouton-non' type='button' value='Annuler' /></a>
                                                    <a href='compte.php?suppression=true&id=".$album->getId()."'><input id='bouton-oui' type='button' value='Supprimer' /></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                        }
                        $output .= "</section>
                                <form action='creationAlbum.php'>
                                    <button type='submit'>Créez un nouvel album</button>
                                </form>";    
                    }
            
                    $output .= "<button onclick='togglePopup(999);' >Fermer mon compte</button>
                                
                                <div id=999 class='popup-overlay'>
                                    <div class='popup-content'>
                                        <div class='contenu-popup'>
                                            <h2>Confirmer la fermeture de votre compte</h2>
                                        </div>
                                        <div class='contenu-popup'>
                                            <a onclick='togglePopup(999);' ><input id='bouton-non' type='button' value='Annuler' /></a>
                                            <a href='compte.php?fermeture=true'><input id='bouton-oui' type='button' value='Fermer' /></a>
                                        </div>
                                    </div>
                                </div>
                            </main>";
                    return $output;
    }

}

?>
