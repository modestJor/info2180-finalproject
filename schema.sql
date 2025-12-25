CREATE DATABASE IF NOT EXISTS dolphin_crm;
USE dolphin_crm;

-- Users Table
DROP TABLE IF EXISTS Users;
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL DEFAULT '',
    lastname VARCHAR(255) NOT NULL DEFAULT '',
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(50) DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Contacts Table
DROP TABLE IF EXISTS Contacts;
CREATE TABLE Contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    firstname VARCHAR(255) NOT NULL DEFAULT '',
    lastname VARCHAR(255) NOT NULL DEFAULT '',
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    company VARCHAR(255),
    type VARCHAR(50) NOT NULL,
    assigned_to INT,
    created_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES Users(id),
    FOREIGN KEY (created_by) REFERENCES Users(id)
);

-- Notes Table
DROP TABLE IF EXISTS Notes;
CREATE TABLE Notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_by INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES Contacts(id),
    FOREIGN KEY (created_by) REFERENCES Users(id)
);

INSERT INTO Users (firstname, lastname, password, email, role) 
VALUES ('Admin', 'User', '$2y$10$NGQjMWI5ZTEzOGVlYjBjOeBz8fLX9W7pZ8JkFmY6nHp5qRsTvXw1C', 'admin@project2.com', 'Admin');