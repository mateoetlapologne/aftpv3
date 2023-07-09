<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $age = ($_POST['age']);
    $ville = $_POST["ville"];
    $adresse = $_POST["adresse"];
    $numero = $_POST["numero"];
    $pseudo = $_POST["pseudo"];
    $infos = $_POST["infos"];

    

    // Vérification des champs de texte
    if (!empty($nom) && !preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $nom)) {
        $erreur = true;
        echo "Le nom ne doit contenir que des lettres, des espaces et des tirets.";
    }

    if (empty($age)) {
        $age = 999;
    }

    if(empty($nomPhotoVictime)) {
        $nomPhotoVictime = "default.jpg";
    }

    if (!preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $prenom)) {
        $erreur = true;
        echo "Le prénom ne doit contenir que des lettres, des espaces et des tirets.";
    }

    if (!empty($ville) && !preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $ville)) {
        $erreur = true;
        echo "La ville ne doit contenir que des lettres, des espaces et des tirets.";
    }

    if (!empty($numero) && !preg_match("/^\d{10}$/", $numero)) {
        $erreur = true;
        echo "Le numéro de téléphone doit comporter 10 chiffres.";
    }
    

    if (!preg_match("/^[a-zA-Z0-9À-ÿ\s-]+$/", $pseudo)) {
        $erreur = true;
        echo "Le pseudo ne doit contenir que des lettres, des chiffres, des espaces et des tirets.";
    }

    if (strlen($infos) > 350) {
        $erreur = true;
        echo "Les informations ne doivent pas dépasser 350 caractères.";
    }

    // Si aucune erreur, enregistrement des données dans la base de données et upload des images
    if (!$erreur) {
        // Connexion à la base de données
        $connexion = mysqli_connect("localhost", "root", "Pologne667", "aftp");

        // Vérification de la connexion
        if (!$connexion) {
            die("Erreur de connexion à la base de données : " . mysqli_connect_error());
        }

        // Génération d'un nom unique pour l'image de la victime
        $nomPhotoVictime = uniqid() . "." . $imageExtension;

        // Déplacement de l'image de la victime vers le dossier de destination
        $dossierDestination = 'image/';
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $dossierDestination . $nomPhotoVictime)) {
            
            echo "L'image a été téléchargée avec succès.";
        } else {
            echo "Erreur lors du téléchargement de l'image : " . $_FILES["image"]["error"];
        }

        // Génération de noms uniques pour les preuves et enregistrement dans la base de données
        $preuveNoms = array();
        foreach ($_FILES["preuve"]["tmp_name"] as $key => $tmp_name) {
            if (!empty($_FILES["preuve"]["name"][$key])) {
                $preuveNom = uniqid() . "." . $extensionsPreuves[$key];
                move_uploaded_file($tmp_name, $dossierDestination . $preuveNom);
                $preuveNoms[] = $preuveNom;
            }
        }

        // Requête SQL pour insérer les données dans la base de données
        $requete = "INSERT INTO utilisateurs (photoVictime, preuve1, preuve2, preuve3, nom, prenom, age, ville, adresse, numero, pseudo, infos, ip)
            VALUES ('$nomPhotoVictime', '$preuveNoms[0]', '$preuveNoms[1]', '$preuveNoms[2]', '$nom', '$prenom', '$age', '$ville', '$adresse', '$numero', '$pseudo', '$infos', '" . $_SERVER["REMOTE_ADDR"] . "')";

        // Exécution de la requête
        if (mysqli_query($connexion, $requete)) {
            echo "Le post a été enregistré avec succès.";
        } else {
            echo "Erreur lors de l'enregistrement du post : " . mysqli_error($connexion);
        }

        // Fermeture de la connexion à la base de données
        mysqli_close($connexion);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Affiche ton pedo</title>
    <link rel="stylesheet" type="text/css" href="css/Main.css">
</head>

<body>
    <div class="navbar">
        <div class="left-button">
            <a href="index.php" class="nav-title">Affiche Ton Pedo</a>
        </div>
        <div class="right-buttons">
            <a href="recherche.php"><button class="btn">Rechercher</button></a>
        </div>
    </div>
    <div class="title-post">Fais peté la data man</div>
    <div class="subtitle-post">Si tu n'as pas une infos, laisse la case vide</div>
    <div class="container center">
        <div class="text">
            Affiche ton Pédo
        </div>
        <form action="#" class="" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="input-data">
                    <div class="underline"></div>
                    <label for="">Photo du pédophile*</label>
                    <br><br>
                    <input type="file" name="image" accept="image/jpeg, image/png, image/jpg" required>


                </div>
                <div class="input-data">
                    <div class="underline"></div>
                    <label for="">Preuve(s)*</label>
                    <br><br>
                    <input type="file" name="preuve[]" accept="image/jpeg, image/png, image/jpg" multiple required>

                </div>
            </div>
            <div class="form-row">
                <div class="input-data">
                    <input type="text" name="nom">
                    <div class="underline"></div>
                    <label for="">Nom</label>
                </div>
                <div class="input-data">
                    <input type="text" name="prenom" required>
                    <div class="underline"></div>
                    <label for="">Prénom*</label>
                </div>
            </div>
            <div class="form-row">
                <div class="input-data">
                    <input type="number" id="age" name="age" min="18" max="100">
                    <div class="underline"></div>
                    <label for="">Âge</label>
                </div>
                <div class="input-data">
                    <input type="text" name="ville">
                    <div class="underline"></div>
                    <label for="">Ville</label>
                </div>
            </div>
            <div class="form-row">
                <div class="input-data">
                    <input type="text" name="adresse">
                    <div class="underline"></div>
                    <label for="">Adresse</label>
                </div>
                <div class="input-data">
                    <input type="phone" name="numero" id="numero">
                    <div class="underline"></div>
                    <label for="">Numéro de téléphone du pedo</label>
                </div>
            </div>
            <div class="form-row">
                <div class="input-data">
                    <input type="text" name="pseudo" id="pseudo" required>
                    <div class="underline"></div>
                    <label for="">Pseudo*</label>
                </div>
            </div>
            <div class="form-row">
                <div class="input-data textarea">
                    <textarea rows="8" cols="80" name="infos" id="infos"></textarea>
                    <br />
                    <div class="underline"></div>
                    <label for="">Informations</label>
                    <br />
                    <div class="form-row submit-btn">
                        <div class="input-data">
                            <div class="inner"></div>
                            <input type="submit" value="submit">
                        </div>
                    </div>
        </form>
    </div>

</body>

</html>