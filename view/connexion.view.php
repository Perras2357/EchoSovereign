<?php
    //1)inclure le fichier head.php require pour éviter de l'inclure plusieurs fois
    //et signaler une erreur s'il y en a
    require("head.php");
?>

<!-- formulaire de connexion qui demande un login un password et un bouton pour se connecter -->
<div class="col-lg-8 col-md-8 offset-lg-2 offset-md-2 shadow-none p-3 mb-5 bg-light rounded p-5" >
    <form name="connexionForm" method="post" onsubmit="return validateFormConnexion()">
        
        <!-- afficher un message d'erreur s'il en a -->
        <span id="error"></span><br>
        <!--entrer un login-->
        <div class="row mb-3">
            <label for="login" class="col-form-label">Login</label>
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
</div>
   
    <!-- code javascript permettant de valider les champs du formulaire -->
<script>
    //fonction permettant de valider les champs du formulaire de connexion
    function validateFormConnexion() 
    {
        // Réinitialiser les messages d'erreur
        document.getElementById('errorLogin').innerHTML = '';
        document.getElementById('errorPassword').innerHTML = '';

        // Récupérer les valeurs des champs de connexion
        var login = document.forms['connexionForm']['login'].value;
        var password = document.forms['connexionForm']['password'].value;
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

        //appel ajax qui transmet les données du formulaire de connexion au serveur
        
        if(!errors) // Si aucune erreur n'est détectée
        {
            $.ajax(
            {
                method: "POST",
                dataType: 'json',
                url: 'http://localhost/EchoSovereign/controller/connexion.php',
                data: {
                    ajax_login: login,
                    ajax_password: password,
                    ajax_submit: 'submit'
                }
            }).done(function(response) 
            {
                if (response.success) 
                {
                    // Rediriger l'utilisateur vers la page d'organisation des scrutins
                    window.location.href = 'view/organisation_scrutin.view.php';
                } 
                else 
                {
                    if (response.message == 'Ce login n\'existe pas.') 
                    {
                        document.getElementById('inscription').innerHTML = '<a href="view/inscription.view.php">Pas de compte ? Inscrivez-vous !</a>';
                    }
                    else
                    {
                        document.getElementById('inscription').innerHTML = response.message;
                    }
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
