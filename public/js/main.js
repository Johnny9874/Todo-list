const form = document.getElementById("addTaskForm");  // Formulaire pour ajouter une tâche

form.addEventListener("submit", function(event) {
    event.preventDefault();  // Empêche le formulaire de soumettre de manière traditionnelle

    const title = document.getElementById("title").value;
    const description = document.getElementById("description").value;
    const priority = document.getElementById("priority").value;
    const status = document.getElementById("status").value;
    const due_date = document.getElementById("due_date").value;

    const taskData = {
        title: title,
        description: description,
        priority: priority,
        status: status,
        due_date: due_date
    };

    // Envoie de la tâche au backend via AJAX
    fetch('/index.php?action=addTask', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(taskData)
    })
    .then(response => response.json())  // Récupérer la réponse JSON
    .then(data => {
        if (data.success) {
            alert("Tâche ajoutée avec succès");
            addTaskToDOM(data.task);  // Ajoute la tâche au DOM
        } else {
            alert("Erreur lors de l'ajout de la tâche");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur s\'est produite.');
    });
});

// Fonction pour ajouter une tâche au DOM
function addTaskToDOM(task) {
    const listContainer = document.getElementById("list-container");
    let li = document.createElement("li");
    li.textContent = task.title; // Affiche le titre de la tâche
    listContainer.appendChild(li);
}
