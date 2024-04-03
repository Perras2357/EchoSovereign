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
                            <th scope="col">Accès au vote</th>
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
    </div>



    <script>
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "http://localhost/EchoSovereign/controller/traitement_vote.php",
            data: {votant: 1}
        }).done(function(response) 
        {
            if(response.success)
            {
                console.log(response);
                //on parcourt le tableau de scrutins et on les affiche dans le tableau
                response.scrutins.forEach(scrutin => {
                    $('#liste_scrutin table tbody').append('<tr><td>'+scrutin.titre+
                        '</td><td>'+scrutin.organisation+'</td><td>'+scrutin.description+
                        '</td><td>'+scrutin.debut+'</td><td>'+scrutin.fin+'</td><td>'+scrutin.acces_vote+
                        '</td><td>'+scrutin.je_peux_voter+'</td><td>'+scrutin.nbr_votants+'</td><td>'+scrutin.nbr_votes+'</td></tr>');
                });
            }
            else
            {
                console.log(response);

            }
        }).fail(function(error) 
        {
            alert( "error" );
            console.log(error);
        });
    </script>














<?php
    require("footer.php");
?>