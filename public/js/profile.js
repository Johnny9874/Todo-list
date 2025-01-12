// Récupération des éléments du DOM
// Ici, on va chercher les éléments HTML (les champs du formulaire) afin de les manipuler avec JavaScript.

const form = document.querySelector("form"); // On trouve le formulaire dans la page (celui où tu écris ton nom, email, etc.)
const usernameInput = document.getElementById("username"); // On trouve le champ pour le nom d'utilisateur par son ID
const emailInput = document.getElementById("email"); // On trouve le champ pour l'email par son ID
const passwordInput = document.getElementById("password"); // On trouve le champ pour le mot de passe par son ID
const submitButton = document.querySelector("button[type='submit']"); // On trouve le bouton pour soumettre le formulaire

// Validation des champs avant soumission
// Avant d'envoyer les données du formulaire, on va vérifier si tout est bien rempli.

form.addEventListener("submit", (event) => {
  let isValid = true; // On commence par dire que tout est valide

  // Réinitialiser les messages d'erreur
  clearErrorMessages(); // On enlève les messages d'erreur s'il y en avait avant

  // Validation du champ Nom d'utilisateur
  if (usernameInput.value.trim() === "") { // Si le champ du nom d'utilisateur est vide
    showError(usernameInput, "Le nom d'utilisateur est requis."); // On affiche un message d'erreur sous le champ
    isValid = false; // Ce n'est pas valide, donc on dit que ce champ est incorrect
  }

  // Validation du champ Email
  if (!validateEmail(emailInput.value)) { // Si l'email n'est pas valide
    showError(emailInput, "Veuillez entrer une adresse email valide."); // On affiche un message d'erreur sous le champ
    isValid = false; // Ce n'est pas valide, donc on dit que ce champ est incorrect
  }

  // Validation du champ Mot de passe (optionnel)
  if (passwordInput.value.trim() !== "" && passwordInput.value.trim().length < 6) { 
    // Si le mot de passe n'est pas vide et qu'il est trop court (moins de 6 caractères)
    showError(passwordInput, "Le mot de passe doit contenir au moins 6 caractères."); // On affiche un message d'erreur sous le champ
    isValid = false; // Ce n'est pas valide, donc on dit que ce champ est incorrect
  }

  // Empêcher la soumission si les champs ne sont pas valides
  if (!isValid) {
    event.preventDefault(); // Si ce n'est pas valide, on arrête l'envoi du formulaire
  }
});

// Validation de l'email avec une expression régulière
// Une expression régulière est une sorte de règle ou de "modèle" qui nous aide à vérifier si l'email est au bon format.

function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Vérifie que l'email contient un "@" et un "."
  return emailRegex.test(email); // Si l'email respecte la règle, cela retourne "true", sinon "false"
}

// Afficher un message d'erreur sous un champ
// Quand un champ n'est pas valide, on affiche un message pour expliquer pourquoi.

function showError(input, message) {
  const error = document.createElement("div"); // On crée un nouvel élément <div> pour afficher le message d'erreur
  error.classList.add("error-message"); // On ajoute une classe "error-message" à ce nouvel élément pour le styliser
  error.textContent = message; // On met le texte du message d'erreur dans l'élément <div>
  input.classList.add("error"); // On ajoute la classe "error" au champ pour le mettre en évidence (par exemple, en rouge)
  input.parentElement.insertBefore(error, input.nextSibling); // On insère le message d'erreur juste après le champ de saisie
}

// Supprimer tous les messages d'erreur
// Cette fonction sert à enlever les anciens messages d'erreur avant de refaire la validation.

function clearErrorMessages() {
  const errors = document.querySelectorAll(".error-message"); // On trouve tous les messages d'erreur
  errors.forEach((error) => error.remove()); // On enlève chaque message d'erreur
  const inputs = document.querySelectorAll("input"); // Trouve tous les champs de saisie
  inputs.forEach((input) => input.classList.remove("error")); // Enlève la classe "error" des champs
}

// Ajouter un effet de focus sur les champs
// Quand tu cliques sur un champ, on lui ajoute un petit effet visuel pour le mettre en valeur.

const inputs = document.querySelectorAll("input"); // Trouve tous les champs de saisie (nom, email, mot de passe)
inputs.forEach((input) => {
  input.addEventListener("focus", () => { // Quand tu cliques dans un champ
    input.classList.add("focused"); // On ajoute une classe "focused" au champ pour le styliser
  });
  input.addEventListener("blur", () => { // Quand tu quittes le champ (tu cliques ailleurs)
    input.classList.remove("focused"); // On enlève la classe "focused" du champ
  });
});
