
<?php
    //1)inclure le fichier head.php require pour éviter de l"inclure plusieurs fois
    //et signaler une erreur s"il y en a
    chdir(dirname(__FILE__));
    require("head.php");
    
?>

<!-- code javascript permettant d"afficher ou désactiver une partie du code -->
<style>
    .hidden 
    {
        display: none;
    }
</style>

<!-- code javascript permettant de valider les champs du formulaire -->
<script>
    //fonction permettant de valider les champs du formulaire d"inscription
    function validateFormInscription()
    {
        var login = document.forms["inscriptForm"]["login2"].value; //récupérer la valeur du login
        var password = document.forms["inscriptForm"]["password2"].value; //récupérer la valeur du password
        var confirm_password = document.forms["inscriptForm"]["confirm_password"].value; //récupérer la valeur du confirm_password
        if (login == "") 
        {
            document.getElementById("errorLogin2").innerHTML = "Le Login est requit //afficher un message d"erreur si le login est vide
            return false;
        }
        if (password == "") 
        {
            document.getElementById("errorPassword2").innerHTML = "Le Password est requit //afficher un message d"erreur si le password est vide
            return false;
        }
        if (password.lenght < 4) 
        {
            document.getElementById("errorPassword2").innerHTML = "Password doit contenir au moins 4 caractères //afficher un message d"erreur si le password contient moins de 4 caractères
            return false;
        }
        if (confirm_password == "") 
        {
            document.getElementById("errorConfirmPassword").innerHTML = "Confirmer le Password //afficher un message d"erreur si le confirm_password est vide
            return false;
        }
        if (confirm_password != password) 
        {
            document.getElementById("errorConfirmPassword").innerHTML = "Les deux Password doivent être identiques //afficher un message d"erreur si le password et le confirm_password ne sont pas identiques
            return false;
        }
    }


</script>

    <!--formulaire d"inscription qui demande un login un password et un bouton pour s"inscrire-->
    <div class="hidden col-lg-8 col-md-8 offset-lg-2 offset-md-2 shadow-none p-3 mb-5 bg-light rounded p-5">

        <form name="inscriptForm"  action="../controller/authentification.php" method="post" onsubmit="return validateFormInscription()">

            <!--entrer un login-->
            <div class="row mb-3">
                <label for="Login" class="col-form-label">Login</label>
                <div col-md-9>
                    <input type="text" class="form-control" name="login2" required>
                    <span id="errorLogin2" class="error"></span><br>

                </div>
            </div>

            <!--entrer un password-->
            <div class="row mb-3">
                <label for="password" class="col-form-label">Password</label>
                <div col-md-9>
                    <input type="password" class="form-control" name="password2" required>
                    <span id="errorPassword2" class="error"></span><br>

                </div>
            </div>

            <!--confirmer le password -->
            <div class="row mb-3">
                <label for="confirm-password" class="col-form-label">Confirm Password</label>
                <div col-md-9>
                    <input type="password" class="form-control" name="confirm_password" required>
                    <span id="errorConfirmPassword" class="error"></span><br>

                </div>
            </div>

            <!--bouton pour s"inscrire-->
            <div class="mb-3 py-2">
                <button type="submit" class="btn btn-primary  col-md-2 offset-md-5 ">Submit</button>
            </div>
            
        </form>
    </div>




<?php   
    //commentaire 1) meme chose pour le fichier footer.php 
    require("footer.php");

?>