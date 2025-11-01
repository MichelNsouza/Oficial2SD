-- ================================================
-- Script de criação do banco e dados iniciais
-- Banco: pedidos_online_db
-- ================================================

-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS pedidos_online_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE pedidos_online_db;

-- ================================================
-- TABELA: usuarios
-- ================================================
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha CHAR(40) NOT NULL, -- SHA1 gera 40 caracteres
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Inserir usuário padrão (Miguel)
-- Senha original: 123456
INSERT INTO usuarios (nome, email, senha)
VALUES ('Miguel', 'miguel@empresa.com', SHA1('123456'));

-- ================================================
-- TABELA: vendas
-- ================================================
DROP TABLE IF EXISTS vendas;

CREATE TABLE vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendedor VARCHAR(100) NOT NULL,
    cliente VARCHAR(100) NOT NULL,
    unidade ENUM('Salvador', 'Feira de Santana', 'Lauro de Freitas') NOT NULL,
    valor_venda DECIMAL(10,2) NOT NULL,
    data_venda TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);
-- ================================================
-- Inserção de 60 vendas com maior variedade
-- 30 vendas em 2024 e 30 vendas em 2025
-- ================================================

-- VENDAS DE 2024
INSERT INTO vendas (vendedor, cliente, unidade, valor_venda, data_venda) VALUES
-- Janeiro 2024
('Carlos Souza', 'João Lima', 'Salvador', 1200.50, '2024-01-05 10:20:00'),
('Ana Paula', 'Fernanda Rocha', 'Feira de Santana', 980.75, '2024-01-15 14:30:00'),
('Marcos Silva', 'Cláudio Oliveira', 'Lauro de Freitas', 2430.00, '2024-01-22 11:10:00'),

-- Fevereiro 2024
('Bruna Costa', 'Marina Santos', 'Salvador', 1500.90, '2024-02-08 09:45:00'),
('Felipe Almeida', 'Ricardo Souza', 'Feira de Santana', 3220.00, '2024-02-14 17:00:00'),
('Joana Pereira', 'Rita Campos', 'Lauro de Freitas', 875.50, '2024-02-25 08:40:00'),

-- Março 2024
('Rafael Santos', 'Gabriel Teixeira', 'Salvador', 1890.25, '2024-03-04 10:00:00'),
('Juliana Lima', 'Aline Costa', 'Feira de Santana', 4050.00, '2024-03-16 13:15:00'),
('Pedro Nunes', 'Bruno Moreira', 'Lauro de Freitas', 560.00, '2024-03-29 15:00:00'),

-- Abril 2024
('Patrícia Souza', 'Daniela Ramos', 'Salvador', 2275.40, '2024-04-10 16:50:00'),
('Marcos Silva', 'José Alves', 'Feira de Santana', 1950.00, '2024-04-18 12:00:00'),
('Carlos Souza', 'Amanda Gomes', 'Lauro de Freitas', 640.00, '2024-04-27 10:00:00'),

-- Maio 2024
('Ana Paula', 'Lucas Rocha', 'Salvador', 3150.00, '2024-05-08 11:30:00'),
('Felipe Almeida', 'Márcia Nogueira', 'Feira de Santana', 1290.00, '2024-05-19 09:15:00'),
('Bruna Costa', 'Tiago Melo', 'Lauro de Freitas', 780.50, '2024-05-30 14:00:00'),

-- Junho 2024
('Rafael Santos', 'Carolina Dias', 'Salvador', 2100.00, '2024-06-07 08:30:00'),
('Joana Pereira', 'Paulo César', 'Feira de Santana', 990.75, '2024-06-15 16:45:00'),
('Juliana Lima', 'Renata Borges', 'Lauro de Freitas', 1450.00, '2024-06-28 10:20:00'),

-- Julho 2024
('Pedro Nunes', 'Eduardo Santos', 'Salvador', 3500.00, '2024-07-05 13:00:00'),
('Patrícia Souza', 'Luciana Martins', 'Feira de Santana', 820.00, '2024-07-18 11:15:00'),
('Carlos Souza', 'Rodrigo Pinto', 'Lauro de Freitas', 1670.00, '2024-07-26 15:30:00'),

-- Agosto 2024
('Marcos Silva', 'Beatriz Cunha', 'Salvador', 2890.00, '2024-08-09 09:00:00'),
('Ana Paula', 'Thiago Ferreira', 'Feira de Santana', 1120.50, '2024-08-20 14:20:00'),
('Felipe Almeida', 'Camila Reis', 'Lauro de Freitas', 450.00, '2024-08-31 16:00:00'),

