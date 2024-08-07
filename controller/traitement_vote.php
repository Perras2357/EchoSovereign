<?php
// Start the session
session_start();

// Initialise la réponse
$response = array();

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['login']) && isset($_SESSION['scrutin']) && isset($_SESSION['titre']) && isset($_POST['organisateur']))
{
    // Vérifie si le répertoire DATA existe
    $dataDirectory = '../DATA/DATA_' . $_SESSION['scrutin'];
    if (file_exists($dataDirectory)) 
    {
        // Vérifie si le fichier scrutin.json existe
        $scrutinFile = $dataDirectory . '/scrutin.json';
        if (file_exists($scrutinFile)) 
        {
            // Récupère le contenu du fichier scrutin.json
            $scrutinData = file_get_contents($scrutinFile);
            if ($scrutinData !== false) 
            {
                // Décode le contenu JSON
                $scrutinArray = json_decode($scrutinData, true);
                if ($scrutinArray !== null) 
                {
                    $scrutins = array();

                    // Parcours chaque scrutin
                    foreach ($scrutinArray as $titre => $scrutinItem) 
                    {
                        // Vérifie l'existence des clés avant d'y accéder
                        $organisation = isset($scrutinItem['organisation']) ? $scrutinItem['organisation'] : '';
                        $description = isset($scrutinItem['description']) ? $scrutinItem['description'] : '';
                        $debut = isset($scrutinItem['debut']) ? $scrutinItem['debut'] : '';
                        $fin = isset($scrutinItem['fin']) ? $scrutinItem['fin'] : '';
                        $voteSimple = isset($scrutinItem['voteSimple']) ? $scrutinItem['voteSimple'] : '';
                        //$options = isset($scrutinItem['options']) ? $scrutinItem['options'] : '';
                        $etat = isset($scrutinItem['etat']) ? $scrutinItem['etat'] : '';
                        $nbr_votants = isset($scrutinItem['votants']) ? count($scrutinItem['votants']) : 1;

                        $options = [];

                        if (isset($scrutinItem['options']) && is_array($scrutinItem['options'])) 
                        {
                            foreach ($scrutinItem['options'] as $option) 
                            {
                                $options[] = $option;
                            }
                        }

                        // Compte le nombre de votes
                        $nbr_votes = 0;
                        if (isset($scrutinItem['votants'])) 
                        {
                            foreach ($scrutinItem['votants'] as $votant) 
                            {
                                if (isset($votant['etat_vote']) && $votant['etat_vote'] === 'oui') 
                                {
                                    $nbr_votes++;
                                }
                                if(isset($votant['procuration']) && $votant['email'] == $_SESSION['login'])
                                {
                                    $email = $votant['email'];
                                    $procuration = $votant['procuration'];
                                    $etat_vote = $votant['etat_vote'];

                                    if($votant['etat_vote'] == 'oui')
                                    {
                                        $etat_vote = 'non';
                                    }
                                    else
                                    {
                                        $etat_vote = 'oui';
                                    }
                                }    
                            }
                        }
                        if ($titre == $_SESSION['titre'] && $email == $_SESSION['login']) 
                        {
                            if ($fin <= date('d-m-Y H:i:s')) //si la date de fin est inférieure à la date actuelle
                            {
                                $etat = 'fermer';
                            }
                            if(empty($description))
                            {
                                $description = 'Pas de description';
                            }
                            // Stocke les données du scrutin dans un tableau
                            $scrutins[] = array(
                                'titre' => $titre,
                                'organisation' => $organisation,
                                'description' => $description,
                                'debut' => $debut,
                                'fin' => $fin,
                                'voteSimple' => $voteSimple,
                                'options' => $options,
                                'acces_vote' => $etat,
                                'procuration' => $procuration,
                                'je_peux_voter' => $etat_vote,
                                'nbr_votants' => $nbr_votants,
                                'nbr_votes' => $nbr_votes
                            );
                        }
                        
                    }

                    // Stocke les scrutins dans la réponse
                    $response['scrutins'] = $scrutins;
                    $response['success'] = true;
                } 
                else 
                {
                    $response['message'] = 'Le fichier scrutin.json est vide ou le contenu est invalide.';
                    $response['success'] = false;
                }
            } 
            else 
            {
                $response['message'] = 'Erreur lors de la lecture du fichier scrutin.json.';
                $response['success'] = false;
            }
        } 
        else 
        {
            $response['message'] = 'Le fichier scrutin.json n\'existe pas dans le répertoire de données.';
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
    $response['message'] = 'Vous devez vous connecter pour accéder à cette page.';
    $response['success'] = false;
}

// Convertit la réponse en JSON et l'envoie
echo json_encode($response);
?>
