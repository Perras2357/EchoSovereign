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
    else if (password !== confirme_password) 
    {
        document.getElementById('errorConfirme_password').innerHTML = 'Les mots de passe ne correspondent pas.';
        errors = true; // Affectez true à la variable d'erreur
    }

    // appel inscr pour envoyer les données du formulaire d'inscription au serveur
    if (!errors) 
    {
        $.ajax(
        {
            method: "POST",
            dataType: 'json',
            url: 'http://localhost/EchoSovereignV2/controller/traitement_inscription.php',
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