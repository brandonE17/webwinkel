-- Webwinkel Database Schema

CREATE DATABASE IF NOT EXISTS webwinkel;
USE webwinkel;

-- Producten tabel
CREATE TABLE IF NOT EXISTS producten (
    id INT PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255) NOT NULL,
    prijs DECIMAL(10, 2) NOT NULL,
    categorie VARCHAR(100) NOT NULL,
    type ENUM('physical', 'digital', 'discount') NOT NULL DEFAULT 'physical',
    
    -- Voor fysieke producten
    gewicht DECIMAL(8, 2),
    
    -- Voor digitale producten
    bestandsgrootte INT,
    
    -- Voor discount producten
    korting_procent INT,
    
    beschrijving TEXT,
    actief BOOLEAN DEFAULT 1,
    aangemaakt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    bijgewerkt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Voorbeelddata invoegen
INSERT INTO producten (naam, prijs, categorie, type, gewicht, beschrijving, actief) VALUES
('PHP Boek', 49.99, 'Boeken', 'physical', 0.5, 'Leer PHP programmeren', 1),
('Gaming Mouse', 69.99, 'Hardware', 'physical', 0.3, 'Precisie muis voor gamers', 1),
('PHP Cursus', 79.99, 'Cursussen', 'digital', NULL, 'Online PHP cursus met video''s', 1),
('Web Bundle', 199.99, 'Bundels', 'discount', NULL, 'Complete web development bundle', 1);

UPDATE producten SET korting_procent = 15 WHERE naam = 'Web Bundle';
UPDATE producten SET bestandsgrootte = 250 WHERE naam = 'PHP Cursus';

-- Orders tabel (optioneel - voor persistente orderopslag)
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id VARCHAR(50) UNIQUE NOT NULL,
    voornaam VARCHAR(100) NOT NULL,
    achternaam VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefoon VARCHAR(20),
    adres VARCHAR(255) NOT NULL,
    postcode VARCHAR(10) NOT NULL,
    plaats VARCHAR(100) NOT NULL,
    subtotaal DECIMAL(10, 2) NOT NULL,
    verzendkosten DECIMAL(10, 2) NOT NULL,
    totaal DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'verwerkt',
    aangemaakt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order items tabel
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    hoeveelheid INT NOT NULL,
    prijs DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
); 
