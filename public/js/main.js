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

    console.log(taskData); // Afficher les données avant d'envoyer la requête

    fetch('/index.php?action=addTask', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(taskData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tâche ajoutée avec succès');
            // Ajouter la tâche à l'interface utilisateur si nécessaire
            // Créer un nouvel élément de tâche dans le DOM
            const newTask = document.createElement("li");
            newTask.textContent = data.task.title; // Utiliser le titre renvoyé par la réponse JSON
            listContainer.appendChild(newTask);
            } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(error => {
        alert('Erreur lors de l\'ajout de la tâche : ' + error.message);
    });
}
