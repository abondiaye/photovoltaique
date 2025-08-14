// assets/app.js

// Import de ton style principal (optionnel)
import './styles/app.scss';


// Ton code JS
document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 Mon app JS est bien chargée !');
    
    const titre = document.createElement('h1');
    titre.textContent = 'Bienvenue sur mon site photovoltaïque ☀️';
    document.body.appendChild(titre);
});
