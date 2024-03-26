<?php
    require("head.php");

    $favicon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16" style="color: blue;">
    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
</svg> ';
?>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>    

    <div class="container">

        <!-- button pour ajouter un vote -->
        <div class="row mb-3">
            <div class="col-md-3 offset-md-3">
                <p class="d-inline-flex gap-1">
                    <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapse">
                        <?= $favicon ; ?>
                        Créer un vote
                    </button>
                    
                </p>
            </div>
        </div>

        <div class="col-lg-10 col-md-10 offset-lg-1 offset-md-1 shadow-none p-3 mb-5 bg-light rounded p-5" >
            
        <!-- formulaire de création de scrutin -->
            <form name="scrutinForm" method="post" onsubmit="return validateFormScrutin()" id="collapseForm" class="collapse col-form-label">
                
                <!-- titre du vote -->
                <div class="row mb-3">
                    <label for="titre" class="col-form-label">Titre</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="titre" name="titre" required>
                        <span style="color: red;" id="errorTitre" class="error"></span><br>
                    </div>
                </div>

                <!-- organisation du vote -->
                <div class="row mb-3">
                    <label for="organisation" class="col-form-label">Organisation</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="organisation" name="organisation">
                        <span style="color: red;" id="errorOrganisation" class="error"></span><br>
                    </div>
                </div>

                <!-- description du vote -->
                <div class="row mb-3">
                    <label for="description" class="col-form-label">Description</label>
                    <div class="col-md-9">
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <span style="color: red;" id="errorDescription" class="error"></span><br>
                    </div>
                </div>

                <!-- debut du vote -->
                <div class="row mb-3">
                    <label for="debut" class="col-form-label">Début du vote</label>
                    <div class="col-md-9">
                        <input type="datetime-local" class="form-control" id="debut" name="debut">
                        <span style="color: red;" id="errorDebut" class="error"></span><br>
                    </div>
                </div>

                <!-- date de fin du vote -->
                <div class="row mb-3">
                    <label for="fin" class="col-form-label">Fin du vote</label>
                    <div class="col-md-9">
                        <input type="datetime-local" class="form-control" id="fin" name="fin" required>
                        <span style="color: red;" id="errorFin" class="error"></span><br>
                    </div>
                </div>

                <!-- Ajouter un vote simple ou un vote pondéré  -->
                <div class="row mb-3">
                    <label for="voteSimple" class="col-form-label">Type de vote</label>
                    <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVoteSimple" aria-expanded="false" aria-controls="collapse">
                        <?= $favicon ; ?>
                        Vote simple
                    </button>
                    <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVotePondere" aria-expanded="false" aria-controls="collapse">
                        <?= $favicon ; ?>
                        Vote pondéré
                    </button>
                </div>

                <!-- zone vote simple uniquement un input de type textarea-->
                <div class="collapse" id="collapseVoteSimple">
                    <div class="row mb-3" >
                        <label for="voteSimple" class="col-form-label">Vote simple</label>
                        <div class="col-md-9">
                            <textarea class="form-control" id="voteSimple" name="voteSimple" rows="3"></textarea>
                            <span style="color: red;" id="errorVoteSimple" class="error"></span><br>
                        </div>
                    </div>
                </div>

                <!-- bouton pour créer le vote -->
                <div class="mb-3 py-2">
                    <button type="submit" class="btn btn-primary  col-md-2 offset-md-4" id="submit" name="submit">Suivant</button>
                </div>
            </form>

            <!-- formulaire de création de votants dans un champs de textarea de maximum 30 lignes -->
            <form name="votantForm" method="post" onsubmit="return validateFormVotant()" id="FormVotant">
                <div class="row mb-3">
                    <label for="votant" class="col-form-label">Votants</label>
                    <div class="col-md-9">
                        <textarea class="form-control" id="votant" name="votant" rows="12"></textarea>
                        <span style="color: red;" id="errorVotant" class="error"></span><br>
                    </div>
                </div>
                <div class="mb-3 py-2">
                    <button type="submit" class="btn btn-primary  col-md-2 offset-md-4" id="submit1" name="submit1">Créer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- code javascript permettant de valider les champs du formulaire -->
    <script>
        
        //masquer FormVotant initialement
        document.getElementById('FormVotant').style.display = 'none';
        //document.getElementById('FormVotant').style.display = 'block';

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
                    url: "../controller/gestion_votant.php",
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
                    url: "../controller/organisation_scrutin.php",
                    data: {titre:titre, organisation:organisation, 
                        description:description, fin:dateFin, debut:dateDebut, 
                        voteSimple:voteSimple}
                }).done(function(response) 
                {
                    if(response.success)
                    {
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
        
    </script>









<?php
    require("footer.php");    
?>






