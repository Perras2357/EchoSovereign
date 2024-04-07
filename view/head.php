<!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      <title>
        EchoSovereign
      </title>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>    
      <script>
         // Avant le rechargement de la page, enregistrez les messages de la console dans le stockage local
         window.addEventListener('beforeunload', function(e) {
            var consoleMessages = [];
            // Récupérer tous les messages de la console actuels
            var oldConsoleLog = console.log;
            console.log = function(message) {
                consoleMessages.push(message);
                oldConsoleLog.apply(console, arguments);
            };
            // Stocker les messages dans le stockage local
            localStorage.setItem('consoleMessages', JSON.stringify(consoleMessages));
        });

        // Après le rechargement de la page, restaurer les messages de la console depuis le stockage local
        window.addEventListener('load', function() {
            var storedMessages = JSON.parse(localStorage.getItem('consoleMessages'));
            if (storedMessages && storedMessages.length > 0) {
                console.log('--- Restoring console history ---');
                storedMessages.forEach(function(message) {
                    console.log(message);
                });
                console.log('--- End of restored console history ---');
            }
        });
    </script>
    </head>
    <body>
      <!-- on appel la couleur purple-700 comme background du header -->
      <!-- on utilise la couleur qui se trouve dans ../ressources/_variables.scss   -->
     


      <header class="container-fluid mb-5 " style="background-color: #b2e3ff;">     
        <div class="row"> 
          <h1 class="offset-4 col-2"> EchoSovereign </h1>  
        </div>
      </header>