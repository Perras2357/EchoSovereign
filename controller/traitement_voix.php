<?php
    // Start the session
    session_start();

    // Initialise la réponse
    $response = array();

    // Vérifie si l'utilisateur est connecté
    if (isset($_SESSION['login']) && isset($_SESSION['scrutin']) && isset($_SESSION['titre']) && isset($_POST['voix']))
    {
        // Vérifie si le répertoire DATA existe
        $dataDirectory = '../DATA/DATA_' . $_SESSION['scrutin'];
        if (file_exists($dataDirectory)) 
        {
            //on vérifie si le fichier scrutin.json existe
            if(file_exists($dataDirectory . '/scrutin.json'))
            {
                //on vérifie si le nombre de procuration est supérieure à -1
                $scrutinFile = $dataDirectory . '/scrutin.json';
                $scrutinData = file_get_contents($scrutinFile);
                $scrutinArray = json_decode($scrutinData, true);

                //on parcours le fichier json des scrutins pour vérifier si le titre existe et si le nombre de procuration de l'utilisateur dont le mail correspond à login est supérieur à -1
                foreach ($scrutinArray as $titre => &$scrutinItem) 
                {
                    if ($titre == $_SESSION['titre']) 
                    {
                        //on vérifie si le srutin est ouvert
                        if($scrutinItem['etat']=='ouvert')
                        {
                            //on vérifie si le nombre de procuration est supérieur à -1
                            foreach ($scrutinItem['votants'] as &$votant)
                            {
                                
                                if($votant['email'] == $_SESSION['login'])
                                {
                                    if(($votant['procuration'] !=0 && $votant['etat_vote'] != 'oui'))
                                    {
                                        // Vérifie si le fichier voix.json existe
                                        $voixFile = $dataDirectory . '/voix.json';

                                        if (file_exists($voixFile)) 
                                        {
                                            //on charge le fichier json des voix
                                            $voixData = file_get_contents($voixFile);
                                            $existing_voix = json_decode($voixData, true);

                                            //on vérifie si le titre existe dans le fichier json des voix
                                            if (array_key_exists($_SESSION['titre'], $existing_voix)) 
                                            {
                                                $voix = $existing_voix;
                                                $voix[$_SESSION['titre']]['votant'][] = array("login" => $_SESSION['login'], 
                                                                            "date" => date('Y-m-d H:i:s'), "voix" => $_POST['vote']);
                                            } 
                                            else 
                                            {
                                                //on complete le fichier voix.json avec les données
                                                $existing_voix[$_SESSION['titre']] = array("votant" => array(array("login" => $_SESSION['login'], 
                                                                            "date" => date('Y-m-d H:i:s'), "voix" => $_POST['vote'])));

                                                $voix = $existing_voix;
                                            }

                                            // Convertit le tableau en JSON
                                            $voixData = json_encode($voix, JSON_PRETTY_PRINT);               
                                            file_put_contents($voixFile, $voixData);
                                            chmod($voixFile, 0777);

                                            //on modifie le nombre de procuration dans le fichier scrutin.json
                                            $votant['procuration'] -= 1;
                                            

                                            //on vérifie si le nombre de procuration est égale à -1 pour mettre l'état de vote à oui
                                            if ($votant['procuration'] == 0) 
                                            {
                                                $votant['etat_vote'] = 'oui';
                                            }

                                                //Convertit le tableau en JSON
                                            $scrutinData1 = json_encode($scrutinArray, JSON_PRETTY_PRINT);
                                            file_put_contents($scrutinFile, $scrutinData1);

                                            $response['message'] = 'Votre vote a été enregistré.';
                                            $response['success'] = true;
                                            
                                            break 2;
                                        }
                                        else
                                        {
                                            //crée le fichier voix.json avec les données
                                            $voix[$_SESSION['titre']] = array("votant" => array(array("login" => $_SESSION['login'], 
                                                                        "date" => date('Y-m-d H:i:s'), "voix" => $_POST['vote'])));

                                            // Convertit le tableau en JSON
                                            $voixData = json_encode($voix, JSON_PRETTY_PRINT);

                                            // Écrit le contenu
                                            file_put_contents($voixFile, $voixData);
                                            chmod($voixFile, 0777);

                                            //on modifie le nombre de procuration dans le fichier scrutin.json
                                            $votant['procuration'] -= 1;
                                            

                                            //on vérifie si le nombre de procuration est égale à -1 pour mettre l'état de vote à oui
                                            if ($votant['procuration'] == 0) 
                                            {
                                                $votant['etat_vote'] = 'oui';
                                            }

                                                //Convertit le tableau en JSON
                                            $scrutinData1 = json_encode($scrutinArray, JSON_PRETTY_PRINT);
                                            file_put_contents($scrutinFile, $scrutinData1);

                                            $response['message'] = 'Votre vote a été enregistré.';
                                            $response['success'] = true;
                                            
                                            break 2;
                                        
                                        }
                                        
                                    }
                                    else
                                    {
                                        $response['message'] = 'Vous ne pouvez plus voter.';
                                        $response['success'] = false;

                                        break 2;
                                    }
                                }
                                
                            }
        
                        }
                        else
                        {
                            $response['message'] = 'Le scrutin est fermé. Vous ne pouvez plus voter.';
                            $response['success'] = false;
                        }
                    }//pas de else pour la vérification du titre
                }
            }
            else
            {
                $response['message'] = 'Le fichier scrutin.json n\'existe pas';
                $response['success'] = false;
            }

        } 
        else 
        {
            $response['message'] = 'Le répertoire de données n\'existe pas pour l\'utilisateur connecté.';
            $response['success'] = false;
        }
    } 
    else 
    {
        $response['message'] = 'Vous devez voter.';
        $response['success'] = false;
    }

// Convertit la réponse en JSON et l'envoie
echo json_encode($response);
?>
