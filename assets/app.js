import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

// POPUP
const btn = document.getElementById('btn-pop');
const popup = document.getElementById('pop-overlay');
const btnReg = document.getElementById('btn-pop-reg');
const popupReg = document.getElementById('pop-overlay-reg');
const closePopUpLogin = document.getElementById('btn-close-login');
const closePopUpReg = document.getElementById('btn-close-reg');
const btnOpenLogin = document.getElementById('btn-open-login');

// On vérifie que le bouton ET le popup existent avant d'ajouter l'événement
if (btn && popup) {
    btn.addEventListener('click', () => {
        popup.classList.remove('hidden');
    });
}

if (btnReg && popupReg && popup) {
    btnReg.addEventListener('click', () => {
        popupReg.classList.remove('hidden');
        popup.classList.add('hidden');
    });
}

if (closePopUpLogin && popup) {
    closePopUpLogin.addEventListener('click', () => {
        popup.classList.add('hidden');
    });
}

if (closePopUpReg && popupReg) {
    closePopUpReg.addEventListener('click', () => {
        popupReg.classList.add('hidden');
    });
}

if (btnOpenLogin && popup && popupReg) {
    btnOpenLogin.addEventListener('click', () => {
        popup.classList.remove('hidden');
        popupReg.classList.add('hidden');
    });
}


// SYSTEME D'ETOILES
document.addEventListener('DOMContentLoaded', function () {
    const ratingContainers = document.querySelectorAll('#rating-stars');

    // emptyColor = couleur des étoiles vides
    // fillColor = couleur des étoiles pleines
    // baseColor = couleur par défaut des étoiles
    // container = conteneur des étoiles
    function updateStars(container, rating, fillColor, emptyColor) {
        const stars = container.querySelectorAll('.star');
        const baseColor = container.dataset.starColor || 'text-gray-500';
        const color = fillColor || 'text-yellow-400';
        const colorEmpty = emptyColor || baseColor;
        const ratingRounded = Math.round(rating);

        stars.forEach((star) => {
            const starValue = parseInt(star.dataset.value);
            star.classList.remove(
                'text-orange-400', 'text-yellow-400', 'text-blue-400', 'text-gray-500'
            );
            if (starValue <= ratingRounded) {
                star.classList.add(color);
                star.innerHTML = '★';
            } else {
                star.classList.add(colorEmpty);
                star.innerHTML = '☆';
            }
        });
    }

    function restoreAverage(container) {
        const avg = parseFloat(container.dataset.average) || 0;
        const baseColor = container.dataset.starColor || 'text-gray-500';
        // Les étoiles pleines sont toujours jaunes (moyenne mondiale), les vides gardent la couleur de base
        updateStars(container, avg, 'text-yellow-400', baseColor);
    }

    ratingContainers.forEach(container => {
        const url = container.dataset.url;
        const stars = container.querySelectorAll('.star');
        const label = document.getElementById('rating-label');
        const isLoggedIn = container.dataset.isLoggedIn === 'true';

        // Note initiale : celle de l'utilisateur s'il a déjà voté, sinon la moyenne
        const userRatingRaw = container.dataset.userRating;
        const userRating = parseInt(userRatingRaw);
        const hasRated = !isNaN(userRating) && userRatingRaw !== '';

        // On garde en mémoire la note "active" pour restaurer l'affichage après un survol
        let currentRating = hasRated ? userRating : (parseFloat(container.dataset.average) || 0);

        // Si l'utilisateur n'est pas connecté -> étoiles non interactives
        if (!isLoggedIn) {
            container.style.cursor = 'default';
            container.style.pointerEvents = 'none';
            return;
        }

        // Utilisateur connecté -> toujours interactif (première note OU modification)
        stars.forEach(star => {

            // SURVOL : étoiles bleues
            star.addEventListener('mouseenter', function () {
                const hoverValue = parseInt(this.dataset.value);
                const baseColor = container.dataset.starColor || 'text-yellow-400';
                updateStars(container, hoverValue, 'text-blue-400', baseColor);
                if (label) label.textContent = hoverValue + '/5';
            });

            // FIN DE SURVOL : retour à la note courante (utilisateur ou moyenne)
            star.addEventListener('mouseleave', function () {
                // Étoiles jaunes jusqu'à currentRating, vides selon starColor
                const baseColor = container.dataset.starColor || 'text-yellow-400';
                updateStars(container, currentRating, 'text-yellow-400', baseColor);
                if (label) {
                    label.textContent = hasRated || currentRating > 0
                        ? 'Votre note : ' + Math.round(currentRating) + '/5 — Modifier'
                        : 'Cliquez pour noter';
                }
            });

            // CLIC : envoi (création ou mise à jour)
            star.addEventListener('click', function () {
                const selectedRating = parseInt(this.dataset.value);

                currentRating = selectedRating;

                updateStars(container, selectedRating, 'text-yellow-400');
                if (label) label.textContent = 'Votre note : ' + selectedRating + '/5 — Modifier';

                container.dataset.userRating = selectedRating;

                // Envoi au serveur
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ rating: selectedRating })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Server response :', data);
                    });
            });
        });
    });
});

// ═══════════════════ BOUTON RETOUR EN HAUT ═══════════════════
document.addEventListener('DOMContentLoaded', function () {
    const scrollBtn = document.getElementById('scroll-to-top');
    const THRESHOLD = 50; // C'est la distance en pixel que l'utilisateur doit parcourir avant que le bouton apparaisse

    if (!scrollBtn) return;

    function toggleBtn() {
        if (window.scrollY > THRESHOLD) {
            scrollBtn.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
            scrollBtn.classList.add('opacity-100', 'translate-y-0');
        } else {
            scrollBtn.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
            scrollBtn.classList.remove('opacity-100', 'translate-y-0');
        }
    }

    // Vérification au chargement 
    toggleBtn();

    window.addEventListener('scroll', toggleBtn, { passive: true });

    // Défilement doux vers le haut
    scrollBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

// ═══════════════════ BOUTONS ROLE ═══════════════════
document.addEventListener('DOMContentLoaded', function () {
    const btnAdd = document.getElementById('newRoleButton');
    const formNew = document.getElementById('newRoleForm');
    const btnCancel = document.getElementById('cancelNewRoleButton');

    if (btnAdd) {
        btnAdd.addEventListener('click', () => {
            btnAdd.classList.add('hidden');
            formNew.classList.remove('hidden');
        })
    }

    if (btnCancel) {
        btnCancel.addEventListener('click', () => {
            formNew.classList.add('hidden');
            btnAdd.classList.remove('hidden');
        })
    }
});


// ═══════════════════ PAGE ARCHIVE/EN ATTENTE ═══════════════════

document.addEventListener('DOMContentLoaded', function () {
    const contentEnAttente = document.getElementById('content-pending');
    const contentArchive = document.getElementById('content-archive');
    const btnEnAttente = document.getElementById('tab-pending');
    const btnArchive = document.getElementById('tab-archive');

    if(btnArchive) {
        btnArchive.addEventListener('click', () => {
            contentArchive.classList.remove('hidden');
            contentEnAttente.classList.add('hidden');
        })
    } 

    if(btnEnAttente) {
        btnEnAttente.addEventListener('click', () => {
            contentArchive.classList.add('hidden');
            contentEnAttente.classList.remove('hidden');
        })
    }
});