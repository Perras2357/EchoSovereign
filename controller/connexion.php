<?php
    // Start the session
    session_start();

    $response = array();

    if (!empty($_POST["ajax_login"]))
    {
        extract($_POST);
        
        // on vérifie si le fichier organisateurs.json existe dans le repertoire DATA
        if (file_exists('../DATA/organisateurs.json') and filesize('../DATA/organisateurs.json') > 0)
        {
            //on charge le fichier json
            $jsonString = file_get_contents('../DATA/organisateurs.json');
            $data = json_decode($jsonString, true);

            //on vérifie si le login existe dans le fichier json des organisateurs
            if (array_key_exists($ajax_login, $data)) 
            {
                //on vérifie si le mot de passe correspond au login
                if ($data[$ajax_login]['password'] == $ajax_password) 
                {
                    //on crée une session pour l'utilisateur
                    $_SESSION['login'] = $ajax_login;

                    $response['message'] = 'Bienvenue ';
                    $response['success'] = true;

                }
                else
                {
                    $response['message'] = 'Mot de passe incorrect.';
                    $response['success'] = false;
                }
            }
            else //on viendra vérifier si c'est un votant plus tard
            {
                $response['message'] = 'Ce login n\'existe pas.';
                $response['success'] = false;
            } 
        }
        else
        {
            $response['message'] = 'Ce login n\'existe pas.';
            $response['success'] = false;
        }
    } 
    else 
    {
        $response['message'] = 'Erreur : vide.';
        $response['success'] = false;
    }

    echo json_encode($response);
?>
