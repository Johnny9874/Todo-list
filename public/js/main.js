function addTask() {
    const title = inputBox.value;
    const description = document.getElementById("description").value;
    const priority = document.getElementById("priority").value;
    const status = document.getElementById("status").value;
    const due_date = document.getElementById("due_date").value;

    const taskData = {
        title: title,
        description: description,
        priority: priority,
        status: status,
        due_date: due_date,
        task_data: { priority, status, due_date }  // Les données supplémentaires qui seront stockées en JSON
    };

    // Envoi via fetch
    fetch('/src/controllers/TaskController.php?action=addTask', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(taskData)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Task added:', data);
        showTasks();  // Rafraîchit la liste des tâches
    })
    .catch(error => {
        console.error('Error adding task:', error);
    });
}
