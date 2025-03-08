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

    const taskData = {
        title: title,
        description: description,
        priority: priority,
        status: status,
        due_date: dueDate
    };

    // Vérifie que taskData contient les bonnes valeurs avant envoi
    console.log("Données de la tâche avant envoi:", taskData);

    fetch('/index.php?action=addTask', {
        method: 'POST',
        body: JSON.stringify(taskData),  // Assure-toi que les bonnes données sont envoyées
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json()) // Convertit la réponse en JSON
    .then(data => {
        if (data.success) {
            alert('Tâche ajoutée avec succès!');
            console.log("Tâche ajoutée:", data.task); // Voir l'objet task renvoyé
        } else {
            alert('Erreur lors de l\'ajout de la tâche : ' + data.message);
        }
    })
    .catch(error => {
        console.log('Erreur dans la requête AJAX : ' + error);
    });    
}

document.addEventListener("DOMContentLoaded", function() {
    // Récupérer les tâches de l'utilisateur via AJAX
    fetch('/index.php?action=getUserTasks')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTasks(data.tasks);
            } else {
                console.log("Erreur : " + data.message);
            }
        })
        .catch(error => {
            console.log('Erreur dans la requête AJAX : ' + error);
        });
});

// Fonction pour afficher les tâches
function displayTasks(tasks) {
    const taskList = document.getElementById('task-list');
    taskList.innerHTML = ''; // Vider la liste avant de la remplir

    tasks.forEach(task => {
        const listItem = document.createElement('li');
        listItem.textContent = `${task.title} - ${task.description} - ${task.due_date}`;
        taskList.appendChild(listItem);
    });
}
