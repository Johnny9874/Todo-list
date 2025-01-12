// Récupération des éléments du DOM
// Le DOM (Document Object Model) est comme une carte de votre page web. 
// Cela permet de trouver des éléments comme des formulaires, des boutons, des champs de texte, etc.

const form = document.querySelector("form"); // On trouve le formulaire dans la page (celui où tu écris ton nom, email, etc.)
const usernameInput = form.querySelector("input[name='username']"); // Trouve le champ pour le nom d'utilisateur
const emailInput = form.querySelector("input[name='email']"); // Trouve le champ pour l'email
const passwordInput = form.querySelector("input[name='password']"); // Trouve le champ pour le mot de passe
const submitButton = form.querySelector("button[type='submit']"); // Trouve le bouton pour soumettre le formulaire (celui sur lequel on clique pour envoyer les informations)

// Validation des champs avant soumission
// Avant d'envoyer les données du formulaire, on va vérifier si tout est bien rempli.

form.addEventListener("submit", (event) => {
  let isValid = true; // On commence par dire que tout est valide

  // Réinitialiser les messages d'erreur
  clearErrorMessages(); // On enlève les messages d'erreur s'il y en avait avant

  // Validation du champ Nom d'utilisateur
  if (usernameInput.value.trim() === "") { // Si le champ est vide
    showError(usernameInput, "Le nom d'utilisateur est requis."); // On affiche un message d'erreur sous le champ
    isValid = false; // Ce n'est pas valide, donc on dit que c'est incorrect
  }

  // Validation du champ Email
  if (!validateEmail(emailInput.value)) { // Si l'email n'est pas valide
    showError(emailInput, "Veuillez entrer une adresse email valide."); // On affiche un message d'erreur sous le champ
    isValid = false; // Ce n'est pas valide, donc on dit que c'est incorrect
  }

  // Validation du champ Mot de passe
  if (passwordInput.value.trim().length < 6) { // Si le mot de passe est trop court (moins de 6 caractères)
    showError(passwordInput, "Le mot de passe doit contenir au moins 6 caractères."); // On affiche un message d'erreur sous le champ
    isValid = false; // Ce n'est pas valide
  }

  // Empêcher la soumission si les champs ne sont pas valides
  if (!isValid) {
    event.preventDefault(); // Si ce n'est pas valide, on arrête l'envoi du formulaire
  }
});

// Validation de l'email avec une expression régulière
// Une expression régulière est comme une règle pour vérifier si quelque chose est correct. Ici, on l'utilise pour vérifier si l'email est au bon format.

function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Cette règle vérifie que l'email contient un '@' et un '.'
  return emailRegex.test(email); // Si l'email respecte la règle, cela retourne "true", sinon "false"
}

// Afficher un message d'erreur sous un champ
// Quand un champ n'est pas valide, on affiche un message pour expliquer pourquoi.

function showError(input, message) {
  const error = document.createElement("div"); // On crée un élément <div> pour afficher l'erreur
  error.classList.add("error-message"); // On lui donne une classe pour pouvoir le styliser
  error.textContent = message; // On ajoute le texte du message d'erreur
  input.classList.add("error"); // On ajoute une classe "error" au champ pour le mettre en évidence
  input.parentElement.insertBefore(error, input.nextSibling); // On ajoute le message d'erreur juste après le champ
}

// Supprimer tous les messages d'erreur
// Avant de valider les champs, on va supprimer les erreurs précédentes.

function clearErrorMessages() {
  const errors = document.querySelectorAll(".error-message"); // Trouve tous les messages d'erreur
  errors.forEach((error) => error.remove()); // Enlève chaque message d'erreur
  const inputs = document.querySelectorAll("input"); // Trouve tous les champs de saisie
  inputs.forEach((input) => input.classList.remove("error")); // Enlève la classe "error" des champs
}

// Ajouter un effet de focus sur les champs
// Quand tu cliques sur un champ, on lui donne un petit effet pour le mettre en valeur.

const inputs = document.querySelectorAll("input"); // Trouve tous les champs de saisie (nom, email, mot de passe)
inputs.forEach((input) => {
  input.addEventListener("focus", () => { // Quand tu cliques dans un champ
    input.classList.add("focused"); // On ajoute une classe "focused" au champ pour le styliser
  });
  input.addEventListener("blur", () => { // Quand tu quittes le champ (tu cliques ailleurs)
    input.classList.remove("focused"); // On enlève la classe "focused" du champ
  });
});
