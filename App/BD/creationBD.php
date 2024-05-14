<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    // Inclure l'autoloader de Composer

    use Symfony\Component\Yaml\Yaml;
    
    // Lecture du fichier YAML
    $dataCompte = Yaml::parseFile(__DIR__ . '/../Static/fixtures/yml/comptes.yml');
    $dataArtiste = Yaml::parseFile(__DIR__ . '/../Static/fixtures/yml/artiste.yml');
    $dataUtilisateur = Yaml::parseFile(__DIR__ . '/../Static/fixtures/yml/utilisateurs.yml');
    $dataGenre = Yaml::parseFile(__DIR__ . '/../Static/fixtures/yml/genres.yml');
    $dataAlbum = Yaml::parseFile(__DIR__ . '/../Static/fixtures/yml/albums.yml');
    $dataTitre = Yaml::parseFile(__DIR__ . '/../Static/fixtures/yml/titres.yml');
    $dataGenresAlbums = Yaml::parseFile(__DIR__ . '/../Static/fixtures/yml/genresAlbums.yml');
    $dataStyleMusicaux = Yaml::parseFile(__DIR__ . '/../Static/fixtures/yml/stylesMusicaux.yml');

    try{
        $file_db = new PDO("sqlite:musinear.sqlite3");
        $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        $file_db->exec("DROP TABLE IF EXISTS PLAYLIST");
        $file_db->exec("DROP TABLE IF EXISTS FAVORIS");
        $file_db->exec("DROP TABLE IF EXISTS NOTER");
        $file_db->exec("DROP TABLE IF EXISTS GENRER");
        $file_db->exec("DROP TABLE IF EXISTS STYLE_MUSICAL");
        $file_db->exec("DROP TABLE IF EXISTS SUIVRE");
        $file_db->exec("DROP TABLE IF EXISTS TITRE");
        $file_db->exec("DROP TABLE IF EXISTS ALBUM");
        $file_db->exec("DROP TABLE IF EXISTS GENRE");
        $file_db->exec("DROP TABLE IF EXISTS UTILISATEUR");
        $file_db->exec("DROP TABLE IF EXISTS ARTISTE");
        $file_db->exec("DROP TABLE IF EXISTS COMPTE");


        ######################################################################
        ############## TABLE COMPTE ##########################################
        ######################################################################

        # Création 

        $file_db->exec("CREATE TABLE COMPTE(
            idCompte INTEGER PRIMARY KEY,
            pseudo VARCHAR(50),
            mdp VARCHAR(64))");

        #Insertion

        $insert="INSERT INTO COMPTE (idCompte, pseudo, mdp) VALUES (:idCompte, :pseudo, :mdp)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idCompte', $idCompte);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':mdp', $mdp);

        foreach($dataCompte as $compte){
            $idCompte = $compte["entryId"];
            $pseudo = $compte["pseudo"];
            $mdp = $compte["mdp"];
            $stmt->execute();
        }

        ######################################################################
        ############## TABLE ARTISTE #########################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE ARTISTE(
            idArtiste INTEGER PRIMARY KEY REFERENCES COMPTE (idCompte),
            nomArtiste VARCHAR(50),
            biographie TEXT,
            cheminPhoto TEXT)");

        #Insertion

        $insert="INSERT INTO ARTISTE (idArtiste, nomArtiste, biographie, cheminPhoto) VALUES (:idArtiste, :nomArtiste, :biographie, :cheminPhoto)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idArtiste', $idArtiste);
        $stmt->bindParam(':nomArtiste', $nomArtiste);
        $stmt->bindParam(':biographie', $biographie);
        $stmt->bindParam(':cheminPhoto', $cheminPhoto);
        
        foreach($dataArtiste as $artiste){
            $idArtiste = $artiste["entryId"];
            $nomArtiste = $artiste["nom"];
            $biographie = $artiste["biographie"];
            $cheminPhoto = $artiste["cheminPhoto"];
            $stmt->execute();
        }

        ######################################################################
        ############## TABLE UTILISATEUR #####################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE UTILISATEUR(
            idUtilisateur INTEGER PRIMARY KEY REFERENCES COMPTE (idCompte),
            nomUtilisateur VARCHAR(20))");

        #Insertion

        $insert="INSERT INTO UTILISATEUR (idUtilisateur, nomUtilisateur) VALUES (:idUtilisateur, :nomUtilisateur)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur);
        $stmt->bindParam(':nomUtilisateur', $nomUtilisateur);

        foreach($dataUtilisateur as $utilisateur){
            $idUtilisateur = $utilisateur["entryId"];
            $nomUtilisateur = $utilisateur["nomUtilisateur"];
            $stmt->execute();
        }

        ######################################################################
        ############## TABLE GENRE ###########################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE GENRE(
            idGenre INTEGER PRIMARY KEY,
            nomGenre VARCHAR(15))");

        #Insertion

        $insert="INSERT INTO GENRE (idGenre, nomGenre) VALUES (:idGenre, :nomGenre)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idGenre', $idGenre);
        $stmt->bindParam(':nomGenre', $nomGenre);

        foreach($dataGenre as $genre){
            $idGenre = $genre["entryId"];
            $nomGenre = $genre["nomGenre"];
            $stmt->execute();
        }

        ######################################################################
        ############## TABLE ALBUM ###########################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE ALBUM(
            idArtiste INTEGER REFERENCES ARTISTE (idArtiste),
            idAlbum INTEGER,
            nomAlbum VARCHAR(80),
            annee INTEGER,
            cheminPochette TEXT,
            PRIMARY KEY(idArtiste, idAlbum))");

        #Insertion

        $insert="INSERT INTO ALBUM(idArtiste, idAlbum, nomAlbum, annee, cheminPochette) VALUES (:idArtiste, :idAlbum, :nomAlbum, :annee, :cheminPochette)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idArtiste', $idArtiste);
        $stmt->bindParam(':idAlbum', $idAlbum);
        $stmt->bindParam(':nomAlbum', $nomAlbum);
        $stmt->bindParam(':annee', $annee);
        $stmt->bindParam(':cheminPochette', $cheminPochette);

        foreach($dataAlbum as $album){
            $idArtiste = $album["by"];
            $idAlbum = $album["entryId"];
            $nomAlbum = $album["title"];
            $annee = $album["releaseYear"];
            $cheminPochette = $album["img"];
            $stmt->execute();
        }


        ######################################################################
        ############## TABLE TITRE ###########################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE TITRE(
            idTitre INTEGER,
            idAlbum INTEGER REFERENCES ALBUM (idAlbum),
            nomTitre VARCHAR(50),
            PRIMARY KEY (idTitre, idAlbum))");

        #Insertion

        $insert="INSERT INTO TITRE (idTitre, idAlbum, nomTitre) VALUES (:idTitre, :idAlbum, :nomTitre)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idTitre', $idTitre);
        $stmt->bindParam(':idAlbum', $idAlbum);
        $stmt->bindParam(':nomTitre', $nomTitre);

        foreach($dataTitre as $titre){
            $idTitre = $titre["numero"];
            $idAlbum = $titre["albumId"];
            $nomTitre = $titre["titre"];
            $stmt->execute();
        }

        ######################################################################
        ############## TABLE SUIVRE #########################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE SUIVRE(
            idUtilisateur INTEGER REFERENCES UTILISATEUR (idUtilisateur),
            idArtiste INTEGER REFERENCES ARTISTE (idArtiste),
            PRIMARY KEY (idUtilisateur, idArtiste))");

        #Insertion

        $insert="INSERT INTO SUIVRE (idUtilisateur, idArtiste) VALUES (:idUtilisateur, :idArtiste)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idUtilisateur',$idUtilisateur);
        $stmt->bindParam(':idArtiste', $idArtiste);


        ######################################################################
        ############## TABLE STYLE_MUSICAL ###################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE STYLE_MUSICAL(
            idArtiste INTEGER REFERENCES ARTISTE (idArtiste),
            idGenre INTEGER REFERENCES GENRE (idGenre),
            PRIMARY KEY (idArtiste, idGenre))");

        #Insertion

        $insert="INSERT INTO STYLE_MUSICAL (idArtiste, idGenre) VALUES (:idArtiste, :idGenre)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idArtiste', $idArtiste);
        $stmt->bindParam(':idGenre', $idGenre);

        foreach($dataStyleMusicaux as $style){
            $idArtiste = $style['artiste'];
            $idGenre = $style['genre'];
            $stmt->execute();
        }


        ######################################################################
        ############## TABLE GENRER ##########################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE GENRER(
            idAlbum INTEGER REFERENCES ALBUM (idAlbum),
            idGenre INTEGER REFERENCES GENRE (idGenre),
            PRIMARY KEY (idAlbum, idGenre))");

        #Insertion

        $insert="INSERT INTO GENRER (idAlbum, idGenre) VALUES (:idAlbum, :idGenre)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idAlbum', $idAlbum);
        $stmt->bindParam(':idGenre', $idGenre);

        foreach($dataGenresAlbums as $data){
            $idAlbum = $data['album'];
            $idGenre = $data['genre'];
            $stmt->execute();
        }


        ######################################################################
        ############## TABLE NOTER ###########################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE NOTER(
            idUtilisateur INTEGER,
            idAlbum INTEGER,
            note INTEGER,
            PRIMARY KEY (idUtilisateur, idAlbum))");

        #Insertion

        $insert="INSERT INTO NOTER (idUtilisateur, idAlbum, note) VALUES (:idUtilisateur, :idAlbum, :note)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur);
        $stmt->bindParam(':idAlbum', $idAlbum);
        $stmt->bindParam(':note', $note);


        ######################################################################
        ############## TABLE FAVORIS #########################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE FAVORIS(
            idUtilisateur INTEGER REFERENCES UTILISATEUR (idUtilisateur),
            idAlbum INTEGER REFERENCES ALBUM (idAlbum),
            PRIMARY KEY (idUtilisateur, idAlbum))");

        #Insertion

        $insert="INSERT INTO FAVORIS (idUtilisateur, idAlbum) VALUES (:idUtilisateur, :idAlbum)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idUtilisateur',$idUtilisateur);
        $stmt->bindParam(':idAlbum', $idAlbum);


        ######################################################################
        ############## TABLE PLAYLIST ########################################
        ######################################################################

        # Création 
        
        $file_db->exec("CREATE TABLE PLAYLIST(
            idUtilisateur INTEGER REFERENCES UTILISATEUR (idUtilisateur),
            idTitre INTEGER REFERENCES TITRE (idTitre),
            idAlbum INTEGER REFERENCES ALBUM (idAlbum),
            PRIMARY KEY (idUtilisateur, idTitre, idAlbum))");

        #Insertion

        $insert="INSERT INTO PLAYLIST (idUtilisateur, idTitre) VALUES (:idUtilisateur, :idTitre)";
        $stmt=$file_db->prepare($insert);
        $stmt->bindParam(':idUtilisateur',$idUtilisateur);
        $stmt->bindParam(':idTitre', $idTitre);

    }
    catch(PDOException $e){
        echo "PDOException: " . $e->getMessage();
    }
?>
