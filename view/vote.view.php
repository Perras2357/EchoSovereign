<?php
    require("head.php");
?>

 <!-- ici on affiche tout les scrutins -->
 <div class="container" id="liste_scrutin">
        <div class="row">
            <div class="col-md-12">
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
                            <th scope="col">Nombre de vote</th>
                            <th scope="col">Je peux voter</th>
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
        <div class="row" id='vote'>
            <form class="col-md-8 offset-md-1" id="form_vote" method="POST" onsubmit="return envoieVote()">
                <div class="col-md-2">
                    <h3 id="question">  </h3>                
                </div>
                <div class="col-md-2">
                    <label for="choix">Reponses : </label>
                    <select class="form-control" id="choix" name="choix">
                        <!-- ici on affiche les options de vote -->
                    </select>  
                    <span style="color: red;" id="erreurVote" class="error"></span><br>               
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary" id="submit">Voter</button>
                </div>
            </form>
        </div>
            
        </div>
    </div>
    <script src="../js/ajax_vote.js"></script>



<script>
    //je coonstruis le formulaire de vote en fonction des questions et des réponses dans le formulaire de vote qui a l'id form_vot

        
        

        

</script>











<?php
    require("footer.php");
?>