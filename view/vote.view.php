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
            <form class="col-md-8 offset-md-1" id="form_vote" method="POST" onsubmit="return envoieVote(event)">
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
                    <span style="color: green;" id="erreurVote" class="error"></span><br>
                </div>
            </form>
        </div>
            
        <!-- div pour afficher les résultat du vote -->
        <div class="row" id="resultat">
            <div class="col-md-12">
                <h3 class="text-center">Resultat du vote</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Nombre de votants</th>
                            <th scope="col">Nombre de vote</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ici on affiche les résultats du vote -->
                    </tbody>
                
            </div>
        </div>

    </div>
    <script src="../js/ajax_vote.js"></script>



<script>
    reponses = [];
    function afficheResultat()
    {
        //appel ajax pour construire le tableau de resultat des résultats
        $.ajax({
            url: '../controller/calcule_resultat.php',
            type: 'POST',
            data: {action: 'resultat'},
            dataType : 'json'
        }).done(function(response)
                {
                    if(response.success)
                    {
                        //on ajoute les éléments dans thead en fonction des réponses qui se trouve dans reponses
                        



                    }
                    else
                    {
                        alert(response.message);
                    }
                }).fail(function(error)
                {
                    alert('Erreur de chargement des résultats');
                });


    }

        
        

        

</script>











<?php
    require("footer.php");
?>