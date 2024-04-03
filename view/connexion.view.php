<?php
    //1)inclure le fichier head.php require pour éviter de l'inclure plusieurs fois
    //et signaler une erreur s'il y en a
    require_once("head.php");
?>

<!-- formulaire de connexion qui demande un login un password et un bouton pour se connecter -->
<div class="col-lg-8 col-md-8 offset-lg-2 offset-md-2 shadow-none p-3 mb-5 bg-light rounded p-5" >
    <form name="connexionForm" method="get" onsubmit="return validateFormConnexion()">
        
        <!-- afficher un message d'erreur s'il en a -->
        <span id="error"></span><br>
        <!--entrer un login-->
        <div class="row mb-3">
            <label for="login" class="col-form-label">Login?</label>
            <div class="col-md-9"> <!-- Ajout de la classe "col-md-9" manquante -->
                <input type="text" class="form-control" id="login" name="login" required>
                <span id="errorLogin" class="error"></span><br>
            </div>
        </div>

        <!--entrer un password-->
        <div class="row mb-3">
            <label for="password" class="col-form-label">Password</label>
            <div class="col-md-9"> <!-- Ajout de la classe "col-md-9" manquante -->
                <input type="password" class="form-control" id="password" name="password" required>
                <span id="errorPassword" class="error"></span><br>
            </div>
        </div>

        <!--bouton pour seconnecter-->
        <div class="mb-3 py-2">
            <button type="submit" class="btn btn-primary  col-md-2 offset-md-5" id="submit" name="submit">Submit</button>
        </div>
    </form>
    <div class="row">
        <div class="col-md-9 offset-md-3 " id="inscription">
        </div>
    </div>
    <div class="row">
        <div class="col-md-9 offset-md-3 " id="insc">
            <a href="controller/inscription.php">Pas de compte ? Inscrivez-vous !</a>
        </div>
    </div>
</div>

    <!-- inclure le fichier js pour la validation du formulaire -->
    <script src="js/ajax_connexion.js"></script>

<?php
    require_once("footer.php");
?>
