create database IF NOT EXISTS BD;

use BD;

CREATE TABLE IF NOT EXISTS usuarios(
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome varchar(50),
    birthdate date NOT NULL,
    senha varchar(20)
)DEFAULT CHARSET=utf8 ;