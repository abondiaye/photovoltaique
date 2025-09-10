document.addEventListener("DOMContentLoaded", () => {
  const suggestions = document.querySelectorAll(".chat-suggestions button");
  const messages = document.getElementById("robot-messages");

  function addMessage(text, prefix = "") {
    if (!messages) return;
    const msg = document.createElement("div");
    msg.textContent = `${prefix} ${text}`;
    messages.appendChild(msg);
    messages.scrollTop = messages.scrollHeight;
  }

  function botReply(q) {
    q = q.toLowerCase();
    if (q.includes("tarif") || q.includes("prix")) return "💰 Nos tarifs dépendent de l’intervention.";
    if (q.includes("délai")) return "⏳ En général, nous intervenons sous 48h.";
    if (q.includes("contact")) return "📞 Vous pouvez nous joindre au 01 23 45 67 89.";
    if (q.includes("garantie")) return "✅ Nos panneaux sont garantis 25 ans.";
    return "🤖 Je n’ai pas encore la réponse à ça.";
  }

  // === Suggestions (sécurité si pas trouvé)
  if (suggestions.length > 0) {
    suggestions.forEach(btn => {
      btn.addEventListener("click", () => {
        const question = btn.dataset.q;
        addMessage(question, "🙋");
        setTimeout(() => addMessage(botReply(question), "🤖"), 500);
      });
    });
  }

  // ===================
  // Effet hacking
  // ===================
  const textElem = document.querySelector(".hacking-animation__text");

  if (textElem) {
    const textString = `/* BornToHack&Crack */
{Je suis toujours là} /// -- \\\\Ama N'DIAYE\\\\>>> Je ne suis pas un robot...>>> ...mais j'y arrive bientôt.   Appelez moi ...
`;

let phraseIndex = 0;
let charIndex = 0;
let isDeleting = false;

function typeEffect() {
  const currentPhrase = phrases[phraseIndex];
  const visibleText = currentPhrase.substring(0, charIndex);

  textElem.innerHTML = `<span class="hacking-animation__character">${visibleText}</span><span class="cursor">_</span>`;

  if (!isDeleting) {
    if (charIndex < currentPhrase.length) {
      charIndex++;
      setTimeout(typeEffect, 80); // vitesse d’écriture
    } else {
      isDeleting = true;
      setTimeout(typeEffect, 1200); // pause avant effacement
    }
  } else {
    if (charIndex > 0) {
      charIndex--;
      setTimeout(typeEffect, 50); // vitesse d’effacement
    } else {
      isDeleting = false;
      phraseIndex = (phraseIndex + 1) % phrases.length; // boucle
      setTimeout(typeEffect, 400);
    }
  }
}

typeEffect();
}
});