DROP DATABASE IF EXISTS dolphin_crm;
CREATE DATABASE dolphin_crm;
USE dolphin_crm;


DROP TABLE IF EXISTS Users;
CREATE TABLE 'Users' (
    'id' INTEGER PRIMARY KEY,
    'firstname' VARCHAR NOT NULL DEFAULT '',
    'lastname' VARCHAR NOT NULL DEFAULT '',
    'password' VARCHAR NOT NULL,
    'email' VARCHAR NOT NULL UNIQUE,
    'role' VARCHAR DEFAULT 'user',
    'created_at' DATETIME DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS Contacts;
CREATE TABLE 'Contacts' (
    'id' INTEGER PRIMARY KEY,
    'title' VARCHAR NOT NULL,
    'firstname' VARCHAR NOT NULL DEFAULT '',
    'lastname' VARCHAR NOT NULL DEFAULT '',
    'email' VARCHAR NOT NULL UNIQUE,
    'telephone' VARCHAR,
    'company' VARCHAR,
    'type' VARCHAR NOT NULL, -- (whether Sales Lead or Support)
    'assigned_to' INTEGER, -- (store the aprropriate user id)
    'created_by' INTEGER, -- (store the appropriate user id)
    'created_at' DATETIME DEFAULT CURRENT_TIMESTAMP,
    'updated_at' DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES Users(id),
    FOREIGN KEY (created_by) REFERENCES Users(id)
)

DROP TABLE IF EXISTS Notes;
CREATE TABLE 'Notes' (
    'id' INTEGER PRIMARY KEY,
    'contact_id' INTEGER NOT NULL,
    'comment' TEXT NOT NULL,
    'created_by' INTEGER, -- (store the appropriate user id)
    'created_at' DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES Contacts(id),
    FOREIGN KEY (created_by) REFERENCES Users(id)
)

INSERT INTO 'Users' (email, password)
VALUES ('admin@project2.com', SHA2('password123', 512));