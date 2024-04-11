<?php
     // Start the session
     session_start();

    require('../model/models.php');

    $response = array();
    if (isset($_POST['votants']) && isset($_SESSION['login'])) 
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
                $etat_vote = "non";
                $vt['nombreProcuration'] = intval($vt['nombreProcuration']) +1;
                if($vt['nombreProcuration']==0)
                {
                    $etat_vote = "oui";
                }


                $motdepasse = generateRandomPassword();

                $authentification_votant[] = array("email" => $vt['email'], "motdepasse" => $motdepasse, "etat_vote"=>$etat_vote, "procuration"=>$vt['nombreProcuration']);

                //on crée un tableau avec le mail, le mot de passe et le lien contenant le scrutin et le mot de passe
                $message = "href='http://localhost/EchoSovereign/?scrutin=".$_SESSION['login']."&titre=".$_SESSION['titre'];
                $data_info[$vt['email']] = array("email" => $vt['email'], "motdepasse" => $motdepasse, "lien" => $message);
                //on crée le fichier json
                $jsonInfo = json_encode($data_info, JSON_PRETTY_PRINT);
                //on écrit le json dans le fichier
                file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/info.json', $jsonInfo);

                //on crée un tableau associatif avec les données du formulaire
                $data[$_SESSION['titre']]['votants'][] = array("email" => $vt['email'], "motdepasse" => $motdepasse,"etat_vote"=>$etat_vote,  "procuration"=>$vt['nombreProcuration']);
                //on convertit le tableau associatif en format json
                $json_data = json_encode($data, JSON_PRETTY_PRINT);
                //on écrit le json dans le fichier
                file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/scrutin.json', $json_data);
                $response['message'] = true;
                $response['success'] = true;

                 
            }
        }
        else
        {
            //on parcourt le tableau $votants pour créer un tableau associatif avec un mot de passe aléatoire de 9 caractères
            //******************************************************************************************* */
            foreach ($votants as $vt) 
            {
                $etat_vote = "non";
                $vt['nombreProcuration'] = intval($vt['nombreProcuration']) +1;
                if($vt['nombreProcuration']==0)
                {
                    $etat_vote = "oui";
                }

                $motdepasse = generateRandomPassword();

                //on crée un tableau avec le mail, le mot de passe et le lien contenant le scrutin et le mot de passe
                $message = "href='http://localhost/EchoSovereign/?scrutin=".$_SESSION['login']."&titre=".$_SESSION['titre'];
                $tableauInfo[$vt['email']] = array("email" => $vt['email'], "motdepasse" => $motdepasse, "lien" => $message);
                //on crée le fichier json
                $jsonInfo = json_encode($tableauInfo, JSON_PRETTY_PRINT);
                //on écrit le json dans le fichier
                file_put_contents('../DATA/DATA_'.$_SESSION['login'].'/info.json', $jsonInfo);

                //on crée un tableau associatif avec les données du formulaire
                $data[$votant] = array("email" => $vt['email'], "motdepasse" => $motdepasse, "etat_vote"=>$etat_vote, "procuration"=>$vt['nombreProcuration']);
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