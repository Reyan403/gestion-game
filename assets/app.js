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
document.addEventListener('DOMContentLoaded', function() {
    const ratingContainers = document.querySelectorAll('#rating-stars');

    function updateStars(container, rating) {
        const stars = container.querySelectorAll('.star');
        const ratingRounded = Math.round(rating);

        stars.forEach((star) => {
            const starValue = parseInt(star.dataset.value);
            if (starValue <= ratingRounded) {
                star.classList.add('filled');
                star.innerHTML = '★'; 
            } else {
                star.classList.remove('filled');
                star.innerHTML = '☆'; 
            }
        });
    }

    ratingContainers.forEach(container => {
        const url = container.dataset.url;
        const stars = container.querySelectorAll('.star');
        
        // On regarde si Symfony nous a envoyé une note pour cet utilisateur
        const userRating = parseInt(container.dataset.userRating);

        if (!isNaN(userRating)) {
            // L'utilisateur a déjà voté (info venant de la BDD)
            updateStars(container, userRating);
            container.classList.add('rated');
        } 
        else {
            // L'utilisateur n'a pas encore voté
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    
                    const isLoggedIn = container.dataset.isLoggedIn === 'true';

                    if (!isLoggedIn) {
                        const loginPopup = document.getElementById('pop-overlay');
                        if (loginPopup) {
                            loginPopup.classList.remove('hidden');
                        }
                        return; 
                    }

                    const selectedRating = parseInt(this.dataset.value);

                    // Mise à jour visuelle instantanée
                    updateStars(container, selectedRating);
                    container.classList.add('rated');

                    // On envoie à Symfony 
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            rating: selectedRating
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Server response :', data);
                    });
                });
            });
        }
    });
});
