<?php
session_start();

define('DB_HOST', getenv('DB_HOST') ?: 'mysql');
define('DB_NAME', getenv('DB_NAME') ?: 'pedidos_online_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

define('RABBITMQ_HOST', getenv('RABBITMQ_HOST') ?: 'rabbitmq');
define('RABBITMQ_PORT', 5672);
define('RABBITMQ_USER', getenv('RABBITMQ_USER') ?: 'admin');
define('RABBITMQ_PASS', getenv('RABBITMQ_PASS') ?: 'admin123');
define('QUEUE_NAME', 'relatorios_queue');
?>
