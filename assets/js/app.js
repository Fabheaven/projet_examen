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


document.addEventListener("DOMContentLoaded", function() {

  const passwordInput = document.getElementById("password");
  const strengthBar = document.getElementById("password-strength-bar");
  const strengthText = document.getElementById("password-strength-text");

  if (!passwordInput || !strengthBar || !strengthText) {
      console.error("Un ou plusieurs éléments HTML sont manquants !");
      return;
  }

  console.log("Éléments trouvés :", passwordInput, strengthBar, strengthText);

  const checkPasswordStrength = (password) => {
      let strength = 0;
      const patterns = [
          { pattern: /[a-z]/, weight: 1 }, // lowercase letter
          { pattern: /[A-Z]/, weight: 1 }, // uppercase letter
          { pattern: /[0-9]/, weight: 1 }, // number
          { pattern: /[!@#$%^&*(),.?":{}|<>]/, weight: 1 }, // special character
          { pattern: /.{8,}/, weight: 2 } // minimum length of 8 characters
      ];

      patterns.forEach(pattern => {
          if (pattern.pattern.test(password)) {
              strength += pattern.weight;
          }
      });

      strength = Math.min(strength, 5);
      updateStrengthBar(strength);
  };

  const updateStrengthBar = (strength) => {
      let width = 0;
      let color = "";
      let strengthLevel = "";

      if (strength <= 1) {
          color = "bg-red-500";
          strengthLevel = "Très faible";
      } else if (strength === 2) {
          color = "bg-yellow-500";
          strengthLevel = "Faible";
      } else if (strength === 3) {
          color = "bg-blue-500";
          strengthLevel = "Moyenne";
      } else if (strength === 4) {
          color = "bg-green-500";
          strengthLevel = "Bonne";
      } else if (strength >= 5) {
          color = "bg-green-700";
          strengthLevel = "Excellente";
      }

      width = (strength / 5) * 100;
      strengthBar.style.width = `${width}%`;
      strengthBar.className = `h-2.5 rounded-full transition-all duration-300 ${color}`;
      strengthText.textContent = `Force du mot de passe : ${strengthLevel}`;
  };

  const debounce = (func, delay) => {
      let timeout;
      return function(...args) {
          clearTimeout(timeout);
          timeout = setTimeout(() => func.apply(this, args), delay);
      };
  };

  passwordInput.addEventListener("input", debounce(function() {
      checkPasswordStrength(passwordInput.value);
  }, 300));
});


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


// carousel
document.addEventListener("DOMContentLoaded", function () {
  const carousel = document.querySelector('#carouselExampleCaptions');
  
  // Vérification si le carousel existe
  if (!carousel) {
    console.error("Carousel not found!");
    return;
  }

  const items = carousel.querySelectorAll('[data-twe-carousel-item]');
  const indicators = carousel.querySelectorAll('[data-twe-carousel-indicators] button');
  const prevButton = carousel.querySelector('[data-twe-slide="prev"]');
  const nextButton = carousel.querySelector('[data-twe-slide="next"]');
  
  let currentIndex = 0;

  // Fonction pour changer le slide actif
  function changeSlide(index) {
    // Masquer l'élément actuel avec une animation
    items[currentIndex].classList.add('page-turn-out');
    indicators[currentIndex].classList.remove('opacity-100');
    indicators[currentIndex].classList.add('opacity-20');

    // Afficher le nouvel élément avec une animation
    currentIndex = (index + items.length) % items.length; // pour assurer une boucle circulaire
    items[currentIndex].classList.remove('hidden', 'page-turn-out');
    items[currentIndex].classList.add('page-turn-in');
    indicators[currentIndex].classList.remove('opacity-20');
    indicators[currentIndex].classList.add('opacity-100');

    // Retirer l'animation après qu'elle soit terminée
    items[currentIndex].addEventListener('animationend', () => {
      items[currentIndex].classList.remove('page-turn-in');
    }, { once: true });
  }

  // Gérer le clic sur les boutons d'indicateur
  indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
      changeSlide(index);
    });
  });

  // Gérer le bouton précédent
  if (prevButton) {
    prevButton.addEventListener('click', () => {
      changeSlide(currentIndex - 1);
    });
  }

  // Gérer le bouton suivant
  if (nextButton) {
    nextButton.addEventListener('click', () => {
      changeSlide(currentIndex + 1);
    });
  }


  // Initialiser le carousel
  changeSlide(currentIndex);

  // Optionnel : Faire avancer automatiquement le carousel toutes les 5 secondes
  setInterval(() => {
    changeSlide(currentIndex + 1);
  }, 5000);
});



// code js du bouton de recherche
// Initialization for ES Users
import {
  Ripple,
  Input,
  initTWE,
} from "tw-elements";

initTWE({ Ripple, Input });

const searchFocus = document.getElementById('search-focus');
const keys = [
  { keyCode: 'AltLeft', isTriggered: false },
  { keyCode: 'ControlLeft', isTriggered: false },
];

window.addEventListener('keydown', (e) => {
  keys.forEach((obj) => {
    if (obj.keyCode === e.code) {
      obj.isTriggered = true;
    }
  });

  const shortcutTriggered = keys.filter((obj) => obj.isTriggered).length === keys.length;

  if (shortcutTriggered) {
    searchFocus.focus();
  }
});

window.addEventListener('keyup', (e) => {
  keys.forEach((obj) => {
    if (obj.keyCode === e.code) {
      obj.isTriggered = false;
    }
  });
});


// script video carousel


te.initTWE(); // Initialisation de tw-elements
