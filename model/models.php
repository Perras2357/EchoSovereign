<?php

    //fonction de génération de mot de passe aléatoire de 9 caractères
    function generateRandomPassword($length = 9) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $password;
    }

    //fonction qui permet d'envoyer un mail à un votant avec son mot de passe 
    function sendMail($email, $password,$titre)
    {
        $envoie = false;
        
        $to = $email;
        $subject = "Lien de vote pour le scrutin ".$titre;
        $message = "Bonjour, voici le lien de vote pour le scrutin".$titre." : http://localhost/ProjetVote/scrutin.php?email=".$email."&password=".$password;
        $headers = "From: moiseperras2357@gmail.com" . "\r\n" .
            "Reply-To: moiseperras2357@gmail.com" . "\r\n" . 
            "X-Mailer: PHP/" . phpversion();

        // Envoyer l'e-mail
        if (mail($to, $subject, $message, $headers)) 
        {
            $envoie = true;
        } 
        else 
        {
            $envoie = false;
        }
        return $envoie;
    }





//     $to = "destinataire@example.com";
// $subject = "Sujet de l'e-mail";
// $message = "Contenu de l'e-mail";
// $headers = "From: expéditeur@example.com" . "\r\n" .
//     "Reply-To: expéditeur@example.com" . "\r\n" .
//     "X-Mailer: PHP/" . phpversion();

// // Envoyer l'e-mail
// if (mail($to, $subject, $message, $headers)) {
//     echo "E-mail envoyé avec succès.";
// } else {
//     echo "Échec de l'envoi de l'e-mail.";
// }

?>