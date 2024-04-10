//-----------------------fonction qui permet d'afficher les resultats------------------
function afficheResultat()
{
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: "http://localhost/EchoSovereign/controller/traitement_resultat.php",
        data: {resultat:1}
    }).done(function(response)
    {
        if(response.success)
        {
            //afficher les resultats du tableau de resultats dans la div liste_resultat
            document.getElementById('liste_resultat').style.display = 'block';
            
            Object.entries(response.resultats).forEach(([key, value]) => {
                $('#liste_resultat #table_resultat #tbody_resultat').append('</tr><tr><td>'+key+'</td><td>'+value+'</td><td class="text-info">'+((value/nbr_votes)*100).toFixed(2)+'%</td></tr>');
            });
            
                //on affiche le taux de participation
            var taux = ((nbr_votes / nbr_votants) * 100).toFixed(2);
            document.getElementById('participation').textContent = taux+'%';
            console.log(response.resultats);

        }
        else
        {
            //on cache le tableau de liste des scrutins si il n'y a pas de scrutin à afficher 
            document.getElementById('liste_resultat').style.display = 'block';
            console.log(response.message);
            alert(response.message);
        }
    }).fail(function(error)
    {
        alert( "error" );
        console.log(error);
    });
}