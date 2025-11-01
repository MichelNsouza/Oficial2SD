<?php
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/auth.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (login($email, $senha)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $erro = 'E-mail ou senha inválidos!';
    }
}
?>