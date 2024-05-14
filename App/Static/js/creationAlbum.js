let buttonAjoutGenre = document.getElementById("ajouterGenre");
buttonAjoutGenre.addEventListener("click", function(e) {
    $new = document.querySelector("#select").cloneNode(true);
    document.querySelector(".selectbox").appendChild($new);
});

let buttonSupprimerGenre = document.getElementById("supprimerGenre");
buttonSupprimerGenre.addEventListener("click", function(e) {
    if (document.querySelector(".selectbox").childNodes.length > 3) {
        document.querySelector(".selectbox").removeChild(document.querySelector(".selectbox").lastChild)
    }
});

let buttonAjout = document.getElementById("ajouter");
buttonAjout.addEventListener("click", function(e) {
    titre = document.querySelector("#titre").cloneNode(true);
    titre.value = "";
    document.querySelector(".tracks").appendChild(titre);
});

let buttonSupprimer = document.getElementById("supprimer");
buttonSupprimer.addEventListener("click", function(e) {
    if (document.querySelector(".tracks").childNodes.length > 3) {
        document.querySelector(".tracks").removeChild(document.querySelector(".tracks").lastChild)
    }
});
