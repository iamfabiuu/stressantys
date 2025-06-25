<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../html/login.html");
    exit;
}

$usuarioId = $_SESSION['usuario']['id'];
$pedidoId = $_GET['id'] ?? null;

if (!$pedidoId) {
    header("Location: meusPedidos.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "stressantysbd";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o pedido pertence ao usuário logado
    $stmtCheck = $pdo->prepare("SELECT * FROM pedidos WHERE id_pedido = :pedidoId AND usuario_id = :usuarioId");
    $stmtCheck->execute(['pedidoId' => $pedidoId, 'usuarioId' => $usuarioId]);
    $pedido = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        // Pedido não encontrado ou não pertence ao usuário
        header("Location: meusPedidos.php");
        exit;
    }

    // Pega os itens do pedido
    $stmtItens = $pdo->prepare("
        SELECT p.nome_produto, ip.quantidade, ip.preco_unitario
        FROM itens_pedido ip
        JOIN produtos p ON ip.produto_id = p.id_produto
        WHERE ip.pedido_id = :pedidoId
    ");
    $stmtItens->execute(['pedidoId' => $pedidoId]);
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao acessar banco de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Detalhes do Pedido #<?= htmlspecialchars($pedidoId) ?> - Stressantys Motors</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Montserrat', sans-serif;
    }
    .container {
      margin-top: 3rem;
      max-width: 800px;
    }
    .table thead th {
      border-bottom: 2px solid #000;
    }
    .btn-back {
      margin-bottom: 1.5rem;
      border-radius: 0;
      background-color: #000;
      color: #fff;
      transition: background-color 0.3s ease;
    }
    .btn-back:hover {
      background-color: #333;
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="meusPedidos.php" class="btn btn-back">&larr; Voltar para Meus Pedidos</a>

    <h2 class="mb-4 fw-bold">Detalhes do Pedido #<?= htmlspecialchars($pedidoId) ?></h2>

    <div class="mb-4">
      <p><strong>Data do Pedido:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
      <p><strong>Status:</strong> <?= htmlspecialchars(ucfirst($pedido['status'])) ?></p>
      <p><strong>Valor Total:</strong> R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></p>
    </div>

    <h4>Itens do Pedido</h4>
    <?php if (empty($itens)): ?>
      <p>Este pedido não possui itens cadastrados.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Produto</th>
              <th>Quantidade</th>
              <th>Preço Unitário</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($itens as $item): 
              $subtotal = $item['quantidade'] * $item['preco_unitario'];
            ?>
              <tr>
                <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                <td><?= htmlspecialchars($item['quantidade']) ?></td>
                <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
