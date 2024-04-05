//variable qui determine si je peux voter
var activeVote = "oui"; 
question;
reponses = [];
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
        //on parcourt le tvariableau de scrutins et on les affiche dans le tvariableau
        response.scrutins.forEach(scrutin => {
            $('#liste_scrutin table tbody').append('<tr><td>'+scrutin.titre+
                '</td><td>'+scrutin.organisation+'</td><td>'+scrutin.description+
                '</td><td>'+scrutin.debut+'</td><td>'+scrutin.fin+'</td><td>'+scrutin.acces_vote+
                '</td><td>'+scrutin.je_peux_voter+'</td><td>'+scrutin.nbr_votants+'</td><td>'+scrutin.nbr_votes+'</td></tr>');
                activeVote = scrutin.je_peux_voter;
                question = scrutin.voteSimple;
                reponses = scrutin.options;
                console.log(scrutin.options);
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

//je coonstruis le formulaire de vote en fonction des questions et des réponses dans le formulaire de vote qui a l'id form_vote
// function construireFormulaireVote()
// {
//     //on affiche la question dans le formulaire de vote dans le label qui a l'id question
//     document.getElementById('question').innerHTML = question;

//     //on parcourt les réponses et on les affiche dans le formulaire de vote dans le select qui a l'id choix
//     reponses.forEach(reponse => {
//         $('#choix').append('<option value="'+reponse+'">'+reponse+'</option>');
//     });

// }
