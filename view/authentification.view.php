<?php
    //1)inclure le fichier head.php require pour éviter de l'inclure plusieurs fois
    //et signaler une erreur s'il y en a
    chdir(dirname(__FILE__));
    require("head.php");
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


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
</div>
   
    <!-- code javascript permettant de valider les champs du formulaire -->
<script>
    //fonction permettant de valider les champs du formulaire de connexion
    function validateFormConnexion() {
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
                type: 'POST',
                url: '../controller/authentification.php',
                data: {
                    login: login,
                    password: password,
                    submit : 'submit'
                },
                dataType: 'json', // Spécifiez le type de données attendu dans la réponse
                success: function(response) 
                {
                    // Traitement de la réponse du serveur

                    // Si la réponse contient des erreurs
                    if (response.errors) 
                    {
                        //on efface les messages d'erreur précédents
                        document.getElementById('error').innerHTML = '';
                        document.getElementById('errorLogin').innerHTML = '';
                        document.getElementById('errorPassword').innerHTML = '';

                        // Affichez les erreurs ou effectuez d'autres actions nécessaires

                        if (response.errors.username) 
                        {
                            // Gérer l'erreur du nom d'utilisateur
                            alert(response.errors.username);
                            document.getElementById('errorLogin').innerHTML = response.errors.username;
                        }

                        if (response.errors.password) 
                        {
                            // Gérer l'erreur du mot de passe
                            document.getElementById('errorPassword').innerHTML = response.errors.password;
                        }
                    } 
                    if (response.success) 
                    {
                        // Si l'inscription a réussi, vous pouvez effectuer d'autres actions
                        alert("Inscription réussie !");
                    }
                },
                error: function(error) 
                {
                    // Gérer les erreurs de la requête Ajax
                    console.error("Erreur Ajax :", error);
                }
            });
        }
        return false; // Empêcher le formulaire de se soumettre normalement
    }
</script>

<?php
    require("footer.php");
?>