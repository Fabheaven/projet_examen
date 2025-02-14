import "../styles/app.css"; // Import du CSS

import * as te from "tw-elements"; // Importer TOUT tw-elements

// Sélection des éléments
const loginButton = document.querySelector('.login-button');
const menuButton = document.querySelector('.menu-boutton');

if (loginButton && menuButton) {
  // Ajout d'un écouteur d’événement au clic
  loginButton.addEventListener('click', (event) => {
    event.stopPropagation(); // Empêche le clic de se propager au document
    menuButton.classList.toggle('hidden'); // Utilisation de Tailwind 'hidden' au lieu de 'active'
  });
  
  // Ajout d'un écouteur d’événement global pour détecter les clics hors div
  document.addEventListener('click', (event) => {
    if (!menuButton.contains(event.target) && !loginButton.contains(event.target)) {
      menuButton.classList.add('hidden');
    }
  });
  
}

// menu-burger
const burgerButton = document.querySelector('.burger-button');
const menuMobile = document.querySelector('.menu-mobile');

if (burgerButton && menuMobile) {
  // Ajout d'un écouteur d’événement au clic
  burgerButton.addEventListener('click', (event) => {
    event.stopPropagation(); // Empêche le clic de se propager au document
    menuMobile.classList.toggle('hidden'); // Utilisation de Tailwind 'hidden' au lieu de 'active'
  });

  // Ajout d'un écouteur d’événement global pour détecter les clics hors div
  document.addEventListener('click', (event) => {
    if (!menuMobile.contains(event.target) && !burgerButton.contains(event.target)) {
      menuMobile.classList.add('hidden');
    }
  });
}



te.initTWE(); // Initialisation de tw-elements
