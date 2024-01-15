// Path: app/assets/flashes.scss

// Flash messages
// Récupère tous les éléments avec la classe .liveFlash puis ajoute la classe .show et supprime la classe .show après 5 secondes
var flashLiveExamples = document.querySelectorAll('.liveFlash');
if (flashLiveExamples) {
    flashLiveExamples.forEach(function (flashLiveExample) {
        flashLiveExample.classList.add('show');
        setTimeout(function () {
            flashLiveExample.classList.remove('show');
        }, 5000);
    });
}
