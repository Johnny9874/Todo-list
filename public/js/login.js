// Récupération des éléments du DOM (Document Object Model)
// Nous sélectionnons les éléments du formulaire en utilisant des sélecteurs CSS.
const form = document.querySelector("form"); // Récupère l'élément <form> dans le document
const usernameInput = document.querySelector("input[name='username']"); // Récupère le champ 'Nom d'utilisateur'
const emailInput = document.querySelector("input[name='email']"); // Récupère le champ 'Email'
const passwordInput = document.querySelector("input[name='password']"); // Récupère le champ 'Mot de passe'
const submitButton = document.querySelector("button[type='submit']"); // Récupère le bouton de soumission du formulaire

// Validation des champs avant soumission
// Nous ajoutons un écouteur d'événements pour l'événement 'submit' du formulaire.
form.addEventListener("submit", (event) => {
  let isValid = true; // On initialise une variable qui va vérifier si le formulaire est valide

  // Réinitialiser les messages d'erreur (si un message d'erreur est affiché précédemment, on le supprime)
  clearErrorMessages();

  // Vérification du champ Nom d'utilisateur
  if (usernameInput.value.trim() === "") {
    showError(usernameInput, "Le nom d'utilisateur est requis."); // Si vide, afficher un message d'erreur
    isValid = false; // Le formulaire devient invalide
  }

  // Vérification du champ Email
  if (!validateEmail(emailInput.value)) {
    showError(emailInput, "Veuillez entrer une adresse email valide."); // Si email invalide, afficher un message d'erreur
    isValid = false; // Le formulaire devient invalide
  }

  // Vérification du champ Mot de passe
  if (passwordInput.value.trim().length < 6) {
    showError(passwordInput, "Le mot de passe doit contenir au moins 6 caractères."); // Si mot de passe trop court, afficher un message d'erreur
    isValid = false; // Le formulaire devient invalide
  }

  // Empêcher la soumission si les champs ne sont pas valides
  if (!isValid) {
    event.preventDefault(); // Empêche l'envoi du formulaire si une erreur est présente
  }
});

// Fonction pour valider l'email avec une expression régulière
function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Définition d'une expression régulière pour vérifier la validité de l'email
  return emailRegex.test(email); // Retourne true si l'email est valide, sinon false
}

// Fonction pour afficher un message d'erreur sous un champ de saisie
function showError(input, message) {
  const error = document.createElement("div"); // Crée un nouvel élément <div> pour afficher le message d'erreur
  error.classList.add("error-message"); // Ajoute la classe CSS 'error-message' pour le style
  error.textContent = message; // Définit le texte du message d'erreur
  input.classList.add("error"); // Ajoute la classe CSS 'error' au champ de saisie (pour le styliser en rouge, par exemple)
  input.parentElement.appendChild(error); // Ajoute l'élément <div> du message d'erreur après l'input dans le DOM
}

// Fonction pour supprimer tous les messages d'erreur
function clearErrorMessages() {
  const errors = document.querySelectorAll(".error-message"); // Sélectionne tous les messages d'erreur présents
  errors.forEach((error) => error.remove()); // Supprime chaque message d'erreur
  const inputs = document.querySelectorAll("input"); // Sélectionne tous les champs de saisie
  inputs.forEach((input) => input.classList.remove("error")); // Supprime la classe 'error' de chaque champ de saisie
}

// Ajouter un effet visuel (focus) sur les champs de saisie
const inputs = document.querySelectorAll("input"); // Sélectionne tous les champs <input>
inputs.forEach((input) => {
  input.addEventListener("focus", () => {
    input.classList.add("focused"); // Lorsque le champ reçoit le focus, ajoute la classe 'focused' pour changer son apparence
  });
  input.addEventListener("blur", () => {
    input.classList.remove("focused"); // Lorsque le champ perd le focus, retire la classe 'focused' pour réinitialiser son apparence
  });
});
