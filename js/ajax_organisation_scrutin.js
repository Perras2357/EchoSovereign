        
        //masquer FormVotant initialement
        document.getElementById('FormVotant').style.display = 'none';
        //document.getElementById('FormVotant').style.display = 'block';

        //appel ajax pour récuperer les informations des scrutins et les afficher dans le tableau de liste des scrutins 
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "http://localhost/EchoSovereign/controller/liste_scrutin.php",
            data: {organisateur: 1}
        }).done(function(response) 
        {
            if(response.success)
            {
                console.log(response);
                //on parcourt le tableau de scrutins et on les affiche dans le tableau
                response.scrutins.forEach(scrutin => {
                    $('#liste_scrutin table tbody').append('<tr><td>'+scrutin.titre+
                        '</td><td>'+scrutin.organisation+'</td><td>'+scrutin.description+
                        '</td><td>'+scrutin.debut+'</td><td>'+scrutin.fin+'</td><td>'+scrutin.etat+
                        '</td><td>'+scrutin.nbr_votants+'</td><td>'+scrutin.nbr_votes+'</td></tr>');
                });
            }
            else
            {
                console.log(response);
                //on cache le tableau de liste des scrutins si il n'y a pas de scrutin à afficher 
                document.getElementById('liste_scrutin').style.display = 'none';

            }
        }).fail(function(error) 
        {
            alert( "error" );
            console.log(error);
        });

        //-----------------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------------

        //fonction permettant de valider les champs du formulaire de votant
        function validateFormVotant() 
        {
            // Réinitialiser les messages d'erreur
            document.getElementById('errorVotant').innerHTML = '';

            // Récupérer les valeurs de tous les champs du votant 
            var votant = document.forms['votantForm']['votant'].value;
            var errors = false; // Initialisez la variable d'erreur à false

            // Validation du votant
            if (votant <1) 
            {
                document.getElementById('errorVotant').innerHTML = 'Veuillez entrer des votants.';
                errors = true; // Affectez true à la variable d'erreur
            }
            if (!errors) 
            {
                //en voyer les informations du formulaire à la page de traitement via ajax
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "http://localhost/EchoSovereign/controller/gestion_votant.php",
                    data: {votant:votant}
                }).done(function(response) 
                {
                    if(response.success)
                    {
                        alert('votant créé avec succès');
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
            return false;
        }

    //-----------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------



        //fonction permettant de valider les champs du formulaire de scrutin
        function validateFormScrutin() 
        {

            //maintenir le collapse ouvert
            $('#collapseVoteSimple').collapse('show');
            $('#collapseForm').collapse('hide');

            
            // Réinitialiser les messages d'erreur
            document.getElementById('errorTitre').innerHTML = '';
            document.getElementById('errorOrganisation').innerHTML = '';
            document.getElementById('errorDescription').innerHTML = '';
            document.getElementById('errorFin').innerHTML = '';
            document.getElementById('errorDebut').innerHTML = '';
            document.getElementById('errorVoteSimple').innerHTML = '';

            
            
            // Récupérer les valeurs de tous les champs du scrutin 
            var titre = document.forms['scrutinForm']['titre'].value;
            var organisation = document.forms['scrutinForm']['organisation'].value;
            var description = document.forms['scrutinForm']['description'].value;
            var fin = document.forms['scrutinForm']['fin'].value;
            var debut = document.forms['scrutinForm']['debut'].value;
            var voteSimple = document.forms['scrutinForm']['voteSimple'].value;

            
            var dateDebut = new Date(debut);// Convertir la date de début en objet Date           
            var dateFin = new Date(fin);// Convertir la date de fin en objet Date

            // Récupérer la date et l'heure actuelles
            var dateActuelle = new Date(); 
            dateActuelle.setMinutes(dateActuelle.getMinutes() + 5); // Ajouter 5 minutes à la date actuelle

            var errors = false; // Initialisez la variable d'erreur à false

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
            if (dateFin <= dateDebut) 
            {
                document.getElementById('errorFin').innerHTML = 'La date de fin doit être supérieure à la date de début.';
                errors = true; // Affectez true à la variable d'erreur
            }
            //la date de début doit au moins dépasser la date actuelle de 5 minutes
            if (dateDebut <= dateActuelle) 
            {
                document.getElementById('errorDebut').innerHTML = 'La date de début doit être supérieure à la date actuelle.';
                //$("#errorDebut").html();
                errors = true; // Affectez true à la variable d'erreur
            }
            
            if (!errors) 
            {
                //en voyer les informations du formulaire à la page de traitement via ajax
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "http://localhost/EchoSovereign/controller/traitement_organisation_scrutin.php",
                    data: {titre:titre, organisation:organisation, 
                        description:description, fin:dateFin, debut:dateDebut, 
                        voteSimple:voteSimple}
                }).done(function(response) 
                {
                    if(response.success)
                    {
                        // on masque liste_scrutin
                        document.getElementById('liste_scrutin').style.display = 'none';
                        // on masque le formulaire de scrutin
                        $('#collapseForm').collapse('hide');
                        // on affiche le formulaire de votants
                        document.getElementById('FormVotant').style.display = 'block';

                        // Réinitialiser les messages d'erreur

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
                            // on masque le formulaire de scrutin
                            $('#collapseForm').collapse('hide');
                            // on affiche le formulaire de votants
                            document.getElementById('FormVotant').style.display = 'block';
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