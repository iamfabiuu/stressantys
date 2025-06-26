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
    color: #212529;
  }

  .card {
    border: none;
    border-radius: 1.25rem;
    padding: 2.5rem;
    box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.06);
    background-color: #fff;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
  }

  .card:hover {
    box-shadow: 0 1rem 2.5rem rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
  }

  h2, h4 {
    font-weight: 600;
    color: #1a1a1a;
  }

  .btn-dark {
    background-color: #000;
    border: none;
    border-radius: 0.6rem;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
  }

  .btn-dark:hover {
    background-color: #333;
    transform: scale(1.02);
  }

  .table thead th {
    background-color: #f1f3f5;
    text-align: center;
    vertical-align: middle;
    font-weight: 500;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
  }

  .table td {
    vertical-align: middle;
    text-align: center;
  }

  td img {
    width: 120px;
    height: 80px;
    border-radius: 0.6rem;
    object-fit: cover;
    transition: transform 0.3s ease;
    box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
  }

  td img:hover {
    transform: scale(1.03);
  }

  .form-check-input:checked {
    background-color: #000;
    border-color: #000;
  }

  .form-check-input {
    cursor: pointer;
    transition: 0.2s ease;
  }

  /* Badge personalizado de estoque */
  .badge-estoque {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    border-radius: 0.5rem;
  }

  .badge-ok {
    background-color: #198754; /* Verde Bootstrap */
    color: #fff;
  }

  .badge-baixo {
    background-color: #ffc107; /* Amarelo Bootstrap */
    color: #212529;
  }

  .badge-zero {
    background-color: #dc3545; /* Vermelho Bootstrap */
    color: #fff;
  }

  .text-end .btn,
  .text-center .btn {
    min-width: 180px;
  }

  .card {
  border-radius: 1rem;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.07);
}

.badge-estoque {
  font-size: 0.75rem;
  padding: 0.35em 0.65em;
  border-radius: 0.5rem;
}

.badge-ok {
  background-color: #198754;
  color: #fff;
}

.badge-baixo {
  background-color: #ffc107;
  color: #212529;
}

.badge-zero {
  background-color: #dc3545;
  color: #fff;
}


  @media (max-width: 767px) {
    td img {
      width: 100%;
      height: auto;
    }

    .table-responsive {
      overflow-x: auto;
    }

    h2, h4 {
      text-align: center;
    }

    .text-end {
      text-align: center !important;
    }
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
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
  <?php foreach ($carros as $carro): ?>
    <div class="col">
      <div class="card h-100 shadow-sm border-0">
        <img src="/stressantys/<?= $carro['img_url'] ?>" class="card-img-top" alt="Carro <?= $carro['modelo'] ?>" style="height: 180px; object-fit: cover;" />

        <div class="card-body">
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="carros[]" value="<?= $carro['id'] ?>" id="carro<?= $carro['id'] ?>">
            <label class="form-check-label fw-semibold" for="carro<?= $carro['id'] ?>">
              Selecionar este carro
            </label>
          </div>

          <h5 class="card-title mb-1"><?= htmlspecialchars($carro['modelo']) ?> - <?= htmlspecialchars($carro['marca']) ?></h5>
          <p class="card-text mb-1"><strong>Placa:</strong> <?= htmlspecialchars($carro['placa']) ?></p>
          <p class="card-text mb-1"><strong>Ano:</strong> <?= htmlspecialchars($carro['ano']) ?></p>
          <p class="card-text mb-1"><strong>Preço:</strong> R$ <?= number_format($carro['preco'], 2, ',', '.') ?></p>

          <?php if ($carro['estoque'] > 5): ?>
            <span class="badge badge-estoque badge-ok">Em estoque</span>
          <?php elseif ($carro['estoque'] > 0): ?>
            <span class="badge badge-estoque badge-baixo">Últimas unidades</span>
          <?php else: ?>
            <span class="badge badge-estoque badge-zero">Esgotado</span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
            </div>

<div class="text-end mt-4">
  <button type="submit" class="btn btn-dark px-4">Finalizar Pedido</button>
</div>

          </form>
          <div class="text-center mt-4">
              <a href="/stressantys/index.php" class="btn btn-dark px-4 ms-2">Voltar à Página Inicial</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

