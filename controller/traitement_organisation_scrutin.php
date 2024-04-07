<?php
    // Start the session
    session_start();

    //ici on traite les données du formulaire venu de organisation_scrutin.view.php par ajax
    $response = array();
    
    if (!empty($_POST["titre"]))
    {
        extract($_POST);

        // Récupérer les valeurs des champs de date depuis $_POST
        $debut = $_POST['debut'];
        $fin = $_POST['fin'];
        
        // Supprimer les informations de fuseau horaire (GMT+0100)
        $debut = preg_replace('/\(.*\)/', '', $debut);
        $fin = preg_replace('/\(.*\)/', '', $fin);
        
        // Convertir les chaînes de date en objets DateTime
        $debut_date = new DateTime($debut);
        $fin_date = new DateTime($fin);

        //etat d'un scrutin
        $etat = "ouvert";

        // Formater les dates en chaînes de caractères
        $debut_formate = $debut_date->format('d-m-Y H:i:s');
        $fin_formate = $fin_date->format('d-m-Y H:i:s');
                
        if ($debut_formate <= date('d-m-Y H:i:s')) //si la date de début est inférieure à la date actuelle
        {
            $response['message'] = 'La date de début doit être supérieure à la date actuelle.';
            $response['success'] = false;
        }
        //si la date de fin est inférieure à la date de début
        if ($fin_formate < $debut_formate) 
        {
            $response['message'] = 'La date de fin doit etre supérieure a la date de debut.';
            $response['success'] = false;
        }
        elseif(empty($reponses))
        {
            $response['message'] = 'Veuillez entrer au moins une option de vote.';
            $response['success'] = false;
        }
        elseif ($voteSimple === "")         //si voteSimple est vide
        {
            $response['message'] = 'Veuillez choisir un type de vote.';
            $response['success'] = false;
        }
        elseif (strlen($titre) < 4)         //si le titre contient moins de 4 caractères
        {
            $response['message'] = 'Veuillez entrer un titre valide.';
            $response['success'] = false;
        }
        else
        {
            if (empty($response['success'])) 
            {
                
                $_SESSION['titre'] = $titre;

                //on vérelseifie si le fichier $titre_scrutin.json existe dans le repertoire DATA/DATA_'.$_SESSION['login']
                if (!file_exists('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json'))
                {
                    //on crée le fichier json du scrutin dans le repertoire DATA/DATA_'.$_SESSION['login']
                    $data[$titre]= array("organisation" => $organisation, "description" => $description, 
                        "debut" => $debut_formate, "fin" => $fin_formate, 
                        "voteSimple" => $voteSimple, 'options' => $reponses, "etat"=>$etat, "votants" => array());
                    $json = json_encode($data, JSON_PRETTY_PRINT);
                    file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json', $json);

                    $response['message'] = 'scrutin cree avec succes';
                    $response['success'] = true;

                }
                else
                {
                    //on charge le fichier json du scrutin
                    $jsonString = file_get_contents('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json');
                    $existing_data = json_decode($jsonString, true);

                    //on vérifie si le titre existe dans le fichier json du scrutin
                    if (array_key_exists($titre, $existing_data)) 
                    {
                        $response['message'] = 'Ce scrutin existe déjà.';
                        $response['success'] = false;
                    }
                    else
                    {
                        //on crée un tableau associatif avec les données du formulaire
                        $existing_data[$titre]= array("organisation" => $organisation, "description" => $description, 
                            "debut" => $debut_formate, "fin" => $fin_formate, 
                            "voteSimple" => $voteSimple, 'options' => $reponses, "etat"=>$etat, "votants" => array());
                        //on convertit le tableau associatif en format json
                        $json_data = json_encode($existing_data, JSON_PRETTY_PRINT);
                        //on écrit le json dans le fichier
                        file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json', $json_data);

                        $response['message'] = 'scrutin cree avec succes';
                        $response['success'] = true;
                    }
                }
            }
        }
    }
    else
    {
        $response['message'] = 'Les donnees du formulaire sont vides.';
        $response['success'] = false;
    }
    echo json_encode($response);

?>

