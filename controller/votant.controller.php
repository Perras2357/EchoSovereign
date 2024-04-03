<?php
    // // Start the session
    // session_start();

    // //ici on traite les données du formulaire venu de organisation_scrutin.view.php par ajax
    // $reponse = array();

    // if (isset($_POST["mail"]) && isset($_POST["nbProcuration"]) && isset($_POST["donnerProcuration"])) 
    // {
    //     extract($_POST);

    //     if ($nbProcuration < 0) 
    //     {
    //         $reponse['success'] = false;
    //         $reponse['message'] = "Le nombre de procuration doit être positif";
    //     } 
    //     elseif ($nbProcuration > 0 && $donnerProcuration == true) 
    //     {
    //         $reponse['success'] = false;
    //         $reponse['message'] = "Le nombre de procuration doit être 0 si vous donnez une procuration";
    //     } 
    //     elseif ($nbProcuration > 2) 
    //     {
    //         $reponse['success'] = false;
    //         $reponse['message'] = "Le nombre de procuration doit être inférieur à 2";
    //     } 
    //     elseif ($donnerProcuration == true && $nbProcuration > 0) 
    //     {
    //         $reponse['success'] = false;
    //         $reponse['message'] = "Vous ne pouvez pas donner une procuration et en avoir une";
    //     } 
    //     else 
    //     {
            
    //         //on cree un fichier json pour stocker les données des votants dans le repertoire DATA
    //         $file = '../DATA/votant.json';
    //         $data = array();
    //         $data['mail'] = $mail;
    //         $data['nbProcuration'] = $nbProcuration;
    //         $data['donnerProcuration'] = $donnerProcuration;

    //         //on encode les données en json
    //         $json = json_encode($data);
    //         //on écrit les données dans le fichier json
    //         file_put_contents($file, $json);

    //         //appel ajax pour envoyer les données du formulaire au serveur pour les traiter
    //         $reponse['success'] = true;
    //         $reponse['message'] = "Les données ont été envoyées avec succès";

    //     }
    // } 
    
    // echo json_encode($reponse);


// Start the session
// session_start();

// // Get the JSON data from the AJAX request
// $votantData = json_decode($_POST['votantData'], true);

// // Initialize the response array
// $reponse = array();

// // Validate the data
// if (!empty($votantData)) 
// {
//     // Extract the data
//     $mail = $votantData["mail"];
//     $nbProcuration = $votantData["nbProcuration"];
//     $donnerProcuration = $votantData["donnerProcuration"];

//     // Validate the data here...

//     // If the data is valid, save it to the database and set the success message
//     if (!empty($mail)) 
//     {
//         // Save the data to the database here...

//         $reponse['success'] = true;
//         $reponse['message'] = "Les données ont été enregistrées avec succès";
//     } 
//     else 
//     {
//         $reponse['success'] = false;
//         $reponse['message'] = "Les données sont invalides";
//     }
// } 
// else 
// {
//     $reponse['success'] = false;
//     $reponse['message'] = "Les données sont manquantes";
// }

// // Return the response as JSON
// echo json_encode($reponse);
if (!isset($_POST))
{
    $reponse['success'] = false;
    $reponse['message'] = "Les données sont manquantes";
   
}
else
{
    $reponse['success'] = true;
    $reponse['message'] = "Les  sont presentes";
}
echo json_encode($reponse);
?>