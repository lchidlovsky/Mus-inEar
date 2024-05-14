<?php
namespace Models\Classes\BD;
use Models\Classes\Miniature;
use Models\Classes\Track;
use Models\Classes\Accueil;
use PDO;
use PDOException;

class BaseDeDonnee {

    protected string $cheminImages = "../../Static/fixtures/images/";
    protected $pdo;

    public function __construct($path) {
        try {
            $this->pdo = new PDO("sqlite:".$path."/../../musinear.sqlite3");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    }

    ##############################################################
    ########################### COMPTE ###########################
    ##############################################################

    public function getIdMax(string $nomTable): int
    {
        try{
            switch (strtoupper($nomTable)) {
                case "COMPTE":
                    $nomColonne = "idCompte";
                    $nomTable = "COMPTE";
                    break;
                case "ARTISTE":
                    $nomColonne = "idArtiste";
                    $nomTable = "ARTISTE";
                    break;
                case "UTILISATEUR":
                    $nomColonne = "idUtilisateur";
                    $nomTable = "UTILISATEUR";
                    break;
                case "ALBUM":
                        $nomColonne = "idAlbum";
                        $nomTable = "ALBUM";
                        break;
                case "TITRE":
                        $nomColonne = "idTitre";
                        $nomTable = "TITRE";
                        break;
                case "GENRE":
                        $nomColonne = "idGenre";
                        $nomTable = "GENRE";
                        break;
            }

            $query = "SELECT max($nomColonne)
            FROM $nomTable";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return intval($row["max($nomColonne)"]);
            }

            return 0;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getIdCompte(string $pseudo): int
    {
        try{
            $query = "SELECT idCompte, pseudo
            FROM COMPTE
            WHERE pseudo = '$pseudo'";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return intval($row["idCompte"]);
            }

            return 0;
        }
        catch (PDOException $e) {
            echo $e->getMessage().gettype($pseudo);
        }   
    }

    public function pseudoExistant(string $pseudo): bool
    {
        try{
            $query = "SELECT *
            FROM COMPTE
            WHERE pseudo = '$pseudo'";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return true;
            }

            return false;
        }
        catch (PDOException $e) {
            echo $e->getMessage().gettype($pseudo);
        }   
    }