-- Setembro 2024
('Bruna Costa', 'Vinícius Lopes', 'Salvador', 1980.00, '2024-09-10 10:30:00'),
('Rafael Santos', 'Larissa Mendes', 'Feira de Santana', 2340.00, '2024-09-22 12:45:00'),
('Joana Pereira', 'André Barros', 'Lauro de Freitas', 890.00, '2024-09-29 08:15:00'),

-- Outubro 2024
('Juliana Lima', 'Priscila Neves', 'Salvador', 1560.00, '2024-10-08 15:00:00'),
('Pedro Nunes', 'Fábio Cardoso', 'Feira de Santana', 3100.00, '2024-10-19 11:00:00'),
('Patrícia Souza', 'Isabela Duarte', 'Lauro de Freitas', 720.00, '2024-10-30 13:30:00'),

-- VENDAS DE 2025
-- Janeiro 2025
('Carlos Souza', 'João Lima', 'Salvador', 2100.00, '2025-01-07 10:00:00'),
('Ana Paula', 'Fernanda Rocha', 'Feira de Santana', 1850.00, '2025-01-18 14:00:00'),
('Marcos Silva', 'Cláudio Oliveira', 'Lauro de Freitas', 3200.00, '2025-01-28 11:10:00'),

-- Fevereiro 2025
('Bruna Costa', 'Marina Santos', 'Salvador', 970.00, '2025-02-05 09:45:00'),
('Felipe Almeida', 'Ricardo Souza', 'Feira de Santana', 4230.00, '2025-02-14 17:00:00'),
('Joana Pereira', 'Rita Campos', 'Lauro de Freitas', 1455.00, '2025-02-23 08:40:00'),

-- Março 2025
('Rafael Santos', 'Gabriel Teixeira', 'Salvador', 2980.00, '2025-03-06 10:00:00'),
('Juliana Lima', 'Aline Costa', 'Feira de Santana', 1920.00, '2025-03-17 13:15:00'),
('Pedro Nunes', 'Bruno Moreira', 'Lauro de Freitas', 850.00, '2025-03-29 15:00:00'),

-- Abril 2025
('Patrícia Souza', 'Daniela Ramos', 'Salvador', 3330.00, '2025-04-09 16:50:00'),
('Marcos Silva', 'José Alves', 'Feira de Santana', 1750.00, '2025-04-20 12:00:00'),
('Carlos Souza', 'Amanda Gomes', 'Lauro de Freitas', 680.00, '2025-04-28 10:00:00'),

-- Maio 2025
('Ana Paula', 'Lucas Rocha', 'Salvador', 2750.00, '2025-05-10 11:30:00'),
('Felipe Almeida', 'Márcia Nogueira', 'Feira de Santana', 1580.00, '2025-05-21 09:15:00'),
('Bruna Costa', 'Tiago Melo', 'Lauro de Freitas', 4450.00, '2025-05-30 14:00:00'),

-- Junho 2025
('Rafael Santos', 'Carolina Dias', 'Salvador', 1100.00, '2025-06-08 08:30:00'),
('Joana Pereira', 'Paulo César', 'Feira de Santana', 2990.75, '2025-06-18 16:45:00'),
('Juliana Lima', 'Renata Borges', 'Lauro de Freitas', 1650.00, '2025-06-27 10:20:00'),

-- Julho 2025
('Pedro Nunes', 'Eduardo Santos', 'Salvador', 3700.00, '2025-07-07 13:00:00'),
('Patrícia Souza', 'Luciana Martins', 'Feira de Santana', 920.00, '2025-07-19 11:15:00'),
('Carlos Souza', 'Rodrigo Pinto', 'Lauro de Freitas', 2670.00, '2025-07-28 15:30:00'),

-- Agosto 2025
('Marcos Silva', 'Beatriz Cunha', 'Salvador', 1890.00, '2025-08-10 09:00:00'),
('Ana Paula', 'Thiago Ferreira', 'Feira de Santana', 3120.50, '2025-08-22 14:20:00'),
('Felipe Almeida', 'Camila Reis', 'Lauro de Freitas', 1450.00, '2025-08-31 16:00:00'),

-- Setembro 2025
('Bruna Costa', 'Vinícius Lopes', 'Salvador', 2180.00, '2025-09-11 10:30:00'),
('Rafael Santos', 'Larissa Mendes', 'Feira de Santana', 1340.00, '2025-09-23 12:45:00'),
('Joana Pereira', 'André Barros', 'Lauro de Freitas', 2890.00, '2025-09-30 08:15:00'),

-- Outubro 2025
('Juliana Lima', 'Priscila Neves', 'Salvador', 1760.00, '2025-10-09 15:00:00'),
('Pedro Nunes', 'Fábio Cardoso', 'Feira de Santana', 4100.00, '2025-10-20 11:00:00'),
('Patrícia Souza', 'Isabela Duarte', 'Lauro de Freitas', 1920.00, '2025-10-31 13:30:00');