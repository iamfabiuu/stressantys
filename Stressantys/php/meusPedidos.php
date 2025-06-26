<?php
session_start();

if (isset($_GET['status']) && $_GET['status'] === 'cancelado') {
    echo '<div class="alert alert-success text-center">Pedido cancelado com sucesso.</div>';
}

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
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    font-family: "Segoe UI", sans-serif;
    color: #212529;
  }

  h2 {
    font-weight: 700;
    font-size: 2.25rem;
    text-align: center;
    color: #111;
    background: linear-gradient(to right, #0d6efd, #00bcd4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 2rem;
  }

  .card {
    border: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 1.2rem;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    box-shadow: 0 1.2rem 2rem rgba(0, 0, 0, 0.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 1.6rem 2.8rem rgba(0, 0, 0, 0.08);
  }

  .pedido-id {
    font-weight: 600;
    font-size: 1.35rem;
    background: linear-gradient(to right, #000, #0d6efd);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .form-check-input {
    cursor: pointer;
    transform: scale(1.25);
    margin-right: 0.75rem;
    border: 2px solid #0d6efd;
    transition: all 0.3s ease;
  }

  .form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
  }

  .form-check-label {
    cursor: pointer;
    font-size: 1rem;
    color: #343a40;
  }

  .btn-dark {
    background: linear-gradient(135deg, #000, #343a40);
    border: none;
    border-radius: 0.6rem;
    padding: 0.75rem 2rem;
    font-weight: 500;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
  }

  .btn-dark:hover {
    background: #0d6efd;
    color: #fff;
    transform: scale(1.03);
  }

  .btn-danger {
    background: none;
    border: 2px solid #dc3545;
    color: #dc3545;
    border-radius: 0.6rem;
    padding: 0.75rem 2rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .btn-danger:hover {
    background-color: #dc3545;
    color: #fff;
  }

  ul.list-group {
    margin-top: 1rem;
    border-radius: 0.5rem;
    overflow: hidden;
  }

  ul.list-group .list-group-item {
    background-color: rgba(255, 255, 255, 0.85);
    border: none;
    border-bottom: 1px solid #dee2e6;
    font-size: 0.95rem;
    padding: 0.8rem 1rem;
    backdrop-filter: blur(6px);
    transition: background 0.3s ease;
  }

  ul.list-group .list-group-item:hover {
    background-color: #f1f3f5;
  }

  ul.list-group .list-group-item:last-child {
    border-bottom: none;
  }

  .alert {
    border-radius: 1rem;
    background: rgba(13, 110, 253, 0.1);
    border-left: 4px solid #0d6efd;
    color: #0d6efd;
    font-weight: 500;
    padding: 1rem;
    text-align: center;
  }

  @media (max-width: 576px) {
    h2 {
      font-size: 1.75rem;
    }

    .btn {
      width: 100%;
      margin-bottom: 1rem;
    }

    .card {
      padding: 1.2rem;
    }
  }
</style>

</head>
<body>
  <div class="container py-5">
    <h2 class="mb-4 text-center">Meus Pedidos</h2>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'cancelado'): ?>
      <div class="alert alert-success text-center">Pedido cancelado com sucesso.</div>
    <?php endif; ?>

    <?php if (empty($pedidos)): ?>
      <div class="alert alert-warning text-center">
        Você ainda não fez nenhum pedido.
      </div>
      <div class="text-center mt-4">
        <a href="/stressantys/index.php" class="btn btn-dark px-4 ms-2">Voltar à Página Inicial</a>
      </div>
    <?php else: ?>
      <form method="POST" action="/stressantys/php/cancelarPedido.php">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <?php foreach ($pedidos as $pedido): ?>
              <div class="card mb-4 p-4">
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" name="pedidos[]" value="<?= $pedido['numped'] ?>" id="pedido<?= $pedido['numped'] ?>">
                  <label class="form-check-label pedido-id" for="pedido<?= $pedido['numped'] ?>">
                    Pedido #<?= str_pad($pedido['numped'], 4, '0', STR_PAD_LEFT) ?>
                  </label>
                </div>

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

            <div class="text-center mt-4">
              <button type="submit" class="btn btn-danger px-4">Cancelar Pedido</button>
              <a href="/stressantys/index.php" class="btn btn-dark px-4 ms-2">Voltar à Página Inicial</a>
            </div>
          </div>
        </div>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>