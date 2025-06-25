<?php
// montar_pedido.html (mas com .php no final para incluir a sessão)
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: /stressantys/php/login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "stressantysbd";

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Buscar carros disponíveis
$stmt = $pdo->query("SELECT * FROM Stcar WHERE status = 1 AND estoque > 0");
$carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Montar Pedido - Stressantys</title>
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

    h2, h3 {
      font-weight: 600;
    }

    .btn-dark {
      background-color: #000;
      border: none;
    }

    .btn-dark:hover {
      background-color: #333;
    }

    .table thead th {
      background-color: #e9ecef;
    }

    .form-check-input:checked {
      background-color: #000;
      border-color: #000;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card p-4">
          <h2 class="mb-3">Olá, <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>!</h2>
          <h4 class="mb-4">Monte seu pedido</h4>

          <form action="processaPedido.php" method="POST">
            <div class="table-responsive">
              <table class="table table-bordered align-middle">
                <thead>
                  <tr>
                    <th scope="col">Selecionar</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Placa</th>
                    <th scope="col">Ano</th>
                    <th scope="col">Estoque</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($carros as $carro): ?>
                    <tr>
                      <td class="text-center">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="carros[]" value="<?= $carro['id'] ?>">
                        </div>
                      </td>
                      <td><?= htmlspecialchars($carro['modelo']) ?></td>
                      <td><?= htmlspecialchars($carro['marca']) ?></td>
                      <td>R$ <?= number_format($carro['preco'], 2, ',', '.') ?></td>
                      <td><?= htmlspecialchars($carro['placa']) ?></td>
                      <td><?= htmlspecialchars($carro['ano']) ?></td>
                      <td><?= $carro['estoque'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <div class="text-end mt-4">
              <button type="submit" class="btn btn-dark px-4">Finalizar Pedido</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</body>
</html>

