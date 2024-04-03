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
                    <!-- zone de texte pour entrer la question du vote simple et les choix possibles -->
                    <div class="row mb-3">
                        <label for="questionSimple" class="col-form-label">Question</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="questionSimple" name="questionSimple">
                            <span style="color: red;" id="errorQuestionSimple" class="error"></span><br>
                        </div>
                        <label for="choixSimple" class="col-form-label">Choix</label>
                        
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
    
    
    <!-- inclure le fichier js pour la validation du formulaire -->
    <script src="../js/ajax_organisation_scrutin.js"></script>
    







<?php
    require_once("footer.php");    
?>