    public function getMdpCompte(int $idCompte): string
    {
        try{
            $query = "SELECT mdp
            FROM COMPTE
            WHERE idCompte = $idCompte";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $row["mdp"];
            }
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }   
    }

    public function getNomCompte(int $idCompte): string
    {
        try{
            $query = "SELECT nomArtiste
            FROM ARTISTE
            WHERE idArtiste = $idCompte";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $row["nomArtiste"];
            }
            $query = "SELECT nomUtilisateur
            FROM UTILISATEUR
            WHERE idUtilisateur = $idCompte";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $row["nomUtilisateur"];
            }
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insertionCompteNormal(string $pseudo, string $mdp, string $nomComplet){
        try{
            $idCompte = $this->getIdMax('compte') + 1;
            $query = "INSERT INTO COMPTE VALUES (" . $idCompte . ", '" .$pseudo. "', '". $mdp."')";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            
            $query = "INSERT INTO UTILISATEUR VALUES (" . $idCompte . ", '".$nomComplet."' )";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insertionCompteArtiste(string $pseudo, string $mdp, string $nomComplet, string $bio, string $cheminPortrait) {
        try{
            $idCompte = $this->getIdMax('compte') + 1;
            $query = "INSERT INTO COMPTE VALUES (" . $idCompte . ", '" .$pseudo. "', '". $mdp."')";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            
            $query = "INSERT INTO ARTISTE VALUES (" . $idCompte . ", '".$nomComplet."', '". $bio ."', '". $cheminPortrait ."' )";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fermerCompte(int $id){
        try{
            if ($this->isArtiste($id)){
                $query = "SELECT idAlbum
                FROM ALBUM
                WHERE idArtiste = $id";
                $stmt=$this->pdo->prepare($query);
                $stmt->execute();
                $titres = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->supprimerAlbum(intval($row['idAlbum']));
                }

                $query = "DELETE FROM STYLE_MUSICAL WHERE idArtiste = $id";
                $stmt=$this->pdo->prepare($query);
                $stmt->execute();

                $query = "DELETE FROM ARTISTE WHERE idArtiste = $id";
                $stmt=$this->pdo->prepare($query);
                $stmt->execute();
                
            } else {
                $query = "DELETE FROM SUIVRE WHERE idUtilisateur = $id";
                $stmt=$this->pdo->prepare($query);
                $stmt->execute();
                $query = "DELETE FROM FAVORIS WHERE idUtilisateur = $id";
                $stmt=$this->pdo->prepare($query);
                $stmt->execute();
                $query = "DELETE FROM NOTER WHERE idUtilisateur = $id";
                $stmt=$this->pdo->prepare($query);
                $stmt->execute();
                $query = "DELETE FROM PLAYLIST WHERE idUtilisateur = $id";
                $stmt=$this->pdo->prepare($query);
                $stmt->execute();
                $query = "DELETE FROM UTILISATEUR WHERE idUtilisateur = $id";
                $stmt=$this->pdo->prepare($query);
                $stmt->execute();
            }

            $stmt->execute();
            $query = "DELETE FROM COMPTE WHERE idCompte = $id";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function isArtiste(int $idCompte): bool
    {
        try{
            $query = "SELECT *
            FROM ARTISTE
            WHERE idArtiste = $idCompte";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return true;
            }
            return false;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    ##############################################################
    ########################### ALBUM ############################
    ##############################################################

    public function getEveryAlbums(): array
    {
        try{
            $query = "SELECT *
            FROM ALBUM NATURAL JOIN ARTISTE
            ORDER BY idArtiste ASC, annee DESC";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $albums = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $album = new Miniature(intval($row["idAlbum"]), $row["nomAlbum"], $this->cheminImages.$row["cheminPochette"], '../../Models/Vue/albumDetail.php?id='.$row["idAlbum"]);
                array_push($albums, $album);
            }

            return $albums;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAlbumById(int $idAlbum): array
    {
        try{
            $query = "SELECT nomAlbum, idArtiste, nomArtiste, annee, cheminPochette
            from ALBUM natural join ARTISTE
            WHERE idAlbum = $idAlbum";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $artiste = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $artiste = array("idArtiste"=>$row["idArtiste"], "nomArtiste"=>$row["nomArtiste"],
                "nomAlbum"=>$row["nomAlbum"], "annee"=>$row["annee"], "cheminPochette"=>$this->cheminImages.$row["cheminPochette"]);
            }

            return $artiste;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAlbumsByKeywords(string $keywords): array
    {
        try{
            $query = "SELECT idAlbum, nomAlbum, cheminPochette
            FROM ALBUM
            WHERE nomAlbum LIKE '%$keywords%'";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $albums = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $album = new Miniature(intval($row["idAlbum"]), $row["nomAlbum"], $this->cheminImages.$row["cheminPochette"], '../../Models/Vue/albumDetail.php?id='.$row["idAlbum"]);
                array_push($albums, $album);
            }

            return $albums;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAlbumImage(int $idalbum): string
    {
        try{
            $query = "SELECT cheminPochette
            FROM ALBUM
            WHERE idAlbum = $idalbum";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return "<img class='couverture' src='".$this->cheminImages.$row['cheminPochette']."' >";
            }
        }
        catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getAlbumsByArtist(int $idArtiste): array
    {
        try{
            $query = "SELECT idAlbum, nomAlbum, cheminPochette
            FROM ALBUM NATURAL JOIN ARTISTE
            WHERE idArtiste = $idArtiste
            ORDER BY annee DESC";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $albums = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($albums, new Miniature(intval($row["idAlbum"]), $row["nomAlbum"], $this->cheminImages.$row["cheminPochette"], '../../Models/Vue/albumDetail.php?id='.$row["idAlbum"]));
            }

            return $albums;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getGenresAlbum(int $idAlbum): array
    {
        try{
            $query = "SELECT nomGenre
            FROM ALBUM NATURAL JOIN GENRER NATURAL JOIN GENRE
            WHERE idAlbum = $idAlbum;";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $genres = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($genres, $row['nomGenre']);
            }

            return $genres;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getAlbumsFavoris(int $idUser): array
    {
        try{
            $query = "SELECT idAlbum, nomAlbum, cheminPochette
            FROM FAVORIS NATURAL JOIN ALBUM
            WHERE idUtilisateur = $idUser";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $albums = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($albums, new Miniature(intval($row["idAlbum"]), $row["nomAlbum"], $this->cheminImages.$row["cheminPochette"], '../../Models/Vue/albumDetail.php?id='.$row["idAlbum"]));
            }

            return $albums;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function albumEnFavoris(int $idUser, int $idAlbum): bool
    {
        try{
            $query = "SELECT * 
            FROM FAVORIS 
            WHERE idUtilisateur = $idUser AND idAlbum = $idAlbum";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return true;
            }

            return false;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function mettreAlbumFavoris(int $idUser, int $idAlbum){
        try{
            $query = "INSERT INTO FAVORIS VALUES ($idUser, $idAlbum)";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function retirerAlbumFavoris(int $idUser, int $idAlbum){
        try{
            $query = "DELETE FROM FAVORIS WHERE idUtilisateur = $idUser AND idAlbum = $idAlbum";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insertionAlbum(int $idArtiste, string $nomAlbum, int $annee, string $lienPochette, array $titres, array $genres)
    {
        try{
            //insertion de l'album
            $idAlbum = $this->getIdMax('album') + 1;
            $query = "INSERT INTO ALBUM VALUES (" . $idArtiste . ", " . $idAlbum . ", '" . $nomAlbum . "', " . $annee . ", '" . $lienPochette . "')";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            //insertion des titres de l'album
            foreach ($titres as $num => $titre) {
                $query = "INSERT INTO TITRE VALUES ($num+1, $idAlbum, '" . $titre . "')";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
            }

            //insertion des genres de l'album
            foreach ($genres as $idGenre) {
                $query = "INSERT INTO GENRER VALUES ($idAlbum, $idGenre)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
            }

            //insertion des genres à l'artiste
            foreach ($genres as $idGenre) {
                $query = "INSERT INTO STYLE_MUSICAL VALUES ($idArtiste, $idGenre)";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
            }

        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function supprimerAlbum(int $id){
        try{
            $query = "SELECT idTitre
            FROM TITRE
            WHERE idAlbum = $id";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->supprimerTitre($id, intval($row["idTitre"]));
            }

            $query = "DELETE FROM FAVORIS WHERE idAlbum = $id";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();

            $query = "DELETE FROM NOTER WHERE idAlbum = $id";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();

            $query = "DELETE FROM GENRER WHERE idAlbum = $id";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();

            $query = "DELETE FROM ALBUM WHERE idAlbum = $id";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    ##############################################################
    ########################### ARTISTE ##########################
    ##############################################################

    public function getEveryArtistes(){
        try{
            $query = "SELECT idArtiste, nomArtiste, cheminPhoto
            FROM ARTISTE";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $artistes = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $artiste = new Miniature(intval($row["idArtiste"]), $row["nomArtiste"], $this->cheminImages . $row["cheminPhoto"], '../../Models/Vue/artisteDetail.php?id='.$row["idArtiste"]);
                array_push($artistes, $artiste);
            }
            return $artistes;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getArtisteById(int $id): array
    {
        try{
            $query = "SELECT nomArtiste, biographie, cheminPhoto
            FROM ARTISTE
            WHERE idArtiste = $id";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return array("nom"=>$row["nomArtiste"], "biographie"=>$row["biographie"], "cheminPhoto"=>$this->cheminImages.$row["cheminPhoto"]);
            }
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getArtistesByKeywords(string $keywords): array
    {
        try{
            $query = "SELECT idArtiste, nomArtiste, cheminPhoto
            FROM ARTISTE
            WHERE nomArtiste LIKE '%$keywords%'";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $artistes = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $artiste = new Miniature(intval($row["idArtiste"]), $row["nomArtiste"], $this->cheminImages.$row["cheminPhoto"], '../../Models/Vue/artisteDetail.php?id='.$row["idArtiste"]);
                array_push($artistes, $artiste);
            }

            return $artistes;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getArtistePortrait(int $idArtiste): string
    {
        try{
            $query = "SELECT cheminPhoto
            FROM ARTISTE
            WHERE idArtiste = $idArtiste";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->cheminImages . $row["cheminPhoto"];
            }
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getStylesArtiste($idArtiste): array
    {
        try{
            $query = "SELECT nomGenre
            FROM ARTISTE NATURAL JOIN STYLE_MUSICAL NATURAL JOIN GENRE
            WHERE idArtiste= $idArtiste;";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $styles = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($styles, $row['nomGenre']);
            }

            return $styles;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getArtistesSuivis(int $idUser): array
    {
        try{
            $query = "SELECT idArtiste, nomArtiste, cheminPhoto
            FROM SUIVRE NATURAL JOIN ARTISTE
            WHERE idUtilisateur = $idUser";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $artistes = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $artiste = new Miniature(intval($row["idArtiste"]), $row["nomArtiste"], $this->cheminImages . $row["cheminPhoto"], '../../Models/Vue/artisteDetail.php?id='.$row["idArtiste"]);
                array_push($artistes, $artiste);
            }
            return $artistes;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function artisteSuivi(int $idUser, int $idArtiste): bool
    {
        try{
            $query = "SELECT * 
            FROM SUIVRE 
            WHERE idUtilisateur = $idUser AND idArtiste = $idArtiste";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return true;
            }
            return false;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function suivreArtiste(int $idUser, int $idArtiste){
        try{
            $query = "INSERT INTO SUIVRE VALUES ($idUser, $idArtiste)";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function abandonnerArtiste(int $idUser, int $idArtiste){
        try{
            $query = "DELETE FROM SUIVRE WHERE idUtilisateur = $idUser AND idArtiste = $idArtiste";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getBiographie(int $idArtiste): string
    {
        try{
            $query = "SELECT biographie
            FROM ARTISTE
            WHERE idArtiste = $idArtiste";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $row["biographie"];
            }
            return "";
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function editerBiographie(int $idArtiste, string $nouvelleBio){
        try{
            $query = "UPDATE ARTISTE
            SET biographie = :nouvelleBio
            WHERE idArtiste = :idArtiste";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':idArtiste', $idArtiste);
            $stmt->bindParam(':nouvelleBio', $nouvelleBio);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    
    public function getEveryGenres(): array
    {
        try {
            $query = "SELECT idGenre, nomGenre
            FROM GENRE";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $genres = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $genre = array('id'=> $row['idGenre'], 'nom'=> $row['nomGenre']);
                array_push($genres, $genre);
            }
            return $genres;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    ##############################################################
    ########################### TITRE ############################
    ##############################################################

    private function supprimerTitre(int $idAlbum, int $idTitre){
        try{
            $query = "DELETE FROM PLAYLIST
            WHERE idAlbum=$idAlbum AND idTitre = $idTitre";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $query = "DELETE FROM TITRE
            WHERE idAlbum=$idAlbum AND idTitre = $idTitre";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
        }

        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getTitresByAlbum(int $idAlbum): array
    {
        try{
            $query = "SELECT idAlbum, idTitre, nomTitre
            FROM TITRE NATURAL JOIN ALBUM
            WHERE idAlbum = $idAlbum";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            $titres = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($titres, new Track($idAlbum, intval($row['idTitre']), $row['nomTitre'], true));
            }

            return $titres;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getPlaylist(int $idUtilisateur): array
    {
        try {
            $query = "SELECT nomTitre, PLAYLIST.idTitre, idAlbum
            FROM PLAYLIST NATURAL JOIN TITRE
            WHERE idUtilisateur = $idUtilisateur";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            $titres = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($titres, new Track(intval($row['idAlbum']), intval($row['idTitre']), $row['nomTitre'], false));
            }
            return $titres;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function titreDansPlaylist(int $idUser, int $idAlbum, int $idTitre): bool
    {
        try{
            $query = "SELECT * 
            FROM PLAYLIST 
            WHERE idUtilisateur = $idUser AND idAlbum = $idAlbum AND idTitre = $idTitre";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return true;
            }

            return false;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function mettreTitreDansPlaylist(int $idUser, int $idAlbum, int $idTitre){
        try{
            $query = "INSERT INTO PLAYLIST VALUES ($idUser, $idTitre, $idAlbum)";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function retirerTitreDePlaylist(int $idUser, int $idAlbum, int $idTitre){
        try{
            $query = "DELETE FROM PLAYLIST WHERE idUtilisateur = $idUser AND idAlbum = $idAlbum AND idTitre = $idTitre";
            $stmt=$this->pdo->prepare($query);
            $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

?>