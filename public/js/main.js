// Récupération des éléments du DOM (Document Object Model)
const inputBox = document.getElementById("input-box"); // On récupère la zone de saisie où l'utilisateur entre la tâche à ajouter
const listContainer = document.getElementById("list-container"); // On récupère le conteneur de la liste des tâches

// Fonction pour ajouter une tâche à la liste
function addTask() {
    // Vérification si la zone de saisie est vide
    if(inputBox.value === '') {
        alert("Please enter a value"); // Si elle est vide, on montre une alerte pour dire à l'utilisateur d'entrer une tâche
    }
    else {
        // Si la zone de saisie n'est pas vide, on crée un nouvel élément de liste <li>
        let li = document.createElement("li");
        li.innerHTML = inputBox.value; // On met dans le nouvel élément <li> le texte de la tâche
        listContainer.appendChild(li); // On ajoute le nouvel élément <li> dans le conteneur de la liste

        // Création d'un bouton pour supprimer la tâche
        let span = document.createElement("span");
        span.innerHTML = "\u00d7"; // Le bouton "x" pour supprimer la tâche
        li.appendChild(span); // On ajoute le bouton "x" à l'élément <li>
    }

    inputBox.value = ''; // On vide la zone de saisie après avoir ajouté la tâche
    saveData(); // On enregistre la liste des tâches dans le stockage local (localStorage)
}

// Ajout d'un événement qui permet d'interagir avec les tâches (marquer comme terminée ou supprimer)
listContainer.addEventListener("click", function(e) {
    // Si on clique sur une tâche (élément <li>), on la marque comme terminée en changeant son apparence
    if(e.target.tagName === "LI") {
        e.target.classList.toggle("checked"); // On alterne la classe "checked" sur l'élément <li> pour le marquer comme terminé ou non
        saveData(); // On enregistre la liste après modification
    }
    // Si on clique sur le bouton "x" (élément <span>), on supprime la tâche
    else if(e.target.tagName === "SPAN") {
        e.target.parentElement.remove(); // On enlève l'élément <li> du conteneur (qui est l'élément parent du bouton "x")
        saveData(); // On enregistre la liste après suppression
    }
}, false); // false indique que l'événement se déclenche lors de la phase de propagation, après la phase de capture

// Fonction pour enregistrer les données de la liste dans le stockage local (localStorage)
function saveData() {
    // On enregistre le contenu HTML du conteneur de la liste dans le stockage local sous la clé "data"
    localStorage.setItem("data", listContainer.innerHTML);
}

// Fonction pour afficher les tâches enregistrées depuis le stockage local
function showTask() {
    // On récupère et affiche les données de la liste stockées dans le stockage local
    listContainer.innerHTML = localStorage.getItem("data");
}

// Appel de la fonction showTask au début pour afficher les tâches sauvegardées lorsque la page se charge
showTask();
