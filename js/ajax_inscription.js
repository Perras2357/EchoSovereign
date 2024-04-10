//fonction permettant de valider les champs du formulaire d'inscription
function validateFormInscription() 
{
    var password_encrypte ;
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
         // Charger la clé publique depuis un fichier
         $.ajax({
            url: '../Encrypte/public.pem',
            dataType: 'text',
            success: function (publicKey) {
                // Initialisez JSEncrypt avec la clé publique
                var encryptor = new JSEncrypt();
                encryptor.setPublicKey(publicKey);

                // Maintenant, vous pouvez utiliser l'encrypteur pour chiffrer les données côté client
                password_encrypte = encryptor.encrypt(password);

                // Envoi du formulaire après le chiffrement du mot de passe
                envoyerFormulaire(login, password_encrypte);
            },
            error: function (xhr, status, error) {
                console.error('Erreur lors du chargement de la clé publique :', error);
                errors = true;
            }
        });
    }
    return false; // Empêcher le formulaire de se soumettre normalement
}
// Fonction pour envoyer le formulaire avec le mot de passe chiffré
function envoyerFormulaire(login, password_encrypte) {
    $.ajax({
        method: "POST",
        dataType: 'json',
        url: 'traitement_inscription.php',
        data: {
            inscr_login: login,
            inscr_password: password_encrypte
        }
    }).done(function (response) {
        if (response.success) {
            console.log(response.message);
            //redirection vers la page de connexion
            window.location.href = "../";

        } 
        else 
        {
            if (response.message === 'Ce login existe déjà veillez vous connecter.') 
            {
                document.getElementById('errorLogin').innerHTML = 'Ce login est déjà utilisé.';
            }
            else 
            {
                document.getElementById('errorLogin').innerHTML = response.message;
            }
            document.getElementById('inscription').innerHTML = '<a href="../">Connectez-vous</a>';
            console.log(response.message);
        }
    }).fail(function (error) {
        console.log(error);
        alert("Une erreur s'est produite lors de la requête AJAX.");
    });
}
