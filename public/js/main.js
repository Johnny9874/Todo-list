// Quand le DOM est prêt
document.addEventListener("DOMContentLoaded", function() {
    // Attacher la fonction addTask au formulaire
    document.getElementById('addTaskForm').addEventListener('submit', addTask);
});

function addTask(event) {
    event.preventDefault(); // Empêcher la soumission du formulaire par défaut

    const title = document.getElementById("title").value;
    const description = document.getElementById("description").value;
    const priority = document.getElementById("priority").value;
    const status = document.getElementById("status").value;
    const dueDate = document.getElementById("due_date").value;
    const listContainer = document.getElementById("list-container"); // Récupérer le conteneur de la liste des tâches

    const taskData = {
        title: title,
        description: description,
        priority: priority,
        status: status,
        due_date: dueDate
    };

    console.log("Données de la tâche:", taskData); // Afficher les données avant d'envoyer la requête

    fetch('/index.php?action=addTask', {
        method: 'POST',
        body: JSON.stringify(taskData),  // Envoyer les données réelles ici
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json()) // Convertir la réponse en JSON
    .then(data => {
        if (data.success) {
            alert('Tâche ajoutée avec succès!');
            console.log(data.task); // Vous devriez pouvoir voir l'objet task ici
            // Faites quelque chose avec la tâche, comme l'afficher dans l'interface utilisateur
        } else {
            alert('Erreur lors de l\'ajout de la tâche : ' + data.message);
        }
    })
    .catch(error => {
        console.log('Erreur dans la requête AJAX : ' + error);
    });    
}
    .then(response => response.json()) // Convertit la réponse en JSON
    .then(data => {
        if (data.success) {
            alert('Tâche ajoutée avec succès!');
            console.log(data.task); // Vous devriez pouvoir voir l'objet task ici
            // Faites quelque chose avec la tâche, comme l'afficher dans l'interface utilisateur
        } else {
            alert('Erreur lors de l\'ajout de la tâche : ' + data.message);
        }
    })
    .catch(error => {
        console.log('Erreur dans la requête AJAX : ' + error);
    });    
}

