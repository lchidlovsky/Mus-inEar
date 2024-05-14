<?php
namespace Models\Classes;
use autoload\Autoloader;
Autoloader::register();
use Models\Classes\Input\InputText;
use Models\Classes\Input\InputNumber;

class InsertionAlbum{

    protected string $cheminEnregistrement = "../../Static/fixtures/images/";
    protected int $me;
    protected BD\BaseDeDonnee $database;

    public function __construct(){
        $this->database = new BD\BaseDeDonnee(__DIR__);
        $this->me = isset($_SESSION['me']) ? (int) $_SESSION['me'] : 0;

        if (!$this->database->isArtiste($this->me)) {
            header('Location: ../Vue/connexion.php');
        }

        if($_POST || $_FILES){
            $this->uploadBD();
        }
    }

    private function uploadBD(){
        $pochette = "defaultCover.png";

        if(!empty($_FILES['pochette']['tmp_name']) && !empty($_FILES['pochette']['name']))
        {
            $tmp_file = $_FILES['pochette']['tmp_name'];

            //on teste si l'upload a reussi
            if(!is_uploaded_file($tmp_file))
                exit('Erreur lors de l\'upload.');

            //on rend le nom du fichier unique
            $unikifier = md5( uniqid('H', 5) );

            //extension du fichier
            $ext = pathinfo($_FILES['pochette']['name'], PATHINFO_EXTENSION);

            //nettoyage du nom du fichier
            $file_name = $unikifier.'.'.$ext;

            //on déplace le fichier vers le répertoire de destination
            if(!move_uploaded_file($tmp_file, $this->cheminEnregistrement.$file_name))
                exit("Impossible de déplacer le fichier. Upload annulé.");
            else
                $pochette = $file_name;
        }
        
        $annee = intval($_POST['annee']);

        $genres = array();
        foreach($_POST['genre'] as $genre){
            array_push($genres, intval($genre));
        }

        $this->database->insertionAlbum($this->me, $_POST['nomAlbum'], $annee, $pochette, $_POST['titre'], $genres);

        header('Location: ../../index.php');

    }

    public function __toString(){
        $output = "<main>
            <h1><img src='../../Static/fixtures/images/line.png'> Créez votre album <img src='../../Static/fixtures/images/line.png'></h1>

            <p> Artiste : ".$this->database->getNomCompte(intval($_SESSION['me']))." </p>

            <h2><img src='../../Static/fixtures/images/line.png'> Couverture de votre album <img src='../../Static/fixtures/images/line.png'></h2>
            
            <form action='creationAlbum.php' method='POST' enctype='multipart/form-data' >
                <input id='dropZone' type='file' name='pochette' size='30'>

                <section class='textArea'>
                    <section class='nomAlbum'>
                        <h2><img src='../../Static/fixtures/images/line.png'> Nom de l'album <img src='../../Static/fixtures/images/line.png'></h2>";

        $nomAlbum = new InputText('saisir', 'nomAlbum', 'Nom album ici...', 'nomAlbum', true, '');
        $output .= $nomAlbum->render();

                        
        $output .= "</section>
                    <section class='anneeParution'>
                        <h2><img src='../../Static/fixtures/images/line.png'> Année de parution <img src='../../Static/fixtures/images/line.png'></h2>";

        $parution = new InputNumber('saisir', 'annee', date('Y'), 'annee', true, '');
        $output .= $parution->render();
                        
        $output .= "</section>
                </section>
                <section class='genreAlbum'>
                    <h2><img src='../../Static/fixtures/images/line.png'> Genre de l'album <img src='../../Static/fixtures/images/line.png'></h2>
                    <div class='selectbox'>";

        $options = $this->database->getEveryGenres();
        $selectGenre = new SelectBox('Choisir un genre', 'genre[]', '', 'select', $options);
        $output .= $selectGenre."</div>
                    <button id='ajouterGenre'><img src='../../Static/fixtures/images/add.png'> Ajouter un genre</button>
                    <button id='supprimerGenre'>❌ Supprimer un genre</button>
                </section>

                <section class='titres'>
                    <h2><img src='../../Static/fixtures/images/line.png'> Titres<img src='../../Static/fixtures/images/line.png'></h2>
                    <div class='tracks'>
                        <input type='text' class='saisir' id='titre' name='titre[]' placeholder='Nom titre ici...' required=true>
                    </div>
                    <button id='ajouter'><img src='../../Static/fixtures/images/add.png'> Ajouter un titre</button>
                    <button id='supprimer'>❌ Supprimer un titre</button>
                </section>
                
                <button class='validation' value='submit' onclick='saveImage()' > Créer l'album </button>
            </form>

        </main>";

        return $output;
    }
}

?>
