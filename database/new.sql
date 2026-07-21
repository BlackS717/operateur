

PRAGMA journal_mode = WAL;
PRAGMA foreign_keys = ON;

CREATE TABLE utilisateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero TEXT NOT NULL UNIQUE,
    dateCreation TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    labelle TEXT NOT NULL UNIQUE,
    motDePasse TEXT NOT NULL,
    dateCreation TEXT NOT NULL DEFAULT (datetime('now'))
);

CREATE TABLE porteFeuille (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    utilisateurId INTEGER NOT NULL UNIQUE REFERENCES utilisateur(id) ON DELETE CASCADE,
    solde REAL NOT NULL DEFAULT 0,
    operateurId INTEGER NOT NULL REFERENCES operateur(id)
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

create table commission(
    id BIGINT PRIMARY KEY AUTOINCREMENT,
    source BIGINT NOT NULL REFERENCES operateur(id),
    destinataire BIGINT NOT NULL REFERENCES operateur(id),
    pourcentage REAL NOT NULL
  );

create table prefix(
    id BIGINT PRIMARY KEY AUTOINCREMENT,
    nom text NOT NULL UNIQUE,
    operateurId INTEGER NOT NULL REFERENCES operateur(id)
);

CREATE TABLE promotion(
    id int PRIMARY key AUTOINCREMENT,
    operateurId int,
    pourcentage real 
);

PRAGMA foreign_keys = ON;

--------------------------------------------------
-- OPERATEURS
--------------------------------------------------

INSERT INTO operateur(labelle) VALUES
('Airtel Money'),
('MVola'),
('Orange Money');

--------------------------------------------------
-- PREFIXES
--------------------------------------------------

INSERT INTO prefix(nom, operateurId) VALUES
('033', 1),
('032', 2),
('034', 3),
('037', 3),
('038', 2);

--------------------------------------------------
-- UTILISATEURS
--------------------------------------------------

INSERT INTO utilisateur(numero) VALUES
('0348041388'),
('0389299922'),
('0331256792'),
('0324567890'),
('0379988776');

--------------------------------------------------
-- PORTEFEUILLES
--------------------------------------------------

INSERT INTO porteFeuille(utilisateurId, solde, operateurId) VALUES
(1, 100000, 3),
(2, 75000, 2),
(3, 50000, 1),
(4, 150000, 2),
(5, 30000, 3);

--------------------------------------------------
-- TYPES DE TRANSACTION
--------------------------------------------------

INSERT INTO typeTransaction(nom) VALUES
('Depot'),
('Retrait'),
('Transfert');

--------------------------------------------------
-- FRAIS
--------------------------------------------------

INSERT INTO frais(minimum, maximum, valeur, typeTransactionId) VALUES
(100,1000,50,3),
(1001,5000,50,3),
(5001,10000,100,3),
(10001,25000,200,3),
(25001,50000,400,3),
(50001,100000,800,3),
(100001,250000,1500,3),
(250001,500000,1500,3),
(500001,1000000,2500,3),
(1000001,2000000,5000,3);

--------------------------------------------------
-- COMMISSIONS ENTRE OPERATEURS
--------------------------------------------------

INSERT INTO commission(source,destinataire,pourcentage) VALUES
(1,2,1.5),
(2,1,1.5),
(1,3,2.0),
(3,1,2.0),
(2,3,1.8),
(3,2,1.8);

--------------------------------------------------
-- TRANSACTIONS
--------------------------------------------------

INSERT INTO transactions
(utilisateurId,destinataireId,typeTransactionId,montant,frais)
VALUES
(1,2,3,10000,100),
(2,3,3,25000,200),
(3,1,3,5000,50),
(4,5,3,40000,400),
(5,2,3,15000,200),
(1,4,3,60000,800),
(2,5,3,8000,100),
(3,4,3,120000,1500);