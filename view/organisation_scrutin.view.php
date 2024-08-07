<?php
    require_once("head.php");

    $favicon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16" style="color: blue;">
    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
</svg> ';
?>

    <!-- ici on affiche tout les scrutins -->
    <div class="container" id="liste_scrutin">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <h3 class="text-center">Liste des scrutins</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Titre</th>
                            <th scope="col">Organisation</th>
                            <th scope="col">Description</th>
                            <th scope="col">Début</th>
                            <th scope="col">Fin</th>
                            <th scope="col">Etat</th>
                            <th scope="col">Nombre de Votants</th>
                            <th scope="col">Nombre de Votes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ici on affiche les scrutins venu de l'appel ajax -->

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- div pour afficher les résultat du vote -->
    <div class="row" id="liste_resultat">
        <div class="col-md-10 offset-1">
            <h3 class="text-center">Resultat du vote</h3>
            <table class="table table-striped" id="table_resultat">
                <thead>
                    <tr>
                        <th scope="col">Question</th>
                        <th scope="col">Reponse</th>
                        <th scope="col">Nombre de vote</th>
                        <th scope="col">Pourcentage</th>
                        <th scope="col">Taux de participation</th>
                    </tr>
                </thead>
                <tbody id="tbody_resultat">
                    <!-- ici on affiche les résultats du vote -->
                </tbody>
            </table>
        </div>
    </div>


    <div class="container">

        <!-- button pour ajouter un vote -->
        <div class="row mb-3">
            <div class="col-md-3 offset-md-3" id="btn_create_vote">
                <p class="d-inline-flex gap-1">
                    <button class="btn" type="button" id="collapseForm">
                        <?= $favicon ; ?>
                        Créer un vote
                    </button>
                    
                </p>
            </div>
        </div>

        <div class="col-lg-10 col-md-10 offset-lg-1 offset-md-1 p-3 mb-5 bg-light rounded p-5" >
            
            <!-- formulaire de création de scrutin -->
            <form name="scrutinForm" method="post" onsubmit="return validateFormScrutin()" id="formOrganisation">
                
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
                <!-- zone vote simple uniquement un input de type textarea-->
                <div id="collapseVoteSimple">
                    <div class="row mb-3" >
                        <label for="voteSimple" class="col-form-label">Vote simple : Entrez la raison du vote</label>
                        <div class="col-md-9">
                            <input class="form-control" id="voteSimple" name="voteSimple" rows="3">
                            <span style="color: red;" id="errorVoteSimple" class="error"></span><br>
                        </div>
                    </div>
                    <!-- zone de texte pour entrer la question du vote simple et les choix possibles -->
                    <div class="row mb-3">
                        <!-- zone de texte pour entrer les choix possibles -->
                        <div class="col-md-9" id="addReponse">
                            <label for="reponse" class="col-form-label">Choix :</label>
                            <input type="text" id="reponse" name="reponse">
                            <button onclick="addReponseToList(event)">Ajouter</button>
                            <span style="color: red;" id="errorChoixSimple" class="error"></span><br>
                            <ul id="listReponse">
                                <!-- Les réponses ajoutées seront affichées ici -->
                            </ul>
                        </div>
                            
                    </div>

                <!-- bouton pour créer le vote -->
                <div class="mb-3 py-2">
                    <button type="submit" class="btn btn-primary  col-md-2 offset-md-4" id="submit" name="submit">Suivant</button>
                </div>
            </form>
        </div>
        <div class="col-lg-10 col-md-10 offset-lg-1 offset-md-1 p-3 mb-5 bg-light rounded p-5">
            <!-- formulaire de création de votants dans un champs de textarea de maximum 30 lignes -->
            <form name="votantForm" method="post" onsubmit="return validateFormVotant()" id="FormVotant">

                <div class="row mb-3">
                    <div class="col-md-10" id="addReponse">
                        <label for="email" class="col-form-label">Email :</label>
                        <input type="email" id="email" name="email" autocomplete="email">  
                        Donner procuration <input type="checkbox" name="donnerProcuration" id="donnerProcuration">
                        <label for="nombreProcuration" class="col-form-label" max=3>  Nombre procuration :</label>
                        <input type="number" id="nombreProcuration" name="nombreProcuration">
                        <button onclick="addRow(event)">Ajouter</button>
                        <span style="color: red;" id="errorAjout" class="error"></span><br>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-10">
                        <table id="tableProcuration" class="table">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Nombre de procuration</th>
                                    <th>Donner procuration</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Les lignes ajoutées seront affichées ici -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mb-3 py-2">
                    <button type="submit" class="btn btn-primary  col-md-2 offset-md-4" id="submit" name="submit">Envoyer</button>
                    <span style="color: red;" id="errorVotant" class="error"></span><br>
                </div>   


            </form>
        </div>
    </div>
    
    
    <!-- inclure le fichier js pour la validation du formulaire -->
    <script src="../js/ajax_organisation_scrutin.js"></script>







<?php
    require_once("footer.php");    
?>






