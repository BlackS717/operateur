create database operateur;
use operateur;

create table utilisateur (
    id int primary key auto_increment,
    numero BIGINT,
    date_creation timestamp default current_timestamp
);

create table porte_feuille(
    id int auto_increment primary key,
    id_utilisateur int primary key,
    solde decimal(10,2) default 0
);

create table type_transaction(
    id int auto_increment primary key,
    nom varchar(50) not null,
);


create table frais(
    id int auto_increment primary key,
    minimum decimal(10,2) ,
    maximum decimal(10,2) not null,
    valeur decimal(10,2) not null,
);

CREATE TABLE transaction (
    id int auto_increment primary key,
    id_utilisateur int,
    id_type_transaction int,
    montant decimal(10,2) not null,
    frais decimal(10,2) not null,
    date_transaction timestamp default current_timestamp,
    foreign key (id_utilisateur) references utilisateur(id),
    foreign key (id_type_transaction) references type_transaction(id)
);
