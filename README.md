# Oficial 2 Sistemas Distribuidos

# Sistema de Relatórios

Sistema de geração e processamento de relatórios com arquitetura baseada em filas, utilizando Docker para orquestração dos serviços.

## 📋 Sobre o Projeto

Este sistema permite a geração assíncrona de relatórios utilizando uma arquitetura de microserviços. As requisições são enfileiradas no RabbitMQ e processadas por consumidor dedicado, com envio do relatorio via email ao término do processamento.

## 🏗️ Arquitetura

O projeto é composto por 6 serviços principais:

- **MySQL**: Banco de dados para armazenamento de dados
- **RabbitMQ**: Sistema de filas para processamento assíncrono
- **App**: Aplicação principal
- **Consumer**: Consumidor de filas para processamento de relatórios
- **phpMyAdmin**: Interface web para gerenciamento do banco de dados
- **Nginx**: Servidor web/proxy reverso

## 🚀 Tecnologias

- Docker & Docker Compose
- MySQL 8.0
- RabbitMQ 3 (com interface de gerenciamento)
- Nginx (Alpine)
- phpMyAdmin
- Mailtrap (para envio de emails)

## 📦 Pré-requisitos

- Docker
- Docker Compose
- Git

## ⚙️ Configuração

Após clonar o projeto crie um arquivo `.env` na raiz do projeto com as seguintes variáveis:

```env
# MySQL
MYSQL_ROOT_PASSWORD=senha_root_segura
MYSQL_DATABASE=pedidos_online_db

# RabbitMQ
RABBITMQ_USER=admin
RABBITMQ_PASS=admin

# MailTrap (substitua pelos seus dados)
MAILTRAP_HOST=sandbox.smtp.mailtrap.io
MAILTRAP_PORT=2525
MAILTRAP_USER=123456
MAILTRAP_PASS=123456
EMAIL_TO=miguel@empresa.com
```

## 🔧 Instalação e Execução

### Iniciar todos os serviços

```bash
docker-compose up -d
```

### Parar os serviços

```bash
docker-compose down
```

## 🌐 Acesso aos Serviços

Após iniciar os containers, os serviços estarão disponíveis em:

- **Aplicação**: http://localhost
- **phpMyAdmin**: http://localhost:8080
- **RabbitMQ Management**: http://localhost:15672
  - Usuário: definido em `RABBITMQ_USER`
  - Senha: definida em `RABBITMQ_PASS`

## 📧 Notificações por Email

O sistema utiliza o Mailtrap para envio de emails de notificação.
Configure as credenciais no arquivo `.env` para habilitar esta funcionalidade.
