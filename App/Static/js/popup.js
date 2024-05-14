function togglePopup(id){
    let popup = document.getElementById(id);
    if (popup) {
        popup.classList.toggle("open");
    } else{
        console.error("Identifiant d'utilisateur introuvable :", id);
    }
}
