// Dès que la page est chargé
$(document).ready(function(){
    // Initalisation des variables
    const apiUrl = 'https://api-adresse.data.gouv.fr/search/?q=';
    const format = '&format=json';
    const complete = '&autocomplete=1';
    var adress = $('#adress');
    var submit = $('#submit');
    var localisation = [];
    var value;

    // Initialisation de la map sur WebForce3 de Lille
    localisation.push(50.6462315, 3.0752943);
    var map = L.map('map').setView(localisation, 13);
    var marker = L.marker(localisation).addTo(map)
    .bindPopup("WebForce3 <br> 105 avenue de la république <br> 59110 La Madeleine")
    .openPopup();

                
    // Ajout des attributions ou configuration de la map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Récupération du choix du bouton radio
    $('#radio input').on('change', function(){
        value = $("input[name='type']:checked").val();
        
        // concaténation pour affichage sois ville sois adresse complète
        const type = '&type='+value;
        
    
        // Quand on sors du champ input de l'adresse
        $(adress).on('blur', function(){
            // Récupération du champ input de l'adresse
            let replaceSpace = $(this).val();
            // Remplacement des espaces par des +
            let name = replaceSpace.split(" ").join("+");
            console.log(name);
    
            // Initialisation de la variable d'appelle à l'api adress.gouv.fr 
            var url = apiUrl+name+format+type+complete;
            console.log(url);
    
            // click sur le bouton envoyer
            $(submit).on('click', function() {
                // Suppression du markeur lors d'une requête
                map.removeLayer(marker);
    
                // Récupération des informations en JSON de l'api
                $.get(url, function(data){
                    localisation = [];
                    lat = data.features[0].geometry.coordinates[1];
                    long = data.features[0].geometry.coordinates[0];
                    name = data.features[0].properties.name;
                    postcode = data.features[0].properties.postcode;
                    city = data.features[0].properties.city;
                    localisation.push(lat, long);
    
                    // Condition si on cherche que la ville
                    if(value == "municipality") {
                        // Supression du markeur
                        map.removeLayer(marker);

                        // Zoom vers la localisation de coordonnées reçu de l'api
                        map = map.setView(localisation, 13);
                        marker = L.marker(localisation).addTo(map)
                            .bindPopup(postcode+"<br>"+city)
                            .openPopup();

                    // Condition si on cherche l'adresse complète
                    } else if(value == "housenumber") {
                        // Supression du markeur
                        map.removeLayer(marker);

                        // Zoom vers la localisation de coordonnées reçu de l'api
                        map = map.setView(localisation, 13);
                        marker = L.marker(localisation).addTo(map)
                            .bindPopup(name+'<br>'+postcode+'<br>'+city)
                            .openPopup();
                    }
                });
            });
        });
    });
});
