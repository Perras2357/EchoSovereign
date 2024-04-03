<?php
    require("head.php");
?>

<!-- formulaire d'inscription qui demande un login un password et un bouton pour s'inscrire -->
<div class="col-lg-8 col-md-8 offset-lg-2 offset-md-2 shadow-none p-3 mb-5 bg-light rounded p-5" >

    <h3 class="text-center">Voulez vous créer des scrutins ? inscrivez vous</h3>
    <form name="inscriptionForm" method="post" onsubmit="return validateFormInscription()">
        
        <!-- afficher un message d'erreur s'il en a -->
        <span id="error"></span><br>
        <!--entrer un login-->
        <div class="row mb-3">
            <label for="login" class="col-form-label">Login</label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="login" name="login" required>
                <span id="errorLogin" class="error"></span><br>
            </div>
        </div>

        <!--entrer un password-->
        <div class="row mb-3">
            <label for="password" class="col-form-label">Password</label>
            <div class="col-md-9">
                <input type="password" class="form-control" id="password" name="password" required>
                <span id="errorPassword" class="error"></span><br>
            </div>
        </div>

        <!--entrer confirmez le password-->
        <div class="row mb-3">
            <label for="confirme_password" class="col-form-label">Confirmez le password</label>
            <div class="col-md-9">
                <input type="password" class="form-control" id="confirme_password" name="confirme_password" required>
                <span id="errorConfirme_password" class="error"></span><br>
            </div>
        </div>

        <!--bouton pour s'inscrire-->
        <div class="mb-3 py-2">
            <button type="submit" class="btn btn-primary  col-md-2 offset-md-5" id="submit" name="submit">Submit</button>
        </div>
    </form>
    <div class="row">
        <div class="col-md-9 offset-md-3 " id="inscription">

        </div>
    </div>
</div>
        

    <!-- inclure le fichier js pour la validation du formulaire -->
    <script src="../js/ajax_inscription.js"></script>


<?php
    require("footer.php");
?>
