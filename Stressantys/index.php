<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <title>Stressantys Motors</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Google Font -->
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

      .hero {
        background: url('img/banner-destaque-home.webp') no-repeat center center;
        background-size: cover;
        height: 100vh;
        position: relative;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
      }

      .hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background-color: rgba(0,0,0,0.6);
      }

      .hero .container {
        position: relative;
        z-index: 2;
      }

      .btn-custom {
        background-color: #000;
        color: white;
        border-radius: 0;
        transition: 0.3s;
      }

      .btn-custom:hover {
        background-color: #333;
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

    <!-- HERO -->
    <section class="hero">
      <div class="container">
        <h1 class="display-4 fw-bold">Seu próximo carro começa aqui</h1>
        <p class="lead">Design e performance que movem você.</p>
        <a href="php/montarPedido.php" class="btn btn-custom btn-lg mt-3">Explorar Modelos</a>
      </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
