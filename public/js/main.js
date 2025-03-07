// Fonction pour ajouter une tâche via fetch
function addTask() {
    const title = document.getElementById("title").value;
    const description = document.getElementById("description").value;
    const priority = document.getElementById("priority").value;
    const status = document.getElementById("status").value;
    const dueDate = document.getElementById("due_date").value;

    const taskData = {
        title: title,
        description: description,
        priority: priority,
        status: status,
        due_date: dueDate
    };

    fetch('/index.php?action=addTask', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(taskData) // Conversion en JSON avant d'envoyer
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tâche ajoutée avec succès');
            // Ajouter la tâche à l'interface utilisateur si nécessaire
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur lors de l\'ajout de la tâche : ' + error.message);
    });
}
