<?php
require_once __DIR__ . '/database.php';

function login($email, $senha) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = SHA1(?)");
    $stmt->execute([$email, $senha]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        return true;
    }
    return false;
}

function verificarLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: index.php');
        exit;
    }
}

function logout() {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>