//variable qui determine si je peux voter
var activeVote = "oui"; 
etat_vote = "fermer";
question;
reponses = [];
var buttonClicked = false;

function afficheScrutin()
{
    //appel ajax pour récuperer les informations des scrutins et les afficher dans le tvariableau de liste des scrutins 
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "http://localhost/EchoSovereign/controller/traitement_vote.php",
        data: {organisateur: 1}
    }).done(function(response) 
    {
        if(response.success)
        {
            $('#liste_scrutin table tbody').empty();
            //on parcourt le tvariableau de scrutins et on les affiche dans le tvariableau
            response.scrutins.forEach(scrutin => {
                $('#liste_scrutin table tbody').append('<tr><td>'+scrutin.titre+
                    '</td><td>'+scrutin.organisation+'</td><td>'+scrutin.description+
                    '</td><td>'+scrutin.debut+'</td><td>'+scrutin.fin+'</td><td>'+scrutin.acces_vote+
                    '</td><td>'+scrutin.procuration+'</td><td>'+scrutin.je_peux_voter+'</td><td>'+scrutin.nbr_votants+'</td><td>'+scrutin.nbr_votes+'</td></tr>');
                    activeVote = scrutin.je_peux_voter;
                    etat_vote = scrutin.acces_vote;
                    question = scrutin.voteSimple;
                    reponses = scrutin.options;
            });
            //je récupère l'élément question dans le formulaire de vote
            var questionElement = document.getElementById('question');
            //je change le texte de l'élément question
            questionElement.textContent = question;
            //je récupère l'élément choix dans le formulaire de vote
            var choixElement = document.getElementById('choix');
            //je vide le contenu de l'élément choix
            choixElement.innerHTML = '';
            //je parcours le tableau des réponses
            reponses.forEach(reponse => {
                //je crée un élément option
                var option = document.createElement('option');
                //je change le texte de l'élément option
                option.textContent = reponse;
                //je change la valeur de l'élément option
                option.value = reponse;
                //j'ajoute l'élément option à l'élément choix
                choixElement.appendChild(option);
            });
            //si je peux voter j'active le formulaire de vote et je désactive le message de non vote
            
        }
        else
        {
            //on cache le tableau de liste des scrutins si il n'y a pas de scrutin à afficher 
            document.getElementById('liste_scrutin').style.display = 'none';
            console.log(response.message);
        }
    }).fail(function(error) 
    {
        alert( "error" );
        console.log(error);
    });
}
//-----------------Fonction qui permet d'afficher les resultats------------------


afficheScrutin();


function envoieVote(event)
{
    //j'empêche la page de se recharger
    event.preventDefault();

    //je vérifie si le vote est actif
    if(etat_vote != "fermer")
    {
        //je récupère le choix de l'utilisateur
        var choix = document.getElementById('choix').value;
        
        //je vérifie si l'utilisateur a bien choisi une réponse
        if(choix == '')
        {
            //j'affiche un message d'erreur dans le span qui a l'id erreurVote
            document.getElementById('erreurVote').textContent = 'Veuillez choisir une réponse';
        }
        else
        {
            //je fais un appel ajax pour envoyer le vote à la base de données
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "http://localhost/EchoSovereign/controller/traitement_voix.php",
                data: {voix:1, vote: choix}
            }).done(function(response)
            {
                if(response.success)
                {
                   console.log(response.message);
                   alert(response.message);
                   afficheScrutin();
                }
                else
                {
                    if(response.message == 'Le scrutin est fermé. Vous ne pouvez plus voter.' || response.message == 'Vous ne pouvez plus voter.')
                    {
                        //je désactive le formulaire le button submit
                        document.getElementById('submit').disabled = true;
                    }
                    console.log(response.message);
                    document.getElementById('erreurVote').textContent = response.message;
                }
            }).fail(function(error)
            {
                alert( "error" );
                console.log(error);
            }
            );
        }
    }
    else
    {
        //je désactive le formulaire le button submit
        document.getElementById('submit').disabled = true;

    }

}

