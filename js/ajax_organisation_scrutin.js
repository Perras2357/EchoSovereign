let votants = {};
let rowId = 0;
let reponses = [];
let resultats = [];


//----------------------------------appel ajax pour récuperer les informations des scrutins et les afficher dans le tvariableau de liste des scrutins----------------------------------
    function listeScrutin()
    {
        document.getElementById('liste_scrutin').style.display = 'block'
        document.getElementById('formOrganisation').style.display = 'none';
        document.getElementById('FormVotant').style.display = 'none';
        document.getElementById('btn_create_vote').style.display ="block";
        document.getElementById('liste_resultat').style.display = 'none';
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "liste_scrutin.php",
            data: {organisateur: 1}
        }).done(function(response) 
        {
            if(response.success)
            {
                //on parcourt le tvariableau de scrutins et on les affiche dans le tvariableau
                response.scrutins.forEach(scrutin => {
                    $('#liste_scrutin table tbody').append('<tr><td>'+scrutin.titre+
                        '</td><td>'+scrutin.organisation+'</td><td>'+scrutin.description+
                        '</td><td>'+scrutin.debut+'</td><td>'+scrutin.fin+'</td><td>'+scrutin.etat+
                        '</td><td>'+scrutin.nbr_votants+'</td><td>'+scrutin.nbr_votes+'</td></tr>');
                        if(scrutin.etat == 'fermer')
                        {
                            // Ajoute un nouvel objet dans le tableau resultats pour chaque scrutin fermé
                            resultats.push({
                                'titre': scrutin.titre,
                                'nbr_votes': scrutin.nbr_votes,
                                'nbr_votants': scrutin.nbr_votants
                            });
                        }

                });
                afficheResultat();
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
    //appel de la fonction listeScrutin
    listeScrutin();

//----------- fonction qui affiche le resultat d'un scrutin -------------------
function afficheResultat()
{
    //on parcourt le tableau de resultat avec un foreach et on fait l'appel ajax pour récuperer les resultats
    resultats.forEach(resultat => 
    {
        document.getElementById('liste_resultat').style.display = 'block';
        console.log(resultat.titre);
        //on calcul le taux de participation
            taux = ((resultat.nbr_votes / resultat.nbr_votants) * 100).toFixed(2);
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "traitement_resultat_organisateur.php",
            data: {resultat:1, titre:resultat.titre}
        }).done(function(response)
        {
            if(response.success)
            {
                //afficher les resultats du tableau de resultats dans la div liste_resultat
                document.getElementById('liste_resultat').style.display = 'block';

                //nbr_options = Object.keys(response.resultats).length;
                
                Object.entries(response.resultats).forEach(([key, value]) => {
                    $('#liste_resultat #table_resultat #tbody_resultat').append('</tr><tr><td>'+resultat.titre+'</td><td>'+key+'</td><td>'+value+'</td><td class="text-info">'+((value/resultat.nbr_votants)*100).toFixed(2)+'%</td><td>'+taux+'%</td></tr>');
                });
                console.log(response.resultats);

            }
            else
            {
                //on cache le tableau de liste des scrutins si il n'y a pas de scrutin à afficher 
                document.getElementById('liste_resultat').style.display = 'block';
                console.log(response.message);
            }
        }).fail(function(error)
        {
            alert( "error" );
            console.log(error);
        });
    });
}
//----------- on click sur le bouton ajouter scrutin on affiche le formulaire de création de scrutin-------------------
    document.getElementById("collapseForm").addEventListener("click", function(){
        document.getElementById("formOrganisation").style.display = "block";
        document.getElementById("FormVotant").style.display = "none";
    });

//----------------------------- fonction d'ajout de votants ----------------------------------------------------------------
            // Récupérer les références des éléments du formulaire
            const checkbox = document.getElementById('donnerProcuration');
            const inputField = document.getElementById('nombreProcuration');

            // Ajouter un écouteur d'événements sur le changement d'état du checkbox
            checkbox.addEventListener('change', function() {
                // Vérifier si le checkbox est coché
                if (checkbox.checked) 
                {
                    // Désactiver le champ de saisie
                    inputField.disabled = true;
                } 
                else 
                {
                    // Activer le champ de saisie
                    inputField.disabled = false;
                }
            });
            function addRow(event) 
            {
                event.preventDefault();
                rowId++;
                
                // Récupérer les valeurs des champs
                let email = document.getElementById('email').value;
                let nombreProcuration = document.getElementById('nombreProcuration').value;
                let donnerProcuration = document.getElementById('donnerProcuration').checked;
                
                // Vérifier si les champs ne sont pas vides
                if (email === '' || (nombreProcuration === '' && !donnerProcuration)) 
                {
                    document.getElementById('errorAjout').innerHTML = 'Veuillez remplir tous les champs';
                    return;
                }
                if (donnerProcuration) 
                {
                    nombreProcuration = -1;
                }
                
                // Réinitialiser le message d'erreur
                document.getElementById('errorAjout').innerHTML = '';

                //on vérifie si le mail existe dans les lignes précédentes
                for (let key in votants) 
                {
                    if (votants[key].email === email) 
                    {
                        document.getElementById('errorAjout').innerHTML = 'Le mail existe déjà';
                        return;
                    }
                }
                
                // Créer une nouvelle ligne dans le tableau
                let newRow = '<tr><td>' + email + '</td><td>' + nombreProcuration +'</td><td>'+ donnerProcuration +'</td></tr>';
                
                // Ajouter la nouvelle ligne au tableau
                document.getElementById('tableBody').innerHTML += newRow;
                console.log(nombreProcuration);

                // Ajouter les valeurs dans le tableau votants avec pour clé rowId
                votants[rowId] = {email: email, nombreProcuration: nombreProcuration, donnerProcuration: donnerProcuration};
                
                // Réinitialiser les champs
                document.getElementById('email').value = '';
                document.getElementById('nombreProcuration').value = '';
                console.log(votants);

            }
//------------------------------fonction permettant de valider les champs du formulaire de votant-----------------------------------
        function validateFormVotant() 
        {
            //on vérifie si le tableau votants n'est pas vide
            if(Object.keys(votants).length > 0)
            {
                //en voyer les informations du formulaire à la page de traitement via ajax
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "gestion_votant.php",
                    data: {votants:votants}
                }).done(function(response) 
                {
                    if(response.success)
                    {
                        listeScrutin();
                        //on vide le contenue du formulaire de scrutin et de votants
                        document.forms['scrutinForm']['titre'].value = '';
                        document.forms['scrutinForm']['organisation'].value = '';
                        document.forms['scrutinForm']['description'].value = '';
                        document.forms['scrutinForm']['fin'].value = '';
                        document.forms['scrutinForm']['debut'].value = '';
                        document.forms['scrutinForm']['voteSimple'].value = '';
                        console.log(response);
                    }
                    else
                    {
                        // Réinitialiser les messages d'erreur
                        document.getElementById('errorVotant').innerHTML = '';
                        console.log(response);

                        //en fonction de la valeur de response.message afficher le message d'erreur correspondant
                        if(response.message == 'Veuillez entrer des votants.')
                        {
                            document.getElementById('errorVotant').innerHTML = response.message;
                        }

                    }
                }).fail(function(error) 
                {
                    alert( "error" );
                    console.log(error);
                });
            }
            else
            {
                document.getElementById('errorVotant').innerHTML = 'Veuillez entrer des votants.';
            }
            return false;
        }
