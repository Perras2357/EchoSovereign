<?php
    // Start the session
    session_start();

    if (isset($_GET['scrutin']) && isset($_GET['titre']))
    {
        foreach ($_GET as $key => $value)
        {
            $_GET[$key] = htmlspecialchars($value);
        }
            extract($_GET);

            $_SESSION['scrutin'] = $scrutin;
            $_SESSION['titre'] = $titre;
    }




    require("view/connexion.view.php");
?>