<?php 
require_once __DIR__ . '/../src/dashboard.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Relatórios</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .header {
            background: #667eea;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .filtros {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .filtros form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: end;
        }
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        select, button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover { background: #5568d3; }
        button.success { background: #28a745; }
        button.success:hover { background: #218838; }
        table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background: #667eea;
            color: white;
        }
        tr:nth-child(even) { background: #f9f9f9; }
        .sucesso {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        a { color: white; text-decoration: none; }

        .modal {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        display: flex;
        justify-content: center;
        align-items: center;
        }
        .modal-content {
        background: white;
        padding: 30px;
        border-radius: 12px;
        width: 600px;    
        text-align: center;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .detalhes {
        background: #f9f9f9;
        padding: 15px;
        margin: 15px 0;
        border-radius: 8px;
        text-align: left;
        }
        .alerta {
        background: #e8f0ff;
        padding: 10px;
        border-radius: 8px;
        font-size: 14px;
        color: #003c8f;
        }
        #closeModal {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 10px;
        }

    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Sistema de Relatórios</h1>
        <div>
            Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?> | 
            <a href="logout.php">Sair</a>
        </div>
    </div>
    
    <div class="container">


     <?php if (isset($relatorio_info)): ?>
        <div id="modal" class="modal">
            <div class="modal-content">
                <h2>📊 Relatório em Processamento</h2>
                <p>Seu relatório está sendo gerado. Assim que estiver pronto, você receberá o arquivo XLS por e-mail.</p>

                <div class="detalhes">
                    <h3>Detalhes da Solicitação:</h3>
                    <p><strong>Unidade:</strong> <?= htmlspecialchars($relatorio_info['unidade']) ?></p>
                    <p><strong>Ano:</strong> <?= htmlspecialchars($relatorio_info['ano']) ?></p>
                    <p><strong>Solicitado em:</strong> <?= htmlspecialchars($relatorio_info['solicitado_em']) ?></p>
                    <p><strong>Será enviado para:</strong> <?= htmlspecialchars($relatorio_info['email']) ?></p>
                </div>

                <div class="alerta">
                    ⚠️ O processamento pode levar alguns minutos dependendo da quantidade de dados.
                </div>

                <button id="closeModal">Solicitar Novo Relatório</button>
            </div>
        </div>
    <?php endif; ?>
        
        <div class="filtros">
            <form method="GET">
                <div class="form-group">
                    <label>Unidade:</label>
                    <select name="unidade">
                        <option value="">Todas</option>
                        <option value="Salvador" <?= $unidade === 'Salvador' ? 'selected' : '' ?>>Salvador</option>
                        <option value="Feira de Santana" <?= $unidade === 'Feira de Santana' ? 'selected' : '' ?>>Feira de Santana</option>
                        <option value="Lauro de Freitas" <?= $unidade === 'Lauro de Freitas' ? 'selected' : '' ?>>Lauro de Freitas</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Ano:</label>
                    <select name="ano">
                        <option value="">Todos</option>
                        <option value="2024" <?= $ano === '2024' ? 'selected' : '' ?>>2024</option>
                        <option value="2025" <?= $ano === '2025' ? 'selected' : '' ?>>2025</option>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit">🔍 Filtrar</button>
                </div>
            </form>
        </div>
        
        <form method="POST" style="margin-bottom: 20px;">
            <button type="submit" name="gerar_relatorio" class="success">
                📧 Gerar XLS e Enviar por E-mail
            </button>
        </form>

        
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vendedor</th>
                    <th>Cliente</th>
                    <th>Unidade</th>
                    <th>Valor</th>
                    <th>Data da Venda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendas as $venda): ?>
                <tr>
                    <td><?= $venda['id'] ?></td>
                    <td><?= htmlspecialchars($venda['vendedor']) ?></td>
                    <td><?= htmlspecialchars($venda['cliente']) ?></td>
                    <td><?= htmlspecialchars($venda['unidade']) ?></td>
                    <td>R$ <?= number_format($venda['valor_venda'], 2, ',', '.') ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($venda['data_venda'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($vendas)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Nenhuma venda encontrada</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const closeBtn = document.getElementById('closeModal');
  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      document.getElementById('modal').remove();
    });
  }
});
</script>

</html>


