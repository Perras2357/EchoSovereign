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
        // Chemin vers la clé privée
        $privateKeyPath = '../Encrypte/private.pem';
        // Lire le contenu de la clé privée
        $privateKey = file_get_contents($privateKeyPath);
        // Vérifier si la clé privée est chargée avec succès
        if ($privateKey === false) 
        {
            $response['message'] = 'Erreur lors du chargement de la clé privée';
            $response['success'] = false;
        }
        else
        {
            // Déchiffrer les données avec la clé privée
            $decryptedData = null;
            openssl_private_decrypt(base64_decode($ajax_password), $decryptedData, $privateKey);
            // Vérifier si le déchiffrement a réussi
            if ($decryptedData === NULL) 
            {
                $response['message'] = 'Erreur lors du déchiffrement des données.';
                $response['success'] = false;
            } 
            else 
            {
        
                // on vérifie si le fichier organisateurs.json existe dans le repertoire DATA
                if (file_exists('../DATA/organisateurs.json') and filesize('../DATA/organisateurs.json') > 0)
                {
                    //on charge le fichier json
                    $jsonString = file_get_contents('../DATA/organisateurs.json');
                    $data = json_decode($jsonString, true);

                    //on vérifie si le login existe dans le fichier json des organisateurs
                    if (array_key_exists($ajax_login, $data)) 
                    {
                        //on vérifie si le mot de passe correspond au hash du mot de passe
                        if (password_verify($decryptedData, $data[$ajax_login]['password'])) 
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
                                        if ($key2['motdepasse'] == $decryptedData) 
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
                                        $response['message'] = 'Ce login n\'existe pas4.';
                                        $response['success'] = false;
                                    }
                                }
                            }
                        }
                        else
                        {
                            $response['message'] = 'Ce login n\'existe pas3.';
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
                            if ($dataVotant[$_SESSION['titre']]['votants'][$ajax_login]['password'] == $decryptedData) 
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
                            $response['message'] = 'Ce login n\'existe pas1.';
                            $response['success'] = false;
                        }   
                    }
                    else
                    {
                        $response['message'] = 'Ce login n\'existe pas2.';
                        $response['success'] = false;
                    }
                }
            }
        }
    } 
    else 
    {
        $response['message'] = 'Erreur : vide.';
        $response['success'] = false;
    }
    echo json_encode($response);
?>
