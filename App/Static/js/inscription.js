//pour rediriger vers la page de connexion lorsque le compte est créé
var formulaireRedirection = document.getElementById('redirectionConnexion');
window.onload = function() {
    formulaireRedirection.submit();
};

//pour faire apparaître les info d'artiste (textarea et input file)
var checkbox = document.getElementById('artiste');
var infoArtiste = document.getElementById('infoArtiste');

function alterneVisibilite() {
    if (checkbox.checked) {
        infoArtiste.style.display = 'block';
    } else {
        infoArtiste.style.display = 'none';
    }
}

alterneVisibilite();
checkbox.addEventListener('change', alterneVisibilite);