//------------------------function addReponseToList --------------------------------------------------
        function addReponseToList(event) 
        {
            event.preventDefault(); // Empêche le rechargement de la page
            let reponse = document.getElementById('reponse').value;
            if (reponse === '') 
            {
                document.getElementById('errorChoixSimple').innerHTML = 'Veuillez entrer une réponse';
                return;
            }

            //on vérifie que la réponse n'existe pas dans le tableau
            if (reponses.includes(reponse)) 
            {
                document.getElementById('errorChoixSimple').innerHTML = 'Ajouter un mail différent ';
                return;
            } 


            reponses.push(reponse);
            document.getElementById('listReponse').innerHTML = '';
            reponses.forEach(reponse => {
                document.getElementById('listReponse').innerHTML += '<li>' + reponse + '</li>';
            });
            document.getElementById('reponse').value = '';
        }
//---------------fonction permettant de valider les champs du formulaire de scrutin----------------------------------
        function validateFormScrutin() 
        {   
            // Réinitialiser les messages d'erreur
            document.getElementById('errorTitre').innerHTML = '';
            document.getElementById('errorOrganisation').innerHTML = '';
            document.getElementById('errorDescription').innerHTML = '';
            document.getElementById('errorFin').innerHTML = '';
            document.getElementById('errorDebut').innerHTML = '';
            document.getElementById('errorVoteSimple').innerHTML = '';

            
            
            // Récupérer les valeurs de tous les champs du scrutin 
            titre = document.forms['scrutinForm']['titre'].value;
            organisation = document.forms['scrutinForm']['organisation'].value;
            description = document.forms['scrutinForm']['description'].value;
            fin = document.forms['scrutinForm']['fin'].value;
            debut = document.forms['scrutinForm']['debut'].value;
            voteSimple = document.forms['scrutinForm']['voteSimple'].value;

            
            dateDebut = new Date(debut);// Convertir la date de début en objet Date           
            dateFin = new Date(fin);// Convertir la date de fin en objet Date

            // Récupérer la date et l'heure actuelles
            dateActuelle = new Date(); 
            dateActuelle.setMinutes(dateActuelle.getMinutes); // Ajouter 5 minutes à la date actuelle

            // Convertir les dates en millisecondes
            let debutMs = dateDebut.getTime();
            let finMs = dateFin.getTime();
            let actuelleMs = dateActuelle.getTime();

            // Calculer la différence en millisecondes
            let diffDebutFinMs = finMs - debutMs;
            let diffDebutActuelleMs = debutMs - actuelleMs;

            // Convertir la différence en minutes
            let diffDebutFinMin = diffDebutFinMs / (1000 * 60);
            let diffDebutActuelleMin = diffDebutActuelleMs / (1000 * 60);
            errors = false; // Initialisez la variable d'erreur à false

            // Validation du titre
            if (titre.length < 4) 
            {
                document.getElementById('errorTitre').innerHTML = 'Veuillez entrer un titre valide.';
                errors = true; // Affectez true à la variable d'erreur
            }
            // Validation de la date de fin
            if (fin === "") 
            {
                document.getElementById('errorFin').innerHTML = 'Veuillez entrer une date de fin.';
                errors = true; // Affectez true à la variable d'erreur
            }
            // Validation du vote simple
            if (voteSimple === "") 
            {
                document.getElementById('errorVoteSimple').innerHTML = 'Veuillez entrer un vote simple.';
                errors = true; // Affectez true à la variable d'erreur
            }
            //si la date de fin est inférieure à la date de début
            if (diffDebutFinMin<=0) 
            {
                document.getElementById('errorFin').innerHTML = 'La date de fin doit être supérieure à la date de début.';
                errors = true; // Affectez true à la variable d'erreur
            }
            //la date de début doit au moins dépasser la date actuelle de 5 minutes
            if (diffDebutActuelleMin <= 3) 
            {
                document.getElementById('errorDebut').innerHTML = 'La date de début doit être supérieure à la date actuelle de 3 minutes.';
                //$("#errorDebut").html();
                errors = true; // Affectez true à la variable d'erreur
            }
            
            if (!errors) 
            {
                //en voyer les informations du formulaire à la page de traitement via ajax
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "traitement_organisation_scrutin.php",
                    data: {titre:titre, organisation:organisation, 
                        description:description, fin:dateFin, debut:dateDebut, 
                        voteSimple:voteSimple, reponses:reponses}
                }).done(function(response) 
                {
                    if(response.success)
                    {
                        // on masque liste_scrutin
                        document.getElementById('liste_scrutin').style.display = 'none';
                        // on masque le formulaire de scrutin
                        document.getElementById('formOrganisation').style.display = 'none';
                        // on affiche le formulaire de votants
                        document.getElementById('FormVotant').style.display = 'block';
                        document.getElementById('btn_create_vote').style.display ="none";
                        document.getElementById('liste_resultat').style.display = 'none';

                    }
                    else
                    {
                        // Réinitialiser les messages d'erreur
                        document.getElementById('errorTitre').innerHTML = '';
                        document.getElementById('errorOrganisation').innerHTML = '';
                        document.getElementById('errorDescription').innerHTML = '';
                        document.getElementById('errorFin').innerHTML = '';
                        document.getElementById('errorDebut').innerHTML = '';
                        document.getElementById('errorVoteSimple').innerHTML = '';

                        //en fonction de la valeur de response.message afficher le message d'erreur correspondant
                        if(response.message == 'La date de fin doit etre supérieure a la date de debut.')
                        {
                            document.getElementById('errorFin').innerHTML = response.message;
                        }
                        else if(response.message == 'La date de début doit être supérieure à la date actuelle.')
                        {
                            document.getElementById('errorDebut').innerHTML = response.message;
                        }
                        else if(response.message == 'Veuillez entrer un titre valide.')
                        {
                            document.getElementById('errorTitre').innerHTML = response.message;
                        }
                        else if(response.message == 'Veuillez entrer un vote simple.')
                        {
                            document.getElementById('errorVoteSimple').innerHTML = response.message;
                        }
                        else if(response.message == 'Ce scrutin existe déjà.')
                        {
                            
                            document.getElementById('errorTitre').innerHTML = response.message;

                        }

                    }
                }).fail(function(error) 
                {
                    alert( "error" );
                    console.log(error);
                });

            }
            return false;
        }