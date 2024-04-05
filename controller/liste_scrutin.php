<?php
// Start the session
session_start();

// Initialise la réponse
$response = array();

// Vérifie si l'utilisateur est connecté
if (isset($_POST['organisateur']) && isset($_SESSION['login'])) 
{
    // Vérifie si le répertoire DATA existe
    $dataDirectory = '../DATA/DATA_' . $_SESSION['login'];
    if (file_exists($dataDirectory)) {
        // Vérifie si le fichier scrutin.json existe
        $scrutinFile = $dataDirectory . '/scrutin.json';
        if (file_exists($scrutinFile)) 
        {
            // Récupère le contenu du fichier scrutin.json
            $scrutinData = file_get_contents($scrutinFile);
            if ($scrutinData !== false) {
                // Décode le contenu JSON
                $scrutinArray = json_decode($scrutinData, true);
                if ($scrutinArray !== null) {
                    $scrutins = array();

                    // Parcours chaque scrutin
                    foreach ($scrutinArray as $titre => $scrutinItem) {
                        // Vérifie l'existence des clés avant d'y accéder
                        $organisation = isset($scrutinItem['organisation']) ? $scrutinItem['organisation'] : '';
                        $description = isset($scrutinItem['description']) ? $scrutinItem['description'] : '';
                        $debut = isset($scrutinItem['debut']) ? $scrutinItem['debut'] : '';
                        $fin = isset($scrutinItem['fin']) ? $scrutinItem['fin'] : '';
                        $etat = isset($scrutinItem['etat']) ? $scrutinItem['etat'] : '';
                        $nbr_votants = isset($scrutinItem['votants']) ? count($scrutinItem['votants']) : 1;

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
                            }
                        }

                        // Stocke les données du scrutin dans un tableau
                        $scrutins[] = array(
                            'titre' => $titre,
                            'organisation' => $organisation,
                            'description' => $description,
                            'debut' => $debut,
                            'fin' => $fin,
                            'etat' => $etat,
                            'nbr_votants' => $nbr_votants,
                            'nbr_votes' => $nbr_votes
                        );
                    }

                    // Stocke les scrutins dans la réponse
                    $response['scrutins'] = $scrutins;
                    $response['success'] = true;
                } else {
                    $response['message'] = 'Le fichier scrutin.json est vide ou le contenu est invalide.';
                    $response['success'] = false;
                }
            } else {
                $response['message'] = 'Erreur lors de la lecture du fichier scrutin.json.';
                $response['success'] = false;
            }
        } else {
            $response['message'] = 'Le fichier scrutin.json n\'existe pas dans le répertoire de données.';
            $response['success'] = false;
        }
    } else {
        $response['message'] = 'Le répertoire de données n\'existe pas pour l\'utilisateur connecté.';
        $response['success'] = false;
    }
} 
else 
{
    $response['message'] = 'Erreur : utilisateur non connecté.';
    $response['success'] = false;
}
// Définit les en-têtes CORS
// Définit l'en-tête CORS pour autoriser les ressources provenant d'un réseau privé
header("Access-Control-Allow-Private-Network: true");
header("Access-Control-Allow-Origin: *"); // Autorise les requêtes depuis n'importe quelle origine
header("Content-Type: application/json"); // Définit le type de contenu comme JSON
// Convertit la réponse en JSON et l'envoie
echo json_encode($response);
?>
