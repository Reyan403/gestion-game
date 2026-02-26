import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

const btn = document.getElementById('btn-pop');
const popup = document.getElementById('pop-overlay');
const btnReg = document.getElementById('btn-pop-reg');
const popupReg = document.getElementById('pop-overlay-reg');
const closePopUpLogin = document.getElementById('btn-close-login');
const closePopUpReg = document.getElementById('btn-close-reg');
const btnOpenLogin = document.getElementById('btn-open-login');

btn.addEventListener('click', () => {
    popup.classList.remove('hidden');
})

btnReg.addEventListener('click', () => {
    popupReg.classList.remove('hidden');
    popup.classList.add('hidden');
})

closePopUpLogin.addEventListener('click', () => {
    popup.classList.add('hidden');
})

closePopUpReg.addEventListener('click', () => {
    popupReg.classList.add('hidden');
})

btnOpenLogin.addEventListener('click', () => {
    popup.classList.remove('hidden');
    popupReg.classList.add('hidden');
})
