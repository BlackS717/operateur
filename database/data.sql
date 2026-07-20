PRAGMA foreign_keys = ON;

-- Roles
INSERT INTO role(nom) VALUES
('admin'),
('client');


-- Prefix
INSERT INTO prefix(nom) VALUES
('032'),
('033'),
('034'),
('037'),
('038');


-- Utilisateurs
INSERT INTO utilisateur(numero, roleId) VALUES
('0348041388', 1),
('0389299922', 2),
('0331256792', 2);


-- Portefeuilles
INSERT INTO porteFeuille(utilisateurId, solde) VALUES
(1, 50000),
(2, 15000),
(3, 7500);


-- Types de transactions
INSERT INTO typeTransaction(nom) VALUES
('Depot'),
('Retrait'),
('Transfert');


-- Frais
INSERT INTO frais(minimum, maximum, valeur) VALUES
(100, 1000, 50),
(1001, 5000, 50),
(5001, 10000, 100),
(10001, 25000, 200),
(25001, 50000, 400),
(50001, 100000, 800),
(100001, 250000, 1500),
(250001, 500000, 1500),
(500001, 1000000, 2500),
(1000001, 2000000, 5000);


-- Transactions
INSERT INTO transactions(
    utilisateurId,
    typeTransactionId,
    montant,
    frais
) VALUES
(1, 1, 50000, 400),
(1, 2, 10000, 100),
(2, 1, 25000, 200),
(2, 3, 5000, 50),
(3, 2, 3000, 50);