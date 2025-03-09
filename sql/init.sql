-- =====================================
-- 📌 Fichier : init.sql
-- 📌 Description : Stockage des scripts SQL (référence uniquement)
-- =====================================

-- TABLE USERS (EXEMPLE - NE PAS EXECUTER DIRECTEMENT)
CREATE TABLE users_reference (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- TABLE TASKS (EXEMPLE - NE PAS EXECUTER DIRECTEMENT)
CREATE TABLE tasks_reference (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATETIME DEFAULT NULL,
    status ENUM('pending','in_progress','completed') DEFAULT 'pending',
    priority ENUM('low','medium','high') DEFAULT 'medium',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    task_data JSON DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users_reference(id) ON DELETE CASCADE
);

-- TABLE TODOS (EXEMPLE - NE PAS EXECUTER DIRECTEMENT)
CREATE TABLE todos_reference (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    completed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users_reference(id) ON DELETE CASCADE
);

-- =====================================
-- 📌 Insertion de données de test (Référence)
-- =====================================

-- Insertion d’utilisateurs (exemple)
INSERT INTO users_reference (username, email, password) VALUES
('Alice', 'alice@example.com', 'hashedpassword1'),
('Bob', 'bob@example.com', 'hashedpassword2'),
('Charlie', 'charlie@example.com', 'hashedpassword3');

-- Insertion de tâches (exemple)
INSERT INTO tasks_reference (user_id, title, description, due_date, status, priority) VALUES
(1, 'Faire les courses', 'Acheter du lait et du pain', '2024-03-15 10:00:00', 'pending', 'high'),
(1, 'Répondre aux emails', 'Vérifier la boîte mail', '2024-03-10 12:00:00', 'in_progress', 'medium'),
(2, 'Faire du sport', 'Courir 5km', '2024-03-20 18:00:00', 'pending', 'high');

-- Insertion de todos (exemple)
INSERT INTO todos_reference (user_id, title, description, completed) VALUES
(1, 'Lire un livre', 'Lire 50 pages du roman', 0),
(2, 'Nettoyer la maison', 'Passer l’aspirateur et laver le sol', 1),
(3, 'Préparer un rapport', 'Rédiger et envoyer le rapport de projet', 0);

-- =====================================
-- 📌 Fin du fichier init.sql (Référence uniquement)
-- =====================================
