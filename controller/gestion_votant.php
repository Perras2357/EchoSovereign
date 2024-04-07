<?php
     // Start the session
     session_start();

    require('../model/models.php');

    

     //ici on traite les données du formulaire venu de organisation_scrutin.view.php par ajax
    //  $response = array();

    //  //gestion des votants venant de la page organisation_scrutin.view.php de la fonction validateFormVotant()

    // if (!empty($_POST["votant"])) 
    // {
    //     extract($_POST);

    //     //on vérifie si le contenu de $votant est une adresse mail
    //     if (empty($votant))
    //     {
    //         $response['message'] = 'Veuillez entrer une adresse mail valide.';
    //         $response['success'] = false;
    //     }
    //     else
    //     {
    //         //on supprime les retours à la ligne
    //         $votant = str_replace(array("\n", "\r"), '', $votant);
    //         //on parcout $votant et lorsqu'on trouve une virgule on mets tout ce qui est avant la virgule dans une case du tableau $tab_votant
    //         $tab_votant = explode(",", $votant);

    //         //on vérifie si le fichier scrutin.json existe dans le repertoire DATA/DATA_'.$_SESSION['login']
    //         if (file_exists('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json') and filesize('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json') > 0)
    //         {
    //             //on parcourt le tableau $tab_votant pour créer un tableau associatif avec un mot de passe aléatoire de 9 caractères
    //             //******************************************************************************************* */
    //             foreach ($tab_votant as $vt) 
    //             {
    //                 $motdepasse = generateRandomPassword();
    //                 $authentification_votant[] = array("email" => $vt, "motdepasse" => $motdepasse, "etat_vote"=>"non", "procuration"=>0);
    //                 //on charge le fichier json
    //                 $jsonString = file_get_contents('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json');
    //                 $data = json_decode($jsonString, true);

                   
    //                 //on crée un tableau associatif avec les données du formulaire
    //                 $data[$_SESSION['titre']]['votants'][] = array("email" => $vt, "motdepasse" => $motdepasse,"etat_vote"=>"non",  "procuration"=>0);
    //                 //on convertit le tableau associatif en format json
    //                 $json_data = json_encode($data, JSON_PRETTY_PRINT);
    //                 //on écrit le json dans le fichier
    //                 file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json', $json_data);
    //                 $response['message'] = true;
    //                 $response['success'] = true;
    //                     // $response['message'] = sendMail($vt, $motdepasse,$_SESSION['titre']);
    //                     // if ($response['message'] == true) 
    //                     // {
    //                     //     $response['success'] = true;
    //                     // }
    //                     // else
    //                     // {
    //                     //     $response['success'] = false;
    //                     // }
                     
    //             }
    //            //******************************************************************************// */    
    //         }
    //         else
    //         {
    //             //on parcourt le tableau $tab_votant pour créer un tableau associatif avec un mot de passe aléatoire de 9 caractères
    //             //******************************************************************************************* */
    //             foreach ($tab_votant as $vt) 
    //             {
    //                 $motdepasse = generateRandomPassword();
    //                 //$authentification_votant[] = array("email" => $vt, "motdepasse" => $motdepasse, "etat_vote"=>"non");
    //                 //on crée un tableau associatif avec les données du formulaire
    //                 $data[$votant] = array("email" => $vt, "motdepasse" => $motdepasse, "etat_vote"=>"non", "procuration"=>0);
    //                 //on convertit le tableau associatif en format json
    //                 $json_data = json_encode($data, JSON_PRETTY_PRINT);
    //                 //on écrit le json dans le fichier
    //                 file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json', $json_data);
    //                 $response['message'] = true;
    //                 $response['success'] = true;
    //                 // $response['message'] = sendMail($vt, $motdepasse,$_SESSION['titre']);
    //                 // if ($response['message'] == true) 
    //                 // {
    //                 //     $response['success'] = true;
    //                 // }
    //                 // else
    //                 // {
    //                 //     $response['success'] = false;
    //                 // }
    //             }
    //         }
            
    //     }
    // }
    // else
    // {
    //     $response['message'] = 'Les données du formulaire sont vides.';
    //     $response['success'] = false;
    // }
    // echo json_encode($response);

    //**************************************************************************************************** */
    $response = array();
    //gestion des votants venant de la page gestion_votant.view.php de la fonction addRow()
    if (isset($_POST['votants'])) 
    {
       
        extract($_POST);
        //on vérifie si le fichier scrutin.json existe dans le repertoire DATA/DATA_'.$_SESSION['login']
        if (file_exists('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json') and filesize('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json') > 0)
        {
            //on charge le fichier json
            $jsonString = file_get_contents('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json');
            $data = json_decode($jsonString, true);
            //on parcourt le tableau $votants pour créer un tableau associatif avec un mot de passe aléatoire de 9 caractères
            //******************************************************************************************* */
            foreach ($votants as $vt) 
            {
                $vt['nombreProcuration'] = intval($vt['nombreProcuration']) +1;


                $motdepasse = generateRandomPassword();

                $info_exist = file_get_contents('../DATA/DATA_'.$_SESSION['login'].'/info.json');
                $data_info = json_decode($info_exist, true);

                $authentification_votant[] = array("email" => $vt['email'], "motdepasse" => $motdepasse, "etat_vote"=>"non", "procuration"=>$vt['nombreProcuration']);

                //on crée un tableau avec le mail, le mot de passe et le lien contenant le scrutin et le mot de passe
                $message = "href='http://localhost/scrutin/scrutin.php?scrutin=".$_SESSION['titre']."&".$_SESSION['titre'];
                $data_info[$vt['email']] = array("email" => $vt['email'], "motdepasse" => $motdepasse, "lien" => $message);
                //on crée le fichier json
                $jsonInfo = json_encode($data_info, JSON_PRETTY_PRINT);
                //on écrit le json dans le fichier
                file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/info.json', $jsonInfo);

                //on crée un tableau associatif avec les données du formulaire
                $data[$_SESSION['titre']]['votants'][] = array("email" => $vt['email'], "motdepasse" => $motdepasse,"etat_vote"=>"non",  "procuration"=>$vt['nombreProcuration']);
                //on convertit le tableau associatif en format json
                $json_data = json_encode($data, JSON_PRETTY_PRINT);
                //on écrit le json dans le fichier
                file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json', $json_data);
                $response['message'] = true;
                $response['success'] = true;

                 
            }
           //******************************************************************************// */    
        }
        else
        {
            //on parcourt le tableau $votants pour créer un tableau associatif avec un mot de passe aléatoire de 9 caractères
            //******************************************************************************************* */
            foreach ($votants as $vt) 
            {
                $vt['nombreProcuration'] = intval($vt['nombreProcuration']) +1;

                $motdepasse = generateRandomPassword();

                //on crée un tableau avec le mail, le mot de passe et le lien contenant le scrutin et le mot de passe
                $message = "href='http://localhost/scrutin/scrutin.php?scrutin=".$_SESSION['titre']."&".$_SESSION['titre'];
                $tableauInfo[$vt['email']] = array("email" => $vt['email'], "motdepasse" => $motdepasse, "lien" => $message);
                //on crée le fichier json
                $jsonInfo = json_encode($tableauInfo, JSON_PRETTY_PRINT);
                //on écrit le json dans le fichier
                file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/info.json', $jsonInfo);

                //on crée un tableau associatif avec les données du formulaire
                $data[$votant] = array("email" => $vt['email'], "motdepasse" => $motdepasse, "etat_vote"=>"non", "procuration"=>$vt['nombreProcuration']);
                //on convertit le tableau associatif en format json
                $json_data = json_encode($data, JSON_PRETTY_PRINT);
                //on écrit le json dans le fichier
                file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json', $json_data);
                $response['message'] = true;
                $response['success'] = true;

            }
        }
}
echo json_encode($response);


?>