<?php
    //1)inclure le fichier head.php require pour éviter de l'inclure plusieurs fois
    //et signaler une erreur s'il y en a
    require("head.php");
?>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>    


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
        


        <!-- code javascript permettant de valider les champs du formulaire -->
<script>
    //fonction permettant de valider les champs du formulaire d'inscription
    function validateFormInscription() 
    {
        // Réinitialiser les messages d'erreur
        document.getElementById('errorLogin').innerHTML = '';
        document.getElementById('errorPassword').innerHTML = '';
        document.getElementById('errorConfirme_password').innerHTML = '';

        // Récupérer les valeurs des champs de inscription
        var login = document.forms['inscriptionForm']['login'].value;
        var password = document.forms['inscriptionForm']['password'].value;
        var confirme_password = document.forms['inscriptionForm']['confirme_password'].value;
        var errors = false; // Initialisez la variable d'erreur à false

        // Validation du login
        if (login === "") 
        {
            document.getElementById('errorLogin').innerHTML = 'Veuillez entrer un login.';
            errors = true; // Affectez true à la variable d'erreur
        }
        // Validation du mot de passe
        if (password === "") 
        {
            document.getElementById('errorPassword').innerHTML = 'Veuillez entrer un mot de passe.';
            errors = true; // Affectez true à la variable d'erreur
        }
        // si le password contient moins de 4 caractères
        if (password.length < 4) 
        {
            document.getElementById('errorPassword').innerHTML = 'Le mot de passe doit contenir au moins 4 caractères.';
            errors = true; // Affectez true à la variable d'erreur
        }
        else
        {
            //si le password et le confirme_password ne sont pas identiques
            if (password !== confirme_password) 
            {
                document.getElementById('errorConfirme_password').innerHTML = 'Les mots de passe ne correspondent pas.';
                errors = true; // Affectez true à la variable d'erreur
            }
        }

        // appel inscr pour envoyer les données du formulaire d'inscription au serveur
        if (!errors) 
        {
            $.ajax(
            {
                method: "POST",
                dataType: 'json',
                url: '../controller/inscription.php',
                data: {
                    inscr_login: login,
                    inscr_password: password
                }
            }).done(function(response) 
            {
                if (response.success) 
                {
                    console.log(response.message);
                    //redirection vers la page de connexion
                    window.location.href = "../";
                    
                } 
                else 
                {
                    //alert(response.message);
                    //afficher un lien de connexion si l'utilisateur n'a pas de compte dans la div inscription
                    document.getElementById('inscription').innerHTML = '<a href="../">Connectez-vous</a>';
                }
            }).fail(function(error) 
            {
                console.log(error);
                alert("Une erreur s'est produite lors de la requête AJAX.");
            });
        }
        return false; // Empêcher le formulaire de se soumettre normalement
    }
</script>







<?php
    require("footer.php");
?>
