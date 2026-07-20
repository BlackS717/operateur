

PRAGMA journal_mode = WAL;
PRAGMA foreign_keys = ON;

create table role(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);

CREATE TABLE utilisateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero TEXT NOT NULL UNIQUE,
    dateCreation TEXT NOT NULL DEFAULT (datetime('now')),
    roleId INTEGER REFERENCES role(id)
);


CREATE TABLE porteFeuille (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    utilisateurId INTEGER NOT NULL UNIQUE REFERENCES utilisateur(id) ON DELETE CASCADE,
    solde REAL NOT NULL DEFAULT 0
);
CREATE TABLE typeTransaction (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);
CREATE TABLE frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    minimum REAL,
    maximum REAL NOT NULL,
    valeur REAL NOT NULL,
    typeTransactionId INTEGER NOT NULL REFERENCES typeTransaction(id)
);
CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    utilisateurId INTEGER NOT NULL REFERENCES utilisateur(id),
    destinataireId INTEGER NOT NULL REFERENCES utilisateur(id),
    typeTransactionId INTEGER NOT NULL REFERENCES typeTransaction(id),
    montant REAL NOT NULL,
    frais REAL NOT NULL,
    dateTransaction TEXT NOT NULL DEFAULT (datetime('now'))
);

create table prefix(
    id BIGINT PRIMARY KEY AUTOINCREMENT,
    nom text NOT NULL UNIQUE
);