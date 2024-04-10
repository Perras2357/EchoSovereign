<?php
    // Start the session
    session_start();

    $response = array();

    if (!empty($_POST["inscr_login"]))
    {
        foreach ($_POST as $key => $value)
        {
            $_POST[$key] = htmlspecialchars($value);
        }
        
        extract($_POST);

        if (file_exists('../DATA/organisateurs.json'))
        {
            //on charge le fichier json
            $jsonString = file_get_contents('../DATA/organisateurs.json');
            $existing_data = json_decode($jsonString, true);
            //on vérifie si le login existe dans le fichier json
            if (array_key_exists($inscr_login, $existing_data)) 
            {
                $response['message'] = 'Ce login existe déjà veillez vous connecter.';
                $response['success'] = false;
            }
            else
            {
                //on crée un tableau associatif avec les données du formulaire
                $existing_data[$inscr_login] = array("login" => $inscr_login,"password" => $inscr_password);
                //on convertit le tableau associatif en format json
                $json_data = json_encode($existing_data, JSON_PRETTY_PRINT);
                //on écrit le json dans le fichier
                file_put_contents('../DATA/organisateurs.json', $json_data);

                //on crée un dossier pour l'organisateur
                mkdir('../DATA/DATA_'.$inscr_login, 0777, true);

                //on se dirige vers la page de connexion
                //header('Location:../');

                $response['message'] = "Bienvenue ";
                $response['success'] = true;


            } 
        }
        else
        {
            //on crée un tableau associatif avec les données du formulaire
            $data[$inscr_login] = array("login" => $inscr_login,"password" => $inscr_password);
            //on convertit le tableau associatif en format json
            $json_data = json_encode($data, JSON_PRETTY_PRINT);
            //on écrit le json dans le fichier
            file_put_contents('../DATA/organisateurs.json', $json_data);

            //on crée un dossier pour l'organisateur
            mkdir('../DATA/DATA_'.$inscr_login, 0777, true);

            //on se dirige vers la page de connexion
            //header('Location:../');

            $response['message'] = "Bienvenue ";
            $response['success'] = true;
        }

    } 
    else 
    {
        $response['message'] = 'Erreur .';
        $response['success'] = false;
    }

    echo json_encode($response);
?>

