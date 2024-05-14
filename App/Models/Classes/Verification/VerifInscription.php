<?php
namespace Models\Classes\Verification;
require_once __DIR__ . '../../../../autoload/Autoloader.php';
use autoload\Autoloader;
Autoloader::register();
use Models\Classes\BD\BaseDeDonnee;
use Models\Classes\Input\InputText;
use Models\Classes\Input\InputPassword;
use Models\Classes\Input\InputCheckbox;

class VerifInscription{

    protected string $cheminEnregistrement = "../../Static/fixtures/images/";
    protected BaseDeDonnee $database;
    protected string $tentative;

    public function __construct(){
        $this->database = new BaseDeDonnee(__DIR__ . '/../');

        if($_POST || $_FILES){
            if ($this->database->pseudoExistant($_POST['pseudo']) == false){
            $this->inscriptionBD();
            }
        }
    }

    private function inscriptionBD(){
        $pseudo = $_POST['pseudo'];
        $mdpChiffre = hash('sha256', $_POST['mdp']);
        $nomComplet = $_POST['nomComplet'];
        $estArtiste = ($_POST['estArtiste']) ? true : false;
        $biographie = $_POST['biographie'];

        $portrait = "defaultPP.png";

        if(!empty($_FILES['portrait']['tmp_name']) && !empty($_FILES['portrait']['name']))
        {
            $tmp_file = $_FILES['portrait']['tmp_name'];

            //on teste si l'upload a reussi
            if(!is_uploaded_file($tmp_file))
                exit('Erreur lors de l\'upload.');

            //on rend le nom du fichier unique
            $unikifier = md5( uniqid('H', 5) );

            //extension du fichier
            $ext = pathinfo($_FILES['portrait']['name'], PATHINFO_EXTENSION);

            //nettoyage du nom du fichier
            $file_name = $unikifier.'.'.$ext;

            //on déplace le fichier vers le répertoire de destination
            if(!move_uploaded_file($tmp_file, $this->cheminEnregistrement.$file_name))
                exit("Impossible de déplacer le fichier. Upload annulé.");
            else
                $portrait = $file_name;
        }

        if ($estArtiste){
            $this->database->insertionCompteArtiste($pseudo, $mdpChiffre, $nomComplet, $biographie, $portrait);}
        else{
            $this->database->insertionCompteNormal($pseudo, $mdpChiffre, $nomComplet);}

        echo "<form id='redirectionConnexion' action='connexion.php' method='POST'>
                <input type='hidden' name='tentative' value='true' />
                <input type='hidden' name='identifiant' value=$pseudo />
                <input type='hidden' name='mdp' value='". $_POST['mdp'] ."' />
            </form>";
    }

    public function __toString(){
        $identifiant = new InputText("connect","pseudo","Pseudo unique","pseudoCompte",true, "Identifiant :");
        $nomComplet = new InputText("connect","nomComplet","Nom original","nomComplet",true, "Nom complet :");
        $mdp = new InputPassword("connect","mdp","Mot de passe confidentiel","mdpCompte",true, "Mot de passe :");
        $estArtiste = new InputCheckbox("artiste","estArtiste","value","estArtiste",false, "Je souhaite créer un compte artiste.");

        $output ="<main>
                    <h1><img src='../../../Static/fixtures/images/line.png'> Inscription <img src='../../../Static/fixtures/images/line.png'></h1>";

        
        if ($_POST){
            $output .= "<p>Identifiants incorrects !</p><p>Assurez vous d'utiliser un pseudonyme unique.</p>";
        }
        $output .= "<form method='POST' enctype='multipart/form-data' >
                        <section>";
        $output .= $identifiant->render();
        $output .="</section>
                    <section>";
        $output .= $nomComplet->render();
        $output .="</section>
                    <section>";
        $output .= $mdp->render();
        $output .="</section>
                    <div id='infoArtiste'>
                        <section>
                            <label>Description personnelle :</label>
                            <textarea name='biographie' rows='6' cols='70' ></textarea>
                        </section>
                        <label>Photo de profil</label>
                        <section class='drop'>
                            <input id='dropZone' type='file' name='portrait' size='30'>
                        </section>
                    </div>
                    <section class = 'estArtiste'>";
        $output .= $estArtiste->render();
        $output .="</section>";

        $output .= "<button type='submit' name='creation'>Créer mon compte</button>
                    </form>";
        $output .= "<a href='connexion.php'>Vous avez déjà un compte ? Connectez vous ici !</a>
                    </main>";

        return $output;
    }
}
