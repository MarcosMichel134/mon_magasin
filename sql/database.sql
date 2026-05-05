-- sql/database.sql

CREATE DATABASE IF NOT EXISTS magasin;
USE magasin;

-- table clients (aussi utilisée pour connexion)
CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- table articles
CREATE TABLE articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    quantite INT NOT NULL DEFAULT 0,
    categorie VARCHAR(100),
    prix DECIMAL(10,2) NOT NULL
);

-- table ventes
CREATE TABLE ventes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    date_vente DATETIME DEFAULT CURRENT_TIMESTAMP,
    montant_total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- table details_ventes (articles vendus dans chaque vente)
CREATE TABLE details_ventes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vente_id INT NOT NULL,
    article_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (vente_id) REFERENCES ventes(id),
    FOREIGN KEY (article_id) REFERENCES articles(id)
);
