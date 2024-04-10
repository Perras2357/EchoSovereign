//variable qui determine si je peux voter
var activeVote = "oui"; 
etat_vote = "fermer";
question;
reponses = [];
nbr_votants = 0;
nbr_votes = 0;
var buttonClicked = false;


//-----------------------fonction qui permet d'afficher les resultats------------------
function afficheResultat()
{
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "http://localhost/EchoSovereign/controller/traitement_resultat.php",
        data: {resultat:1}
    }).done(function(response)
    {
        if(response.success)
        {
            //afficher les resultats du tableau de resultats dans la div liste_resultat
            document.getElementById('liste_resultat').style.display = 'block';
            
            Object.entries(response.resultats).forEach(([key, value]) => {
                $('#liste_resultat #table_resultat #tbody_resultat').append('</tr><tr><td>'+key+'</td><td>'+value+'</td><td class="text-info">'+((value/nbr_votes)*100).toFixed(2)+'%</td></tr>');
            });
            
                //on affiche le taux de participation
            var taux = ((nbr_votes / nbr_votants) * 100).toFixed(2);
            document.getElementById('participation').textContent = taux+'%';
            console.log(response.resultats);

        }
        else
        {
            //on cache le tableau de liste des scrutins si il n'y a pas de scrutin à afficher 
            document.getElementById('liste_resultat').style.display = 'block';
            console.log(response.message);
            alert(response.message);
        }
    }).fail(function(error)
    {
        alert( "error" );
        console.log(error);
    });
}

//------------------------fonction qui permet d'afficher les resultats des scrutins------------------
function afficheScrutin()
{
    //on masque la div qui a pour id liste_resultat
    document.getElementById('liste_resultat').style.display = 'none';

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
            $('#liste_scrutin #table_scrutin #tbody_scrutin').empty();
            //on parcourt le tvariableau de scrutins et on les affiche dans le tvariableau
            response.scrutins.forEach(scrutin => {
                $('#liste_scrutin #table_scrutin #tbody_scrutin').append('<tr><td>'+scrutin.titre+
                    '</td><td>'+scrutin.organisation+'</td><td>'+scrutin.description+
                    '</td><td>'+scrutin.debut+'</td><td>'+scrutin.fin+'</td><td>'+scrutin.acces_vote+
                    '</td><td>'+scrutin.procuration+'</td><td>'+scrutin.je_peux_voter+'</td><td>'+scrutin.nbr_votants+'</td><td>'+scrutin.nbr_votes+'</td></tr>');
                    activeVote = scrutin.je_peux_voter;
                    etat_vote = scrutin.acces_vote;
                    question = scrutin.voteSimple;
                    reponses = scrutin.options;
                    nbr_votants = scrutin.nbr_votants;
                    nbr_votes = scrutin.nbr_votes;
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
            
            if(etat_vote == "fermer")
            {
                //je désactive le formulaire le button submit
                document.getElementById('submit').disabled = true;
                
                //j'appelle la fonction afficheResultat
                afficheResultat();
            }
            
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

//-----------------Appel de la fonction afficheScrutin-----------------------------------
afficheScrutin();

//--------------------- Function qui permet de voter -----------------------------------
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
                }
                else
                {
                    document.getElementById('submit').disabled = true;
                    console.log(response.message);
                    document.getElementById('erreurVote').textContent = response.message;
                }

                //j'appelle la fonction afficheScrutin
                afficheScrutin();

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
        //j'affiche un message d'erreur dans le span qui a l'id erreurVote
        document.getElementById('erreurVote').textContent = 'Le vote est fermé';

        //j'appelle la fonction afficheScrutin
        afficheScrutin();


    }

}

