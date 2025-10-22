<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "[*] Iniciando consumer...\n";

// Função para conectar com retry
function connectWithRetry($maxRetries = 30, $delay = 2) {
    $retries = 0;
    
    while ($retries < $maxRetries) {
        try {
            echo "[*] Tentando conectar ao RabbitMQ (tentativa " . ($retries + 1) . "/$maxRetries)...\n";
            
            $connection = new AMQPStreamConnection(
                getenv('RABBITMQ_HOST'),
                5672,
                getenv('RABBITMQ_USER'),
                getenv('RABBITMQ_PASS')
            );
            
            echo "[✓] Conectado ao RabbitMQ com sucesso!\n";
            return $connection;
            
        } catch (Exception $e) {
            $retries++;
            if ($retries >= $maxRetries) {
                echo "[✗] Falha ao conectar após $maxRetries tentativas.\n";
                throw $e;
            }
            echo "[!] Falha na conexão. Aguardando {$delay}s antes de tentar novamente...\n";
            sleep($delay);
        }
    }
}

// Conectar com retry
$connection = connectWithRetry();
$channel = $connection->channel();
$channel->queue_declare('relatorios_queue', false, true, false, false);

echo "[*] Aguardando mensagens na fila...\n";

$callback = function ($msg) {
    echo "[x] Recebida mensagem\n";
    
    $dados = json_decode($msg->body, true);
    $unidade = $dados['unidade'] ?? '';
    $ano = $dados['ano'] ?? '';
    $email = $dados['email'] ?? getenv('EMAIL_TO');
    
    echo "[→] Processando relatório para: $email\n";
    echo "    Unidade: " . ($unidade ?: 'Todas') . "\n";
    echo "    Ano: " . ($ano ?: 'Todos') . "\n";
    
    try {
        // Conectar ao banco
        $pdo = new PDO(
            "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME'),
            getenv('DB_USER'),
            getenv('DB_PASS')
        );
        
        // Buscar dados
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
        
        $sql .= " ORDER BY data_venda DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "[→] Encontradas " . count($vendas) . " vendas\n";
        
        // Gerar XLS
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Cabeçalhos
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Vendedor');
        $sheet->setCellValue('C1', 'Cliente');
        $sheet->setCellValue('D1', 'Unidade');
        $sheet->setCellValue('E1', 'Valor');
        $sheet->setCellValue('F1', 'Data da Venda');
        
        // Estilizar cabeçalho
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        
        // Dados
        $row = 2;
        foreach ($vendas as $venda) {
            $sheet->setCellValue('A' . $row, $venda['id']);
            $sheet->setCellValue('B' . $row, $venda['vendedor']);
            $sheet->setCellValue('C' . $row, $venda['cliente']);
            $sheet->setCellValue('D' . $row, $venda['unidade']);
            $sheet->setCellValue('E' . $row, 'R$ ' . number_format($venda['valor_venda'], 2, ',', '.'));
            $sheet->setCellValue('F' . $row, $venda['data_venda']);
            $row++;
        }
        
        // Ajustar largura das colunas
        foreach(range('A','F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $filename = '/tmp/relatorio_' . time() . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        
        echo "[→] Relatório XLS gerado: $filename\n";
        
        // Enviar e-mail
        $mail = new PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = getenv('MAILTRAP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('MAILTRAP_USER');
        $mail->Password = getenv('MAILTRAP_PASS');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = getenv('MAILTRAP_PORT');
        
        $mail->setFrom('sistema@empresa.com', 'Sistema de Relatórios');
        $mail->addAddress($email);
        
        $mail->Subject = 'Relatório de Vendas - ' . date('d/m/Y H:i');
        $mail->Body = "Olá,\n\n";
        $mail->Body .= "Segue em anexo o relatório de vendas solicitado.\n\n";
        $mail->Body .= "Filtros aplicados:\n";
        $mail->Body .= "• Unidade: " . ($unidade ?: 'Todas') . "\n";
        $mail->Body .= "• Ano: " . ($ano ?: 'Todos') . "\n";
        $mail->Body .= "• Total de registros: " . count($vendas) . "\n\n";
        $mail->Body .= "Atenciosamente,\n";
        $mail->Body .= "Sistema de Relatórios";
        
        $mail->addAttachment($filename, 'relatorio_vendas.xlsx');
        
        $mail->send();
        echo "[✓] E-mail enviado com sucesso para: $email\n";
        
        // Limpar arquivo temporário
        unlink($filename);
        
    } catch (Exception $e) {
        echo "[✗] Erro ao processar relatório: " . $e->getMessage() . "\n";
    }
    
    // Confirmar processamento
    $msg->ack();
    echo "[✓] Mensagem processada\n\n";
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('relatorios_queue', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();