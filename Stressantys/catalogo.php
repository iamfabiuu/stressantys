<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Catálogo - Stressantys Motors</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #f8f9fa;
    }
    .navbar {
      background-color: #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .btn-dark {
      border-radius: 0;
      transition: background-color 0.3s ease;
    }
    .btn-dark:hover {
      background-color: #222;
    }
    .card-title {
      letter-spacing: 0.05em;
    }
    .card-text {
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top">
      <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="#">Stressantys</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="nav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="php/montarPedido.php">Modelos</a></li>
            <li class="nav-item"><a class="nav-link" href="php/meusPedidos.php">Meus Pedidos</a></li>
            <?php if ($usuario): ?>
              <li class="nav-item"><span class="nav-link">Olá, <strong><?= htmlspecialchars($usuario['nome']) ?></strong></span></li>
              <li class="nav-item"><a class="nav-link text-danger" href="php/logout.php">Sair</a></li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="html/login.html">Entrar</a></li>
              <li class="nav-item"><a class="nav-link" href="html/cadastro.html">Cadastrar</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>

  <!-- CATÁLOGO -->
  <div class="container my-5">
    <h2 class="mb-4 fw-bold text-center">Modelos Disponíveis</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <!-- Card 1 -->
      <div class="col">
        <div class="card h-100 shadow-sm border-0">
          <img src="img/jeep-commander-desktop@2x.webp" class="card-img-top" alt="Modelo X" />
          <div class="card-body">
            <h5 class="card-title fw-bold">Jeep Commander</h5>
            <p class="card-text text-muted">Versão Sport, motor 2.0 Turbo, acabamento premium.</p>
            <p class="card-text fw-bold fs-5 text-primary">R$ 150.000,00</p>
            <a href="php/montarPedido.php?carro=modelo-x" class="btn btn-dark w-100">Ver Detalhes</a>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col">
        <div class="card h-100 shadow-sm border-0">
          <img src="img/jeep-renegade-desktop@2x.webp" class="card-img-top" alt="Modelo Y" />
          <div class="card-body">
            <h5 class="card-title fw-bold">Jeep Renegade</h5>
            <p class="card-text text-muted">Versão Luxo, motor 3.0 V6, tecnologia de ponta.</p>
            <p class="card-text fw-bold fs-5 text-primary">R$ 200.000,00</p>
            <a href="php/montarPedido.php?carro=modelo-y" class="btn btn-dark w-100">Ver Detalhes</a>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col">
        <div class="card h-100 shadow-sm border-0">
          <img src="img/jeep-compass-desktop@2x.webp" class="card-img-top" alt="Modelo Z" />
          <div class="card-body">
            <h5 class="card-title fw-bold">Jeep Compass</h5>
            <p class="card-text text-muted">Versão Off-Road, motor 2.5 Diesel, robustez total.</p>
            <p class="card-text fw-bold fs-5 text-primary">R$ 170.000,00</p>
            <a href="php/montarPedido.php?carro=modelo-z" class="btn btn-dark w-100">Ver Detalhes</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
