document.addEventListener("DOMContentLoaded", function() {
    var userBtn = document.getElementById("userBtn");
    var menu = document.getElementById("menu");

    userBtn.addEventListener("click", function(event) {
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
        event.stopPropagation();
    });

    document.addEventListener("click", function(event) {
        var targetElement = event.target;
        var isClickInsideMenu = menu.contains(targetElement);
        var isClickInsideUserBtn = userBtn.contains(targetElement);

        if (!isClickInsideMenu && !isClickInsideUserBtn) {
            menu.style.display = "none";
        }
    });
});
