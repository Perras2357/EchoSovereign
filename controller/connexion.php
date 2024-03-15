<?php
    // Start the session
    session_start();

    $response = array();

    if (!empty($_POST["ajax_login"]))
    {
        //on le 
        
        $x = $_POST["ajax_login"];
        $response['message'] = $x;
        $response['success'] = true;
    } 
    else 
    {
        $response['message'] = 'Erreur lors de la connexion.';
        $response['success'] = false;
    }

    echo json_encode($response);
?>
