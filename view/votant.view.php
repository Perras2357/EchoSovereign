<?php
    require_once("head.php");
?>

<!-- formulaire de création de votants dans un formulaire sous forme de tableau avec pour en-tête : le mail, le nombre de procuration qui est 0 par défaut et une case donner procuration qui est une case à cocher -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Création des votants</h1>
            <form name="votantForm" method="post" id="votantForm" class="p-2">
                <div class="row mb-3">
                    <div class="col-md-10" id="addReponse">
                        <label for="email" class="col-form-label">Email :</label>
                        <input type="email" id="email" name="email">  
                        Donner procuration <input type="checkbox" name="donnerProcuration" id="donnerProcuration">
                        <label for="nombreProcuration" class="col-form-label" max=3>  Nombre procuration :</label>
                        <input type="number" id="nombreProcuration" name="nombreProcuration">
                        <button onclick="addRow(event)">Ajouter</button>
                        <span style="color: red;" id="errorAjout" class="error"></span><br>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-9">
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
                </div>

            </form> 
        </div>
    </div>
</div>

<script>

    //declartion tableau
    let votants = {};
    let rowId = 0;

    // Récupérer les références des éléments du formulaire
    const checkbox = document.getElementById('donnerProcuration');
    const inputField = document.getElementById('nombreProcuration');

    // Ajouter un écouteur d'événements sur le changement d'état du checkbox
    checkbox.addEventListener('change', function() {
        // Vérifier si le checkbox est coché
        if (checkbox.checked) 
        {
            // Désactiver le champ de saisie
            inputField.disabled = true;
        } 
        else 
        {
            // Activer le champ de saisie
            inputField.disabled = false;
        }
    });

    function addRow(event) 
    {
        event.preventDefault();
        rowId++;
        
        // Récupérer les valeurs des champs
        let email = document.getElementById('email').value;
        let nombreProcuration = document.getElementById('nombreProcuration').value;
        let donnerProcuration = document.getElementById('donnerProcuration').checked;
        
        // Vérifier si les champs ne sont pas vides
        if (email === '' || (nombreProcuration === '' && !donnerProcuration)) 
        {
            document.getElementById('errorAjout').innerHTML = 'Veuillez remplir tous les champs';
            return;
        }
        if (donnerProcuration) 
        {
            nombreProcuration = 0;
        }
        
        // Réinitialiser le message d'erreur
        document.getElementById('errorAjout').innerHTML = '';

        //on vérifie si le mail existe dans les lignes précédentes
        for (let key in votants) 
        {
            if (votants[key].email === email) 
            {
                document.getElementById('errorAjout').innerHTML = 'Le mail existe déjà';
                return;
            }
        }
        
        // Créer une nouvelle ligne dans le tableau
        let newRow = '<tr><td>' + email + '</td><td>' + nombreProcuration +'</td><td>'+ donnerProcuration +'</td></tr>';
        
        // Ajouter la nouvelle ligne au tableau
        document.getElementById('tableBody').innerHTML += newRow;

        // Ajouter les valeurs dans le tableau votants avec pour clé rowId
        votants[rowId] = {email: email, nombreProcuration: nombreProcuration};
        
        // Réinitialiser les champs
        document.getElementById('email').value = '';
        document.getElementById('nombreProcuration').value = '';
        console.log(votants);

    }

</script>

<?php
    require_once("footer.php");    
?>
