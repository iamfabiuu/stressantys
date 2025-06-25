<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "stressantysbd";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id_usr = $_SESSION['usuario']['id'];

    // Buscar todos os pedidos do usuário
    $stmt = $pdo->prepare("SELECT * FROM Stped WHERE id_usr = :id_usr ORDER BY numped DESC");
    $stmt->execute([':id_usr' => $id_usr]);
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao carregar pedidos: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Meus Pedidos - Stressantys</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: "Segoe UI", sans-serif;
    }
    .card {
      border: none;
      box-shadow: 0 0 1rem rgba(0, 0, 0, 0.05);
    }
    .pedido-id {
      font-weight: bold;
      font-size: 1.2rem;
    }
    .btn-dark {
      background-color: #000;
      border: none;
    }
    .btn-dark:hover {
      background-color: #333;
    }
    ul.list-group {
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <h2 class="mb-4 text-center">Meus Pedidos</h2>

    <?php if (empty($pedidos)): ?>
      <div class="alert alert-warning text-center">
        Você ainda não fez nenhum pedido.
      </div>
    <?php else: ?>
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <?php foreach ($pedidos as $pedido): ?>
            <div class="card mb-4 p-4">
              <div class="pedido-id mb-2">Pedido #<?= str_pad($pedido['numped'], 4, '0', STR_PAD_LEFT) ?></div>
              <p><strong>Valor Total:</strong> R$ <?= number_format($pedido['valortotal'], 2, ',', '.') ?></p>
              <p><strong>Desconto:</strong> R$ <?= number_format($pedido['desconto'], 2, ',', '.') ?></p>
              <p><strong>Forma de Pagamento:</strong> <?= htmlspecialchars($pedido['tipo_pag']) ?></p>

              <h5 class="mt-4">Carros neste pedido:</h5>
              <ul class="list-group list-group-flush">
                <?php
                  $stmt = $pdo->prepare("
                    SELECT c.modelo, c.marca, c.placa, c.preco, c.ano
                    FROM Stpedcar pc
                    JOIN Stcar c ON pc.id_car = c.id
                    WHERE pc.id_ped = :id_ped
                  ");
                  $stmt->execute([':id_ped' => $pedido['numped']]);
                  $carros = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  foreach ($carros as $carro):
                ?>
                  <li class="list-group-item">
                    <strong><?= htmlspecialchars($carro['marca']) ?> <?= htmlspecialchars($carro['modelo']) ?></strong> |
                    Ano: <?= $carro['ano'] ?> |
                    Placa: <?= $carro['placa'] ?> |
                    <strong>R$ <?= number_format($carro['preco'], 2, ',', '.') ?></strong>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="text-center mt-4">
      <a href="/stressantys/index.php" class="btn btn-dark px-4">Voltar à Página Inicial</a>
    </div>
  </div>
</body>
</html>
