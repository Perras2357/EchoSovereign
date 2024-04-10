<?php
    require("head.php");
?>
    <script src="../Encrypte/node_modules/jsencrypt/bin/jsencrypt.min.js"></script>


 <!-- ici on affiche tout les scrutins -->
    <div class="container" id="liste_scrutin">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center">Liste des scrutins</h3>
                <table class="table table-striped" id="table_scrutin">
                    <thead>
                        <tr>
                            <th scope="col">Titre</th>
                            <th scope="col">Organisation</th>
                            <th scope="col">Description</th>
                            <th scope="col">Début</th>
                            <th scope="col">Fin</th>
                            <th scope="col">Etat</th>
                            <th scope="col">Nombre de vote</th>
                            <th scope="col">Je peux voter</th>
                            <th scope="col">Nombre de Votants</th>
                            <th scope="col">Nombre de Votes</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_scrutin">
                        <!-- ici on affiche les scrutins venu de l'appel ajax -->

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" id='vote'>
            <form class="col-md-8 offset-md-2" id="form_vote" method="POST" onsubmit="return envoieVote(event)">
                <div class="col-md-2 offset-md-4">
                    <h3 id="question">  </h3>                
                </div>
                <div class="col-md-2 offset-md-4">
                    <label for="choix">Reponses : </label>
                    <select class="form-control" id="choix" name="choix">
                        <!-- ici on affiche les options de vote -->
                    </select>  
                    <span style="color: red;" id="erreurVote" class="error"></span><br>               
                </div>
                <div class="col-md-2 offset-md-4">
                    <button type="submit" class="btn btn-primary" id="submit">Voter</button>
                    <span style="color: green;" id="erreurVote" class="error"></span><br>
                </div>
            </form>
        </div>
            
        <!-- div pour afficher les résultat du vote -->
        <div class="row" id="liste_resultat">
            <div class="col-md-10 offset-2">
                <h3 class="text-center">Resultat du vote</h3>
                <table class="table table-striped" id="table_resultat">
                    <thead>
                        <tr>
                            <th scope="col">Reponse</th>
                            <th scope="col">Nombre de vote</th>
                            <th scope="col">Pourcentage</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_resultat">
                        <!-- ici on affiche les résultats du vote -->
                    </tbody>
                </table>
            </div>
            <div class="col-md-10 offset-2" id="taux_participation">
                <h3 class="text-center">Taux de participation</h3>
                <p id="participation" class="offset-5 text-success"> </p> 
            </div>
        </div>

    </div>
    <script src="../js/ajax_vote.js"></script>

<?php
    require("footer.php");
?>