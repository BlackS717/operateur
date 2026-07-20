

PRAGMA journal_mode = WAL;
PRAGMA foreign_keys = ON;
CREATE TABLE utilisateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero TEXT NOT NULL UNIQUE,
    date_creation TEXT NOT NULL DEFAULT (datetime('now'))
);
CREATE TABLE porte_feuille (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    utilisateur_id INTEGER NOT NULL UNIQUE REFERENCES utilisateur(id) ON DELETE CASCADE,
    solde REAL NOT NULL DEFAULT 0
);
CREATE TABLE type_transaction (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);
CREATE TABLE frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    minimum REAL,
    maximum REAL NOT NULL,
    valeur REAL NOT NULL
);
CREATE TABLE transaction (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    utilisateur_id INTEGER NOT NULL REFERENCES utilisateur(id),
    type_transaction_id INTEGER NOT NULL REFERENCES type_transaction(id),
    montant REAL NOT NULL,
    frais REAL NOT NULL,
    date_transaction TEXT NOT NULL DEFAULT (datetime('now'))
);