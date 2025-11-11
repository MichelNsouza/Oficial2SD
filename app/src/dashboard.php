<?php
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

verificarLogin();

$unidade = $_GET['unidade'] ?? '';
$ano = $_GET['ano'] ?? '';
$mensagem_sucesso = '';

$pdo = getConnection();
$sql = "SELECT * FROM vendas WHERE 1=1";
$params = [];

if ($unidade) {
    $sql .= " AND unidade = ?";
    $params[] = $unidade;
}
if ($ano) {
    $sql .= " AND YEAR(data_venda) = ?";
    $params[] = $ano;
}

$sql .= " ORDER BY data_venda DESC LIMIT 8";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['gerar_relatorio'])) {
    try {
        $connection = new AMQPStreamConnection(
            RABBITMQ_HOST,
            RABBITMQ_PORT,
            RABBITMQ_USER,
            RABBITMQ_PASS
        );
        $channel = $connection->channel();
        $channel->queue_declare(QUEUE_NAME, false, true, false, false);
        
        $dados = json_encode([
            'unidade' => $unidade,
            'ano' => $ano,
            'email' => $_SESSION['usuario_email']
        ]);
        
        $msg = new AMQPMessage($dados, ['delivery_mode' => 2]);
        $channel->basic_publish($msg, '', QUEUE_NAME);
        
        $channel->close();
        $connection->close();

         $relatorio_info = [
            'unidade' => $unidade ?: 'Todas',
            'ano' => $ano ?: 'Todos',
            'solicitado_em' => date('d/m/Y'),
            'email' => $_SESSION['usuario_email']
        ];
        
        // $mensagem_sucesso = 'Relatório enviado para processamento! Você receberá o arquivo por e-mail em breve.';
    } catch (Exception $e) {
        $mensagem_sucesso = 'Erro ao enviar para fila: ' . $e->getMessage();
    }
}
?>