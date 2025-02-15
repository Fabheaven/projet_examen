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


// Carousel

document.addEventListener("DOMContentLoaded", function () {
  const carousel = document.querySelector('#carouselExampleCaptions');
  const items = carousel.querySelectorAll('[data-twe-carousel-item]');
  const indicators = carousel.querySelectorAll('[data-twe-carousel-indicators] button');
  const prevButton = carousel.querySelector('[data-twe-slide="prev"]');
  const nextButton = carousel.querySelector('[data-twe-slide="next"]');

  let currentIndex = 0;
  
  // Fonction pour changer le slide actif
  function changeSlide(index) {
    // Masquer l'élément actuel
    items[currentIndex].classList.add('hidden');
    indicators[currentIndex].classList.remove('opacity-100');
    indicators[currentIndex].classList.add('opacity-20');
    
    // Afficher le nouvel élément
    currentIndex = (index + items.length) % items.length; // pour assurer une boucle circulaire
    items[currentIndex].classList.remove('hidden');
    indicators[currentIndex].classList.remove('opacity-20');
    indicators[currentIndex].classList.add('opacity-100');
  }

  // Gérer le clic sur les boutons d'indicateur
  indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
      changeSlide(index);
    });
  });

  // Gérer le bouton précédent
  prevButton.addEventListener('click', () => {
    changeSlide(currentIndex - 1);
  });

  // Gérer le bouton suivant
  nextButton.addEventListener('click', () => {
    changeSlide(currentIndex + 1);
  });

  // Initialiser le carousel
  changeSlide(currentIndex);

  // Optionnel : Faire avancer automatiquement le carousel toutes les 5 secondes
  setInterval(() => {
    changeSlide(currentIndex + 1);
  }, 5000);
});



te.initTWE(); // Initialisation de tw-elements
