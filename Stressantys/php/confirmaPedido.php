<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Pedido inválido.";
    exit;
}

$id_pedido = $_GET['id'];

$host = "localhost";
$user = "root";
$pass = "";
$db   = "stressantysbd";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Busca informações do pedido
    $stmt = $pdo->prepare("SELECT * FROM Stped WHERE numped = :id AND id_usr = :id_usr");
    $stmt->execute([
        ':id' => $id_pedido,
        ':id_usr' => $_SESSION['usuario']['id']
    ]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        echo "Pedido não encontrado ou não pertence a você.";
        exit;
    }

    // Busca os carros relacionados ao pedido
    $stmt = $pdo->prepare("
        SELECT c.* FROM Stpedcar pc
        JOIN Stcar c ON pc.id_car = c.id
        WHERE pc.id_ped = :id_ped
    ");
    $stmt->execute([':id_ped' => $id_pedido]);
    $carros = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro ao buscar dados do pedido: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Confirmação de Pedido</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  body {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    font-family: "Segoe UI", sans-serif;
    color: #212529;
  }

  h2 {
    font-weight: 700;
    font-size: 2.2rem;
    text-align: center;
    background: linear-gradient(to right, #000, #0d6efd);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 2rem;
  }

  .card {
    border: none;
    border-radius: 1.2rem;
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(12px);
    box-shadow: 0 1.25rem 2.5rem rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 1.75rem 2.8rem rgba(0, 0, 0, 0.08);
  }

  p {
    font-size: 1rem;
    margin-bottom: 0.6rem;
  }

  h4 {
    font-weight: 600;
    color: #111;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
  }

  .table {
    border-radius: 0.5rem;
    overflow: hidden;
    background-color: rgba(255, 255, 255, 0.95);
  }

  .table th {
    background-color: #f1f3f5;
    color: #000;
    font-weight: 600;
    border: none;
  }

  .table td {
    border-top: 1px solid #dee2e6;
    vertical-align: middle;
    background-color: #ffffff;
  }

  .btn-dark {
    background: linear-gradient(135deg, #000, #222);
    border: none;
    border-radius: 0.6rem;
    padding: 0.75rem 2rem;
    font-weight: 500;
    color: #fff;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  .btn-dark:hover {
    background-color: #0d6efd;
    color: #fff;
    transform: scale(1.03);
  }

  hr {
    border-top: 2px dashed #ccc;
    margin: 2rem 0;
  }

  @media (max-width: 576px) {
    h2 {
      font-size: 1.75rem;
    }

    .table th, .table td {
      font-size: 0.875rem;
    }

    .btn-dark {
      width: 100%;
    }
  }
</style>

</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card p-4">
          <h2 class="mb-4 text-center">Pedido Confirmado</h2>

          <p><strong>Número do Pedido:</strong> <?= str_pad($pedido['numped'], 4, '0', STR_PAD_LEFT) ?></p>
          <p><strong>Valor Total:</strong> R$ <?= number_format($pedido['valortotal'], 2, ',', '.') ?></p>
          <p><strong>Desconto:</strong> R$ <?= number_format($pedido['desconto'], 2, ',', '.') ?></p>
          <p><strong>Tipo de Pagamento:</strong> <?= htmlspecialchars($pedido['tipo_pag']) ?></p>

          <hr class="my-4" />

          <h4>Carros no Pedido</h4>
          <div class="table-responsive">
            <table class="table table-bordered table-hover mt-3">
              <thead class="table-light">
                <tr>
                  <th>Modelo</th>
                  <th>Marca</th>
                  <th>Placa</th>
                  <th>Preço</th>
                  <th>Ano</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($carros as $carro): ?>
                  <tr>
                    <td><?= htmlspecialchars($carro['modelo']) ?></td>
                    <td><?= htmlspecialchars($carro['marca']) ?></td>
                    <td><?= htmlspecialchars($carro['placa']) ?></td>
                    <td>R$ <?= number_format($carro['preco'], 2, ',', '.') ?></td>
                    <td><?= $carro['ano'] ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="text-center mt-4">
            <a href="/stressantys/index.php" class="btn btn-dark px-4">Voltar para Página Inicial</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

