<?php
    // Start the session
    session_start();

    $response = array();

    if (isset($_POST['ajax_submit']) && !empty($_POST['ajax_login']) && !empty($_POST['ajax_password']))
    {
        foreach ($_POST as $key => $value)
        {
            $_POST[$key] = htmlspecialchars($value);
        }

        extract($_POST);
        //on crée une session pour l'utilisateur
        $_SESSION['login'] = $ajax_login;
        
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
                    $response['message'] = 'Bienvenue organisateur.';
                    $response['success'] = true;
                }
                else
                {
                    $response['message'] = 'Mot de passe incorrect.';
                    $response['success'] = false;
                }
            }
            else
            {
                //on vérifie s'il y a une session scrutin et titre
                if (isset($_SESSION['scrutin']) and isset($_SESSION['titre']))
                {
                    //on ouvre le fichier json du scrutin
                    $jsonString = file_get_contents('../DATA/DATA_' . $_SESSION['scrutin'] . '/scrutin.json');
                    $dataVotant = json_decode($jsonString, true);

                    foreach($dataVotant as $key => $value)
                    {
                        foreach($value['votants'] as $key2)
                        {
                            if($key2['email']==$ajax_login)
                            {
                                //on vérifie si le mot de passe correspond au login
                                if ($key2['motdepasse'] == $ajax_password) 
                                {
                                    $response['message'] = 'Bienvenue votant.';
                                    $response['success'] = true;

                                    break 2;
                                }
                                else
                                {
                                    $response['message'] = 'Mot de passe incorrect.';
                                    $response['success'] = false;
                                }
                            }
                            else
                            {
                                $response['message'] = 'Ce login n\'existe pas.';
                                $response['success'] = false;
                            }
                        }
                    }
                      
                }
                else
                {
                    $response['message'] = 'Ce login n\'existe pas.';
                    $response['success'] = false;
                }
            } 
        }
        else
        {
            //on vérifie s'il y a une session scrutin et titre
            if (isset($_SESSION['scrutin']) and isset($_SESSION['titre']))
            {
                //on ouvre le fichier json du scrutin
                $jsonString = file_get_contents('../DATA/DATA_' . $_SESSION['scrutin'] . '/scrutin.json');
                $dataVotant = json_decode($jsonString, true);

                if (array_key_exists($ajax_login, $dataVotant[$_SESSION['titre']]['votants'])) 
                {
                    //on vérifie si le mot de passe correspond au login
                    if ($dataVotant[$_SESSION['titre']]['votants'][$ajax_login]['password'] == $ajax_password) 
                    {
                        $response['message'] = 'Bienvenue votant.';
                        $response['success'] = true;
                    }
                    else
                    {
                        $response['message'] = 'Mot de passe incorrect.';
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
                $response['message'] = 'Ce login n\'existe pas.';
                $response['success'] = false;
            }
        }
    } 
    else 
    {
        $response['message'] = 'Erreur : vide.';
        $response['success'] = false;
    }

    // Définit les en-têtes CORS
// Définit l'en-tête CORS pour autoriser les ressources provenant d'un réseau privé
header("Access-Control-Allow-Private-Network: true");
header("Access-Control-Allow-Origin: *"); // Autorise les requêtes depuis n'importe quelle origine
header("Content-Type: application/json"); // Définit le type de contenu comme JSON
    echo json_encode($response);
?>
