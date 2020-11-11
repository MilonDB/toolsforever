CREATE DATABASE IF NOT EXISTS toolsforever;

USE toolsforever;

CREATE TABLE medewerker(
    medewerkerscode INT NOT NULL AUTO_INCREMENT,
    voorletters VARCHAR(255) NOT NULL,
    voorvoegsels VARCHAR(255),
    achternaam VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY(medewerkerscode)
);

CREATE TABLE fabriek(
    fabriekscode INT NOT NULL AUTO_INCREMENT,
    fabriek VARCHAR(255) NOT NULL,
    telefoon VARCHAR(255) NOT NULL,
    PRIMARY KEY(fabriekscode)
);

CREATE TABLE artikel(
    productcode INT NOT NULL AUTO_INCREMENT,
    product VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    fabriekscode INT,
    inkoopprijs DECIMAL(2),
    verkoopprijs DECIMAL(2),
    PRIMARY KEY(productcode),
    FOREIGN KEY(fabriekscode) REFERENCES fabriek(fabriekscode)
);

CREATE TABLE locatie(
    locatiecode INT NOT NULL AUTO_INCREMENT,
    locatie VARCHAR(255) NOT NULL,
    PRIMARY KEY(locatiecode)
);

CREATE TABLE voorraad(
    locatiecode INT NOT NULL,
    productcode INT NOT NULL,
    aantal INT NOT NULL,
    FOREIGN KEY(locatiecode) REFERENCES locatie(locatiecode),
    FOREIGN KEY (productcode) REFERENCES artikel(productcode)
);

