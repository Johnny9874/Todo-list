document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");  // Sélectionner le formulaire
  const usernameInput = document.getElementById("username");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");

  form.addEventListener("submit", function (event) {
      event.preventDefault();  // Empêcher la soumission du formulaire classique

      // Validation des champs
      let isValid = true;
      clearErrorMessages();  // Effacer les messages d'erreur précédents

      // Validation des champs (même validation que tu avais déjà)
      if (usernameInput.value.trim() === "") {
          showError(usernameInput, "Le nom d'utilisateur est requis.");
          isValid = false;
      }
      if (!validateEmail(emailInput.value)) {
          showError(emailInput, "Veuillez entrer une adresse email valide.");
          isValid = false;
      }
      if (passwordInput.value.trim() !== "" && passwordInput.value.trim().length < 6) {
          showError(passwordInput, "Le mot de passe doit contenir au moins 6 caractères.");
          isValid = false;
      }

      if (!isValid) {
          return;  // Si la validation échoue, on ne continue pas
      }

      // Si tout est valide, on crée un objet FormData
      const formData = new FormData(form);

      // Envoi des données via Fetch (requête asynchrone)
      fetch('/index.php?action=updateProfile', {
          method: 'POST',
          body: formData
      })
          .then(response => response.json())  // Le serveur doit renvoyer un JSON
          .then(data => {
              if (data.success) {
                  alert('Profil mis à jour avec succès!');
                  window.location.href = "/html/profile.html";  // Redirection vers la page profil
              } else {
                  alert('Erreur lors de la mise à jour : ' + data.message);
              }
          })
          .catch(error => {
              console.error('Erreur:', error);
              alert('Une erreur est survenue lors de la mise à jour du profil.');
          });
  });

  // Fonction de validation de l'email
  function validateEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
  }

  // Affichage des messages d'erreur
  function showError(input, message) {
      const error = document.createElement("div");
      error.classList.add("error-message");
      error.textContent = message;
      input.classList.add("error");
      input.parentElement.insertBefore(error, input.nextSibling);
  }

  // Effacer les messages d'erreur
  function clearErrorMessages() {
      const errors = document.querySelectorAll(".error-message");
      errors.forEach(error => error.remove());
      const inputs = document.querySelectorAll("input");
      inputs.forEach(input => input.classList.remove("error"));
  }
});
