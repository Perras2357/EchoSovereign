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
        //on masque insc qui est un lien pour s'inscrire
        document.getElementById('insc').style.display = 'none';

        $.ajax(
        {
            method: "POST",
            dataType: 'json',
            url: 'controller/traitement_connexion.php',
            data: {
                ajax_login: login,
                ajax_password: password,
                ajax_submit: 'submit'
            }
        }).done(function(response) 
        {
            if (response.success) 
            {
                if(response.message == 'Bienvenue organisateur.')
                {
                    // Rediriger l'utilisateur vers la page d'organisation des scrutins
                    window.location.href = 'controller/organisation_scrutin.php';
                }
                else if(response.message == 'Bienvenue votant.')
                {
                    // Rediriger l'utilisateur vers la page de vote
                    window.location.href = 'controller/vote.php';
                }             
            }
            else if (response.message == 'Ce login n\'existe pas.') 
            {
                document.getElementById('inscription').innerHTML = '<a href="controller/inscription.php">Pas de compte ? Inscrivez-vous !</a>';
            }
            else 
            {
                document.getElementById('errorLogin').innerHTML = response.message;                           
            }

            console.log(response.message);

        }).fail(function(error) 
        {
            console.log(error);
            alert("Une erreur s'est produite lors de la requête AJAX.");
        });

    }
    return false;
}