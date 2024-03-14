<?php
    chdir(dirname(__FILE__));
    //1)start the session
    session_start();
    


    //on vérifie si le formulaire de connexion a été soumis et si la méthode est POST
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) 
    {
        var_dump($_POST);
    }









    //2)inclure le fichier organisateur.view.php qui se trouve dans le model avec
    //require pour signaler une erreur s'il y en a et pourvoir utliser ses resulats
    include('../view/inscription.view.php');

?>