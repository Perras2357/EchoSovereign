<?php
chdir(dirname(__FILE__));
// Start the session
session_start();

// Traitement du formulaire d'inscription et gestion des erreurs
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) 
{
    // Récupérer les données du formulaire d'inscription
    $newUsername = isset($_POST["login"]) ? trim($_POST["login"]) : '';
    $newPassword = isset($_POST["password"]) ? $_POST["password"] : '';

    // Validation côté serveur
    $return = array();

    // Validation du nom d'utilisateur (exemple : doit être alphanumérique)
    if (!ctype_alnum($newUsername)) 
    {
        $return['errors']['username'] = "Le nom d'utilisateur doit être alphanumérique.";
    }

    // Validation du mot de passe (exemple : doit avoir au moins 6 caractères)
    if (strlen($newPassword) < 6) 
    {
        $return['errors']['password'] = "Le mot de passe doit avoir au moins 6 caractères.";
    }

    // Si aucune erreur, procéder à la connexion à la base de données
    if (empty($return['errors'])) 
    {
        // Vous pouvez aussi renvoyer une réponse JSON pour indiquer le succès
        $return['success'] = 'success';
        echo json_encode($return);
        exit(); // Assurez-vous de terminer le script après une réponse JSON
    } 
    else 
    {
        // Retourner les erreurs au format JSON
        echo json_encode($return);
        exit(); // Assurez-vous de terminer le script après une réponse JSON
    }
}

// Inclure le fichier authentification.view.php seulement si le formulaire n'a pas été soumis ou s'il y a des erreurs
include('../view/authentification.view.php');
?>
