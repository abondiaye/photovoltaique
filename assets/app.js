// assets/app.js
// assets/app.js
import './background';

// Import de ton style principal (optionnel)
import './styles/app.scss';
import 'flatpickr/dist/flatpickr.css';
import flatpickr from 'flatpickr';
import './js/mountainBackground.js';


// active le sélecteur sur tous les inputs datetime
document.addEventListener('DOMContentLoaded', () => {
  const els = document.querySelectorAll('input[type="datetime-local"], input.flatpickr-datetime');
  els.forEach(el => {
    flatpickr(el, {
      enableTime: true,
      time_24hr: true,
      minuteIncrement: 15,
      dateFormat: 'Y-m-d H:i',
      locale: 'fr', // si tu as importé la locale fr
    });
  });
});




document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 Mon app JS est bien chargée !');
    
    const titre = document.createElement('h1');
    titre.textContent = 'Bienvenue sur mon site photovoltaïque ☀️';
    document.body.appendChild(titre);
});
