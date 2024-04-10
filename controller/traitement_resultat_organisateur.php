<?php
    // Start the session
    session_start();

    // Initialise la réponse
    $response = array();

    // Vérifie si l'utilisateur est connecté
    if (isset($_POST['titre']) && isset($_POST['resultat']))
    {
         // Vérifie si le répertoire DATA existe
         $dataDirectory = '../DATA/DATA_' . $_SESSION['login'];
        
        // Vérifie si le fichier voix.json existe
        $voixFile = $dataDirectory . '/voix.json';

        if (file_exists($voixFile)) 
        {
            //on charge le fichier json des voix
            $voixData = file_get_contents($voixFile);
            $voix = json_decode($voixData, true);

            //on vérifie si le titre existe dans le fichier json des voix
            if (array_key_exists($_POST['titre'], $voix)) 
            {
                //on vérifie si le vote existe dans le tableau comptage
                if(!empty($voix[$_POST['titre']]['comptage']))
                {
                    $response['resultats'] = $voix[$_POST['titre']]['comptage'];
                    $response['message'] = "Résultat des votes";
                    $response['success'] = true;
                }
                else
                {
                    $response['success'] = false;
                    $response['message'] = "Aucun vote n'a été enregistré";
                }
            } 
            else 
            {
                $response['success'] = false;
                $response['message'] = "Le scrutin n'existe pas";
            }

            // Convertit le tableau en JSON
            $voixData = json_encode($voix, JSON_PRETTY_PRINT);               
            file_put_contents($voixFile, $voixData);
            chmod($voixFile, 0777);
        }
    }
    else
    {
        $response['success'] = false;
        $response['message'] = "Vous n'êtes pas connecté";
    }
    echo json_encode($response);

?>