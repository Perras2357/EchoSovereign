<?php
    require_once("head.php");
?>

<!-- formulaire de création de votants dans un formulaire sous forme de tableau avec pour en-tête : le mail, le nombre de procuration qui est 0 par défaut et une case donner procuration qui est une case à cocher -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Création des votants</h1>
            <form name="votantForm" method="post" id="votantForm" class="p-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Mail</th>
                            <th scope="col">Nombre de procuration</th>
                            <th scope="col">Donner procuration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" class="form-control" id="mail1" name="mail1" required></td>
                            <td><input type="number" class="form-control" id="nbProcuration1" name="nbProcuration1" value="0" max='2'></td>
                            <td><input type="checkbox" class="form-check-input" id="donnerProcuration1" name="donnerProcuration1"></td>
                        </tr>
                    </tbody>
                </table>
                <!-- div pour afficher les erreurs -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="error" class="text-danger"></div>
                    </div>
                </div>
                <!-- bouton pour créer les votants -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-primary" onclick="sendData()">Créer</button>
                    </div>
                </div>
            </form>
            <!-- bouton pour ajouter une nouvelle ligne dans le tableau de votants -->
            <div class="row ">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-primary" onclick="addRow()">Ajouter une ligne</button>
                </div>
            </div>  
        </div>
    </div>
</div>

<script>
    function addRow() 
    {
        var table = document.querySelector("#votantForm table");
        if (!table) {
            console.error('Tableau dans le formulaire avec ID "votantForm" non trouvé');
            return;
        }
        var rowCount = table.rows.length;

        // Génération d'ID uniques pour chaque nouvelle ligne
        var mailId = "mail" + rowCount;
        var nbProcurationId = "nbProcuration" + rowCount;
        var donnerProcurationId = "donnerProcuration" + rowCount;

        if (rowCount > 0 && (table.rows[rowCount - 1].cells[0].children[0].value == "" || table.rows[rowCount - 1].cells[1].children[0].value == "")) {
            document.getElementById("error").innerHTML = "Veuillez remplir la ligne avant d'en ajouter une autre";
        } else {
            document.getElementById("error").innerHTML = ""; // Effacer le message d'erreur
            var row = table.insertRow(rowCount);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            cell1.innerHTML = "<input type='text' class='form-control' id='" + mailId + "' name='mail[]' required>";
            cell2.innerHTML = "<input type='number' class='form-control' id='" + nbProcurationId + "' name='nbProcuration[]' value='0' max='2'>";
            cell3.innerHTML = "<input type='checkbox' class='form-check-input' id='" + donnerProcurationId + "' name='donnerProcuration[]'>";
        }
    }


    function sendData() 
    {
        var table = document.querySelector("#votantForm table");
        if (!table) {
            console.error('Tableau dans le formulaire avec ID "votantForm" non trouvé');
            return;
        }

        var rowCount = table.rows.length;
        var data = [];
        var errors = false;

        for (var i = 0; i < rowCount; i++) {
            var mailInput = document.querySelectorAll("input[name='mail[]']")[i];
            var nbProcurationInput = document.querySelectorAll("input[name='nbProcuration[]']")[i];
            var donnerProcurationInput = document.querySelectorAll("input[name='donnerProcuration[]']")[i];

            
            console.log("Mail Input:", mailInput);
            console.log("NbProcuration Input:", nbProcurationInput);
            console.log("Donner Procuration Input:", donnerProcurationInput);


            var mail = mailInput.value;
            var nbProcuration = nbProcurationInput.value;
            var donnerProcuration = donnerProcurationInput.checked;

            if (mail.trim() === "") 
            {
                document.getElementById("error").innerHTML = "Le champ mail ne peut pas être vide pour la ligne " + (i + 1);
                errors = true;
                break;
            }
            if (nbProcuration < 0) 
            {
                document.getElementById("error").innerHTML = "Le nombre de procuration doit être positif pour la ligne " + (i + 1);
                errors = true;
                break;
            } 
            else if (nbProcuration > 2) 
            {
                document.getElementById("error").innerHTML = "Le nombre de procuration ne peut pas être supérieur à 2 pour la ligne " + (i + 1);
                errors = true;
                break;
            }
            if (nbProcuration > 0 && donnerProcuration) 
            {
                document.getElementById("error").innerHTML = "Le nombre de procuration doit être 0 si vous donnez une procuration pour la ligne " + (i + 1);
                errors = true;
                break;
            }

            if (!errors) 
            {
                data.push({ mail: mail, nbProcuration: nbProcuration, donnerProcuration: donnerProcuration ? 1 : 0 });
            } else 
            {
                // Stop the loop if there are errors
                break;
            }
        }

        if (!errors) 
        {
            // Convertir l'objet data en chaîne JSON
            var jsonData = JSON.stringify(data);

            // Envoyer les données via AJAX
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "http://localhost/EchoSovereign/controller/votant.controller.php",
                data: { votantData: jsonData },
            }).done(function (reponse) 
            {
                if (reponse.success) 
                {
                    alert(reponse.message);
                    console.log(reponse.message);
                    location.reload();
                }
                else 
                {
                    alert(reponse.message);
                    console.log(reponse.message);
                    location.reload();
                }
            }).fail(function (error) 
            {
                alert("Une erreur s'est produite lors de la requête AJAX.");
                console.log(error);
                
            });
        }
    }

</script>

<?php
    require_once("footer.php");    
?>
