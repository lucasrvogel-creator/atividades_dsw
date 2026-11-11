-- Cria o banco de dados da atividade
CREATE DATABASE IF NOT EXISTS linktree
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE linktree;

-- Guarda os links de cada pessoa
CREATE TABLE IF NOT EXISTS links (
    url VARCHAR(255) NOT NULL PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    apelido VARCHAR(50) NOT NULL
);

-- Guarda o acesso do administrador
CREATE TABLE IF NOT EXISTS administradores (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);
